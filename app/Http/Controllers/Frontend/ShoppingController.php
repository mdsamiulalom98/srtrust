<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\ProductVariable;
use App\Models\Product;

class ShoppingController extends Controller
{
    public function addTocartGet($id, Request $request)
    {
        $otherInstanceItems = Cart::instance('wishlist')->content();
        if ($otherInstanceItems->contains('id', $id)) {
            $rowId = $otherInstanceItems->firstWhere('id', $id)->rowId;
            Cart::instance('wishlist')->remove($rowId);
        }
        $qty = 1;
        $productInfo = DB::table('products')->where('id', $id)->first();
        $productImage = DB::table('productimages')->where('product_id', $id)->first();
        $cartinfo = Cart::instance('shopping')->add([
            'id' => $productInfo->id,
            'name' => $productInfo->name,
            'qty' => $qty,
            'price' => $productInfo->new_price,
            'weight' => 1,
            'options' => [
                'image' => $productImage->image,
                'old_price' => $productInfo->old_price,
                'slug' => $productInfo->slug,
                'purchase_price' => $productInfo->purchase_price,
                'category_id' => $productInfo->category_id,
            ]
        ]);
        $updatedHtml = view('frontEnd.layouts.partials.product_buttons', ['value' => $productInfo])->render();

        return response()->json([
            'success' => true,
            'cartinfo' => $cartinfo,
            'updatedHtml' => $updatedHtml,
        ]);
    }

    public function cart_store(Request $request)
    {
        $product = Product::select('id', 'name', 'slug', 'new_price', 'old_price', 'purchase_price', 'type', 'stock', 'category_id')->where(['id' => $request->id])->first();
        $var_product = ProductVariable::where(['product_id' => $request->id, 'color' => $request->product_color, 'size' => $request->product_size])->first();
        if ($product->type == 0) {
            $purchase_price = $var_product ? $var_product->purchase_price : 0;
            $old_price = $var_product ? $var_product->old_price : 0;
            $new_price = $var_product ? $var_product->new_price : 0;
            $stock = $var_product ? $var_product->stock : 0;
        } else {
            $purchase_price = $product->purchase_price;
            $old_price = $product->old_price;
            $new_price = $product->new_price;
            $stock = $product->stock;
        }
        $cartitem = Cart::instance('shopping')->content()->where('id', $product->id)->first();
        if ($cartitem) {
            $cart_qty = $cartitem->qty + $request->qty ?? 1;
        } else {
            $cart_qty = $request->qty ?? 1;
        }
        if ($stock < $cart_qty) {
            Toastr::error('Product stock limit over', 'Failed!');
            return back();
        }

        Cart::instance('shopping')->add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => $request->qty ?? 1,
            'price' => $new_price,
            'weight' => 1,
            'options' => [
                'slug' => $product->slug,
                'image' => $product->image->image,
                'old_price' => $new_price,
                'purchase_price' => $purchase_price,
                'product_size' => $request->product_size,
                'product_color' => $request->product_color,
                'type' => $product->type,
                'category_id' => $product->category_id,
                'free_shipping' =>  0
            ],
        ]);



        Toastr::success('Product successfully add to cart', 'Success!');
        if ($request->add_cart) {
            return back();
        }
        return redirect()->route('customer.checkout');
    }
    public function campaign_stock(Request $request)
    {
        $product = ProductVariable::where(['product_id' => $request->id, 'color' => $request->color, 'size' => $request->size])->first();

        $status = $product ? true : false;
        $response = [
            'status' => $status,
            'product' => $product
        ];
        return response()->json($response);
    }
    public function cart_content(Request $request)
    {
        $data = Cart::instance('shopping')->content();
        return view('frontEnd.layouts.ajax.cart', compact('data'));
    }
    public function cart_remove(Request $request)
    {
        $remove = Cart::instance('shopping')->update($request->id, 0);
        $data = Cart::instance('shopping')->content();
        return view('frontEnd.layouts.ajax.cart', compact('data'));
    }
    public function cart_increment(Request $request)
    {
        $item = Cart::instance('shopping')->get($request->id);
        $qty = $item->qty + 1;
        $increment = Cart::instance('shopping')->update($request->id, $qty);
        $data = Cart::instance('shopping')->content();
        return view('frontEnd.layouts.ajax.cart', compact('data'));
    }
    public function cart_decrement(Request $request)
    {
        $item = Cart::instance('shopping')->get($request->id);
        $qty = $item->qty - 1;
        $decrement = Cart::instance('shopping')->update($request->id, $qty);
        $data = Cart::instance('shopping')->content();
        return view('frontEnd.layouts.ajax.cart', compact('data'));
    }
    public function cart_count(Request $request)
    {
        $data = Cart::instance('shopping')->count();
        return view('frontEnd.layouts.ajax.cart_count', compact('data'));
    }
    public function mobilecart_qty(Request $request)
    {
        $data = Cart::instance('shopping')->count();
        return view('frontEnd.layouts.ajax.mobilecart_qty', compact('data'));
    }

    public function cart_increment_camp(Request $request)
    {
        $item = Cart::instance('shopping')->get($request->id);
        $qty = $item->qty + 1;
        $increment = Cart::instance('shopping')->update($request->id, $qty);
        $data = Cart::instance('shopping')->content();
        return view('frontEnd.layouts.ajax.cart_camp', compact('data'));
    }
    public function cart_decrement_camp(Request $request)
    {
        $item = Cart::instance('shopping')->get($request->id);
        $qty = $item->qty - 1;
        $decrement = Cart::instance('shopping')->update($request->id, $qty);
        $data = Cart::instance('shopping')->content();
        return view('frontEnd.layouts.ajax.cart_camp', compact('data'));
    }
    public function cart_content_camp(Request $request)
    {
        $data = Cart::instance('shopping')->content();
        return view('frontEnd.layouts.ajax.cart_camp', compact('data'));
    }
    public function updateCart(Request $request)
    {
        $productId = $request->input('product_id');
        $productInfo = DB::table('products')->where('id', $productId)->first();
        $action = $request->input('action');
        $cart = Cart::instance('shopping');
        $item = $cart->content()->where('id', $productId)->first();

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item not found in cart.']);
        }

        if ($action === 'decrease' && $item->qty === 1) {
            $cart->remove($item->rowId);
            $newQuantity = 0;
        } else {
            $newQuantity = $action === 'increase' ? $item->qty + 1 : $item->qty - 1;
            $cart->update($item->rowId, $newQuantity);
        }

        $updatedHtml = view('frontEnd.layouts.partials.product_buttons', ['value' => $productInfo])->render();
        return response()->json(['success' => true, 'updatedHtml' => $updatedHtml]);
    }

    // wishlist script
    public function wishlist_store(Request $request)
    {
        $product = Product::where(['id' => $request->id])->select('id', 'name', 'name_bn', 'new_price', 'purchase_price')->first();
        Cart::instance('wishlist')->add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => 1,
            'price' => $product->new_price,
            'weight' => 1,
            'options' => ['image' => $product->image->image, 'purchase_price' => $product->purchase_price, 'name_bn' => $product->name_bn,]
        ]);
        $data['data'] = $product;

        return response()->json(['success' => true, 'data' => $data]);
    }
    public function wishlist_show()
    {
        $wishlistItems = Cart::instance('wishlist')->content();

        if ($wishlistItems->isNotEmpty()) {
            $productIds = $wishlistItems->pluck('id')->all();

            $products = Product::whereIn('id', $productIds)
                ->select('id', 'name', 'stock', 'slug', 'new_price', 'old_price', 'type')
                ->with(['variable'])
                ->get();

            $wishlistDetails = $wishlistItems->map(function ($item) use ($products) {
                $product = $products->firstWhere('id', $item->id);
                return [
                    'wishlist_item' => $item,
                    'product' => $product,
                ];
            });
        } else {
            $wishlistDetails = [];
            $products = [];
        }
        return view('frontEnd.layouts.pages.wishlist', compact('products'));
    }
    public function wishlist_remove(Request $request)
    {
        $remove = Cart::instance('wishlist')->update($request->id, 0);
        $data   = Cart::instance('wishlist')->content();
        return view('frontEnd.layouts.ajax.wishlist', compact('data'));
    }
    public function wishlist_count(Request $request)
    {
        $data   = Cart::instance('wishlist')->count();
        return view('frontEnd.layouts.ajax.wishlist_count', compact('data'));
    }
}
