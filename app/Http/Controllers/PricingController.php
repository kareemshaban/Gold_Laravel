<?php

namespace App\Http\Controllers;

use App\Models\Pricing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PricingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pricings = Pricing::all();
        $pricings = Pricing::all();
        $roles = DB::table('role_views')
            -> join('views' , 'role_views.view_id' , '=' , 'views.id')
            ->join('roles' , 'role_views.role_id' , '=' , 'roles.id')
            ->select('role_views.*' , 'views.name_ar as view_name_ar' ,  'views.name_en as view_name_en' ,
                'roles.name_ar as role_name_ar' ,  'roles.name_en as role_name_en' , 'views.route')
            ->where('role_views.role_id' , '=' , Auth::user() -> role_id)
            ->where('role_views.all_auth' , '=' , 1)
            -> get();


        $routes = [] ;
        foreach ($roles as $role){
            array_push($routes , $role -> route);
        }
        return  view('Pricing.index' , compact('pricings' , 'routes'))  ;
    }

    public function pricing(){
        $pricings = Pricing::all();
        if(count($pricings) == 0){
            return $this -> updatePricng() ;
        }
        $pricings = $pricings -> first();
        return  view('welcome' , compact('pricings'))  ;

    }
    public function updatePricng(){
        $apiURL = 'https://gold-price-live.p.rapidapi.com/get_metal_prices';

        $client = new \GuzzleHttp\Client([ 'verify' => false , 'headers' => ['X-RapidAPI-Key' => '9e8093f608msh0f02470f904a42bp1b7dc1jsn58dcc8e943fb' , 'X-RapidAPI-Host' => 'gold-price-live.p.rapidapi.com']]);
        $response = $client->request('GET', $apiURL);
        $responseBody = json_decode($response->getBody(), true);
        $price21 = $responseBody['gold'] ;
        $price24 = (24/21) * $price21 ; // / 31.1035 ;
        $price = $price24 * 31.1035;

        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );



        Pricing::create([
            'last_Update' => Carbon::now(),
            'user_update' => Auth::user() ? Auth::user() -> id : 0,
            'price' => $price,
            'price_21' => $price21,
            'price_22' => (22/21) * $price21,
            'price_24' => $price24,
            'price_18' => (18/21) * $price21,
            'price_14' => (14/21) * $price21,
            'currency' =>  file_get_contents('https://ipapi.co/currency/' , false , stream_context_create($arrContextOptions))

            ]);
        $pricings = Pricing::all() -> first();

        return  view('welcome' , compact('pricings'))  ;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pricing  $pricing
     * @return \Illuminate\Http\Response
     */
    public function show(Pricing $pricing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pricing  $pricing
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $apiURL = 'https://gold-price-live.p.rapidapi.com/get_metal_prices';

        $client = new \GuzzleHttp\Client(['headers' => ['X-RapidAPI-Key' => '9e8093f608msh0f02470f904a42bp1b7dc1jsn58dcc8e943fb' , 'X-RapidAPI-Host' => 'gold-price-live.p.rapidapi.com']]);
        $response = $client->request('GET', $apiURL);
        $responseBody = json_decode($response->getBody(), true);
        $price21 = $responseBody['gold'] ;
        $price24 = (24/21) * $price21 ; // / 31.1035 ;
        $price = $price24 * 31.1035;

        $pricings = Pricing::all();
        foreach ($pricings as $pricing){
            $pricing -> delete();
        }


        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );

        Pricing::create([
            'last_Update' => Carbon::now(),
            'user_update' => Auth::user() ? Auth::user() -> id : 0,
            'price' => $price,
            'price_21' => $price21,
            'price_22' => (22/21) * $price21,
            'price_24' => $price24,
            'price_18' => (18/21) * $price21,
            'price_14' => (14/21) * $price21,
            'currency' =>  file_get_contents('https://ipapi.co/currency/' , false , stream_context_create($arrContextOptions))

        ]);

        return redirect() -> route('prices')->with('success' , __('main.price_updated')) ;
       // return  view('Pricing.index' , compact('pricings')) ->with('success' , __('main.price_updated')) ;

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pricing  $pricing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $price21 = $request -> price21 ;
        $price24 = (24/21) *  $request -> price21 ;
        $price = $price24 * 31.1035;

        $pricings = Pricing::all();
        foreach ($pricings as $pricing){
            $pricing -> delete();
        }

        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );

        Pricing::create([
            'last_Update' => Carbon::now(),
            'user_update' => Auth::user() ? Auth::user() -> id : 0,
            'price' => $price,
            'price_21' => $price21,
            'price_22' => (22/21) * $price21,
            'price_24' => $price24,
            'price_18' => (18/21) * $price21,
            'price_14' => (14/21) * $price21,
            'currency' =>  file_get_contents('https://ipapi.co/currency/' , false , stream_context_create($arrContextOptions))

        ]);

        return redirect() -> route('prices')->with('success' , __('main.price_updated')) ;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pricing  $pricing
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pricing $pricing)
    {
        //
    }

}
