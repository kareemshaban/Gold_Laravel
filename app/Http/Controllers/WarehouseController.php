<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyMovement;
use App\Models\Item;
use App\Models\ItemMaterials;
use App\Models\Karat;
use App\Models\VendorMovement;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
  /* $type => new or old
   * $karat_id => karat id
   * $weight => actual weight
   * $direction => +1 or -1 (enter or out)
   * $bill_id => Bill Id
   * */
    public function syncQnt($type , $karat_id , $bill_id , $weight , $direction){
        Warehouse::create([
            'type' => $type,
            'karat_id' => $karat_id,
            'enter_weight' => $direction > 0 ? $weight : 0,
            'out_weight' => $direction < 0 ? $weight : 0 ,
            'bill_id' => $bill_id,
            'date' => Carbon::now(),
            'user_created' => Auth::user() -> id,
        ]);
    }

    public function deleteQnt($bill_id){
        $items = Warehouse::where('bill_id' , '=' , $bill_id) -> get();
        foreach ($items as $item){
            $item -> delete();
        }
    }

    public function syncVendorAccount($vendor_id , $money , $gold , $direction , $bill_id , $bill_number , $type){
        $vebdor = Company::find($vendor_id);
        if($direction > 0){
            $vebdor -> credit_amount += $money ;
            $vebdor -> credit_gold += $gold ;

        } else {
            $vebdor -> deposit_amount += $money ;
            $vebdor -> deposit_gold += $gold ;
        }
        $vebdor -> update();
        CompanyMovement::create([
            'company_id' => $vendor_id,
            'paid_money' => 0,
            'credit_money' => $direction > 0 ? $money : 0,
            'debit_money'  => $direction < 0 ? $money : 0,
            'paid_gold' => 0 ,
            'credit_gold' => $direction > 0 ? $gold : 0,
            'debit_gold' => $direction < 0 ? $gold : 0,
            'date' => Carbon::now(),
            'invoice_type' => $type,
            'bill_id' => $bill_id,
            'bill_number' => $bill_number,
            'user_created' => Auth::user() -> id,
        ]);
    }

    public function deleteVendorMove($vendor_id , $bill_id ,$money ,$gold , $type){

         //    $vebdor -> deposit_amount += $money ;
        //     $vebdor -> deposit_gold += $gold ;
        $supplier = Company::find($vendor_id);
        $movement = CompanyMovement::where('company_id' , '=' , $vendor_id) -> where('bill_id' , '=' , $bill_id)
            ->where('invoice_type' , '=' , $type) -> get() -> first();
        if($movement){
            $supplier -> deposit_amount -= $movement -> debit_money ;
            $supplier -> deposit_gold  -= $movement -> debit_gold ;
            $supplier -> update();
            $movement -> delete();
        }
    }
    public function gold_stock(){
        $workWarehouses = Warehouse::where('type' , '=' , 1) ->get() -> groupBy('karat_id') ;
        $oldWarehouses = Warehouse::where('type' , '=' , 0) -> get() -> groupBy('karat_id') ;
        $karats = Karat::all();
        $work = $workWarehouses -> map(function ($item) {
            return [
                'enter_weight' => $item -> sum('enter_weight'),
                'out_weight'=> $item -> sum('out_weight'),
            ];
        });
        $old = $oldWarehouses -> map(function ($item) {
            return [
                'enter_weight' => $item -> sum('enter_weight'),
                'out_weight'=> $item -> sum('out_weight'),
            ];
        });
      // return $work ;
        $slag = 3 ;
        $subSlag = 13 ;

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
        return view('Item.gold_stock' , compact('work' , 'old' , 'karats' , 'slag' , 'subSlag' , 'routes')) ;
    }

    public function makeItemUnAvailable($id){
        $item = Item::find($id);
        if($item){
            if($item -> item_type == 1){
                $item -> state = 0 ;
                $item -> update();
            } else if($item -> item_type == 3){
                $materials = ItemMaterials::where('parent_id' , '=' , $id) -> get();
                foreach ($materials as $material){
                    $sub = Item::find($material -> item_id);
                    $sub -> state = 0 ;
                    $sub -> update();
                }
                $item -> state = 0 ;
                $item -> update();
            } else if($item -> item_type == 2){
                if($item -> quantity == 0){
                    $item -> state = 0 ;
                    $item -> update();
                }
            }

        }
    }
}
