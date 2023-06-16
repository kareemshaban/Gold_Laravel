<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\EnterOld;
use App\Models\EnterWork;
use App\Models\ExitMoney;
use App\Models\ExitWork;
use App\Models\Pricing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExitMoneyController extends WarehouseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('exit_money')
            ->join('companies' , 'exit_money.supplier_id' , '=' , 'companies.id')
            ->leftJoin('enter_works' , 'exit_money.based_on' , 'enter_works.id')
            ->leftJoin('enter_olds' , 'exit_money.based_on' , 'enter_olds.id')
            ->select('exit_money.*' , 'companies.name as vendor_name' , 'enter_works.bill_number as invoice_number0' , 'enter_olds.bill_number as invoice_number1'  )
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

        return view('Money.Exit.index' , compact('data' , 'routes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $suppliers = Company::where('group_id' , '=' , 4) -> get();
        $bill_no = $this -> getBillNo();
        $pricing = Pricing::all() -> first();
        $html = view('Money.Exit.create' , compact('suppliers','bill_no' , 'pricing')) -> render();
        return $html ;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

     //   return $request ;
        $validated = $request->validate([
            'date' => 'required',
            'doc_number' => 'required|unique:exit_money',
            'supplier_id' => 'required',
            'based_on' => 'required',
            'amount' => 'required',
            'type' => 'required'
        ]);

        $id =  ExitMoney::create([
            'doc_number' => $request ->doc_number ,
            'date' => $request -> date,
            'supplier_id' => $request -> supplier_id,
            'type' => $request -> type ,
            'based_on' => $request -> based_on,
            'amount' => $request -> amount,
            'payment_method' => $request -> payment_method,
            'user_created' => Auth::user() -> id,
            'notes' => $request -> notes ?? '',
            'price_gram' => $request -> price_gram
        ]) -> id   ;

        $auto_accounting =  env("AUTO_ACCOUNTING", 1);
        if($auto_accounting == 1){
            $systemController = new SystemController();

            $systemController -> ExitMoneyAccounting($id);
        }

        if($id) {

            $money = 0 ;
            $gold = 0 ;
            if($request->type == 0){
                $money = $request -> amount ;   //تسديد أجر للمصنع
                $gold = 0 ;
            } else if($request->type == 1){
                //هنسدد الذهب الكسر  بنقدية
                $money = 0 ;
                $gold = ($request -> amount / $request -> price_gram);
            } else if($request->type == 2){
                //هنسدد الذهب المشغول بنقدية
                $money = 0 ;
                $gold = ($request -> amount / $request -> price_gram);

            }
            $this->syncVendorAccount($request->supplier_id, $money, $gold, 1,
                $id, $request->doc_number, 'Exit Money Bill');



            if ($request->type == 0) {
                $bill = EnterWork::find($request->based_on);
                $bill->remain_money -= $request->amount;
                $bill->paid_money += $request->amount;
                $bill->update();
            } else if ($request->type == 2){
                $bill = EnterWork::find($request->based_on);
                $bill->remain_gold -= $gold;
                $bill->paid_gold += $gold;
                $bill->update();
            }
            else {
                $bill = EnterOld::find($request->based_on);
                $bill->remain_gold -= $gold;
                $bill->paid_gold += $gold;
                $bill->update();

            }


            return redirect()->route('money_exit_list')->with('success', __('main.created'));
        }
        return redirect()->route('money_exit_list')->with('error', __('something went wrong'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExitMoney  $exitMoney
     * @return \Illuminate\Http\Response
     */
    public function show($id , $type)
    {
        $clients = Company::all();
        if($type == 0 || $type == 2){
            $bill = DB::table('exit_money')
                ->join('companies' , 'exit_money.supplier_id' , '=' , 'companies.id')
                ->join('enter_works' , 'exit_money.based_on' , 'enter_works.id')
                ->select('exit_money.*' , 'companies.name as vendor_name' , 'enter_works.bill_number as invoice_number')
                ->where('exit_money.id' , '=' ,$id )
                -> get() -> first();
        } else {
            $bill = DB::table('exit_money')
                ->join('companies' , 'exit_money.supplier_id' , '=' , 'companies.id')
                ->join('enter_olds' , 'exit_money.based_on' , 'enter_olds.id')
                ->select('exit_money.*' , 'companies.name as vendor_name' , 'enter_olds.bill_number as invoice_number')
                ->where('exit_money.id' , '=' ,$id )
                -> get() -> first();
        }


        $html = view('Money.Exit.view' , compact('bill', 'clients')) -> render();
        return $html ;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExitMoney  $exitMoney
     * @return \Illuminate\Http\Response
     */
    public function edit(ExitMoney $exitMoney)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExitMoney  $exitMoney
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExitMoney $exitMoney)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExitMoney  $exitMoney
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExitMoney $exitMoney)
    {
        //
    }

    public function getBillNo(){
        $bills = ExitMoney::orderBy('id', 'ASC')->get();
        if(count($bills) > 0){
            $id = $bills[count($bills) -1] -> id ;
        } else
            $id = 0 ;
        $prefix = "MEx-";
        $no = ($prefix . str_pad($id + 1, 6 , '0' , STR_PAD_LEFT)) ;
        return $no ;
    }
    public function getClientSupplier($type){
        if($type == 0 || $type == 2){
            $vendors = Company::where('group_id' , '=' , 4) -> get();
        } else {
            $vendors = Company::where('group_id' , '=' , 3) -> get();
        }

        echo json_encode($vendors);
        exit();
    }
    public function getClientSupplierWorks($client_id , $type){
        if($type == 0 || $type == 2){
            $works = EnterWork::where('supplier_id' , '=' ,$client_id )-> get();
        } else {
            $works = EnterOld::where('client_id' , '=' ,$client_id )-> get();
        }
        echo json_encode($works);
        exit();

    }

    public function getClientDocumentdata($id , $type){
        if($type == 0 || $type == 2){
            $document = EnterWork::find($id);
            echo json_encode($document);
            exit();
        } else if ($type == 1){
            $document = EnterOld::find($id);
            echo json_encode($document);
            exit();
        } else if ($type == 4){
            $document = ExitWork::find($id);
            echo json_encode($document);
            exit();
        }
    }

}
