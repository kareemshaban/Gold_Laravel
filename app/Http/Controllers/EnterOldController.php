<?php

namespace App\Http\Controllers;

use Alkoumi\LaravelArabicTafqeet\Tafqeet;
use App\Models\Company;
use App\Models\EnterOld;
use App\Models\EnterOldDetails;
use App\Models\Karat;
use App\Models\Pricing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EnterOldController extends WarehouseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('enter_olds')
            -> join('companies' , 'companies.id' , '=' , 'enter_olds.client_id')
            -> select('enter_olds.*' , 'companies.name as vendor_name')
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
        return view('Old.Enter.index' , compact('data' , 'routes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vendors = Company::where('group_id' , '=' , 3) -> get();
        $karats = Karat::all();

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

        return view('Old.Enter.Create' , compact('vendors' , 'karats' , 'routes'));
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
            'bill_number' => 'required|unique:enter_olds',
            'client_id' => 'required'
        ]);
        $items = array();
        if(count($request -> karat_id)){
            //store header
            $total21_gold = 0 ;
            $total_money = 0 ;

            for($i = 0 ; $i < count($request -> karat_id) ; $i++ ){
                $item =[
                    'bill_id' => 0,
                    'karat_id' => $request -> karat_id[$i],
                    'gram_price' => 0,
                    'weight' => $request -> weight[$i],
                    'weight21'=> $request -> weight21[$i],
                    'made_money'=> 0,
                    'net_weight' => $request -> weight [$i],
                    'net_money' => 0,
                ];
                $total21_gold += $request -> weight21[$i];
                $items[] = $item ;
            }

            $id =  EnterOld::create([
                'bill_number' => $request -> bill_number,
                'date' => $request -> date,
                'client_id' => $request -> client_id,
                'total_money' => 0,
                'total21_gold' => $total21_gold,
                'paid_money' => 0,
                'remain_money' => 0,
                'paid_gold' => 0,
                'remain_gold' => $total21_gold,
                'discount' => 0,
                'net_money' => 0 ,
                'notes'=> $request -> notes ?? '',
                'user_created' => Auth::user() -> id,
            ]) -> id;

            $auto_accounting =  env("AUTO_ACCOUNTING", 1);
            if($auto_accounting == 1){
                $systemController = new SystemController();

                $systemController -> EnterOldAccounting($id);
            }

            foreach ($items as $product){
                $product['bill_id'] = $id;
                EnterOldDetails::create($product) ;

                $this -> syncQnt(0 , $product['karat_id'], $id , $product['weight'] , 1 );
            }
            $this -> syncVendorAccount($request -> client_id , 0 ,$total21_gold , -1 ,
                $id , $request -> bill_number , 'Old Entry Bill');

            return redirect()->route('oldEntryAll')->with('success' ,  __('main.created'));
        } else {
            return redirect()->route('oldEntryAll')->with('error' ,  __('main.nodetails'));
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EnterOld  $enterOld
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bill = DB::table('enter_olds')
            -> join('companies' , 'companies.id' , '=' , 'enter_olds.client_id')
            -> select('enter_olds.*' , 'companies.name as vendor_name')
            -> where('enter_olds.id' , '=' , $id)
            -> get() -> first();

        $vendors = Company::where('group_id' , '=' , 3) -> get();

        $details   =  DB::table('enter_old_details')
            -> join('karats' , 'karats.id' , '=' , 'enter_old_details.karat_id')
            -> select('enter_old_details.*' , 'karats.name_ar as karat_ar' , 'karats.name_en as karat_en' , 'karats.transform_factor')
            -> where('enter_old_details.bill_id' , '=' , $id)
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

        return view('Old.Enter.Preview' , compact('bill' , 'details' , 'vendors' , 'routes'));
    }

    public function print($id){
        $bill = DB::table('enter_olds')
            -> join('companies' , 'companies.id' , '=' , 'enter_olds.client_id')
            -> select('enter_olds.*' , 'companies.name as vendor_name' , 'companies.vat_no as vendor_vat_no')
            -> where('enter_olds.id' , '=' , $id)
            -> get() -> first();

        $vendors = Company::where('group_id' , '=' , 3) -> get();

        $details   =  DB::table('enter_old_details')
            -> join('karats' , 'karats.id' , '=' , 'enter_old_details.karat_id')
            -> select('enter_old_details.*' , 'karats.name_ar as karat_ar' , 'karats.name_en as karat_en' , 'karats.transform_factor')
            -> where('enter_old_details.bill_id' , '=' , $id)
            -> get();


        $grouped_ar = $details   -> groupBy('karat_ar');
        $karats = Karat::all();
        $pos = \Illuminate\Support\Env::get('PROGRAMME_TYPE');
        $amar = Tafqeet::inArabic($bill -> net_money,'sar');

        if($pos == 0) {//A4

            return view('Old.Enter.print' , compact('bill' , 'details' , 'vendors' , 'karats' , 'grouped_ar' , 'amar'));
        } else { //A5
            return view('Old.Enter.printA5' , compact('bill' , 'details' , 'vendors' , 'karats' , 'grouped_ar' , 'amar'));
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EnterOld  $enterOld
     * @return \Illuminate\Http\Response
     */
    public function edit(EnterOld $enterOld)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EnterOld  $enterOld
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EnterOld $enterOld)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EnterOld  $enterOld
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bill = EnterOld::find($id);
        if($bill){
            $details = EnterOldDetails::where('bill_id' , '=' , $id) -> get();
            $this -> deleteQnt($id);
            $this -> deleteVendorMove($bill -> client_id , $id , 0 , $bill -> total21_gold , 'Old Entry Bill');
            foreach ($details as $detail){
                $detail -> delete();
            }
            $bill -> delete();
            return redirect()->route('oldEntryAll')->with('success' ,  __('main.deleted'));
        }
    }

    public function get_old_entry_no(){
        $bills = EnterOld::orderBy('id', 'ASC')->get();
        if(count($bills) > 0){
            $id = $bills[count($bills) -1] -> id ;
        } else
            $id = 0 ;
        $prefix = "OE-";
        $no = json_encode($prefix . str_pad($id + 1, 6 , '0' , STR_PAD_LEFT)) ;
        echo $no ;
        exit;
    }
}
