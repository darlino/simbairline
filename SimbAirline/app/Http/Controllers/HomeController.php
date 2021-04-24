<?php
namespace App\Http\Controllers;

use App\Plan;
use App\User;
use Carbon\Carbon;
use Modules\Location\Models\Location;
use Modules\Page\Models\Page;
use Modules\News\Models\NewsCategory;
use Modules\News\Models\Tag;
use Modules\News\Models\News;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Modules\Space\Models\Space;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $home_page_id = setting_item('home_page_id');
        if($home_page_id && $page = Page::where("id",$home_page_id)->where("status","publish")->first())
        {
            $this->setActiveMenu($page);
            $translation = $page->translateOrOrigin(app()->getLocale());
            $seo_meta = $page->getSeoMetaWithTranslation(app()->getLocale(), $translation);
            $seo_meta['full_url'] = url("/");
            $seo_meta['is_homepage'] = true;
            $data = [
                'row'=>$page,
                "seo_meta"=> $seo_meta,
                'translation'=>$translation
            ];
            return view('Page::frontend.detail',$data);
        }
        $model_News = News::where("status", "publish");
        $data = [
            'rows'=>$model_News->paginate(5),
            'model_category'    => NewsCategory::where("status", "publish"),
            'model_tag'         => Tag::query(),
            'model_news'        => News::where("status", "publish"),
            'breadcrumbs' => [
                ['name' => __('News'), 'url' => url("/news") ,'class' => 'active'],
            ],
            "seo_meta" => News::getSeoMetaForPageList()
        ];
        return view('News::frontend.index',$data);
    }

    public function checkConnectDatabase(Request $request){
        $connection = $request->input('database_connection');
        config([
            'database' => [
                'default' => $connection."_check",
                'connections' => [
                    $connection."_check" => [
                        'driver' => $connection,
                        'host' => $request->input('database_hostname'),
                        'port' => $request->input('database_port'),
                        'database' => $request->input('database_name'),
                        'username' => $request->input('database_username'),
                        'password' => $request->input('database_password'),
                    ],
                ],
            ],
        ]);
        try {
            DB::connection()->getPdo();
            $check = DB::table('information_schema.tables')->where("table_schema","performance_schema")->get();
            if(empty($check) and $check->count() == 0){
                return $this->sendSuccess(false , __("Access denied for user!. Please check your configuration."));
            }
            if(DB::connection()->getDatabaseName()){
                return $this->sendSuccess(false , __("Yes! Successfully connected to the DB: ".DB::connection()->getDatabaseName()));
            }else{
                return $this->sendSuccess(false , __("Could not find the database. Please check your configuration."));
            }
        } catch (\Exception $e) {
            return $this->sendError( $e->getMessage() );
        }
    }

    public function plan(Request $request)
    {
        $plan = DB::table('plan')->whereDate('date', '>=', Carbon::today()->toDateString())->get();
        $data = [
            'plan' => $plan
        ];
        return view('Layout::plan', $data);
    }
    public function store_plan(Request $request)
    {
        $plan = DB::table('plan')->whereDate('date', '>=', Carbon::today()->toDateString())->get();
        $plane = new Space;
        $location = new Location;
        $data_location = $location::where("status", "publish")->get();
        $data_plane = $plane::all();




        $data = [
            'plan' => $plan,
            'data_plane' => $data_plane,
            'data_location' => $data_location,
        ];

        return view('Layout::admin_plan', $data);
    }

    public function store_plan_real(Request $request){
        $plan = DB::table('plan')->whereDate('date', '>=', Carbon::today()->toDateString())->get();
        $plane = new Space;
        $location = new Location;
        $data_location = $location::where("status", "publish")->get();
        $data_plane = $plane::all();


        $plans = new Plan;




        $data = [
            'plan' => $plan,
            'data_plane' => $data_plane,
            'data_location' => $data_location,
        ];

        
        $request->validate(
            [
                "routing"  => 'required',
                'plane' => 'required',
                'capacity' => 'required',
                'date_dispo' => 'required',
                'from' => 'required',
                'date_depart' => 'required',
                'date_return' => 'required',
                'to' => 'required',
                'time_flight' => 'required',
                'time_ground' => 'required',
                'time_night_rest' => 'required',
                'routing_nature_1' => 'required',
                'routing_nature_2' => 'required',
                'call_sign' => 'required',
                'flight_number' => 'required',
                'baggages' => 'required'

            ]
        );

        $plans::create(
            [
                "routing" => $request->get('routing'),
                "pax" => $request->get('capacity'),
                "date" => ($request->get('date_dispo')),
                "from" => $request->get('from'),
                "etd" => $request->get('date_depart'),
                "eta" => $request->get('date_return'),
                "to" => $request->get('to'),
                "eet" => $request->get('time_flight'),
                "ground_time" => $request->get('time_ground'),
                "night_stop" => $request->get('time_night_rest'),
                "routing_nature1" => $request->get('routing_nature_1'),
                "routing_nature2" => $request->get('routing_nature_2'),
                "flight_number" => $request->get('flight_number'),
                "call_sign" => $request->get('call_sign')
            ] 
        );

        return view('Layout::admin_plan', $data);

    }
}
