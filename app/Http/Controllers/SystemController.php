<?php

namespace App\Http\Controllers;

use App\Models\AccountSetting;
use App\Models\AccountsTree;
use App\Models\Brand;
use App\Models\CatchRecipt;
use App\Models\Category;
use App\Models\Company;
use App\Models\Currency;
use App\Models\CustomerGroup;
use App\Models\EnterMoney;
use App\Models\EnterOld;
use App\Models\EnterWork;
use App\Models\ExitMoney;
use App\Models\ExitOld;
use App\Models\ExitOldDetails;
use App\Models\ExitWork;
use App\Models\ExitWorkDetails;
use App\Models\Expenses;
use App\Models\ExpensesCategory;
use App\Models\ExpenseType;
use App\Models\Journal;
use App\Models\JournalDetails;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Purchase;
use App\Models\Sales;
use App\Models\TaxRates;
use App\Models\Unit;
use App\Models\Warehouse;
use App\Models\WarehouseProducts;
use Database\Factories\JournalFactory;
use Faker\Core\Number;
use Illuminate\Support\Facades\DB;

class SystemController extends Controller
{
    public function syncQnt($items = null, $oldItems = null, $isMinus = true)
    {

        $multy = $isMinus ? -1 : 1;

        if ($items) {
            foreach ($items as $item) {
                $item->quantity = $item->quantity * $multy;

                $productId = $item->product_id;
                $warehouseId = $item->warehouse_id;

                $product = Product::find($productId);
                $product->update([
                    'quantity' => $product->quantity + $item->quantity
                ]);

                $warehouseProduct = WarehouseProducts::query()
                    ->where('product_id', $productId)
                    ->where('warehouse_id', $warehouseId)
                    ->get()->first();

                if ($warehouseProduct) {
                    $warehouseProduct->update([
                        'quantity' => $warehouseProduct->quantity + $item->quantity
                    ]);
                }


            }
        }

        if ($oldItems) {
            foreach ($oldItems as $item) {

                $item->quantity = $item->quantity * $multy;

                $productId = $item->product_id;
                $warehouseId = $item->warehouse_id;

                $product = Product::find($productId);
                $product->update([
                    'quantity' => $product->quantity - $item->quantity
                ]);

                $warehouseProduct = WarehouseProducts::query()
                    ->where('product_id', $productId)
                    ->where('warehouse_id', $warehouseId)
                    ->get()->first();


                $warehouseProduct->update([
                    'quantity' => $warehouseProduct->quantity - $item->quantity
                ]);

            }
        }

    }


    //region Journals


    public function saleJournals($id)
    {
        $saleInvoice = Sales::find($id);
        if ($saleInvoice->net < 0) {
            return $this->returnSaleJournal($id);
        }

        $settings = AccountSetting::query()->where('warehouse_id', $saleInvoice->warehouse_id)->get()->first();
        if (!$settings)
            return;

        $headerData = [
            'date' => $saleInvoice->date,
            'basedon_no' => $saleInvoice->invoice_no,
            'basedon_id' => $id,
            'baseon_text' => 'فاتورة مبيعات',
            'total_credit' => 0,
            'total_debit' => 0,
            'notes' => ''
        ];
        //journal details
        $detailsData = [];

        //credit for details
        //حساب الصندوق - الخصم
        if ($saleInvoice->discount > 0) {
            $detailsData[] = [
                'account_id' => $settings->sales_discount_account,
                'credit' => $saleInvoice->discount,
                'debit' => 0,
                'ledger_id' => 0,
                'notes' => ''
            ];
        }

        if ($saleInvoice->net > 0) {

            $remain = $saleInvoice->net;

            if ($remain > 0) {
                $customerAccount = $this->getClientById($saleInvoice->customer_id)->account_id;
                $detailsData[] = [
                    'account_id' => $customerAccount,
                    'credit' => $remain,
                    'debit' => 0,
                    'ledger_id' => $saleInvoice->customer_id,
                    'notes' => ''
                ];
            }
        }
        //debit for details
        //الضريبة - المبيعات
        if ($saleInvoice->total > 0) {
            $detailsData[] = [
                'account_id' => $settings->sales_account,
                'debit' => $saleInvoice->total,
                'credit' => 0,
                'ledger_id' => 0,
                'notes' => ''
            ];
        }

        if ($saleInvoice->tax > 0) {
            $detailsData[] = [
                'account_id' => $settings->sales_tax_account,
                'debit' => $saleInvoice->tax,
                'credit' => 0,
                'ledger_id' => 0,
                'notes' => ''
            ];
        }

        if ($saleInvoice->total > 0 && $settings->profit_account > 0 && $settings->cost_account > 0) {


            // هيدخل هنا في التكلفة وفي الارباح
            $detailsData[] = [
                'account_id' => $settings->profit_account,
                'credit' => $saleInvoice->profit,
                'debit' => 0,
                'ledger_id' => 0,
                'notes' => ''
            ];

            if ($settings->reverse_profit_account > 0) {
                $detailsData[] = [
                    'account_id' => $settings->reverse_profit_account,
                    'debit' => $saleInvoice->profit,
                    'credit' => 0,
                    'ledger_id' => 0,
                    'notes' => ''
                ];
            }


            $detailsData[] = [
                'account_id' => $settings->cost_account,
                'credit' => $saleInvoice->total - $saleInvoice->profit,
                'debit' => 0,
                'ledger_id' => 0,
                'notes' => ''
            ];


            $detailsData[] = [
                'account_id' => $settings->stock_account,
                'debit' => $saleInvoice->total - $saleInvoice->profit,
                'credit' => 0,
                'ledger_id' => 0,
                'notes' => ''
            ];

        }


        $this->insertJournal($headerData, $detailsData);


    }

    private function returnSaleJournal($id)
    {
        $saleInvoice = Sales::find($id);

        $settings = AccountSetting::query()->where('warehouse_id', $saleInvoice->warehouse_id)->get()->first();
        if (!$settings)
            return;
        //journal header
        $headerData = [
            'date' => $saleInvoice->date,
            'basedon_no' => $saleInvoice->invoice_no,
            'basedon_id' => $id,
            'baseon_text' => 'مرتجع مبيعات',
            'total_credit' => 0,
            'total_debit' => 0,
            'notes' => ''
        ];
        //journal details
        $detailsData = [];

        //credit for details
        //حساب الصندوق - الخصم
        if ($saleInvoice->discount <> 0) {
            $detailsData[] = [
                'account_id' => $settings->purchase_discount_account,
                'debit' => $saleInvoice->discount * -1,
                'credit' => 0,
                'ledger_id' => 0,
                'notes' => ''
            ];
        }
        if ($saleInvoice->net <> 0) {


            $customerAccount = $this->getClientById($saleInvoice->customer_id)->account_id;
            $detailsData[] = [
                'account_id' => $customerAccount,
                'debit' => $saleInvoice->net * -1,
                'credit' => 0,
                'ledger_id' => $saleInvoice->customer_id,
                'notes' => ''
            ];


        }
        //debit for details
        //الضريبة - المبيعات
        if ($saleInvoice->total <> 0) {
            $detailsData[] = [
                'account_id' => $settings->return_sales_account,
                'credit' => $saleInvoice->total * -1,
                'debit' => 0,
                'ledger_id' => 0,
                'notes' => ''
            ];
        }

        if ($saleInvoice->tax <> 0) {
            $detailsData[] = [
                'account_id' => $settings->sales_tax_account,
                'credit' => $saleInvoice->tax * -1,
                'debit' => 0,
                'ledger_id' => 0,
                'notes' => ''
            ];
        }


        if ($saleInvoice->total <> 0 && $settings->profit_account > 0 && $settings->cost_account > 0) {

            // هيدخل هنا في التكلفة وفي الارباح
            $detailsData[] = [
                'account_id' => $settings->profit_account,
                'debit' => $saleInvoice->profit * -1,
                'credit' => 0,
                'ledger_id' => 0,
                'notes' => ''
            ];

            if ($settings->reverse_profit_account > 0) {
                $detailsData[] = [
                    'account_id' => $settings->reverse_profit_account,
                    'credit' => $saleInvoice->profit * -1,
                    'debit' => 0,
                    'ledger_id' => 0,
                    'notes' => ''
                ];
            }

            $detailsData[] = [
                'account_id' => $settings->return_sales_account,
                'debit' => ($saleInvoice->total - $saleInvoice->profit) * -1,
                'credit' => 0,
                'ledger_id' => 0,
                'notes' => ''
            ];


            $detailsData[] = [
                'account_id' => $settings->stock_account,
                'credit' => ($saleInvoice->total - $saleInvoice->profit) * -1,
                'debit' => 0,
                'ledger_id' => 0,
                'notes' => ''
            ];


        }

        $this->insertJournal($headerData, $detailsData);
    }


    public function purchaseJournals($id)
    {


        $saleInvoice = Purchase::find($id);
        if ($saleInvoice->net < 0)
            return $this->returnPurchaseJournals($id);

        $settings = AccountSetting::query()->where('warehouse_id', $saleInvoice->warehouse_id)->get()->first();
        if (!$settings)
            return;

        $headerData = [
            'date' => $saleInvoice->date,
            'basedon_no' => $saleInvoice->invoice_no,
            'basedon_id' => $id,
            'baseon_text' => 'فاتورة مشتريات',
            'total_credit' => 0,
            'total_debit' => 0,
            'notes' => ''
        ];

        $detailsData = [];

        //credit for details
        //حساب الصندوق - الخصم
        if ($saleInvoice->discount > 0) {
            $detailsData[] = [
                'account_id' => $settings->sales_discount_account,
                'debit' => $saleInvoice->discount,
                'credit' => 0,
                'ledger_id' => 0,
                'notes' => ''
            ];
        }

        ////log_message('error','F6 :'.$id);

        if ($saleInvoice->net > 0) {
            $customerAccount = $this->getClientById($saleInvoice->customer_id)->account_id;;
            $detailsData[] = [
                'account_id' => $customerAccount,
                'debit' => $saleInvoice->net,
                'credit' => 0,
                'ledger_id' => $saleInvoice->customer_id,
                'notes' => ''
            ];

        }

        ////log_message('error','F7 :'.$id);
        //debit for details
        //الضريبة - المبيعات
        if ($saleInvoice->total > 0) {
            $detailsData[] = [
                'account_id' => $settings->purchase_account,
                'credit' => $saleInvoice->total,
                'debit' => 0,
                'ledger_id' => 0,
                'notes' => ''
            ];


            $detailsData[] = [
                'account_id' => $settings->stock_account,
                'credit' => $saleInvoice->total,
                'debit' => 0,
                'ledger_id' => 0,
                'notes' => ''
            ];

            $detailsData[] = [
                'account_id' => $settings->purchase_account,
                'debit' => $saleInvoice->total,
                'credit' => 0,
                'ledger_id' => 0,
                'notes' => ''
            ];
        }

        if ($saleInvoice->tax > 0) {
            $detailsData[] = [
                'account_id' => $settings->purchase_tax_account,
                'credit' => $saleInvoice->tax,
                'debit' => 0,
                'ledger_id' => 0,
                'notes' => ''
            ];
        }

        $this->insertJournal($headerData, $detailsData);
    }

    public function returnPurchaseJournals($id)
    {

        $saleInvoice = Purchase::find($id);

        $settings = AccountSetting::query()->where('warehouse_id', $saleInvoice->warehouse_id)->get()->first();
        if (!$settings)
            return;

        //journal header
        $headerData = [
            'date' => $saleInvoice->date,
            'basedon_no' => $saleInvoice->invoice_no,
            'basedon_id' => $id,
            'baseon_text' => 'مرتجع مشتريات',
            'total_credit' => 0,
            'total_debit' => 0,
            'notes' => ''
        ];
        //journal details
        $detailsData = [];

        //credit for details
        //حساب الصندوق - الخصم
        if ($saleInvoice->order_discount < 0) {
            $detailsData[] = [
                'account_id' => $settings->purchase_discount_account,
                'debit' => $saleInvoice->order_discount * -1,
                'credit' => 0,
                'ledger_id' => 0,
                'notes' => ''
            ];
        }

        if ($saleInvoice->net < 0) {

            $customerAccount = $this->getClientById($saleInvoice->customer_id)->account_id;;
            $detailsData[] = [
                'account_id' => $customerAccount,
                'credit' => $saleInvoice->net * -1,
                'debit' => 0,
                'ledger_id' => $saleInvoice->customer_id,
                'notes' => ''
            ];

        }
        //debit for details
        //الضريبة - المبيعات
        if ($saleInvoice->total < 0) {
            $detailsData[] = [
                'account_id' => $settings->return_purchase_account,
                'debit' => $saleInvoice->total * -1,
                'credit' => 0,
                'ledger_id' => 0,
                'notes' => ''
            ];

            $detailsData[] = [
                'account_id' => $settings->stock_account,
                'debit' => $saleInvoice->total * -1,
                'credit' => 0,
                'ledger_id' => 0,
                'notes' => ''
            ];

            $detailsData[] = [
                'account_id' => $settings->return_purchase_account,
                'credit' => $saleInvoice->total * -1,
                'debit' => 0,
                'ledger_id' => 0,
                'notes' => ''
            ];
        }

        if ($saleInvoice->tax < 0) {
            $detailsData[] = [
                'account_id' => $settings->purchase_tax_account,
                'debit' => $saleInvoice->tax * -1,
                'credit' => 0,
                'ledger_id' => 0,
                'notes' => ''
            ];
        }

        $this->insertJournal($headerData, $detailsData);
    }


    public function getJournal($data)
    {

        $data = Journal::query()
            ->where('basedon_no', $data['basedon_no'])
            ->where('basedon_id', $data['basedon_id'])
            ->where('baseon_text', $data['baseon_text'])->get()->first();

        if ($data) {
            return $data->id;
        }
        return 0;
    }

    private function getOldDetails($id)
    {
        return JournalDetails::query()->where('journal_id', $id)->get();
    }

    public function insertJournal($header, $details, $manual = 0)
    {

        if ($id = $this->getJournal($header)) {

            $journal = Journal::find($id);
            $journal->update($header);

            $oldDetails = $this->getOldDetails($id);
            ////log_message('error',$id);
            foreach ($oldDetails as $oldDetail) {
                $this->updateAccountBalance($oldDetail->account_id, -1 * $oldDetail->credit, -1 * $oldDetail->debit, $header['date'], $id , $oldDetail -> notes);
            }

            DB::table('journal_details')
                ->where('journal_id', $id)
                ->delete();

            DB::table('account_movements')
                ->where('journal_id', $id)
                ->delete();


            foreach ($details as $detail) {
                $detail['journal_id'] = $id;

                DB::table('journal_details')
                    ->insert($detail);

                $this->updateAccountBalance($detail['account_id'], $detail['credit'], $detail['debit'], $header['date'], $id , $detail['notes']);
            }

            return true;
        } else {
            $journal_id = DB::table('journals')
                ->insertGetId($header);
            if ($journal_id) {

                foreach ($details as $detail) {
                    $detail['journal_id'] = $journal_id;

                    DB::table('journal_details')
                        ->insert($detail);


                    $this->updateAccountBalance($detail['account_id'], $detail['credit'], $detail['debit'], $header['date'], $journal_id , $detail['notes']);
                }

                if ($manual == 1) {
                    $journal = Journal::find($journal_id);


                    $journal->update(['baseon_text' => 'سند قيد يدوي رقم ' . $journal_id]);
                }
            }
            return true;
        }

        return false;

    }


    private function updateAccountBalance($id, $credit, $debit, $date, $journalId , $notes)
    {
        $account = $this->getAccountById($id);

        if (!$account) {
            return;
        }


        if ($credit <> 0 || $debit <> 0) {
            $accountMData = [
                'journal_id' => $journalId,
                'account_id' => $id,
                'credit' => $credit,
                'debit' => $debit,
                'date' => $date,
                'notes' =>  $notes
            ];

            DB::table('account_movements')->insert($accountMData);
        }


        if ($account->parent_id > 0) {
            $this->updateAccountBalance($account->parent_id, $credit, $debit, $date, $journalId , $notes);
        }

    }

    private function getAccountById($id)
    {
        if (!$id) {
            $id = 0;
        }
        return AccountsTree::find($id);

    }

    private function getJournalForDelete($data)
    {

        $data = Journal::query()
            ->where('basedon_id', $data['basedon_id'])
            ->where('baseon_text', $data['baseon_text'])->get()->first();

        if ($data) {
            return $data->id;
        }
        return 0;
    }

    public function deleteJournal($header)
    {

        if ($id = $this->getJournalForDelete($header)) {


            $oldDetails = $this->getOldDetails($id);
            foreach ($oldDetails as $oldDetail) {
                $this->updateAccountBalance($oldDetail->account_id, -1 * $oldDetail->credit, -1 * $oldDetail->debit
                    , $header['date'], $id , $oldDetail -> notes) ;
            }

            DB::table('journal_details')
                ->where('journal_id', $id)
                ->delete();

            DB::table('account_movements')
                ->where('journal_id', $id)
                ->delete();

            DB::table('journals')
                ->where('id', $id)
                ->delete();

            return true;
        }
    }


    public function EnterWorkAccounting($id)
    {
        // purchase gold from factory (money - gold)
        $bill = EnterWork::find($id);
        if ($bill) {
            $settings = AccountSetting::all()->first();
            if (!$settings)
                return;

            //journal header
            $headerData = [
                'date' => $bill->date,
                'basedon_no' => $bill->bill_number,
                'basedon_id' => $id,
                'baseon_text' => 'شراء ذهب مشغول',
                'total_credit' => 0,
                'total_debit' => 0,
                'notes' => ''
            ];

            $detailsData = [];

            $customerAccount = Company::find($bill->supplier_id)->account_id;;

            //credit for details
            //حساب المشتريات نقدا - الي حساب المورد نقدا


            if ($bill->discount > 0) {
                $detailsData[] = [
                    'account_id' => $settings->purchase_discount_account,
                    'debit' => $bill->discount,
                    'credit' => 0,
                    'ledger_id' => 0,
                    'notes' => ''
                ];
            }

            ////log_message('error','F6 :'.$id);

            if ($bill->net_money > 0) {

                $detailsData[] = [
                    'account_id' => $customerAccount,
                    'debit' => $bill->net_money,
                    'credit' => 0,
                    'ledger_id' => $bill->supplier_id,
                    'notes' => ''
                ];

            }

            ////log_message('error','F7 :'.$id);
            //debit for details
            //الضريبة - المبيعات
            if ($bill->total_money > 0) {
                $detailsData[] = [
                    'account_id' => $settings->purchase_account,
                    'credit' => $bill->total_money,
                    'debit' => 0,
                    'ledger_id' => 0,
                    'notes' => ''
                ];





            }

            if ($bill->tax > 0) {
                $detailsData[] = [
                    'account_id' => $settings->purchase_tax_account,
                    'credit' => $bill->tax,
                    'debit' => 0,
                    'ledger_id' => 0,
                    'notes' => ''
                ];
            }




            //حساب المشتريات  ذهب - الي حساب المورد ذهب
            $detailsData[] = [
                'account_id' => $settings->purchase_account,
                'debit' => 0,
                'credit' => $bill->total21_gold,
                'ledger_id' => 0,
                'notes' => 'جرام ذهب عيار 21'
            ];

            $detailsData[] = [
                'account_id' => $customerAccount,
                'debit' => $bill->total21_gold,
                'credit' => 0,
                'ledger_id' => $bill->supplier_id,
                'notes' => 'جرام ذهب عيار 21'
            ];

            //



            $this->insertJournal($headerData, $detailsData);

        }

    }

    public function ExitWorkAccounting($id){
        $bill = ExitWork::find($id);
        $details = ExitWorkDetails::where('bill_id' , '=' , $id) -> get();
        $total_tax = 0 ;
        foreach ($details as $detail){
            $total_tax = + ($detail -> weight * $detail -> gram_tax );
        }
        if($bill){
            $settings = AccountSetting::all()->first();
            if (!$settings)
                return;

            //journal header
            $headerData = [
                'date' => $bill->date,
                'basedon_no' => $bill->bill_number,
                'basedon_id' => $id,
                'baseon_text' => 'بيع ذهب مشغول ',
                'total_credit' => 0,
                'total_debit' => 0,
                'notes' => ''
            ];

            $detailsData = [];

            $customerAccount = Company::find($bill->client_id)->account_id;


            if ($bill->discount > 0) {
                $detailsData[] = [
                    'account_id' => $settings->sales_discount_account,
                    'credit' => $bill->discount,
                    'debit' => 0,
                    'ledger_id' => 0,
                    'notes' => ''
                ];
            }

            if ($bill->net_money > 0) {

                $remain = $bill->net_money;

                if ($remain > 0) {
                    $detailsData[] = [
                        'account_id' => $customerAccount,
                        'credit' => $remain,
                        'debit' => 0,
                        'ledger_id' => $bill->client_id,
                        'notes' => ''
                    ];
                }
            }
            //debit for details
            //الضريبة - المبيعات
            if ($bill->total_money > 0) {
                $detailsData[] = [
                    'account_id' => $settings->sales_account,
                    'debit' => $bill->total_money,
                    'credit' => 0,
                    'ledger_id' => 0,
                    'notes' => ''
                ];
            }

            if ($bill->tax > 0) {
                $detailsData[] = [
                    'account_id' => $settings->sales_tax_account,
                    'debit' => $bill->tax,
                    'credit' => 0,
                    'ledger_id' => 0,
                    'notes' => ''
                ];
            }

            $detailsData[] = [
                'account_id' => $settings->sales_account,
                'debit' => $bill->total21_gold,
                'credit' => 0,
                'ledger_id' => 0,
                'notes' => 'جرام ذهب عيار 21'
            ];
            $detailsData[] = [
                'account_id' => $customerAccount,
                'debit' => 0,
                'credit' => $bill->total21_gold,
                'ledger_id' => 0,
                'notes' => 'جرام ذهب عيار 21'
            ];




            $this->insertJournal($headerData, $detailsData);

        }
    }



    public function ReturnExitWorkAccounting($id){
        $bill = ExitWork::find($id);
        $details = ExitWorkDetails::where('bill_id' , '=' , $id) -> get();
        $total_tax = 0 ;
        foreach ($details as $detail){
            $total_tax = + ($detail -> weight * $detail -> gram_tax );
        }
        if($bill){
            $settings = AccountSetting::all()->first();
            if (!$settings)
                return;

            //journal header
            $headerData = [
                'date' => $bill->date,
                'basedon_no' => $bill->bill_number,
                'basedon_id' => $id,
                'baseon_text' => 'مرتجع بيع ذهب مشغول ',
                'total_credit' => 0,
                'total_debit' => 0,
                'notes' => ''
            ];

            $detailsData = [];

            $customerAccount = Company::find($bill->client_id)->account_id;




            //debit for details
            //الضريبة - المبيع

            if ($bill->discount <> 0) {
                $detailsData[] = [
                    'account_id' => $settings->purchase_discount_account,
                    'credit' => $bill->discount * -1,
                    'debit' => 0,
                    'ledger_id' => 0,
                    'notes' => ''
                ];
            }

            if ($bill->net_money <> 0) {

                $remain = $bill->net_money;

                if ($remain <> 0) {
                    $detailsData[] = [
                        'account_id' => $customerAccount,
                        'credit' => $remain * -1 ,
                        'debit' => 0,
                        'ledger_id' => $bill->client_id,
                        'notes' => ''
                    ];
                }
            }
            //debit for details
            //الضريبة - المبيعات
            if ($bill->total_money <> 0) {
                $detailsData[] = [
                    'account_id' => $settings->return_sales_account,
                    'debit' => $bill->total_money * -1,
                    'credit' => 0,
                    'ledger_id' => 0,
                    'notes' => ''
                ];
            }

            if ($bill->tax <> 0) {
                $detailsData[] = [
                    'account_id' => $settings->sales_tax_account,
                    'debit' => $bill->tax * -1,
                    'credit' => 0,
                    'ledger_id' => 0,
                    'notes' => ''
                ];
            }

            $detailsData[] = [
                'account_id' => $settings->return_sales_account,
                'debit' => $bill->total21_gold * -1,
                'credit' => 0,
                'ledger_id' => 0,
                'notes' => 'جرام ذهب عيار 21'
            ];
            $detailsData[] = [
                'account_id' => $customerAccount,
                'debit' => 0,
                'credit' => $bill->total21_gold * -1,
                'ledger_id' => 0,
                'notes' => 'جرام ذهب عيار 21'
            ];




            $this->insertJournal($headerData, $detailsData);

        }
    }



    public function ExitOldAccounting($id){
        $bill = ExitOld::find($id);
        $details = ExitOldDetails::where('bill_id' , '=' , $id) -> get();
        $total_tax = 0 ;
        foreach ($details as $detail){
            $total_tax = + ($detail -> weight * $detail -> gram_tax );
        }
        if($bill){
            $settings = AccountSetting::all()->first();
            if (!$settings)
                return;

            //journal header
            $headerData = [
                'date' => $bill->date,
                'basedon_no' => $bill->bill_number,
                'basedon_id' => $id,
                'baseon_text' => 'بيع ذهب كسر ',
                'total_credit' => 0,
                'total_debit' => 0,
                'notes' => ''
            ];

            $detailsData = [];

            $customerAccount = Company::find($bill->supplier_id)->account_id;
            //حساب العميل  نقدا - الي حساب المبيعات نقدا

            if ($bill->discount > 0) {
                $detailsData[] = [
                    'account_id' => $settings->sales_discount_account,
                    'credit' => $bill->discount,
                    'debit' => 0,
                    'ledger_id' => 0,
                    'notes' => ''
                ];
            }

            if ($bill->net_money > 0) {

                $remain = $bill->net_money;

                if ($remain > 0) {
                    $detailsData[] = [
                        'account_id' => $customerAccount,
                        'credit' => $remain,
                        'debit' => 0,
                        'ledger_id' => $bill->supplier_id,
                        'notes' => ''
                    ];
                }
            }
            //debit for details
            //الضريبة - المبيعات
            if ($bill->total_money > 0) {
                $detailsData[] = [
                    'account_id' => $settings->sales_account,
                    'debit' => $bill->total_money,
                    'credit' => 0,
                    'ledger_id' => 0,
                    'notes' => ''
                ];
            }

            if ($bill->tax > 0) {
                $detailsData[] = [
                    'account_id' => $settings->sales_tax_account,
                    'debit' => $bill->tax,
                    'credit' => 0,
                    'ledger_id' => 0,
                    'notes' => ''
                ];
            }

            //حساب العميل  ذهب - الي حساب المبيعات ذهب
            $detailsData[] = [
                'account_id' => $settings->sales_account,
                'debit' => $bill->total21_gold,
                'credit' => 0,
                'ledger_id' => 0,
                'notes' => 'جرام ذهب عيار 21'
            ];
            $detailsData[] = [
                'account_id' => $customerAccount,
                'debit' => 0,
                'credit' => $bill->total21_gold,
                'ledger_id' => 0,
                'notes' => 'جرام ذهب عيار 21'
            ];



            $this->insertJournal($headerData, $detailsData);

        }
    }

    public function EnterMoneyAccounting($id){
        $bill = EnterMoney::find($id);
        if($bill){
            $settings = AccountSetting::all()->first();
            if (!$settings)
                return;

            //journal header
            $headerData = [
                'date' => $bill->date,
                'basedon_no' => $bill->doc_number,
                'basedon_id' => $id,
                'baseon_text' => 'مستند قبض',
                'total_credit' => 0,
                'total_debit' => 0,
                'notes' => ''
            ];

            $detailsData = [];

            $customerAccount = Company::find($bill->client_id)->account_id;
            //حساب العميل  نقدا - الي حساب الصندوق نقدا
            $detailsData[] = [
                'account_id' => $settings->safe_account,
                'debit' => $bill -> amount ,
                'credit' => 0,
                'ledger_id' => 0,
                'notes' => ''
            ];
            $detailsData[] = [
                'account_id' => $customerAccount,
                'debit' => 0,
                'credit' => $bill -> amount,
                'ledger_id' => 0,
                'notes' => ''
            ];
            $this->insertJournal($headerData, $detailsData);

        }
    }

    public function EnterOldAccounting($id)
    {
        // purchase gold from factory (money - gold)
        $bill = EnterOld::find($id);
        if ($bill) {
            $settings = AccountSetting::all()->first();
            if (!$settings)
                return;

            //journal header
            $headerData = [
                'date' => $bill->date,
                'basedon_no' => $bill->bill_number,
                'basedon_id' => $id,
                'baseon_text' => 'شراء ذهب كسر',
                'total_credit' => 0,
                'total_debit' => 0,
                'notes' => ''
            ];

            $detailsData = [];

            $customerAccount = Company::find($bill->client_id)->account_id;;

            //credit for details
            //حساب المشتريات نقدا - الي حساب المورد نقدا

            if ($bill->discount > 0) {
                $detailsData[] = [
                    'account_id' => $settings->purchase_discount_account,
                    'debit' => $bill->discount,
                    'credit' => 0,
                    'ledger_id' => 0,
                    'notes' => ''
                ];
            }

            ////log_message('error','F6 :'.$id);

            if ($bill->net_money > 0) {

                $detailsData[] = [
                    'account_id' => $customerAccount,
                    'debit' => $bill->net_money,
                    'credit' => 0,
                    'ledger_id' => $bill->client_id,
                    'notes' => ''
                ];

            }

            ////log_message('error','F7 :'.$id);
            //debit for details
            //الضريبة - المبيعات
            if ($bill->total_money > 0) {
                $detailsData[] = [
                    'account_id' => $settings->purchase_account,
                    'credit' => $bill->total_money,
                    'debit' => 0,
                    'ledger_id' => 0,
                    'notes' => ''
                ];





            }

            if ($bill->tax > 0) {
                $detailsData[] = [
                    'account_id' => $settings->purchase_tax_account,
                    'credit' => $bill->tax,
                    'debit' => 0,
                    'ledger_id' => 0,
                    'notes' => ''
                ];
            }

            //حساب المشتريات  ذهب - الي حساب المورد ذهب
            $detailsData[] = [
                'account_id' => $settings->purchase_account,
                'debit' => 0,
                'credit' => $bill->total21_gold,
                'ledger_id' => 0,
                'notes' => 'جرام ذهب عيار 21'
            ];

            $detailsData[] = [
                'account_id' => $customerAccount,
                'debit' => $bill->total21_gold,
                'credit' => 0,
                'ledger_id' => 0,
                'notes' => 'جرام ذهب عيار 21'
            ];

            //


            $this->insertJournal($headerData, $detailsData);

        }

    }

    public function ExitMoneyAccounting($id){
        $bill = ExitMoney::find($id);
        if($bill){
            $settings = AccountSetting::all()->first();
            if (!$settings)
                return;

            //journal header
            $headerData = [
                'date' => $bill->date,
                'basedon_no' => $bill->doc_number,
                'basedon_id' => $id,
                'baseon_text' => 'مستند صرف',
                'total_credit' => 0,
                'total_debit' => 0,
                'notes' => ''
            ];

            $detailsData = [];

            $customerAccount = Company::find($bill->supplier_id)->account_id;
            //حساب العميل  نقدا - الي حساب الصندوق نقدا
            $detailsData[] = [
                'account_id' => $settings->safe_account,
                'debit' =>  0,
                'credit' => $bill -> amount,
                'ledger_id' => 0,
                'notes' => ''
            ];
            $detailsData[] = [
                'account_id' => $customerAccount,
                'debit' => $bill -> amount,
                'credit' => 0,
                'ledger_id' => 0,
                'notes' => ''
            ];
            $this->insertJournal($headerData, $detailsData);

        }
    }


    public function ExpenseAccounting($id){
        $bill = Expenses::find($id);
        if ($bill) {
            $settings = AccountSetting::all()->first();
            if (!$settings)
                return;

            //journal header
            $headerData = [
                'date' => $bill->date,
                'basedon_no' => $bill->docNumber,
                'basedon_id' => $id,
                'baseon_text' => 'مستند صرف صندوق',
                'total_credit' => 0,
                'total_debit' => 0,
                'notes' => ''
            ];

            $detailsData = [];

            $expenseTypeAccount = ExpenseType::find($bill->type_id)->account_id;


            $detailsData[] = [
                'account_id' => $settings->safe_account,
                'debit' =>  0,
                'credit' => $bill -> amount,
                'ledger_id' => 0,
                'notes' => ''
            ];
            $detailsData[] = [
                'account_id' => $expenseTypeAccount,
                'debit' => $bill -> amount,
                'credit' => 0,
                'ledger_id' => 0,
                'notes' => ''
            ];
            $this->insertJournal($headerData, $detailsData);

        }
    }

    public function CatchAccounting($id){
        $bill = CatchRecipt::find($id);
        if ($bill) {
            $settings = AccountSetting::all()->first();
            if (!$settings)
                return;

            //journal header
            $headerData = [
                'date' => $bill->date,
                'basedon_no' => $bill->docNumber,
                'basedon_id' => $id,
                'baseon_text' => 'مستند قيض',
                'total_credit' => 0,
                'total_debit' => 0,
                'notes' => ''
            ];

            $detailsData = [];

            $expenseTypeAccount = ExpenseType::find($bill->type_id)->account_id;


            $detailsData[] = [
                'account_id' => $settings->safe_account,
                'credit' =>  0,
                'debit' => $bill -> amount,
                'ledger_id' => 0,
                'notes' => ''
            ];
            $detailsData[] = [
                'account_id' => $expenseTypeAccount,
                'credit' => $bill -> amount,
                'debit' => 0,
                'ledger_id' => 0,
                'notes' => ''
            ];
            $this->insertJournal($headerData, $detailsData);

        }
    }

    //endregion
}
