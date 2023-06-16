<?php

namespace App\Http\Controllers;

use App\Models\CompanyMovement;
use App\Models\Payment;
use App\Models\Purchase;
use App\Models\Sales;
use App\Models\VendorMovement;
use App\Http\Requests\StoreVendorMovementRequest;
use App\Http\Requests\UpdateVendorMovementRequest;
use Illuminate\Support\Facades\Auth;

class VendorMovementController extends Controller
{
    public function addSaleMovement($id){
        $sale = Sales::find($id);

        CompanyMovement::create([
            'company_id' => $sale->customer_id,
            'paid_money' => 0,
            'credit_money' => $sale->net,
            'debit_money' => 0,
            'paid_gold' => 0 ,
            'credit_gold' => 0 ,
            'debit_gold' => 0,
            'date' =>  $sale->date,
            'invoice_type' => 'Sales',
            'bill_id' => $id,
            'bill_number' => $sale->invoice_no,
            'user_created' => Auth::user() -> id,
        ]);
//        VendorMovement::create([
//            'vendor_id' => $sale->customer_id,
//            'paid' => 0,
//            'credit' => $sale->net,
//            'debit' => 0,
//            'date' => $sale->date,
//            'invoice_type' => 'Sales',
//            'invoice_id' => $id,
//            'invoice_no' => $sale->invoice_no,
//            'paid_by' => ''
//        ]);
    }

    public function addSalePaymentMovement($id){
//        $payment = Payment::find($id);
//        $sale = Sales::find($payment->sale_id);
//
//        VendorMovement::create([
//            'vendor_id' => $sale->customer_id,
//            'paid' => 0,
//            'credit' => 0,
//            'debit' => $payment->amount,
//            'date' => $payment->date,
//            'invoice_type' => 'Sale_Payment',
//            'invoice_id' => $id,
//            'invoice_no' => $sale->invoice_no,
//            'paid_by' => $payment->paid_by
//        ]);
    }

    public function removeSalePaymentMovement($id){
//        $vendorMovementId = VendorMovement::query()->where('invoice_id',$id)->get()->first();
//        VendorMovement::destroy($vendorMovementId);
    }

    public function addPurchaseMovement($id){
        $sale = Purchase::find($id);
        CompanyMovement::create([
            'company_id' => $sale->customer_id,
            'paid_money' => 0,
            'credit_money' => 0,
            'debit_money' => $sale->net,
            'paid_gold' => 0 ,
            'credit_gold' => 0 ,
            'debit_gold' => 0,
            'date' =>  $sale->date,
            'invoice_type' => 'Purchases',
            'bill_id' => $id,
            'bill_number' => $sale->invoice_no,
            'user_created' => Auth::user() -> id,
        ]);
    }

    public function removePurchaseMovement($id){
        $purchase = Purchase::find($id);
        $vendorMovementId = CompanyMovement::query()->where('bill_id',$id)->get()->first();
        VendorMovement::destroy($vendorMovementId);
    }

    public function addPurchasePaymentMovement($id){
        $payment = Payment::find($id);
        $sale = Purchase::find($payment->purchase_id);

        VendorMovement::create([
            'vendor_id' => $sale->customer_id,
            'paid' => 0,
            'debit' => 0,
            'credit' => $payment->amount,
            'date' => $payment->date,
            'invoice_type' => 'Purchase_Payment',
            'invoice_id' => $id,
            'invoice_no' => $sale->invoice_no,
            'paid_by' => $payment->paid_by
        ]);
    }

    public function removePurchasePaymentMovement($id){
        $vendorMovementId = VendorMovement::query()->where('invoice_id',$id)->get()->first();
        VendorMovement::destroy($vendorMovementId);
    }

}
