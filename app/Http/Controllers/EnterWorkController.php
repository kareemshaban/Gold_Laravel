<?php

namespace App\Http\Controllers;

use App\Models\AccountMovement;
use App\Models\Company;
use App\Models\EnterOld;
use App\Models\EnterWork;
use App\Models\EnterWorkDetails;
use App\Models\Journal;
use App\Models\JournalDetails;
use App\Models\Karat;
use App\Models\Pricing;
use App\Models\TaxSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EnterWorkController extends WarehouseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('enter_works')
            -> join('companies' , 'companies.id' , '=' , 'enter_works.supplier_id')
           -> select('enter_works.*' , 'companies.name as vendor_name')
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
        return view('Work.Enter.index' , compact('data' , 'routes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vendors = Company::where('group_id' , '=' , 4) -> get();
        $karats = Karat::all();
        $setting = TaxSettings::all() -> first();

        $roless = DB::table('role_views')
            -> join('views' , 'role_views.view_id' , '=' , 'views.id')
            ->join('roles' , 'role_views.role_id' , '=' , 'roles.id')
            ->select('role_views.*' , 'views.name_ar as view_name_ar' ,  'views.name_en as view_name_en' ,
                'roles.name_ar as role_name_ar' ,  'roles.name_en as role_name_en' , 'views.route')
            ->where('role_views.role_id' , '=' , Auth::user() -> role_id)
            ->where('role_views.all_auth' , '=' , 1)
            -> get();


        $routes = [] ;
        foreach ($roless as $role){
            array_push($routes , $role -> route);
        }

        return view('Work.Enter.Create' , compact('vendors' , 'karats' , 'setting' , 'routes'));

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
            'bill_number' => 'required|unique:enter_works',
            'supplier_id' => 'required'
        ]);

        $items = array();
        if(count($request -> karat_id)){
            //store header
            $total_money = 0 ;
            $total21_gold = 0 ;
            for($i = 0 ; $i < count($request -> karat_id) ; $i++ ){
                    $item =[
                        'bill_id' => 0,
                        'karat_id' => $request -> karat_id[$i],
                        'weight' => $request -> weight[$i],
                        'weight21'=> $request -> weight21[$i],
                        'made_money'=> $request -> made_money[$i],
                        'net_weight' => $request -> weight [$i],
                        'net_money' => $request -> made_money[$i],
                    ];
                    $total_money += $request -> made_money[$i];
                    $total21_gold += $request -> weight21[$i];
                    $items[] = $item ;
            }

           $id =  EnterWork::create([
                'bill_number' => $request -> bill_number,
                'date' => $request -> date,
                'supplier_id' => $request -> supplier_id,
                'total_money' => $total_money,
                'total21_gold' => $total21_gold,
                'paid_money' => 0,
                'remain_money' => $request -> net_after_discount,
                'paid_gold' => 0,
                'remain_gold' => $total21_gold,
                'discount' => $request -> discount,
                'tax' => $request -> tax,
                'net_money' => $request -> net_after_discount,
                'notes'=> $request -> notes ?? '',
                'user_created' => Auth::user() -> id,
                'pos' => 0,
               'supplier_bill_number' => $request -> supplier_bill_number
            ]) -> id;

            foreach ($items as $product){
                $product['bill_id'] = $id;
                EnterWorkDetails::create($product) ;

                $this -> syncQnt(1 , $product['karat_id'], $id , $product['weight'] , 1 );
            }
             $this -> syncVendorAccount($request -> supplier_id , $request -> net_after_discount ,$total21_gold , -1 ,
                 $id , $request -> bill_number , 'Work Entry Bill');



           $auto_accounting =  env("AUTO_ACCOUNTING", 1);
           if($auto_accounting == 1){
               $systemController = new SystemController();

               $systemController -> EnterWorkAccounting($id);
           }



            return redirect()->route('workEntryAll')->with('success' ,  __('main.created'));
        } else {
            return redirect()->route('workEntryAll')->with('error' ,  __('main.nodetails'));
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EnterWork  $enterWork
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bill = DB::table('enter_works')
            -> join('companies' , 'companies.id' , '=' , 'enter_works.supplier_id')
            -> select('enter_works.*' , 'companies.name as vendor_name')
            -> where('enter_works.id' , '=' , $id)
            -> get() -> first();

        $vendors = Company::where('group_id' , '=' , 4) -> get();

        $details   =  DB::table('enter_work_details')
            -> join('karats' , 'karats.id' , '=' , 'enter_work_details.karat_id')
            -> select('enter_work_details.*' , 'karats.name_ar as karat_ar' , 'karats.name_en as karat_en' , 'karats.transform_factor')
            -> where('enter_work_details.bill_id' , '=' , $id)
            -> get();

        $roless = DB::table('role_views')
            -> join('views' , 'role_views.view_id' , '=' , 'views.id')
            ->join('roles' , 'role_views.role_id' , '=' , 'roles.id')
            ->select('role_views.*' , 'views.name_ar as view_name_ar' ,  'views.name_en as view_name_en' ,
                'roles.name_ar as role_name_ar' ,  'roles.name_en as role_name_en' , 'views.route')
            ->where('role_views.role_id' , '=' , Auth::user() -> role_id)
            ->where('role_views.all_auth' , '=' , 1)
            -> get();


        $routes = [] ;
        foreach ($roless as $role) {
            array_push($routes, $role->route);
        }

            return view('Work.Enter.Preview' , compact('bill' , 'details' , 'vendors' , 'routes'));
    }

    public function print($id){
        $bill = DB::table('enter_works')
            -> join('companies' , 'companies.id' , '=' , 'enter_works.supplier_id')
            -> select('enter_works.*' , 'companies.name as vendor_name' , 'companies.vat_no as vendor_vat_no')
            -> where('enter_works.id' , '=' , $id)
            -> get() -> first();


        $karats = Karat::all();
        $details   =  DB::table('enter_work_details')
            -> join('karats' , 'karats.id' , '=' , 'enter_work_details.karat_id')
            -> select('enter_work_details.*' , 'karats.name_ar as karat_ar' , 'karats.name_en as karat_en' , 'karats.transform_factor')
            -> where('enter_work_details.bill_id' , '=' , $id)
            -> get();

        $grouped_ar = $details   -> groupBy('karat_ar');

        $pos = \Illuminate\Support\Env::get('PROGRAMME_TYPE');
        if($pos == 0) {//A4
            return view('Work.Enter.print' , compact('bill' , 'details' , 'karats' , 'grouped_ar'));
        } else { //A5
            return view('Work.Enter.printA5 ' , compact('bill' , 'details' , 'karats' , 'grouped_ar'));

        }



    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EnterWork  $enterWork
     * @return \Illuminate\Http\Response
     */
    public function edit(EnterWork $enterWork)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EnterWork  $enterWork
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EnterWork $enterWork)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EnterWork  $enterWork
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bill = EnterWork::find($id);
        if($bill){
            $details = EnterWorkDetails::where('bill_id' , '=' , $id) -> get();
            $this -> deleteQnt($id);
            $this -> deleteVendorMove($bill -> supplier_id , $id , $bill -> total_money , $bill -> total21_gold , 'Work Entry Bill');
            $this -> deleteAccountingData($id , $bill -> bill_number , 'شراء ذهب مشغول');


            foreach ($details as $detail){
                $detail -> delete();
            }
            $bill -> delete();
            return redirect()->route('workEntryAll')->with('success' ,  __('main.deleted'));
        }


    }
    function deleteAccountingData($bill_id , $bill_number , $basedon_txt){
        $journal = Journal::where('basedon_no' , '=' , $bill_number)
            ->where('basedon_id' , '=' , $bill_id)
            ->where('baseon_text' , '=' , $basedon_txt) -> get() -> first();
        if($journal){
            $details = JournalDetails::where('journal_id' , '=' , $journal -> id) -> get();
            $movements = AccountMovement::where('journal_id' , '=' , $journal -> id) -> get();
            foreach ($movements as $movement){
                $movement -> delete();
            }
            foreach ($details as $detail){
                $detail -> delete();
            }
            $journal -> delete();
        }
    }


    public function get_work_entry_no(){
        $bills = EnterWork::orderBy('id', 'ASC')->get();
        if(count($bills) > 0){
            $id = $bills[count($bills) -1] -> id ;
        } else
            $id = 0 ;
        $prefix = "WE-";
        $no = json_encode($prefix . str_pad($id + 1, 6 , '0' , STR_PAD_LEFT)) ;
        echo $no ;
        exit;
    }
    public function get_purchase_pos_no(){
        $bills = EnterOld::orderBy('id', 'ASC')->get();
        if(count($bills) > 0){
            $id = $bills[count($bills) -1] -> id ;
        } else
            $id = 0 ;
        $prefix = "SPOI-";
        $no = json_encode($prefix . str_pad($id + 1, 6 , '0' , STR_PAD_LEFT)) ;
        echo $no ;
        exit;
    }

}
