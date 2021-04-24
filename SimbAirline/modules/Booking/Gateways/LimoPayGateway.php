<?php
namespace Modules\Booking\Gateways;

use App\Currency;
use App\Helpers\Assets;
use Illuminate\Http\Request;
use Mockery\Exception;
use Modules\Booking\Events\BookingCreatedEvent;
use Modules\Booking\Events\BookingUpdatedEvent;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\Payment;
use Validator;
use Illuminate\Support\Facades\Log;

class LimoPayGateway extends BaseGateway
{
    public $name = 'LimoPay Checkout';

    protected $id = 'limopay';

    protected $url = 'https://api.limopay.net/payment';

    protected $gateway;

    public function getOptionsConfigs()
    {
        return [
            [
                'type'  => 'checkbox',
                'id'    => 'enable',
                'label' => __('Enable LimoPay Standard?')
            ],
            [
                'type'       => 'input',
                'id'         => 'name',
                'label'      => __('Custom Name'),
                'std'        => __("LimoPay"),
                'multi_lang' => "1"
            ],
            [
                'type'  => 'upload',
                'id'    => 'logo_id',
                'label' => __('Custom Logo'),
            ],
            [
                'type'  => 'editor',
                'id'    => 'html',
                'label' => __('Custom HTML Description'),
                'multi_lang' => "1"
            ],
            [
                'type'      => 'input',
                'id'        => 'account',
                'label'     => __('Shop ID'),
                'condition' => 'g_paypal_test:is()'
            ],
            [
                'type'      => 'input',
                'id'        => 'receiver',
                'label'     => __('Receiver Name'),
                'condition' => 'g_paypal_test:is()'
            ],
            [
                'type'      => 'input',
                'id'        => 'api',
                'label'     => __('API Key'),
                'condition' => 'g_paypal_test:is()'
            ],
            [
                'type'      => 'input',
                'id'        => 'client_id',
                'label'     => __('Public Key'),
                'condition' => 'g_paypal_test:is()'
            ],
            [
                'type'      => 'input',
                'id'        => 'client_secret',
                'label'     => __('Private Key'),
                'std'       => '',
                'condition' => 'g_paypal_test:is()'
            ],
        ];
    }

    public function process(Request $request, $booking, $service)
    {
        $service->beforePaymentProcess($booking, $this);

        $rules = [
            'username'    => ['required'],
            'otp'  => ['required'],
        ];
        $messages = [
            'username.required'    => __('Username is required field'),
            'otp.required'  => __('Git AUth OTP invalid!'),
        ];
        $rules = [
            'username'    => ['required'],
            'otp'  => ['required']
        ];
        $messages = [
            'username.required'    => __('Username is required field'),
            'otp.required'  => __('Git AUth OTP invalid!'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['errors'   => $validator->errors() ], 200)->send();
        }

        // Quantite: Nombre de personnes * Nombre de Jours
        $qty = intval($booking->duration_days) * intval($booking->total_guests);
        $total = floatval($booking->service->price) * intval($qty);
        $totalPrice = $total;
        $extra = floatval($booking->total - ($booking->total_before_fees * intval($booking->total_guests)));

        $fields = array(
            'publicKey' => urlencode($this->getOption('client_id')),
            'secretKey' => urlencode($this->getOption('client_secret')),
            'receiverName' => urlencode($this->getOption('receiver')),
            'senderName' => urlencode($request->input("username")),
            "otp" => urlencode($request->input("otp")),
            'billingDate' => urlencode(date("Y-m-d H:i:s")),
            'billingNote' => urlencode($booking->customer_notes),
            'shopID' => urlencode($this->getOption('account')),
            'curtItem' => array(
                array(
                    'itemID' => $booking->service->id,
                    'title' => $booking->service->title,
                    'quantity' => $qty,
                    'BasePrice' => floatval($booking->service->price),
                    'totalPrice' => $total
                )
            ),
            'invoiceAmount' => urlencode($total),
            'deliveryCharge' => urlencode('0'),
            'vatTax' => urlencode('0'),
            'extraCharge' => urlencode($extra),
            'totalAmount' => urlencode(floatval(floatval($extra) + floatval($total))),
        );

        $fields_string = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'x-api-key: '.$this->getOption('api'),
            'Content-Type: application/json'
        ]);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return response()->json(['errors'   => curl_error($ch) ], 200)->send();
        } else {
            curl_close($ch);
        }

        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $out->writeln($fields_string);
        $out->writeln($result);

        $res = json_decode($result);
        if((int)$res->body->response->statusCode === 400 || $res->body->response->error === true){
            throw new Exception($res->body->response->msg);
        }

        if((int)$res->body->response->statusCode === 200 || $res->body->response->success === true){
            $booking->paid = $res->body->data->totalAmount;
            $booking->transactionID = $res->body->data->InvoiceNo;
            $booking->sender = $res->body->data->senderName;
            $booking->receiver = $res->body->data->receiverName;
        }

        if($booking->paid <= 0){
            $booking->status = $booking::PROCESSING;
        }else{
            if($booking->paid < $booking->total){
                $booking->status = $booking::PARTIAL_PAYMENT;
            }else{
                $booking->status = $booking::PAID;
            }
        }

        $booking->save();

        try{
            event(new BookingCreatedEvent($booking));
        } catch(\Swift_TransportException $e){
            Log::warning($e->getMessage());
        }

        $service->afterPaymentProcess($booking, $this);
        return response()->json([
            'url' => $booking->getDetailUrl()
        ])->send();
    }

    public function handlePurchaseData($data, $booking, $request)
    {
        $data['currency'] = setting_item('currency_main');
        if(is_api()){
            $cardData = array(
                'username'     => $request->input("username"),
                'otp'          => $request->input("otp"),
            );
            $data["card"] = $cardData;

        }else{
            $data['token'] = $request->input("token");
        }
        $data['description'] = setting_item("site_title")." - #".$booking->id;
        return $data;
    }

    public function handlePurchaseDataNormal($data, $payment)
    {
        $data['currency'] = setting_item('currency_main');
        $data['token'] = \request()->input("token");
        $data['description'] = setting_item("site_title")." - #".$payment->id;
        return $data;
    }

    public function getValidationRules()
    {
        $rules = [
            'username'    => ['required'],
            'otp'  => ['required'],
        ];
        return $rules;
    }

    public function getValidationMessages()
    {
        return  [
            'username.required'    => __('Username is required field'),
            'otp.required'  => __('GIT Auth OTP invalid!'),
        ];
    }

    public function getDisplayHtml()
    {
        Assets::registerJs("https://js.stripe.com/v3/",true);
        Assets::registerJs( asset('module/booking/gateways/stripe.js') ,true);
        $data = [
            'html' => $this->getOption('html', ''),
        ];
        return view("Booking::frontend.gateways.limopay",$data);
    }
    public function getApiDisplayHtml(){
        return "";
    }

    public function getForm()
    {
        return [
            'username'    => [
                'label'=>__('Username'),
                'required'=>true,
            ],
            'otp'          =>[
                'label'=>__('Git AUth OTP'),
                'required'=>true,
            ],
        ];
    }
}
