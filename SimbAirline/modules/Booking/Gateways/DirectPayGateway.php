<?php
namespace Modules\Booking\Gateways;

use App\Currency;
use Illuminate\Http\Request;
use Mockery\Exception;
use Modules\Booking\Events\BookingUpdatedEvent;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\Payment;
use Omnipay\Omnipay;
use Omnipay\PayPal\ExpressGateway;
use Illuminate\Support\Facades\Log;

class DirectPayGateway extends BaseGateway
{
    public $name = 'Direct Pay Checkout';
    /**
     * @var $gateway ExpressGateway
     */
    protected $gateway;

    public function getOptionsConfigs()
    {
        return [
            [
                'type'  => 'checkbox',
                'id'    => 'enable',
                'label' => __('Enable Direct Pay Standard?')
            ],
            [
                'type'       => 'input',
                'id'         => 'name',
                'label'      => __('Name'),
                'std'        => __("DirectPay"),
                'multi_lang' => "1"
            ],
            [
                'type'  => 'upload',
                'id'    => 'logo_id',
                'label' => __('Logo'),
            ],
            [
                'type'  => 'editor',
                'id'    => 'html',
                'label' => __('HTML Description'),
                'multi_lang' => "1"
            ],
            [
                'type'      => 'input',
                'id'        => 'terminal',
                'label'     => __('Numéro du Terminal'),
                'condition' => 'g_paypal_test:is()'
            ],
            [
                'type'      => 'input',
                'id'        => 'secret',
                'label'     => __('Clé Secrète'),
                'condition' => 'g_paypal_test:is()'
            ],
        ];
    }

    public function process(Request $request, $booking, $service)
    {
        if (in_array($booking->status, [
            $booking::PAID,
            $booking::COMPLETED,
            $booking::CANCELLED
        ])) {

            throw new Exception(__("Booking status does need to be paid"));
        }
        if (!$booking->total) {
            throw new Exception(__("Booking total is zero. Can not process payment gateway!"));
        }
        $payment = new Payment();
        $payment->booking_id = $booking->id;
        $payment->payment_gateway = $this->id;
        $payment->status = 'draft';

        $time =  time(); /* Générer le timestamp */

        $payment_id = $time; /* Identifiant unique de l'opération générer par votre system */

        $qty = (int)$booking->duration_days * (int)$booking->total_guests;
        $total = (float)$booking->service->price * (int)$qty;
        $extra = (float)($booking->total - ($booking->total_before_fees * (int)$booking->total_guests));

        $amount = $total + $extra;

        $currency = \App\Currency::getCurrent('currency_main');
        $currency = strtoupper($currency);

        $auth = md5($time.$amount.$payment_id.$currency.$this->getOption('secret'));

        $post_data = array(
            'transactionCurrencyCode' => $currency,
            'terminalNumber' => $this->getOption('terminal'),
            'requestId' => $payment_id,
            'amount' => $amount,
            'timestamp' => $time,
            'auth' => $auth,
            'returnUrl' => $booking->getDetailUrl()."/process/",
            'customerFirstName' => $booking->first_name,
            'customerLastName' => $booking->last_name,
            'customerEmail' => $booking->email,
            'customerAddress' => $booking->address,
            'customerCountry' => $booking->country,
            'customerState' => $booking->state,
            'customerCity' => $booking->city,
            'customerZipCode' => $booking->zip_code,
            'customerPhone' => $booking->phone,
            'customerBirthDate' => $booking->created_at,
            'customerBirthPlace' => $booking->address2,
        );

        $url = "https://wdev.afripayway.com/payline/v1/payment/api/initiateOnlinePayment";

        $ch = curl_init();
        $headers  = [
            'Content-Type: application/json'
        ];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result     = curl_exec ($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $response = json_decode($result, true);

        if (is_array($response) && isset($response['code']) && $response['code'] === '0000') {
            $payment_request_url = $response['paymentUrl'];
            response()->json([
                'url' => $payment_request_url
            ])->send();
        }
        else {
            throw new Exception($response['message']);
        }

        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }
        else {
            curl_close($ch);
        }

    }

    public function confirmPayment(Request $request)
    {
        $c = $request->query('c');
        $booking = Booking::where('code', $c)->first();
        if (!empty($booking) and in_array($booking->status, [$booking::UNPAID])) {
            $this->getGateway();
            $data = $this->handlePurchaseData([
                'amount'        => (float)$booking->pay_now,
                'transactionId' => $booking->code . '.' . time()
            ], $booking);
            $response = $this->gateway->completePurchase($data)->send();
            if ($response->isSuccessful()) {
                $payment = $booking->payment;
                if ($payment) {
                    $payment->status = 'completed';
                    $payment->logs = \GuzzleHttp\json_encode($response->getData());
                    $payment->save();
                }
                try{
                    $booking->paid += $data['amount'];
                    $booking->markAsPaid();

                } catch(\Swift_TransportException $e){
                    Log::warning($e->getMessage());
                }
                return redirect($booking->getDetailUrl())->with("success", __("You payment has been processed successfully"));
            } else {

                $payment = $booking->payment;
                if ($payment) {
                    $payment->status = 'fail';
                    $payment->logs = \GuzzleHttp\json_encode($response->getData());
                    $payment->save();
                }
                try{
                    $booking->markAsPaymentFailed();

                } catch(\Swift_TransportException $e){
                    Log::warning($e->getMessage());
                }
                return redirect($booking->getDetailUrl())->with("error", __("Payment Failed"));
            }
        }
        if (!empty($booking)) {
            return redirect($booking->getDetailUrl(false));
        } else {
            return redirect(url('/'));
        }
    }

    public function confirmNormalPayment()
    {
        /**
         * @var Payment $payment
         */
        $request = \request();
        $c = $request->query('pid');
        $payment = Payment::where('code', $c)->first();

        if (!empty($payment) and in_array($payment->status,['draft'])) {
            $this->getGateway();
            $data = $this->handlePurchaseDataNormal([
                'amount'        => (float)$payment->amount,
                'transactionId' => $payment->code . '.' . time()
            ], $payment);
            $response = $this->gateway->completePurchase($data)->send();
            if ($response->isSuccessful()) {
                return $payment->markAsCompleted(\GuzzleHttp\json_encode($response->getData()));

            } else {
                return $payment->markAsFailed(\GuzzleHttp\json_encode($response->getData()));
            }
        }
        if($payment){
            if($payment->status == 'cancel'){
                return [false,__("Your payment has been canceled")];
            }
        }
        return [false];
    }

    public function processNormal($payment)
    {
        $this->getGateway();
        $payment->payment_gateway = $this->id;
        $data = $this->handlePurchaseDataNormal([
            'amount'        => (float)$payment->amount,
            'transactionId' => $payment->code . '.' . time()
        ],  $payment);

        $response = $this->gateway->purchase($data)->send();

        if($response->isSuccessful()){
            return [true];
        }elseif($response->isRedirect()){
            return [true,false,$response->getRedirectUrl()];
        }else{
            return [false,$response->getMessage()];
        }
    }

    public function cancelPayment(Request $request)
    {
        $c = $request->query('c');
        $booking = Booking::where('code', $c)->first();
        if (!empty($booking) and in_array($booking->status, [$booking::UNPAID])) {
            $payment = $booking->payment;
            if ($payment) {
                $payment->status = 'cancel';
                $payment->logs = \GuzzleHttp\json_encode([
                    'customer_cancel' => 1
                ]);
                $payment->save();
            }

            // Refund without check status
            $booking->tryRefundToWallet(false);

            return redirect($booking->getDetailUrl())->with("error", __("You cancelled the payment"));
        }
        if (!empty($booking)) {
            return redirect($booking->getDetailUrl());
        } else {
            return redirect(url('/'));
        }
    }

    public function handlePurchaseDataNormal($data, &$payment = null)
    {
        $main_currency = setting_item('currency_main');
        $supported = $this->supportedCurrency();
        $convert_to = $this->getOption('convert_to');
        $data['currency'] = $main_currency;
        $data['returnUrl'] = $this->getReturnUrl(true) . '?pid=' . $payment->code;
        $data['cancelUrl'] = $this->getCancelUrl(true) . '?pid=' . $payment->code;
        if (!array_key_exists($main_currency, $supported)) {
            if ($payment) {
                $payment->converted_currency = $convert_to;
                $payment->converted_amount = $payment->amount;
                $payment->save();
            }
            $data['amount'] = number_format( $payment->amount , 2 );
            $data['currency'] = $convert_to;
        }
        return $data;
    }

    public function handlePurchaseData($data, $booking, &$payment = null)
    {
        $main_currency = setting_item('currency_main');
        $supported = $this->supportedCurrency();
        $convert_to = $this->getOption('convert_to');
        $data['currency'] = $main_currency;
        $data['returnUrl'] = $this->getReturnUrl() . '?c=' . $booking->code;
        $data['cancelUrl'] = $this->getCancelUrl() . '?c=' . $booking->code;
        if (!array_key_exists($main_currency, $supported)) {
            if ($payment) {
                $payment->converted_currency = $convert_to;
                $payment->converted_amount = $booking->total;
            }
            $data['amount'] = number_format( $booking->total , 2 );
            $data['currency'] = $convert_to;
        }
        return $data;
    }

}
