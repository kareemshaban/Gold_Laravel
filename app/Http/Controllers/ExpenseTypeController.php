<?php

namespace App\Http\Controllers;

use App\Models\AccountsTree;
use App\Models\CatchRecipt;
use App\Models\Expenses;
use App\Models\ExpenseType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {
        $types = ExpenseType::all();
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
        return view('Expenses.types' , compact('routes' , 'types' , 'accounts' , 'type'));
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
        if($request -> id == 0){

            $validated = $request->validate([
                'name_ar' => 'required',
                'name_en' => 'required',
                'account_id' => 'required',
                'type' => 'required'
            ]);
            try {
                ExpenseType::create([
                    'name_ar' => $request -> name_ar,
                    'name_en' => $request -> name_en,
                    'notes' => $request -> notes ?? '' ,
                    'account_id' => $request -> account_id,
                    'type' => $request -> type
                ]);

                return redirect()->route('expenses_type' , $request -> type)->with('success' , __('main.created'));
            } catch(QueryException $ex){

                return redirect()->route('expenses_type' , $request -> type)->with('error' ,  $ex->getMessage());
            }
        } else {
            return  $this -> update($request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExpenseType  $expenseType
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $type = ExpenseType::find($id);
        echo json_encode($type);
        exit();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExpenseType  $expenseType
     * @return \Illuminate\Http\Response
     */
    public function edit(ExpenseType $expenseType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExpenseType  $expenseType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $category = ExpenseType::find($request -> id);
        if($category){
            $validated = $request->validate([
                'name_ar' => 'required',
                'name_en' => 'required',
                'account_id' => 'required',
                'type' => 'required'
            ]);
            try {
                $category -> update([
                    'name_ar' => $request -> name_ar,
                    'name_en' => $request -> name_en,
                    'notes' => $request -> notes ?? '' ,
                    'account_id' => $request -> account_id,
                    'type' => $request -> type
                ]);
                return redirect()->route('expenses_type' , $request -> type)->with('success' , __('main.updated'));
            } catch (QueryException $ex){
                return redirect()->route('expenses_type' , $request -> type)->with('error' ,  $ex->getMessage());
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExpenseType  $expenseType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = ExpenseType::find($id);
        if($category){
            $expenses = Expenses::where('type_id' , '=' , $id) -> get();
            $catshes = CatchRecipt::where('type_id' , '=' , $id) -> get();
            if(count($expenses) == 0 && count($catshes) == 0){
                $category -> delete();
                return redirect()->route('expenses_type' , $category -> type)->with('success' , __('main.deleted'));
            } else {
                return redirect()->route('expenses_type' , $category -> type)->with('error' , __('main.can_not_delete_item'))  ;
            }


        }
    }
}
