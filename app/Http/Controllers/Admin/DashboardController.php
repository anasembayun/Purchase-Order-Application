<?php

namespace App\Http\Controllers\Admin;

use App\Models\Auth\User\User;
use Arcanedev\LogViewer\Entities\Log;
use Arcanedev\LogViewer\Entities\LogEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Route;
use DB;
use App\Models\Product;
use App\Models\PurchaseOrderLine;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $counts = [
            'users' => \DB::table('users')->count(),
            'users_unconfirmed' => \DB::table('users')->where('confirmed', false)->count(),
            'users_inactive' => \DB::table('users')->where('active', false)->count(),
            'protected_pages' => 0,
        ];

        foreach (\Route::getRoutes() as $route) {
            foreach ($route->middleware() as $middleware) {
                if (preg_match("/protection/", $middleware, $matches)) $counts['protected_pages']++;
            }
        }

        $product = [];
        $months = ['01','02','03','04','05','06','07','08','09','10','11','12'];
        $month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        foreach ($months as $key => $value) {
            $product[(int)$key] = Product::where(\DB::raw("DATE_FORMAT(created_at, '%Y-%m')"),'2022'.'-'.$value)->count();   
        }

        $order = [];
        foreach ($months as $key => $value) {
            $order[(int)$key] = PurchaseOrderLine::select('qty')->where(\DB::raw("DATE_FORMAT(date, '%Y-%m')"),'2022'.'-'.$value)->sum(DB::raw('qty'));
        }

        return view('admin.dashboard', ['counts' => $counts])->with('product',json_encode($product,JSON_NUMERIC_CHECK))
        ->with('month',json_encode($month))
        ->with('order',json_encode($order,JSON_NUMERIC_CHECK));
    }


    public function getLogChartData(Request $request)
    {
        \Validator::make($request->all(), [
            'start' => 'required|date|before_or_equal:now',
            'end' => 'required|date|after_or_equal:start',
        ])->validate();

        $start = new Carbon($request->get('start'));
        $end = new Carbon($request->get('end'));

        $dates = collect(\LogViewer::dates())->filter(function ($value, $key) use ($start, $end) {
            $value = new Carbon($value);
            return $value->timestamp >= $start->timestamp && $value->timestamp <= $end->timestamp;
        });


        $levels = \LogViewer::levels();

        $data = [];

        while ($start->diffInDays($end, false) >= 0) {

            foreach ($levels as $level) {
                $data[$level][$start->format('Y-m-d')] = 0;
            }

            if ($dates->contains($start->format('Y-m-d'))) {
                /** @var  $log Log */
                $logs = \LogViewer::get($start->format('Y-m-d'));

                /** @var  $log LogEntry */
                foreach ($logs->entries() as $log) {
                    $data[$log->level][$log->datetime->format($start->format('Y-m-d'))] += 1;
                }
            }

            $start->addDay();
        }

        return response($data);
    }

    public function getRegistrationChartData()
    {

        $data = [
            'registration_form' => User::whereDoesntHave('providers')->count(),
            'google' => User::whereHas('providers', function ($query) {
                $query->where('provider', 'google');
            })->count(),
            'facebook' => User::whereHas('providers', function ($query) {
                $query->where('provider', 'facebook');
            })->count(),
            'twitter' => User::whereHas('providers', function ($query) {
                $query->where('provider', 'twitter');
            })->count(),
        ];

        return response($data);
    }

    public function reportIndex(){
        $products = Product::all();
        return view('admin.reporting',compact('products'));
    }

    public function getAllDataProduct(){
        $products = Product::all();
        return response($products);
    }

    public function getProductPriceGrouping(){
    $less_50000 = Product::where('price', '>=', '0')->where('price', '<=', '50000')->count();
    $_50000_99999 = Product::where('price', '>', '50000')->where('price', '<=', '99999')->count();
    $_100000_999999 = Product::where('price', '>=', '100000')->where('price', '<=', '999999')->count();
    $more_than_equal_1000000 = Product::where('price', '>=', '1000000')->count();
    $data = [
        'less_50000' => $less_50000,
        '_50000_99999' => $_50000_99999,
        '_100000_999999' => $_100000_999999,
        'more_than_equal_1000000' => $more_than_equal_1000000
    ];
    return response($data);
    }

    public function getDataAllProducts(){
        $products = Product::all();
        for ($i=0; $i<count($products); $i++){
            if ($products[$i]["price"] <= 50000){
                $products[$i]['price_range'] = 'less_50000';
            }else if ($products[$i]["price"] > 50000 && $products[$i]["price"] <= 99999){
                $products[$i]['price_range'] = '_50000_99999';
            }else if ($products[$i]["price"] > 100000 && $products[$i]["price"] <= 999999){
                $products[$i]['price_range'] = '_100000_999999';
            }else{
                $products[$i]['price_range'] = 'more_than_equal_1000000';
            }
            $products[$i]['created_range'] = substr($products[$i]['created_at'],0,7);
        }
        return response($products);
    }

    public function getReport(){ 
        $products_2021 = [];
        $products_2022 = [];
        $products_2023 = [];
        $months = ['01','02','03','04','05','06','07','08','09','10','11','12'];
        $month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $product_2021_arr = [];
        $product_2022_arr = [];
        $product_2023_arr = [];

        //2021
        $_2021 = Product::whereYear('created_at', '=', '2021')->sum(DB::raw('price'));
        foreach ($months as $key => $value) {
            $products_2021[(int)$key] = Product::where(\DB::raw("DATE_FORMAT(created_at, '%Y-%m')"),'2021'.'-'.$value)->sum(DB::raw('price'));  
        }

        for ($i = 0; $i <= 11; $i++) {
            if (!empty($products_2021[$i])) {
                $product_2021_arr[$i]['total'] = $products_2021[$i];
            } else {
                $product_2021_arr[$i]['total'] = 0;
            }
            $product_2021_arr[$i]['month'] = $month[$i];
        }

        //2022
        $_2022 = Product::whereYear('created_at', '=', '2022')->sum(DB::raw('price'));
        foreach ($months as $key => $value) {
            $products_2022[(int)$key] = Product::where(\DB::raw("DATE_FORMAT(created_at, '%Y-%m')"),'2022'.'-'.$value)->sum(DB::raw('price'));  
        }

        for ($i = 0; $i <= 11; $i++) {
            if (!empty($products_2022[$i])) {
                $product_2022_arr[$i]['total'] = $products_2022[$i];
            } else {
                $product_2022_arr[$i]['total'] = 0;
            }
            $product_2022_arr[$i]['month'] = $month[$i];
        }

        //2023
        $_2023 = Product::whereYear('created_at', '=', '2023')->sum(DB::raw('price'));
        foreach ($months as $key => $value) {
            $products_2023[(int)$key] = Product::where(\DB::raw("DATE_FORMAT(created_at, '%Y-%m')"),'2023'.'-'.$value)->sum(DB::raw('price'));  
        }

        for ($i = 0; $i <= 11; $i++) {
            if (!empty($products_2023[$i])) {
                $product_2023_arr[$i]['total'] = $products_2023[$i];
            } else {
                $product_2023_arr[$i]['total'] = 0;
            }
            $product_2023_arr[$i]['month'] = $month[$i];
        }

        $datas=[
            'duasatu'=>[
                'year' => '2021',
                'total_price_year'=>$_2021,
                'months' => $product_2021_arr,],
            'duadua'=>[
                'year' => '2022',
                'total_price_year'=>$_2022,
                'months' => $product_2022_arr,],
            'duatiga'=>[
                    'year' => '2023',
                    'total_price_year'=>$_2023,
                    'months' => $product_2023_arr,],
        ];

        
        return view('admin.report')->with('datas',json_encode($datas));
    }

    public function getAllProductPrice(){
        $products_2021 = [];
        $products_2022 = [];
        $products_2023 = [];
        $months = ['01','02','03','04','05','06','07','08','09','10','11','12'];
        $month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $product_2021_arr = [];
        $product_2022_arr = [];
        $product_2023_arr = [];

        //2021
        $_2021 = Product::whereYear('created_at', '=', '2021')->sum(DB::raw('price'));
        foreach ($months as $key => $value) {
            $products_2021[(int)$key] = Product::where(\DB::raw("DATE_FORMAT(created_at, '%Y-%m')"),'2021'.'-'.$value)->sum(DB::raw('price'));  
        }

        for ($i = 0; $i <= 11; $i++) {
            if (!empty($products_2021[$i])) {
                $product_2021_arr[$i]['total'] = $products_2021[$i];
            } else {
                $product_2021_arr[$i]['total'] = 0;
            }
            $product_2021_arr[$i]['month'] = $month[$i];
        }

        //2022
        $_2022 = Product::whereYear('created_at', '=', '2022')->sum(DB::raw('price'));
        foreach ($months as $key => $value) {
            $products_2022[(int)$key] = Product::where(\DB::raw("DATE_FORMAT(created_at, '%Y-%m')"),'2022'.'-'.$value)->sum(DB::raw('price'));  
        }

        for ($i = 0; $i <= 11; $i++) {
            if (!empty($products_2022[$i])) {
                $product_2022_arr[$i]['total'] = $products_2022[$i];
            } else {
                $product_2022_arr[$i]['total'] = 0;
            }
            $product_2022_arr[$i]['month'] = $month[$i];
        }

        //2023
        $_2023 = Product::whereYear('created_at', '=', '2023')->sum(DB::raw('price'));
        foreach ($months as $key => $value) {
            $products_2023[(int)$key] = Product::where(\DB::raw("DATE_FORMAT(created_at, '%Y-%m')"),'2023'.'-'.$value)->sum(DB::raw('price'));  
        }

        for ($i = 0; $i <= 11; $i++) {
            if (!empty($products_2023[$i])) {
                $product_2023_arr[$i]['total'] = $products_2023[$i];
            } else {
                $product_2023_arr[$i]['total'] = 0;
            }
            $product_2023_arr[$i]['month'] = $month[$i];
        }

        $data=[
            'duasatu'=>[
                'year' => '2021',
                'total_price_year'=>$_2021,
                'months' => $product_2021_arr,],
            'duadua'=>[
                'year' => '2022',
                'total_price_year'=>$_2022,
                'months' => $product_2022_arr,],
            'duatiga'=>[
                    'year' => '2023',
                    'total_price_year'=>$_2023,
                    'months' => $product_2023_arr,],
        ];

        return response($data);
    }
}
