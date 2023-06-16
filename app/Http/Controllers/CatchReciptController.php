<?php

namespace App\Http\Controllers;

use App\Models\CatchRecipt;
use App\Models\ExpenseType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CatchReciptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        $bills = DB::table('catch_recipts')
            -> join('expense_types' , 'expense_types.id' , '=' , 'catch_recipts.type_id')
            -> select('catch_recipts.*' , 'expense_types.name_ar' , 'expense_types.name_en')
            -> get();

        $types = ExpenseType::all();

        return view('catchs.index' , compact('routes' , 'bills' , 'types'));
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
        $validated = $request->validate([
            'date' => 'required',
            'docNumber' => 'required',
            'amount' => 'required',
            'type_id' => 'required'
        ]);



        $id =  CatchRecipt::create([
            'type_id' => $request -> type_id,
            'amount' => $request -> amount,
            'notes' => $request -> notes ?? '',
            'date' => Carbon::parse($request -> date) ,
            'docNumber' => $request -> docNumber
        ]) -> id   ;

        $auto_accounting =  env("AUTO_ACCOUNTING", 1);
        if($auto_accounting == 1){
            $systemController = new SystemController();

            $systemController -> CatchAccounting($id);
        }

        return redirect()->route('catches')->with('success', __('main.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CatchRecipt  $catchRecipt
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $expense = CatchRecipt::find($id);
        echo json_encode($expense);
        exit();

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CatchRecipt  $catchRecipt
     * @return \Illuminate\Http\Response
     */
    public function edit(CatchRecipt $catchRecipt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CatchRecipt  $catchRecipt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CatchRecipt $catchRecipt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CatchRecipt  $catchRecipt
     * @return \Illuminate\Http\Response
     */
    public function destroy(CatchRecipt $catchRecipt)
    {
        //
    }


    public function get_Catch_no(){
        $bills = CatchRecipt::orderBy('id', 'ASC')->get();
        $prefix = "ETSM-";

        if(count($bills) > 0){
            $id = $bills[count($bills) -1] -> id ;
        } else
            $id = 0 ;

        $no = json_encode($prefix . str_pad($id + 1, 6 , '0' , STR_PAD_LEFT)) ;
        echo $no ;
        exit;
    }
}
