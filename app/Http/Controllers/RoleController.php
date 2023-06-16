<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\RoleViews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles  = Role::all() -> sortBy('id');
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
        return view('Roles.index' , compact('roles' , 'routes'));
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
        if ($request -> id == 0){
            $validated = $request->validate([
                'name_ar' => 'required',
                'name_en' => 'required',
            ]);

            Role::create([
                'name_ar' => $request -> name_ar,
                'name_en' => $request -> name_en ,
                'description' => $request -> description ? $request -> description : '',
            ]);

            return redirect() -> route('roles') -> with('success' , __('main.created'));
        } else {
            return  $this -> update($request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        if($role){
            echo  json_encode($role);
            exit();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $role = Role::find($request -> id);
        if($role){
            $role ->update([
                'name_ar' => $request -> name_ar,
                'name_en' => $request -> name_en ,
                'description' => $request -> description ? $request -> description : '',
            ]);
            return redirect() -> route('roles') -> with('success' , __('main.updated'));
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = RoleViews::find($id);
        if($role){
            $role -> delete();
            return redirect() -> route('roleViews') -> with('success' , __('main.deleted'));
        }
    }
}
