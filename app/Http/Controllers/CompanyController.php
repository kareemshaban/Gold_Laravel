<?php

namespace App\Http\Controllers;

use App\Models\AccountsTree;
use App\Models\Company;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\CompanyMovement;
use App\Models\CustomerGroup;
use App\Models\Pricing;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {
        $companies = Company::all();
        $accounts = AccountsTree::all() ;
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
        return view('Company.index' , ['type' => $type , 'companies' =>
            $companies , 'accounts' => $accounts , 'routes' => $routes] );
    }
    public function clientAccount($id){
        $client = Company::find($id);
        $type = $client -> group_id ;
        $movements = CompanyMovement::where('company_id' , '=' , $id) -> get();
        $slag =  $type == 3 ? 5 : 4;
        $subSlag = 4 ;

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

        return view('Company.accountMovement' , compact('type' , 'movements' , 'slag' , 'subSlag' , 'routes'));
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
     * @param  \App\Http\Requests\StoreCompanyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if ($request -> id == 0){
            $validated = $request->validate([
                'company' => 'required',
                'name' => 'required',
                'opening_balance' => 'required',
                'type' => 'required'
            ]);
            try {
                Company::create([
                    'group_id' => $request -> type,
                    'group_name' => '',
                    'customer_group_id' => $request -> customer_group_id ? $request -> customer_group_id : 0 ,
                    'customer_group_name' => '',
                    'name' => $request->name,
                    'company' => $request->company,
                    'vat_no' => $request->vat_no ? $request->vat_no : '',
                    'address' => $request-> address ? $request-> address: '',
                    'city' => '' ,
                    'state' => '',
                    'postal_code' => '',
                    'country' => '',
                    'email' => $request -> email ? $request -> email : '',
                    'phone' => $request -> phone ? $request -> phone : '',
                    'invoice_footer' => '',
                    'logo' => '',
                    'award_points' => 0 ,
                    'deposit_amount' => 0 ,
                    'credit_gold' => 0 ,
                    'deposit_gold' => 0 ,
                    'opening_balance' =>$request -> opening_balance? $request -> opening_balance: 0 ,
                    'credit_amount' =>$request -> has('credit_amount')? $request -> credit_amount: 0 ,
                    'stop_sale' =>$request -> has('stop_sale')? 1: 0 ,
                    'account_id' => $request -> account_id

                ]);
                return redirect()->route('clients' , $request -> type)->with('success' , __('main.created'));
            } catch(QueryException $ex){

                return redirect()->route('clients' , $request -> type)->with('error' ,  $ex->getMessage());
            }
        } else {
         return   $this -> update($request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $company = Company::find($id);
        echo json_encode ($company);
        exit;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCompanyRequest  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request  $request)
    {
        $company = Company::find($request -> id);
        if($company){
            $validated = $request->validate([
                'company' => 'required',
                'name' => 'required',
                'opening_balance' => 'required',
                'type' => 'required'
            ]);
            try {
                $company -> update([
                    'group_id' => $request -> type,
                    'group_name' => '',
                    'customer_group_id' => $request -> customer_group_id ? $request -> customer_group_id : $company -> customer_group_id,
                    'customer_group_name' => '',
                    'name' => $request->name,
                    'company' => $request->company,
                    'vat_no' => $request->vat_no ? $company->vat_no : '',
                    'address' => $request-> address ? $company-> address: '',
                    'city' => '' ,
                    'state' => '',
                    'postal_code' => '',
                    'country' => '',
                    'email' => $request -> email ? $request -> email : '',
                    'phone' => $request -> phone ? $request -> phone : '',
                    'invoice_footer' => '',
                    'logo' => '',
                    'award_points' => 0 ,
                    'opening_balance' =>$request -> opening_balance? $request -> opening_balance: $company ->  opening_balance,
                    'stop_sale' =>$request -> has('stop_sale')? 1: $company -> stop_sale ,

                ]);
                return redirect()->route('clients' , $request -> type)->with('success' , __('main.updated'));
            } catch(QueryException $ex){

                return redirect()->route('clients' , $request -> type)->with('error' ,  $ex->getMessage());
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id)
    {
        $company = Company::find($id);
        if($company){
            $type = $company -> group_id ;
            $company -> delete();
            return redirect()->route('clients' , $type )->with('success' , __('main.deleted'));
        }
    }
}
