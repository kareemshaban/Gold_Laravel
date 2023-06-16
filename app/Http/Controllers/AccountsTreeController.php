<?php

namespace App\Http\Controllers;

use App\Models\AccountsTree;
use App\Http\Requests\StoreAccountsTreeRequest;
use App\Http\Requests\UpdateAccountsTreeRequest;
use App\Models\Journal;
use App\Models\Payment;
use App\Models\Pricing;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AccountsTreeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = AccountsTree::all();
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
        return view('accounts.index',compact('accounts' , 'routes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accounts = AccountsTree::all();

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

        return view('accounts.create',compact('accounts' , 'routes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAccountsTreeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAccountsTreeRequest $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:accounts_trees',
            'name' => 'required|unique:accounts_trees',
        ]);

        if(!$request->has('parent_id')){
            $request->parent_id = 0;
        }

        $parentId = $request->parent_id;
        $parentCode = '';
        if($parentId > 0){
            $parentCode = AccountsTree::find($parentId)->code;
        }


        AccountsTree::create([
            'code' => $request->code,
            'name' => $request->name,
            'type' => $request->type,
            'parent_id' => $parentId,
            'parent_code' => $parentCode,
            'level' => $request->level,
            'list' => $request->list,
            'department' => $request->department,
            'side' => $request->side
        ]);

        return redirect()->route('accounts_list');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccountsTree  $accountsTree
     * @return \Illuminate\Http\Response
     */
    public function show(AccountsTree $accountsTree)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AccountsTree  $accountsTree
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $accounts = AccountsTree::all();
        $account = AccountsTree::find($id);
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


        return view('accounts.update',compact('accounts','account' , 'routes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAccountsTreeRequest  $request
     * @param  \App\Models\AccountsTree  $accountsTree
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAccountsTreeRequest $request, $id)
    {
        $validated = $request->validate([
            'code' => ['required' , Rule::unique('accounts_trees')->ignore($id)],
            'name' => ['required' , Rule::unique('accounts_trees')->ignore($id)],
        ]);

        if(!$request->has('parent_id')){
            $request->parent_id = 0;
        }

        $parentId = $request->parent_id;
        $parentCode = '';
        if($parentId > 0){
            $parentCode = AccountsTree::find($parentId)->code;
        }


        $account =AccountsTree::find($id);
            $account->update([
            'code' => $request->code,
            'name' => $request->name,
            'type' => $request->type,
            'parent_id' => $parentId,
            'parent_code' => $parentCode,
            'level' => $request->level,
            'list' => $request->list,
            'department' => $request->department,
            'side' => $request->side
        ]);

        return redirect()->route('accounts_list');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccountsTree  $accountsTree
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccountsTree $accountsTree)
    {
        //
    }

    public function getLevel($parent){
        $account = AccountsTree::find($parent);
        return response()->json(['account' => $account]);
    }


    public function journals(){
        $journals = DB::table('journals')
            ->join('journal_details','journals.id','=','journal_details.journal_id')
            ->select('journals.id','journals.date','journals.basedon_no',
                'journals.basedon_id',
                'journals.baseon_text',
                DB::raw('SUM(CASE WHEN journal_details.notes = "" THEN journal_details.credit END) credit_total'),
                DB::raw('SUM(CASE WHEN journal_details.notes = "" THEN journal_details.debit END) debit_total'),
                DB::raw('SUM(CASE WHEN journal_details.notes != "" THEN journal_details.credit END) credit_totalg'),
                DB::raw('SUM(CASE WHEN journal_details.notes != "" THEN journal_details.debit END) debit_totalg'),
                )
            ->groupBy('journals.id','journals.date','journals.basedon_no',
                'journals.basedon_id',
                'journals.baseon_text')
            ->orderByDesc('journals.id')->get();

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

        return view('accounts.journals',compact('journals' , 'routes'));
    }


    public function previewJournal($id){
        $payments = DB::table('journal_details')
            ->join('accounts_trees','journal_details.account_id','=','accounts_trees.id')
            ->leftJoin('companies','companies.id','=','journal_details.ledger_id')
            ->select('accounts_trees.code','accounts_trees.name','journal_details.credit','journal_details.debit',
                'companies.name as ledger_name' , 'journal_details.notes')
            ->where('journal_details.journal_id','=',$id)
            ->get();
        $html = view('accounts.preview_journal',compact('payments'))->render();
        return $html;
    }

    public function getAccount($code)
    {
        $single = $this->getSingleAccount($code);

        if($single){
            echo response()->json([$single]);
            exit;
        }else{
            $product = AccountsTree::where('code' , 'like' , '%'.$code.'%')
                ->orWhere('name','like' , '%'.$code.'%')
                ->limit(5)
                -> get();
            echo json_encode ($product);
            exit;
        }

    }

    private function getSingleAccount($code){
        return AccountsTree::where('code' , '=' , $code)
            ->orWhere('name','=' , $code)
            -> get()->first();
    }
}
