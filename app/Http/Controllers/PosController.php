<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\EnterMoney;
use App\Models\EnterOld;
use App\Models\EnterOldDetails;
use App\Models\EnterWork;
use App\Models\EnterWorkDetails;
use App\Models\ExitMoney;
use App\Models\ExitOld;
use App\Models\ExitOldDetails;
use App\Models\ExitWork;
use App\Models\ExitWorkDetails;
use App\Models\Item;
use App\Models\Karat;
use App\Models\Pricing;
use App\Models\TaxSettings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PosController extends WarehouseController
{
    //
    public function pos(){
        $customers =  Company::where('group_id' , '=' , 3) -> get();
        $suppliers =  Company::where('group_id' , '=' , 4) -> get();
        $karats = Karat::all();
        $setting = TaxSettings::all() -> first();
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
        return view('pos.index' , compact('customers' , 'suppliers' ,'karats' , 'setting' , 'routes'));
    }
    public function store_pos(Request $request){
        //document_type == 1 ExitWork , ExitOld
          if($request -> document_type == 1 ){
            return  $this -> sellNewGold($request);
          } else if($request -> document_type == 2){
              return $this-> sellOldGold($request);
          }
    }
    public function MakePayment($request , $money , $type , $based_on , $doc_type ,$based_on_num){
        $bill_number = $this -> getpaymentNo()  ;
        $id =  EnterMoney::create([
            'doc_number' => $bill_number ,
            'date' => $request -> bill_date,
            'client_id' => $request -> customer_id,
            'amount' => $money,
            'payment_method' => $type,
            'user_created' => Auth::user() -> id,
            'based_on' => $based_on,
            'based_on_bill_number' => $based_on_num,
            'notes' => ''
        ]) -> id   ;

        $auto_accounting =  env("AUTO_ACCOUNTING", 1);
        if($auto_accounting == 1){
            $systemController = new SystemController();

            $systemController -> EnterMoneyAccounting($id);
        }
        if($request -> customer_id > 0){
            $this -> syncVendorAccount($request -> customer_id , $money ,0 , -1 ,
                $id , $bill_number , 'Enter Money Bill');
        }

        if($based_on > 0){
            if($doc_type == 1){
                $bill = ExitWork::find($based_on);
            } else {
                $bill = ExitOld::find($based_on);
            }

            if($bill ){
                $bill -> remain_money -= $money ;
                $bill -> paid_money += $money ;
                $bill -> update();
            }
        }
    }
    public function MakePaymentOut($request , $money , $type , $based_on ){
        $bill_number = $this -> getpaymentOutNo()  ;


        $id =  ExitMoney::create([
            'doc_number' => $bill_number ,
            'date' => $request -> bill_date2,
            'supplier_id' => $request -> customer_id2,
            'type' => 1 ,
            'based_on' => $based_on,
            'amount' => $money,
            'payment_method' => $type,
            'user_created' => Auth::user() -> id,
            'notes' =>  '',
            'price_gram' => 0
        ]) -> id   ;


        $auto_accounting =  env("AUTO_ACCOUNTING", 1);
        if($auto_accounting == 1){
            $systemController = new SystemController();

            $systemController -> ExitMoneyAccounting($id);
        }

        if($request -> customer_id2 > 0){
            $moneyout = $money ;
            $gold = 0;
            $this->syncVendorAccount($request->customer_id2, $moneyout, $gold, 1,
                $id, $bill_number, 'Exit Money Bill');
        }

        if($based_on > 0){
                $bill = EnterOld::find($based_on);
            if($bill ){
                $bill -> remain_money -= $money ;
                $bill -> paid_money += $money ;
                $bill -> update();
            }
        }
    }

    public function getpaymentNo(){
        $bills = EnterMoney::orderBy('id', 'ASC')->get();
        if(count($bills) > 0){
            $id = $bills[count($bills) -1] -> id ;
        } else
            $id = 0 ;
        $prefix = "ME-";
        $no = ($prefix . str_pad($id + 1, 6 , '0' , STR_PAD_LEFT)) ;
        return $no ;
    }
    public function getpaymentOutNo(){
        $bills = ExitMoney::orderBy('id', 'ASC')->get();
        if(count($bills) > 0){
            $id = $bills[count($bills) -1] -> id ;
        } else
            $id = 0 ;
        $prefix = "MEx-";
        $no = ($prefix . str_pad($id + 1, 6 , '0' , STR_PAD_LEFT)) ;
        return $no ;
    }


    public function store_pos_purchase(Request $request){
     //  return $request ;


        $items = array();
        if(count($request -> karat_id_old2)){
            //store header
            $total_money = 0 ;
            $total21_gold = 0 ;
            for($i = 0 ; $i < count($request -> karat_id_old2) ; $i++ ){
                $item =[
                    'bill_id' => 0,
                    'karat_id' => $request -> karat_id_old2[$i],
                    'weight' => $request -> weight_old2[$i],
                    'weight21'=> $request -> weight21_old2[$i],
                    'made_money'=> 0,
                    'net_weight' => $request -> weight_old2[$i],
                    'net_money' => $request -> net_money_old2[$i],
                ];
                $total_money += $request -> net_money_old2[$i];
                $total21_gold += $request -> weight21_old2[$i];
                $items[] = $item ;
            }

            $id =  EnterOld::create([
                'bill_number' => $request -> bill_number2,
                'date' => $request -> bill_date2,
                'client_id' => $request -> customer_id2,
                'total_money' => $total_money,
                'total21_gold' => $total21_gold,
                'paid_money' => 0,
                'remain_money' => $request -> net_after_discount2,
                'paid_gold' => 0,
                'remain_gold' => 0,
                'notes'=> $request -> notes ?? '',
                'user_created' => Auth::user() -> id,
                'pos' => 1,
                'discount' => $request -> discount2,
                'net_money' => $request -> net_after_discount2,
                'tax' => $request -> tax
            ]) -> id;

            foreach ($items as $product){
                $product['bill_id'] = $id;
                EnterOldDetails::create($product) ;

                $this -> syncQnt(0 , $product['karat_id'], $id , $product['weight'] , 1 );
            }


            $this -> syncVendorAccount($request -> customer_id2 , $request -> net_after_discount2 , 0, -1 ,
                $id , $request -> bill_number2 , 'Old Entry Bill');


            if($request -> cash > 0){
                $this -> MakePaymentOut($request , $request -> cash , 0 , $id  );
            }
            if($request -> visa > 0){
                $this -> MakePaymentOut($request , $request -> visa , 1 , $id );
            }

            $auto_accounting =  env("AUTO_ACCOUNTING", 1);
            if($auto_accounting == 1){
                $systemController = new SystemController();

                $systemController -> EnterOldAccounting($id);
            }

            return redirect()->route('oldEntryPreview' , $id)->with('success' ,  __('main.created'));
        } else {
            return redirect()->route('pos')->with('error' ,  __('main.nodetails'));
        }

    }
    public function posPayment(Request $request){

    }
    public function pos_payment_show($money , $type){
        $html = view('pos.payment' , compact('money' , 'type')) -> render();
        return $html ;
    }

    public function pos_payment_show2($money){
        $html = view('pos.payment2' , compact('money')) -> render();
        return $html ;
    }

    public function sellNewGold($request){

        $validated = $request->validate([
            'bill_date' => 'required',
            'bill_number' => 'required|unique:exit_works',
            'customer_id' => 'required'
        ]);
        $items = array();
        if(count($request -> item_id    )){
            //store header
            $total = 0 ;
            for($i = 0 ; $i < count($request -> item_id) ; $i++ ){
                $item =[
                    'bill_id' => 0,
                    'item_id' => $request -> item_id[$i],
                    'karat_id' => $request -> karat_id[$i],
                    'weight' => $request -> weight[$i],
                    'gram_price' => $request -> gram_price[$i],
                    'gram_manufacture' => $request -> gram_manufacture[$i],
                    'gram_tax' => $request -> gram_tax[$i],
                    'net_money'=> $request -> net_money[$i],
                ];
                $total += $request -> net_money[$i] ;
                $items[] = $item ;
            }

            $id =  ExitWork::create([
                'bill_number' => $request -> bill_number,
                'date' => $request -> bill_date,
                'client_id' => $request -> customer_id,
                'total_money' => $total,
                'total21_gold' => $request -> total_weight21,
                'paid_money' => $request -> paid,
                'remain_money' => $request -> net_after_discount - $request -> paid,
                'paid_gold' => 0,
                'remain_gold' => 0,
                'notes'=> $request -> notes ?? '',
                'user_created' => Auth::user() -> id,
                'pos' => 1,
                'discount' => $request -> discount,
                 'tax' => $request ->tax ,
                'net_money' => $request -> net_after_discount,
                'bill_client_name' => $request -> bill_client_name
            ]) -> id;

            foreach ($items as $product){
                $product['bill_id'] = $id;
                ExitWorkDetails::create($product) ;
                $this -> syncQnt(1 , $product['karat_id'], $id , $product['weight'] , -1 );
                $this -> makeItemUnAvailable($product['item_id'] );

            }
            $this -> syncVendorAccount($request -> customer_id , $request -> net_after_discount ,0 , 1 ,
                $id , $request -> bill_number , 'Work Exit Bill');

            if($request -> cash > 0){
                $this -> MakePayment($request , $request -> cash , 0 , $id ,1 , $request -> bill_number);
            }
            if($request -> visa > 0){
                $this -> MakePayment($request , $request -> visa , 1 , $id , 1 , $request -> bill_number);
            }

            $auto_accounting =  env("AUTO_ACCOUNTING", 1);
            if($auto_accounting == 1){
                $systemController = new SystemController();

                $systemController -> ExitWorkAccounting($id);
            }

            //   return $this -> pos_payment_show( $request -> net_after_discount);
            return redirect()->route('workExitPreview' , $id)->with('success' ,  __('main.created'));
        } else {
            return redirect()->route('pos')->with('error' ,  __('main.nodetails'));
        }
    }


    public function sellOldGold($request){



        $validated = $request->validate([
            'bill_date' => 'required',
            'bill_number' => 'required|unique:exit_olds',
            'customer_id' => 'required'
        ]);
        $items = array();
        if(count($request -> karat_id_old    )){
            //store header
            $total_money = 0 ;
            $total21_gold = 0 ;
            for($i = 0 ; $i < count($request -> karat_id_old) ; $i++ ){
                $item =[
                    'bill_id' => 0,
                    'karat_id' => $request -> karat_id_old[$i],
                    'weight' => $request -> weight_old[$i],
                    'weight21'=> $request -> weight21_old[$i],
                    'gram_price' => $request -> gram_price_old[$i],
                    'made_money'=> $request -> gram_manufacture_old[$i],
                    'gram_tax' => $request -> gram_tax_old[$i],
                    'net_weight' => $request -> weight21_old [$i],
                    'net_money' => $request -> net_money_old[$i],
                ];
                $total21_gold += $request -> weight21_old[$i];
                $total_money += $request -> net_money_old[$i];
                $items[] = $item ;
            }


            $id =  ExitOld::create([
                'bill_number' => $request -> bill_number,
                'date' => $request -> bill_date,
                'supplier_id' => $request -> customer_id,
                'total_money' => $total_money,
                'total21_gold' => $total21_gold,
                'paid_money' => 0,
                'remain_money' => $request -> net_after_discount,
                'paid_gold' => 0,
                'remain_gold' => 0,
                'notes'=> $request -> notes ?? '',
                'user_created' => Auth::user() -> id,
                'pos' => 1,
                'discount' => $request -> discount,
                'tax' => $request -> tax ,
                'net_money' => $request -> net_after_discount,
                'bill_client_name' => $request -> bill_client_name
            ]) -> id;

            foreach ($items as $product){
                $product['bill_id'] = $id;
                ExitOldDetails::create($product) ;
                $this -> syncQnt(0 , $product['karat_id'], $id , $product['weight'] , -1 );


            }


            $this -> syncVendorAccount($request -> customer_id , $request -> net_after_discount , 0 , 1 ,
                $id , $request -> bill_number , 'Old Exit Bill');

            if($request -> cash > 0){
                $this -> MakePayment($request , $request -> cash , 0 , $id , 2 , $request -> bill_number);
            }
            if($request -> visa > 0){
                $this -> MakePayment($request , $request -> visa , 1 , $id , 2 , $request -> bill_number);
            }


            $auto_accounting =  env("AUTO_ACCOUNTING", 1);
            if($auto_accounting == 1){
                $systemController = new SystemController();

                $systemController -> ExitOldAccounting($id);
            }
            //   return $this -> pos_payment_show( $request -> net_after_discount);
            return redirect()->route('oldExitPreview' ,$id )->with('success' ,  __('main.created'));
        } else {
            return redirect()->route('pos')->with('error' ,  __('main.nodetails'));
        }
    }

    public function pos_sales(){
        $work = ExitWork::where('pos' , '=' , 1) -> get();
        foreach ($work as $w){
            $w -> type = 1 ;
            $client = Company::find($w -> client_id );
            $w -> vendor_name = $client ? $client -> name : '--';
        }
        $old = ExitOld::where('pos' , '=' , 1) -> get();
        foreach ($old as $o){
            $o -> type = 0 ;
            $client = Company::find($o -> supplier_id );
            $o -> vendor_name = $client ? $client -> name : '--';
        }

        $data = $work->merge($old);

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

        return view ('pos.sales' , compact('data' , 'routes'));

    }
    public function pos_purchase(){
        $data = EnterOld::where('pos' , '=' , 1) -> get();
        foreach ($data as $w){
            $client = Company::find($w -> client_id );
            $w -> vendor_name = $client ? $client -> name : '--';
        }

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

        return view ('pos.purchases' , compact('data' , 'routes'));

    }

    public function test_print(){
        return view ('pos.print_sales');

    }


    public function return_work($id){
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

        $bill = DB::table('exit_works')
            -> leftJoin('companies' , 'companies.id' , '=' , 'exit_works.client_id')
            -> select('exit_works.*' , 'companies.name as vendor_name' , 'companies.vat_no as vendor_vat_no')
            -> where('exit_works.id' , '=' , $id)
            -> get() -> first();


        $details   =  DB::table('exit_work_details')
            -> join('items' , 'items.id' , '=' , 'exit_work_details.item_id')
            -> join('karats' , 'karats.id' , '=' , 'exit_work_details.karat_id')
            -> select('exit_work_details.*' , 'karats.name_ar as karat_ar' , 'karats.name_en as karat_en' , 'karats.transform_factor as transform_factor',   'items.name_ar as item_ar' , 'items.name_en as item_en')
            -> where('exit_work_details.bill_id' , '=' , $id)
            -> get();


      //  return $bill ;
        return view ('pos.workReturn' , compact('routes' , 'bill' , 'details'));
    }

    public function return_work_post(Request $request){
        $bill = ExitWork::find($request -> bill_id);



        $data = ExitWorkDetails::where('bill_id' , '=' , $request -> bill_id) -> get();
        $details = [] ;
        $total = 0 ;
        $tax = 0 ;
        $total_weight21 = 0 ;
        foreach ($data as $detail){
            if(in_array($detail -> id , $request -> checkDetail)){
                array_push($details , $detail);
                $total += $detail -> net_money ;
                $karat = Karat::find($detail -> karat_id);
                $total_weight21 += ($detail -> weight * $karat ->transform_factor)  ;
                $taxS = TaxSettings::all() -> first();
                if($taxS -> enabled == 1){
                    $tax += ($detail -> net_money * ($taxS -> value / 100));
                }

            }
        }

        $discountOld = $bill -> discount ;
        $discountPer = 1 - ($bill ->total_money - $discountOld) /($bill ->total_money) ;
        $discount = $discountPer * $total ;
         $net = $total + $tax - $discount ;




        $id =  ExitWork::create([
            'bill_number' =>  'R' . $bill -> bill_number,
            'date' => Carbon::now(),
            'client_id' => $bill -> client_id,
            'total_money' => $total * -1,
            'total21_gold' => $total_weight21 * -1 ,
            'paid_money' => 0,
            'remain_money' => 0,
            'paid_gold' => 0,
            'remain_gold' => 0,
            'notes'=> $bill -> notes ?? '',
            'user_created' => Auth::user() -> id,
            'pos' => 1,
            'discount' => $discount * -1,
             'tax' => $tax * -1,
            'net_money' =>  $net * -1,
            'bill_client_name' => $bill -> bill_client_name
        ]) -> id;

        $bill -> returned_bill_id = $id ;
        $bill -> update ();


        foreach ($details as $detail){
            $item = Item::find($detail -> item_id) ;
            if($item){
                $item -> state = 1 ;
                $item -> update();
                $this -> syncQnt(1 , $item -> karat_id, $id , $item -> weight , 1 );
            }

            ExitWorkDetails::create([
                'bill_id' => $id,
                'item_id' => $detail -> item_id,
                'karat_id' => $detail -> karat_id,
                'weight' => $detail -> weight,
                'gram_price'=> $detail -> gram_price,
                'gram_manufacture'=> $detail -> gram_manufacture,
                'gram_tax'=> $detail -> gram_tax,
                'net_money' => $detail -> net_money,
            ]);

            $detail -> returned = 1;
            $detail -> update();
        }

        $this -> syncVendorAccount($bill -> client_id , $net ,0 , -1 ,
            $id ,  'R'.$bill -> bill_number , 'Return Work Exit Bill');

        $auto_accounting =  env("AUTO_ACCOUNTING", 1);
        if($auto_accounting == 1){
            $systemController = new SystemController();

            $systemController -> ReturnExitWorkAccounting($id);
        }




        return redirect() -> route('pos_sales') ->with('success' ,  __('main.created'));

    }


    public function return_sales(){
        $work = ExitWork::where('pos' , '=' , 1) -> where('net_money' , '<' , 0) -> get();
        foreach ($work as $w){
            $w -> type = 1 ;
            $client = Company::find($w -> client_id );
            $w -> vendor_name = $client ? $client -> name : '--';
            $billSales = ExitWork::where('returned_bill_id' , '=' , $w -> id) -> get() -> first();
            $w -> salesNo = $billSales -> bill_number;
        }



        $data = $work;

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

        return view ('pos.salesReturn' , compact('data' , 'routes'));
    }


}
