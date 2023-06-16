<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\RoleViews;
use App\Models\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoleViewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roleViews = DB::table('role_views')
            -> join('views' , 'role_views.view_id' , '=' , 'views.id')
            ->join('roles' , 'role_views.role_id' , '=' , 'roles.id')
            ->select('role_views.*' , 'views.name_ar as view_name_ar' ,  'views.name_en as view_name_en' ,
                'roles.name_ar as role_name_ar' ,  'roles.name_en as role_name_en') -> get();
        $roles = Role::all();
        $views = View::all();

        $rolesss = DB::table('role_views')
            -> join('views' , 'role_views.view_id' , '=' , 'views.id')
            ->join('roles' , 'role_views.role_id' , '=' , 'roles.id')
            ->select('role_views.*' , 'views.name_ar as view_name_ar' ,  'views.name_en as view_name_en' ,
                'roles.name_ar as role_name_ar' ,  'roles.name_en as role_name_en' , 'views.route')
            ->where('role_views.role_id' , '=' , Auth::user() -> role_id)
            ->where('role_views.all_auth' , '=' , 1)
            -> get();


        $routes = [] ;
        foreach ($rolesss as $role){
            array_push($routes , $role -> route);
        }

        return view('Roles.views' , compact('roleViews' , 'roles' , 'views' , 'routes'));

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
                'role_id' => 'required',
                'view_id' => 'required',
            ]);

            $roleView = RoleViews::where('role_id' , '=' , $request ->role_id )
                -> where('view_id' , '=' , $request -> view_id) -> get();

            if(count($roleView) == 0 ){
                RoleViews::create([
                    'role_id' => $request -> role_id,
                    'view_id' => $request -> view_id ,
                    'all_auth' => $request -> all_auth == 'on' ? 1 : 0,
                ]);
                return redirect() -> route('roleViews') -> with('success' , __('main.created'));
            } else{
                return redirect() -> route('roleViews') -> with('error' ,  'can not duplicate view ');
            }



        } else {
            return  $this -> update($request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RoleViews  $roleViews
     * @return \Illuminate\Http\Response
     */
    public function show(RoleViews $roleViews)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RoleViews  $roleViews
     * @return \Illuminate\Http\Response
     */
    public function edit(RoleViews $roleViews)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RoleViews  $roleViews
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        return redirect() -> route('roleViews');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RoleViews  $roleViews
     * @return \Illuminate\Http\Response
     */
    public function destroy(RoleViews $roleViews)
    {
        //
    }
}
