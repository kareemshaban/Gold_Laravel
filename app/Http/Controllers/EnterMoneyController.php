<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\EnterMoney;
use App\Models\ExitWork;
use App\Models\Pricing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EnterMoneyController extends WarehouseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('enter_money')
            ->join('companies' , 'enter_money.client_id' , '=' , 'companies.id')
            ->join('exit_works' , 'enter_money.based_on' , 'exit_works.id')
            ->select('enter_money.*' , 'companies.name as vendor_name' , 'exit_works.bill_number as invoice_number')
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
        return view('Money.Enter.index' , compact('data' , 'routes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Company::where('group_id' , '=' , 3) -> get();
        $bill_no = $this -> getBillNo();
        $html = view('Money.Enter.create' , compact('clients','bill_no')) -> render();
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
        $validated = $request->validate([
            'date' => 'required',
            'doc_number' => 'required|unique:enter_money',
            'client_id' => 'required',
            'based_on' => 'required',
            'amount' => 'required',
        ]);
        $id =  EnterMoney::create([
             'doc_number' => $request ->doc_number ,
             'date' => $request -> date,
             'client_id' => $request -> client_id,
             'amount' => $request -> amount,
             'payment_method' => $request -> payment_method,
             'user_created' => Auth::user() -> id,
             'based_on' => $request -> based_on,
             'based_on_bill_number' => $request -> based_on_bill_number ,
             'notes' => $request -> notes ?? ''
         ]) -> id   ;
        $this -> syncVendorAccount($request -> client_id , $request -> amount ,0 , -1 ,
            $id , $request -> doc_number , 'Enter Money Bill');

        $auto_accounting =  env("AUTO_ACCOUNTING", 1);
        if($auto_accounting == 1){
            $systemController = new SystemController();

            $systemController -> EnterMoneyAccounting($id);
        }

        if($request -> based_on > 0){
            $bill = ExitWork::find($request -> based_on);
            if($bill ){
                $bill -> remain_money -= $request -> amount ;
                $bill -> paid_money += $request -> amount ;
                $bill -> update();
            }
        }

         return redirect() -> route('money_entry_list') -> with('success' , __('main.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EnterMoney  $enterMoney
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $clients = Company::where('group_id' , '=' , 3) -> get();
        $bill = DB::table('enter_money')
            ->join('companies' , 'enter_money.client_id' , '=' , 'companies.id')
            ->join('exit_works' , 'enter_money.based_on' , 'exit_works.id')
            ->select('enter_money.*' , 'companies.name as vendor_name' , 'exit_works.bill_number as invoice_number')
            ->where('enter_money.id' , '=' ,$id )
            -> get() -> first();

     //   return $bill ;

        $html = view('Money.Enter.view' , compact('bill', 'clients')) -> render();
        return $html ;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EnterMoney  $enterMoney
     * @return \Illuminate\Http\Response
     */
    public function edit(EnterMoney $enterMoney)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EnterMoney  $enterMoney
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EnterMoney $enterMoney)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EnterMoney  $enterMoney
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bill = EnterMoney::find($id);
        if ($bill) {
            $this->deleteVendorMove($bill->client_id, $id, $bill->amount, 0, 'Enter Money Bill');
            if ($bill->based_on > 0) {
                $exit = ExitWork::find($bill->based_on);
                if ($exit) {
                    $exit->remain_money += $bill->amount;
                    $exit->paid_money -= $bill->amount;
                    $exit->update();
                }
            }
            $bill ->delete();
            return redirect() -> route('money_entry_list') -> with('success' , __('main.deleted'));
        }
    }
    public function getBillNo(){
        $bills = EnterMoney::orderBy('id', 'ASC')->get();
        if(count($bills) > 0){
            $id = $bills[count($bills) -1] -> id ;
        } else
            $id = 0 ;
        $prefix = "ME-";
        $no = ($prefix . str_pad($id + 1, 6 , '0' , STR_PAD_LEFT)) ;
        return $no ;
    }
    public function getClientExitWorks($id){
        $bills = ExitWork::where('client_id' , '=' , $id)->get();
        echo json_encode($bills);
        exit();
    }
}
