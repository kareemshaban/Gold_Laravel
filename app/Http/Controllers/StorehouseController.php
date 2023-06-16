<?php

namespace App\Http\Controllers;

use App\Models\Storehouse;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StorehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $storehouses = Storehouse::all();
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
        return view('storehouses.index' , compact('storehouses', 'routes'));

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
                'code' => 'required|unique:storehouses',
                'name' => 'required',
            ]);
            try {
                Storehouse::create([
                    'code' => $request->code,
                    'name' => $request->name,
                    'phone' => $request->phone ? $request->phone : ' ' ,
                    'email' => $request->email ? $request->email : ' ',
                    'address' => $request->address ? $request->address : ' ',
                    'tax_number' => $request->tax_number ?? ' ',
                    'commercial_registration' => $request->commercial_registration ??  ' ',
                    'serial_prefix' => $request->serial_prefix ?? ' ',
                ]);
                return redirect()->route('warehouses')->with('success' , __('main.created'));
            } catch(QueryException $ex){

                return redirect()->route('warehouses')->with('error' ,  $ex->getMessage());
            }
        } else {
            return  $this -> update($request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Storehouse  $storehouse
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $warehouse = Storehouse::find($id );
        echo json_encode ($warehouse);
        exit;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Storehouse  $storehouse
     * @return \Illuminate\Http\Response
     */
    public function edit(Storehouse $storehouse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Storehouse  $storehouse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $warehouse = Storehouse::find($request -> id);
        if($warehouse){
            $validated = $request->validate([
                'code' => ['required' , Rule::unique('storehouses')->ignore($request -> id)],
                'name' => 'required',
            ]);
            try {
                $warehouse -> update([
                    'code' => $request->code,
                    'name' => $request->name,
                    'phone' => $request->phone ? $request->phone : ' ' ,
                    'email' => $request->email ? $request->email : ' ',
                    'address' => $request->address ? $request->address : ' ',
                    'tax_number' => $request->tax_number ?? ' ',
                    'commercial_registration' => $request->commercial_registration ??  ' ',
                    'serial_prefix' => $request->serial_prefix ?? ' ',
                ]);
                return redirect()->route('warehouses')->with('success' , __('main.updated'));
            } catch(QueryException $ex){

                return redirect()->route('warehouses')->with('error' ,  $ex->getMessage());
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Storehouse  $storehouse
     * @return \Illuminate\Http\Response
     */
    public function destroy(Storehouse $storehouse)
    {
        //
    }
}
