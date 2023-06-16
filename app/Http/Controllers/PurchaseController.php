<?php

namespace App\Http\Controllers;

use App\Models\Cashier;
use App\Models\Company;
use App\Models\Item;
use App\Models\Pricing;
use App\Models\Product;
use App\Models\Purchase;
use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\PurchaseDetails;
use App\Models\Storehouse;
use App\Models\SystemSettings;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends SiteController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('purchases')
            ->join('storehouses','purchases.warehouse_id','=','storehouses.id')
            ->join('companies','purchases.customer_id','=','companies.id')
            ->select('purchases.*','storehouses.name as warehouse_name','companies.name as customer_name')
            ->get();
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
        return view('purchases.index',compact('data' , 'routes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $warehouses = Storehouse::all();
        $customers =  Company::where('group_id' , '=' , 4) -> get();

        return view('purchases.create',compact('warehouses','customers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePurchaseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $total = 0;
        $tax = 0;
        $net = 0;

        $products = array();
        $qntProducts = array();
        foreach ($request->product_id as $index=>$id){
            $productDetails = Item::find($id);
            $product = [
                'purchase_id' => 0,
                'product_code' => $productDetails->code,
                'product_id' => $id,
                'quantity' => $request->qnt[$index],
                'cost_without_tax' => $request->price_without_tax[$index],
                'cost_with_tax' => $request->price_with_tax[$index],
                'warehouse_id' => $request->warehouse_id,
                'unit_id' => 0,
                'tax' => $request->tax[$index],
                'total' => $request->total[$index],
                'net' => $request->net[$index]
            ];

            $item = new Item();
            $item -> product_id = $id;
            $item -> quantity = $request->qnt[$index] ;
            $item -> warehouse_id = $request ->  warehouse_id;
            $qntProducts[] = $item ;

            $products[] = $product;
            $total +=$request->total[$index];
            $tax +=$request->tax[$index];
            $net +=$request->net[$index];

        }


        $purchase = Purchase::create([
            'date' => $request->bill_date,
            'invoice_no' => $request-> bill_number,
            'customer_id' => $request->customer_id,
            'biller_id' => Auth::id(),
            'warehouse_id' => $request->warehouse_id,
            'note' => $request->notes ?? '' ,
            'total' => $total,
            'discount' => 0,
            'tax' => $tax,
            'net' => $net,
            'paid' => 0,
            'purchase_status' => 'completed',
            'payment_status' => 'not_paid',
            'created_by' => Auth::id()
        ]);

        foreach ($products as $product){
            $product['purchase_id'] = $purchase->id;
            PurchaseDetails::create($product);
        }


        $this->syncQnt($qntProducts,null , false);
        $clientController = new ClientMoneyController();
        $clientController->syncMoney($request->customer_id,0,$net);

        $vendorMovementController = new VendorMovementController();
        $vendorMovementController->addPurchaseMovement($purchase->id);

        //$siteController->purchaseJournals($purchase->id);

        return redirect()->route('purchases');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $datas = DB::table('purchases')
            ->join('storehouses','purchases.warehouse_id','=','storehouses.id')
            ->join('companies','purchases.customer_id','=','companies.id')
            ->select('purchases.*','storehouses.name as warehouse_name','companies.name as customer_name' )
            ->where('purchases.id' , '=' , $id) -> get();
        if(count($datas)){
            $data = $datas[0];
            $details = DB::table('purchase_details')
                -> join('items' , 'purchase_details.product_id' , '=' , 'items.id')
                -> select('purchase_details.*' , 'items.code' , 'items.name_ar' , 'items.name_en')
                ->where('purchase_details.purchase_id' , '=' , $id)-> get();
            // return  $details ;


            $vendor = Company::find($data->customer_id);

            return view('purchases.view',compact('data' , 'details','vendor'))->render();
        }


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $purchase = Purchase::find($id);
        if($purchase->net < 0){
            return redirect()->back();
        }


        $siteContrller = new SystemController();
        $warehouses = $siteContrller->getAllWarehouses();
        $customers = $siteContrller->getAllVendors();

        $purchaseItems = DB::table('purchase_details')
            ->join('products','products.id','=','purchase_details.product_id')
            ->select('purchase_details.*','products.name as product_name')
            ->where('purchase_id',$id)->get();


        $zeroItems = 0;
        foreach ($purchaseItems as $purchaseItem){
            $returnedQnt = $this->getAllProductReturnForSameInvoice($id,$purchaseItem->product_id);
            $purchaseItem->quantity = $purchaseItem->quantity + $returnedQnt;

            if($purchaseItem->quantity <= 0){
                $zeroItems +=1;
            }
        }


        if($zeroItems >= count($purchaseItems)){
            return redirect()->back();
        }


        //$purchaseItems = $purchaseItems->toJson();


       // return  $purchaseItems ;
        return view('purchases.edit',compact('warehouses','customers','purchaseItems','id','purchase'));

    }

    private function getAllProductReturnForSameInvoice($invoiceId,$productId){
        $totalQnt = 0;

        $allOtherPurchaseItems = DB::table('purchase_details')
            ->join('purchases','purchases.id','=','purchase_details.purchase_id')
            ->select('purchase_details.*')
            ->where('purchases.returned_bill_id',$invoiceId)
            ->where('purchase_details.product_id',$productId)->get();

        foreach ($allOtherPurchaseItems as $item){

            $totalQnt += $item->quantity;
        }

        return $totalQnt;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePurchaseRequest  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request  $request , $billid)
    {
        $siteController = new SystemController();
        $total = 0;
        $tax = 0;
        $discount = 0;
        $net = 0;
        $lista = 0;
        $profit = 0;

        $products = array();
        $qntProducts = array();
        foreach ($request->product_id as $index=>$id){
            $productDetails = $siteController->getProductById($id);
            $product = [
                'purchase_id' => 0,
                'product_code' => $productDetails->code,
                'product_id' => $id,
                'quantity' => $request->qnt[$index] * -1,
                'cost_without_tax' => $request->price_without_tax[$index] * -1,
                'cost_with_tax' => $request->price_with_tax[$index] * -1,
                'warehouse_id' => $request->warehouse_id,
                'unit_id' => $productDetails->unit,
                'tax' => $request->tax[$index] * -1,
                'total' => $request->total[$index] * -1,
                'net' => $request->net[$index] * -1,
            ];

            $item = new Product();
            $item -> product_id = $id;
            $item -> quantity = $request->qnt[$index]  * -1;
            $item -> warehouse_id = $request->warehouse_id ;
            $qntProducts[] = $item ;

            $products[] = $product;
            $total +=$request->total[$index];
            $tax +=$request->tax[$index];
            $net +=$request->net[$index];
        }

        $sale = Purchase::create([
            'returned_bill_id' => $billid,
            'date' => $request->bill_date,
            'invoice_no' => $request-> bill_number,
            'customer_id' => $request->customer_id,
            'biller_id' => Auth::id(),
            'warehouse_id' => $request->warehouse_id,
            'note' => $request->notes ? $request->notes :'',
            'total' => $total * -1,
            'discount' => 0,
            'tax' => $tax * -1,
            'net' => $net * -1,
            'paid' => 0,
            'purchase_status' => 'completed',
            'payment_status' => 'not_paid',
            'created_by' => Auth::id()
        ]);

        foreach ($products as $product){
            $product['purchase_id'] = $sale->id;
            PurchaseDetails::create($product);
        }

        $siteController->syncQnt($qntProducts,null , false);
        $clientController = new ClientMoneyController();
        $clientController->syncMoney($request->customer_id,0,$net*-1);

        $vendorMovementController = new VendorMovementController();
        $vendorMovementController->addPurchaseMovement($sale->id);

        $siteController->purchaseJournals($sale->id);
        return redirect()->route('purchases');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $purchase = Purchase::find($id);
        $item = new Item();
        $qntProducts = array();
        $net = 0 ;
        if($purchase){
            $details = PurchaseDetails::where('purchase_id' , '=' , $id) -> get();
            foreach ($details as $detail){
                $item = new Item();
                $item -> product_id = $detail -> product_id;
                $item -> quantity = $detail-> quantity  * -1;
                $item -> warehouse_id = $detail->warehouse_id ;
                $qntProducts[] = $item ;
                $net +=$detail->net;
                $detail -> delete();
            }


            $this->syncQnt($qntProducts,null , false);
            $clientController = new ClientMoneyController();
            $clientController->syncMoney($purchase->customer_id,0,$net*-1);



            $vendorMovementController = new VendorMovementController();
            $vendorMovementController->removePurchaseMovement($purchase->id);

            $paymentController = new PaymentController();
            $paymentController->deleteAllPurchasePayments($purchase->id);

            $purchase -> delete();


            return redirect()->route('purchases')->with('success' ,  __('main.deleted'));

        }
    }

    public function get_purchase_no($id){
        $warehouse = Storehouse::find($id);
        $bills = Purchase::where('warehouse_id' , '=' , $id) -> orderBy('id', 'ASC')->get();
        if(count($bills) > 0){
            $id = $bills[count($bills) -1] -> id ;
        } else
            $id = 0 ;
        $prefix = "";

        if($warehouse -> serial_prefix)
            $prefix = $warehouse -> serial_prefix ;

        $no = json_encode($prefix . str_pad($id + 1, 6 , '0' , STR_PAD_LEFT)) ;
        echo $no ;
        exit;
    }
    public function getNoR(){
        $bills = Purchase::orderBy('id', 'ASC')->get();
        if(count($bills) > 0){
            $id = $bills[count($bills) -1] -> id ;
        } else
            $id = 0 ;
        $settings = SystemSettings::all();
        $prefix = "";
        if(count($settings) > 0){
            if($settings[0] -> purchase_return_prefix)
                $prefix =     $settings[0] -> purchase_return_prefix ;
            else
                $prefix = "" ;
        } else {
            $prefix = "";
        }
        $no = json_encode($prefix . str_pad($id + 1, 6 , '0' , STR_PAD_LEFT)) ;
        echo $no ;
        exit;
    }

}
