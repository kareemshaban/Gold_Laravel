<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Http\Requests\StoreJournalRequest;
use App\Http\Requests\UpdateJournalRequest;
use App\Models\Pricing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JournalController extends Controller
{

    public function incoming_list(){
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
        return view('Report.incoming_list' , compact('routes'));
    }


    public function search_incoming_list(Request $request){
        $startDate = Carbon::now()->addYears(-5);
        $endDate = Carbon::now() -> addDays(1);
        $period = 'Period : ';
        $period_ar = 'الفترة  :';
        if($request -> has('isStartDate')){
            $startDate = $request->StartDate;

            $period .= $startDate ;
            $period_ar .= $startDate ;
        } else {
            $period .= 'Starting Date';
            $period_ar .= 'من البداية' ;

        }

        if($request -> has('isEndDate')){
            $endDate =  Carbon::parse($request->EndDate) -> addDay()  ;

            $period .= ' - '  . $endDate ;
            $period_ar .= ' - '  . $endDate ;
        } else {
            $period .= ' - '  . 'Today' ;
            $period_ar .= ' - '  . 'حتي اليوم' ;
        }

        $accounts = DB::table('accounts_trees')
            ->join('account_movements','accounts_trees.id','=','account_movements.account_id')
            ->select('accounts_trees.id as idd','accounts_trees.code','accounts_trees.name' , 'accounts_trees.parent_id' , 'accounts_trees.level',
                DB::raw('SUM(CASE WHEN account_movements.notes = "" THEN account_movements.credit END) credit'),
                DB::raw('SUM(CASE WHEN account_movements.notes = "" THEN account_movements.debit END) debit'),
                DB::raw('(CASE WHEN accounts_trees.parent_id = account_movements.account_id THEN accounts_trees.name END) childs'),
            )
            ->groupBy('accounts_trees.id','accounts_trees.code','accounts_trees.name')
            ->where('accounts_trees.department',1)
            ->where('account_movements.date','>=',$startDate)
            ->where('account_movements.date','<=',$endDate)
            ->get();

        $accounts1 =  $accounts -> where('level' , '=' , 1) ;

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

        foreach ($accounts1 as $account){
            $list = $accounts -> where('parent_id' , '=' ,$account -> idd );
            $account -> childs =  $list ? $list  : [] ;

            foreach($account -> childs as $child){
                $list2 = $accounts -> where('parent_id' , '=' ,$child -> idd );
                $child -> childs = $list2 ? $list2 : [];
            }
            foreach($child -> childs as $subChild){
                $list22 = $accounts -> where('parent_id' , '=' ,$subChild -> idd );
                $subChild -> childs = $list22 ? $list22  : [];
            }
        }
    //    return  $accounts ;
        return view('Report.incoming_list_report',compact('accounts1' , 'routes' , 'period' , 'period_ar'));
    }



    public function balance_sheet(){
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

        return view('Report.balance_sheet',compact(  'routes'));
    }

    public function search_balance_sheet(Request $request){

        $startDate = Carbon::now()->addYears(-5);
        $endDate = Carbon::now() -> addDays(1);
        $period = 'Period : ';
        $period_ar = 'الفترة  :';
        if($request -> has('isStartDate')){
            $startDate = $request->StartDate;

            $period .= $startDate ;
            $period_ar .= $startDate ;
        } else {
            $period .= 'Starting Date';
            $period_ar .= 'من البداية' ;

        }

        if($request -> has('isEndDate')){
            $endDate =  Carbon::parse($request->EndDate) -> addDay()  ;

            $period .= ' - '  . $endDate ;
            $period_ar .= ' - '  . $endDate ;
        } else {
            $period .= ' - '  . 'Today' ;
            $period_ar .= ' - '  . 'حتي اليوم' ;
        }


        $accounts = DB::table('accounts_trees')
            ->join('account_movements','accounts_trees.id','=','account_movements.account_id')
            ->select('accounts_trees.id as idd','accounts_trees.code','accounts_trees.name',  'accounts_trees.parent_id' , 'accounts_trees.level',
                DB::raw('sum(account_movements.credit) as credit'),
                DB::raw('sum(account_movements.debit) as debit'))
            ->groupBy('accounts_trees.id','accounts_trees.code','accounts_trees.name' )
            ->where('accounts_trees.department',0)
            ->get();


        $accounts1 =  $accounts -> where('level' , '=' , 1) ;

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


        foreach ($accounts1 as $account){
            $list = $accounts -> where('parent_id' , '=' ,$account -> idd );
            $account -> childs =  $list ? $list  : [] ;

            foreach($account -> childs as $child){
                $list2 = $accounts -> where('parent_id' , '=' ,$child -> idd );
                $child -> childs = $list2 ? $list2 : [];
            }
            foreach($child -> childs as $subChild){
                $list22 = $accounts -> where('parent_id' , '=' ,$subChild -> idd );
                $subChild -> childs = $list22 ? $list22  : [];
            }
        }

       // return $accounts1 ;
        return view('Report.balance_sheet_report',compact('accounts1' , 'routes' , 'period' , 'period_ar'));
    }


    public function create(){
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
        return view('accounts.manual' , compact('routes'));
    }

    public function store(Request $request){
        $siteController = new SystemController();

        $header =[
            'date' => date('Y-m-d').'T'.date('H:i'),
            'basedon_no' => '',
            'basedon_id' => 0,
            'baseon_text' => 'سند قيد يدوي',
            'total_credit' => 0,
            'total_debit' => 0,
            'notes' => $request->notes ? $request->notes : ''
        ];


        $details = [];
        foreach ($request->account_id as $index=>$account_id){
            $accountId = $account_id;
            $credit = $request->credit[$index];
            $debit = $request->debit[$index];
            $ledger = 0;

            $details[] = [
                'account_id' => $accountId,
                'credit' => $credit,
                'debit' => $debit,
                'ledger_id' => $ledger,
                'notes' => ''
            ];
        }

        $siteController->insertJournal($header,$details,1);
        return redirect()->route('journals');
    }

    public function delete($id){

        $header = [
            'date' => '',
            'basedon_no' => '',
            'basedon_id' => '',
            'baseon_text' => 'سند قيد يدوي رقم '.$id,
            'total_credit' => 0,
            'total_debit' => 0,
            'notes' => ''
        ];
        $siteController = new SystemController();
        $siteController->deleteJournal($header);

        return redirect()->route('journals');
    }

}
