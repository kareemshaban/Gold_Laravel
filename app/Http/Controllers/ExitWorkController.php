<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyInfo;
use App\Models\EnterMoney;
use App\Models\ExitOld;
use App\Models\ExitWork;
use App\Models\ExitWorkDetails;
use App\Models\Karat;
use App\Models\Pricing;
use http\Env;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Alkoumi\LaravelArabicTafqeet\Tafqeet;

class ExitWorkController extends WarehouseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('exit_works')
            -> join('companies' , 'companies.id' , '=' , 'exit_works.client_id')
            -> select('exit_works.*' , 'companies.name as vendor_name')
            -> get();
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
        return view('Work.Exit.index' , compact('data' , 'routes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vendors = Company::where('group_id' , '=' , 3) -> get();

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

        return view('Work.Exit.Create' , compact('vendors' , 'routes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {



        $validated = $request->validate([
            'date' => 'required',
            'bill_number' => 'required|unique:exit_works',
            'client_id' => 'required'
        ]);
        $items = array();
        if(count($request -> item_id    )){
            //store header
            $total = 0 ;
            for($i = 0 ; $i < count($request -> item_id) ; $i++ ){
                $item =[
                    'bill_id' => 0,
                    'item_id' => $request -> item_id[$i],
                    'karat_id' => $request -> karat_id[$i],
                    'weight' => $request -> weight[$i],
                    'gram_price' => $request -> gram_price[$i],
                    'gram_manufacture' => $request -> gram_manufacture[$i],
                    'gram_tax' => $request -> gram_tax[$i],
                    'net_money'=> $request -> net_money[$i],
                ];
                $total += $request -> net_money[$i] ;
                $items[] = $item ;
            }

            $id =  ExitWork::create([
                'bill_number' => $request -> bill_number,
                'date' => $request -> date,
                'client_id' => $request -> client_id,
                'total_money' => $total,
                'total21_gold' => $request -> total_weight21,
                'paid_money' => 0,
                'remain_money' => $request -> net_after_discount,
                'paid_gold' => 0,
                'remain_gold' => 0,
                'notes'=> $request -> notes ?? '',
                'user_created' => Auth::user() -> id,
                'pos' => 0,
                'discount' => $request -> discount,
                'net_money' => $request -> net_after_discount,
                'bill_client_name' => $request -> bill_client_name
            ]) -> id;

            $auto_accounting =  env("AUTO_ACCOUNTING", 1);
            if($auto_accounting == 1){
                $systemController = new SystemController();

                $systemController -> ExitWorkAccounting($id);
            }


            foreach ($items as $product){
                $product['bill_id'] = $id;
                ExitWorkDetails::create($product) ;
                $this -> syncQnt(1 , $product['karat_id'], $id , $product['weight'] , -1 );
                $this -> makeItemUnAvailable($product['item_id'] );

            }
            $this -> syncVendorAccount($request -> client_id , $request -> net_after_discount ,0 , 1 ,
                $id , $request -> bill_number , 'Work Exit Bill');






            return redirect()->route('workExitAll')->with('success' ,  __('main.created'));
        } else {
            return redirect()->route('workExitAll')->with('error' ,  __('main.nodetails'));
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExitWork  $exitWork
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bill = DB::table('exit_works')
            -> leftJoin('companies' , 'companies.id' , '=' , 'exit_works.client_id')
            -> select('exit_works.*' , 'companies.name as vendor_name' , 'companies.vat_no as vendor_vat_no')
            -> where('exit_works.id' , '=' , $id)
            -> get() -> first();

        $vendors = Company::where('group_id' , '=' , 3) -> get();

        $details   =  DB::table('exit_work_details')
            -> join('items' , 'items.id' , '=' , 'exit_work_details.item_id')
            -> join('karats' , 'karats.id' , '=' , 'exit_work_details.karat_id')
            -> select('exit_work_details.*' , 'karats.name_ar as karat_ar' , 'karats.name_en as karat_en' , 'karats.transform_factor as transform_factor',   'items.name_ar as item_ar' , 'items.name_en as item_en')
            -> where('exit_work_details.bill_id' , '=' , $id)
            -> get();


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

      //  $bill = ExitWork::find($id);
       // return $bill ;

        return view('Work.Exit.Preview' , compact('bill' , 'details' , 'vendors', 'routes'));
    }
    public function print($id){
        $bill = DB::table('exit_works')
            -> join('companies' , 'companies.id' , '=' , 'exit_works.client_id')
            -> select('exit_works.*' , 'companies.name as vendor_name' , 'companies.phone as vendor_phone' , 'companies.vat_no as vendor_vat_no' )
            -> where('exit_works.id' , '=' , $id)
            -> get() -> first();

        $karats = Karat::all();
        $details   =  DB::table('exit_work_details')
            -> join('items' , 'items.id' , '=' , 'exit_work_details.item_id')
            -> join('karats' , 'karats.id' , '=' , 'exit_work_details.karat_id')
            -> select('exit_work_details.*' , 'karats.name_ar as karat_ar' , 'karats.name_en as karat_en' , 'karats.transform_factor as transform_factor',
                'items.name_ar as item_ar' , 'items.name_en as item_en' , 'items.no_metal' , 'items.no_metal_type' , 'items.code as item_code')
            -> where('exit_work_details.bill_id' , '=' , $id)
            -> get();


        $grouped_ar = $details   -> groupBy('karat_ar');


        $pos = \Illuminate\Support\Env::get('PROGRAMME_TYPE');

        $amar = Tafqeet::inArabic($bill -> net_money,'sar');

        $payments = EnterMoney::where('based_on_bill_number' , '=' , $bill -> bill_number) -> get();
        $company = CompanyInfo::all() -> firts();

       // return $payments ;
        if($pos == 0) {//A4
            return view('Work.Exit.print' , compact('bill' , 'details' , 'karats' , 'grouped_ar' , 'amar' , 'payments' , 'company' ));
        } else { //A5
            return view('Work.Exit.printA5' , compact('bill' , 'details' , 'karats' , 'grouped_ar' , 'amar' , 'payments'));
        }
    }

    public function Qrcode($id){
        $bill = DB::table('exit_works')
            -> join('companies' , 'companies.id' , '=' , 'exit_works.client_id')
            -> select('exit_works.*' , 'companies.name as vendor_name' , 'companies.vat_no as vendor_vat_no')
            -> where('exit_works.id' , '=' , $id)
            -> get() -> first();

      //  return $bill ;
        return view('Work.Exit.Qrcode' , compact('bill' ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExitWork  $exitWork
     * @return \Illuminate\Http\Response
     */
    public function edit(ExitWork $exitWork)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExitWork  $exitWork
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExitWork $exitWork)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExitWork  $exitWork
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExitWork $exitWork)
    {
        //
    }

    public function get_work_exit_no(){
        $bills = ExitWork::orderBy('id', 'ASC') -> where('returned_bill_id' , '>' , 0)->get();
        if(count($bills) > 0){
            $id = $bills[count($bills) -1] -> id ;
        } else
            $id = 0 ;
        $prefix = "WEX-";
        $no = json_encode($prefix . str_pad($id + 1, 6 , '0' , STR_PAD_LEFT)) ;
        echo $no ;
        exit;
    }
    public function get_sales_pos_no($type){

        if($type == 1){
            $bills = ExitWork::orderBy('id', 'ASC')->get();
            $prefix = "SWSI-";
        } else {
            $bills = ExitOld::orderBy('id', 'ASC')->get();
            $prefix = "SOSI-";
        }

        if(count($bills) > 0){
            $id = $bills[count($bills) -1] -> id ;
        } else
            $id = 0 ;

        $no = json_encode($prefix . str_pad($id + 1, 6 , '0' , STR_PAD_LEFT)) ;
        echo $no ;
        exit;
    }
    public function getKaratPrice(){

    }
}
