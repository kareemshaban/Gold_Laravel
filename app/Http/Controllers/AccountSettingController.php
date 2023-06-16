<?php

namespace App\Http\Controllers;

use App\Models\AccountSetting;
use App\Http\Requests\StoreAccountSettingRequest;
use App\Http\Requests\UpdateAccountSettingRequest;
use App\Models\AccountsTree;
use App\Models\Pricing;
use App\Models\Storehouse;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = AccountSetting::all();
        foreach ($accounts as $account){
            $account->warehouse_name = Storehouse::find($account->warehouse_id)->name;
            $account->safe_account_name = AccountsTree::find($account->safe_account)->name;
            $account->sales_account_name = AccountsTree::find($account->sales_account)->name;
            $account->purchase_account_name = AccountsTree::find($account->purchase_account)->name;
            $account->return_sales_account_name = AccountsTree::find($account->return_sales_account)->name;
            $account->return_purchase_account_name = AccountsTree::find($account->return_purchase_account)->name;
            $account->stock_account_name = AccountsTree::find($account->stock_account)->name;
            $account->sales_discount_account_name = AccountsTree::find($account->sales_discount_account)->name;
            $account->sales_tax_account_name = AccountsTree::find($account->sales_tax_account)->name;
            $account->purchase_discount_account_name = AccountsTree::find($account->purchase_discount_account)->name;
            $account->purchase_tax_account_name = AccountsTree::find($account->purchase_tax_account)->name;

            $account->cost_account_name = AccountsTree::find($account->cost_account)->name;
            $account->profit_account_name = AccountsTree::find($account->profit_account)->name;

            $account->reverse_profit_account_name = AccountsTree::find($account->reverse_profit_account)->name;
        }

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
        return view('accounts.settings',compact('accounts' , 'routes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accounts = AccountsTree::query()->where('type','>',1)->get();
        $warehouses = Storehouse::all();

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

        return view('accounts.create_settings',compact('accounts','warehouses' , 'routes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAccountSettingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAccountSettingRequest $request)
    {
        AccountSetting::create($request->all());
        return redirect()->route('account_settings_list');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccountSetting  $accountSetting
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AccountSetting  $accountSetting
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $accounts = AccountsTree::query()->where('type','>',1)->get();
        $warehouses = Storehouse::all();
        $setting = AccountSetting::find($id);
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
        return view('accounts.update_settings',compact('accounts','warehouses' , 'setting' , 'routes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAccountSettingRequest  $request
     * @param  \App\Models\AccountSetting  $accountSetting
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAccountSettingRequest $request, $id)
    {
        $setting = AccountSetting::find($id);
        if($setting){
            $setting -> update($request->all());
            return redirect()->route('account_settings_list');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccountSetting  $accountSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccountSetting $accountSetting)
    {
        //
    }
}
