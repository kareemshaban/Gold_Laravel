<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CompanyInfo;
use App\Models\ExitWorkDetails;
use App\Models\Item;
use App\Models\Item2;
use App\Models\ItemMaterials;
use App\Models\Karat;
use App\Models\Pricing;
use App\Models\Purchase;
use App\Models\PurchaseDetails;
use App\Models\SaleDetails;
use App\Models\Storehouse;
use App\Models\WarehouseProducts;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        $karats = Karat::all();
        $items = DB::table('items')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->leftJoin('karats', 'karats.id', '=', 'items.karat_id')
            ->select('items.*', 'categories.name_ar as category_name_ar', 'categories.name_en as category_name_en',
                'karats.name_ar as karat_name_ar', 'karats.name_en as karat_name_en')->orderByDesc('id')->get();

        $data = $items;
        // return  $data ;
        $pricings = Pricing::all();
        $roles = DB::table('role_views')
            ->join('views', 'role_views.view_id', '=', 'views.id')
            ->join('roles', 'role_views.role_id', '=', 'roles.id')
            ->select('role_views.*', 'views.name_ar as view_name_ar', 'views.name_en as view_name_en',
                'roles.name_ar as role_name_ar', 'roles.name_en as role_name_en', 'views.route')
            ->where('role_views.role_id', '=', Auth::user()->role_id)
            ->where('role_views.all_auth', '=', 1)
            ->get();


        $routes = [];
        foreach ($roles as $role) {
            array_push($routes, $role->route);
        }

        return view('Item.index', compact('data', 'categories', 'karats', 'routes'));

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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->id == 0) {
            if ($request->item_type == 1 || $request->item_type == 3) {
                $validated = $request->validate([
                    'code' => 'required|unique:items',
                    'name_ar' => 'required',
                    'category_id' => 'required',
                    'karat_id' => 'required',
                    'weight' => 'required',
                    'no_metal' => 'required',
                    'no_metal_type' => 'required',
                ]);
            } else {
                $validated = $request->validate([
                    'code' => 'required|unique:items',
                    'name_ar' => 'required',
                    'category_id' => 'required',
                    'price' => 'required',
                    'cost' => 'required',
                    'taxx' => 'required',
                ]);
            }


            if ($request->has('img')) {
                if ($request->file('img')->getSize() / 1000 > 2000) {
                    return redirect()->route('items')->with('error', __('main.img_big'));
                }
                $imageName = time() . '.' . $request->img->extension();
                $request->img->move(('images/Items'), $imageName);
            } else {
                $imageName = '';
            }


            $tax = 0;
            if ($request->item_type == 1 || $request->item_type == 1) {
                if ($request->tax) {
                    $tax = $request->tax;
                }
            } else {
                if ($request->taxx) {
                    $tax = $request->taxx;
                }
            }
            try {

                $product = Item::create([
                    'code' => $request->code,
                    'name_ar' => $request->name_ar,
                    'name_en' => $request->name_en != null ? $request->name_en : ' ',
                    'category_id' => $request->category_id,
                    'karat_id' => $request->karat_id ?? 0,
                    'weight' => $request->weight ?? 0,
                    'no_metal' => $request->no_metal ?? 0,
                    'no_metal_type' => $request->no_metal_type ?? 0,
                    'made_Value' => $request->made_Value ?? 0,
                    'item_type' => $request->item_type ?? 1,
                    'tax' => $tax,
                    'state' => $request->state ?? 1,
                    'img' => $imageName,
                    'price' => $request->price ?? 0,
                    'cost' => $request->cost ?? 0,
                ]);

                Item2::create([
                    'code' => $request->code,
                    'name_ar' => $request->name_ar,
                    'name_en' => $request->name_en != null ? $request->name_en : ' ',
                    'category_id' => $request->category_id,
                    'karat_id' => $request->karat_id ?? 0,
                    'weight' => $request->weight ?? 0,
                    'no_metal' => $request->no_metal ?? 0,
                    'no_metal_type' => $request->no_metal_type ?? 0,
                    'made_Value' => $request->made_Value ?? 0,
                    'item_type' => $request->item_type ?? 1,
                    'tax' => $tax,
                    'state' => $request->state ?? 1,
                    'img' => $imageName,
                    'price' => $request->price ?? 0,
                    'cost' => $request->cost ?? 0,
                ]);


                if ($request->item_type == 2) {
                    $warehouses = Storehouse::all();
                    foreach ($warehouses as $warehouse) {
                        WarehouseProducts::create([
                            'warehouse_id' => $warehouse->id,
                            'product_id' => $product->id,
                            'cost' => $product->cost,
                            'quantity' => 0
                        ]);
                    }
                }


                return redirect()->route('items')->with('success', __('main.created'));
            } catch (QueryException $ex) {

                return redirect()->route('items')->with('error', $ex->getMessage());
            }
        } else {
            return $this->update($request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Item $item
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Item::find($id);
        if ($item) {
            echo json_encode($item);
            exit;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Item $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Item $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $item = Item::find($request->id);
        if ($item) {

            $old_weight = $item->weight;
            if ($item->item_type == 1 || $item->item_type == 3) {
                $validated = $request->validate([
                    'code' => ['required', Rule::unique('items')->ignore($request->id)],
                    'name_ar' => 'required',
                    'category_id' => 'required',
                    'karat_id' => 'required',
                    'weight' => 'required',
                    'no_metal' => 'required',
                    'no_metal_type' => 'required',
                ]);
            } else {
                $validated = $request->validate([
                    'code' => ['required', Rule::unique('items')->ignore($request->id)],
                    'name_ar' => 'required',
                    'category_id' => 'required',
                    'price' => 'required',
                    'cost' => 'required',
                    'taxx' => 'required',
                ]);
            }


            if ($request->img) {
                if ($request->file('img')->getSize() / 1000 < 2000) {
                    $imageName = time() . '.' . $request->img->extension();
                    $request->img->move(('images/Items'), $imageName);

                } else {
                    return redirect()->route('items')->with('error', __('main.img_big'));
                }

            } else {
                $imageName = $item->img;
            }

            try {
                $item->update([
                    'code' => $request->code,
                    'name_ar' => $request->name_ar,
                    'name_en' => $request->name_en != null ? $request->name_en : ' ',
                    'category_id' => $request->category_id,
                    'karat_id' => $request->karat_id,
                    'weight' => $request->weight,
                    'no_metal' => $request->no_metal,
                    'no_metal_type' => $request->no_metal_type,
                    'made_Value' => $request->made_Value ?? 0,
                    'item_type' => $request->item_type ?? 1,
                    'tax' => $request->tax ?? 0,
                    'state' => $request->state ?? 1,
                    'img' => $imageName
                ]);
                $materials = ItemMaterials::where('item_id', '=', $request->id)->get();
                foreach ($materials as $material) {
                    $parent = Item::find($material->parent_id);
                    if ($parent) {
                        $parent->weight -= $old_weight;
                        $parent->weight += $request->weight;
                        $parent->update();
                    }
                }

                $item2 = Item2::find($request->id);
                if ($item2) {
                    $item2->update([
                        'code' => $request->code,
                        'name_ar' => $request->name_ar,
                        'name_en' => $request->name_en != null ? $request->name_en : ' ',
                        'category_id' => $request->category_id,
                        'karat_id' => $request->karat_id,
                        'weight' => $request->weight,
                        'no_metal' => $request->no_metal,
                        'no_metal_type' => $request->no_metal_type,
                        'made_Value' => $request->made_Value ?? 0,
                        'item_type' => $request->item_type ?? 1,
                        'tax' => $request->tax ?? 0,
                        'state' => $request->state ?? 1,
                        'img' => $imageName
                    ]);


                }

                return redirect()->route('items')->with('success', __('main.updated'));
            } catch (QueryException $ex) {
                return redirect()->route('items')->with('error', $ex->getMessage());
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Item $item
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Item::find($id);
        if ($item) {
            $sales = SaleDetails::where('product_id', '=', $id)->get();
            $purchase = PurchaseDetails::where('product_id', '=', $id)->get();
            $exit = ExitWorkDetails::where('item_id', '=', $id)->get();

            //   return  count($sales) + count($purchase) + count($exit) ;
            if (count($sales) == 0 && count($purchase) == 0 && count($exit) == 0) {
                $materials = ItemMaterials::where('item_id', '=', $id)->get();
                if (count($materials) == 0) {
                    $warehouseProducts = WarehouseProducts::where('product_id', '=', $id)->get();
                    foreach ($warehouseProducts as $wpro) {
                        $wpro->delete();
                    }
                    $item->delete();
                    return redirect()->route('items')->with('success', __('main.deleted'));
                } else {
                    return redirect()->route('items')->with('success', __('main.can_not_delete_item2'));
                }

            } else {
                return redirect()->route('items')->with('success', __('main.can_not_delete_item'));
            }

        }
    }


    public function getItemPro($code)
    {
        $single = $this->getSingleItem($code);

        if ($single) {

            echo response()->json([$single]);
            exit;

        } else {
            $product = Item::where('item_type', '=', 2)
                ->where('code', 'like', '%' . $code . '%')
                ->orWhere('name_ar', 'like', '%' . $code . '%')
                ->limit(5)
                ->get();


            echo json_encode($product);
            exit;
        }

    }

    private function getSingleItem($code)
    {
        return Item::where('item_type', '=', 2)
            ->where('code', '=', $code)
            ->orWhere('name_ar', '=', $code)
            ->get()->first();
    }

    public function getProduct($code)
    {
        $price = Pricing::all()->first();
        $single = $this->getSingleProduct($code);

        if ($single) {
            $single->price = $price->price_21 * $single->karat->transform_factor;
            $materials = ItemMaterials::where('item_id', '=', $single->id)->get();
            if (count($materials) == 0) {
                echo json_encode([$single]);
                exit;
            } else {
                $single = null;
                echo json_encode([$single]);
                exit;
            }


        } else {
            $product = Item::with('karat')
                ->where('code', 'like', '%' . $code . '%')
                ->orWhere('name_ar', 'like', '%' . $code . '%')
                ->orWhere('name_en', 'like', '%' . $code . '%')
                ->limit(5)
                ->get();

            $data = [];

            foreach ($product as $item) {
                if ($item->karat_id > 0) {
                    $item->price = $price->price_21 * ($item->karat->transform_factor);
                }
                $materials = ItemMaterials::where('item_id', '=', $item->id)->get();
                if (count($materials) == 0) {
                    array_push($data, $item);
                }
            }


            echo   json_encode($data) ;
            exit;
        }

    }

    private function getSingleProduct($code)
    {
        return Item::with('karat')
            ->where('code', '=', $code)
            ->orWhere('name_ar', '=', $code)
            ->orWhere('name_en', '=', $code)
            ->get()->first();
    }

    public function getParentItem($id)
    {
        $item = Item::with('karat', 'category')->find($id);
        $allItems = Item::where('item_type', '=', 1)
            ->where('karat_id', '=', $item->karat_id)->get();
        $items = [];
        foreach ($allItems as $dd) {
            $materisal = ItemMaterials::where('item_id', '=', $dd->id)->get();
            if (count($materisal) == 0) {
                array_push($items, $dd);
            }
        }

        $data = DB::table('items')
            ->join('item_materials', 'item_materials.item_id', '=', 'items.id')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->leftJoin('karats', 'karats.id', '=', 'items.karat_id')
            ->select('items.*', 'categories.name_ar as category_name_ar', 'categories.name_en as category_name_en',
                'karats.name_ar as karat_name_ar', 'karats.name_en as karat_name_en')
            ->where('item_materials.parent_id', '=', $id)
            ->orderByDesc('id')->get();

        $html = view('Item.compineItem', compact('data', 'items', 'item'))->render();
        return $html;

    }

    public function compineItem(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'required',
            'item_id' => 'required',
        ]);

        ItemMaterials::create([
            'parent_id' => $request->parent_id,
            'item_id' => $request->item_id
        ]);
        $child =  Item::find($request->item_id);
        $parent = Item::find($request->parent_id);


        if($parent -> weight > 0){
            $oldWeight = $parent->weight ;
            $oldMade = $parent->made_Value;
            $totalMade = $oldWeight * $oldMade ;



            $parent->weight += $child->weight;

            $newMade = $totalMade + ($child->weight * $child->made_Value);

            $madeVal = $newMade / $parent->weight ;
          //  return $madeVal ;
            $parent->made_Value = $madeVal;
        } else {
            $parent->weight += $child->weight;
            $parent->made_Value += $child->made_Value;
        }




        $parent->update();

        return redirect()->route('items')->with('success', __('main.created'));;
    }

    public function print_barcode()
    {

        return view('Item.print_barcode');
    }

    public function do_print_barcode(Request $request)
    {

        $data = [];
        foreach ($request->product_id as $index => $id) {
            $product = Item::with('karat')->find($id);
            $qnt = $request->qnt[$index];
            $item = [
                'quantity' => $qnt,
                'weight' => $request->weight == 1 ? $product->weight : false,
                'karat' => $request->karat == 1 ? $product->karat->label : false,
                'barcode' => $product->code,
                'name_ar' => $product->name_ar,
                'name_en' => $product->name_en

            ];

            $data[] = $item;
        }

        return view('Item.print_barcode', compact('data'));
    }

    public function print_qrcode()
    {

        return view('Item.print_qr');
    }


    public function do_print_qr(Request $request)
    {

        $data = [];
        foreach ($request->product_id as $index => $id) {
            $product = Item::with('karat')->find($id);
            $qnt = $request->qnt[$index];

            $text = $request->weight == 1 ? $product->weight . "\n" : '';
            $text .= $request->karat == 1 ? $product->karat . "\n" : '';
            $text .= $product->code;

            $item = [
                'quantity' => $qnt,
                'data' => $text,
                'name_ar' => $product->name_ar,
                'name_en' => $product->name_en,
            ];

            $data[] = $item;
        }

        return view('Item.print_qr', compact('data'));
    }

    public function printBarcode($id)
    {
        $item = Item::with('karat')->find($id);
        $company = CompanyInfo::all() -> first();
        $html = view('Item.barcode', compact('item' , 'company'));

        return $html;
    }

    public function getItemCode()
    {
        $items = Item::orderBy('id', 'ASC')->get();
        if (count($items) > 0) {
            $id = (int)$items[count($items) - 1]->code;
        } else
            $id = 0;

        $no = json_encode(str_pad($id + 1, 6, '0', STR_PAD_LEFT));
        echo $no;
        exit;
    }

    public function deleteItemMaterial($id)
    {
        $material = ItemMaterials::where('item_id', '=', $id)->get()->first();
        $item = Item::find($material->parent_id);
        if ($item->state == 1) {
            $child = Item::find($id);
            $item->weight -= $child->weight;
            $item->made_Value -= $child->made_Value;
            $item->update();
            $material->delete();
        }

        return redirect()->route('items')->with('success', __('main.deleted'));
    }


    public function fixItems()
    {
        $parents = Item::where('item_type', '=', 3)->get();
        $parents_toFix = [];

        foreach ($parents as $parent) {
            $childs = DB::table('items')
                ->join('item_materials', 'item_materials.item_id', '=', 'items.id')
                ->select('items.*')
                ->where('items.item_type', '=', 1)
                ->where('item_materials.parent_id', '=', $parent -> id)
                ->get();
            $parent -> childs =  $childs ;

        }


        foreach ($parents as $p){

            $mt = 0 ;
            $wt = 0 ;
            foreach ($p -> childs as $child) {
                $mt += ( $child -> made_Value  * $child -> weight  );
                $wt = $p -> weight ;
            }

               // array_push($parents_toFix , $p);
                $par = Item::find($p -> id);
                $par -> made_Value = ($mt / $wt) ;
                $par -> update();



        }

        //$parents = Item::where('item_type', '=', 3)->get();
        return $parents;



    }
}
