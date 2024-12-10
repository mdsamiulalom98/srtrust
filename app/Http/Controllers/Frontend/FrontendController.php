<?php

namespace App\Http\Controllers\Frontend;

use shurjopayv2\ShurjopayLaravelPackage8\Http\Controllers\ShurjopayController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Session;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Childcategory;
use App\Models\Product;
use App\Models\District;
use App\Models\CreatePage;
use App\Models\Campaign;
use App\Models\Banner;
use App\Models\CouponCode;
use App\Models\ShippingCharge;
use App\Models\Customer;
use App\Models\OrderDetails;
use App\Models\ProductVariable;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Review;
use App\Models\Brand;
use App\Models\Blog;

class FrontendController extends Controller
{
    public function index()    {

        $sliders = Banner::where(['status' => 1, 'category_id' => 1])
            ->select('id', 'image', 'link')
            ->get();

        $hotdeal_top = Product::where(['status' => 1, 'topsale' => 1])
            ->orderBy('id', 'DESC')
            ->select('id', 'name', 'stock', 'slug', 'new_price', 'old_price', 'type', 'category_id')
            ->with('variable', 'reviews')
            ->withCount('variable')
            ->limit(12)
            ->get();
        // return $hotdeal_top;

        $allproducts = Product::where(['status' => 1])
            ->orderBy('id', 'DESC')
            ->select('id', 'name', 'stock', 'slug', 'new_price', 'old_price', 'type', 'category_id')
            ->with('variable')
            ->withCount('variable', 'reviews')
            ->paginate(12);

        $new_arrivals = Product::where(['status' => 1])
            ->orderBy('id', 'DESC')
            ->select('id', 'name', 'stock', 'slug', 'new_price', 'old_price', 'type', 'category_id')
            ->with('variable')
            ->withCount('variable', 'reviews')
            ->get();

        return view('frontEnd.layouts.pages.index', compact('sliders', 'hotdeal_top', 'allproducts', 'new_arrivals'));
    }

    public function loadproducts(Request $request)
    {
        $page = $request->page;
        $perPage = 6;

        $products = Product::where('status', 1)
            ->withCount('variable')
            ->orderBy('id', 'DESC')
            ->skip(($page) * $perPage)
            ->take($perPage)
            ->get();

        return view('frontEnd.layouts.pages.loadproducts', compact('products'))->render();
    }

    public function category($slug, Request $request)
    {
        $category = Category::where(['slug' => $slug, 'status' => 1])->first();
        $products = Product::where(['status' => 1, 'category_id' => $category->id])
            ->select('id', 'name', 'stock', 'slug', 'new_price', 'old_price', 'category_id', 'stock')
            ->withCount('variable')
            ->with('reviews', 'variable');
        $subcategories = Subcategory::where('category_id', $category->id)->get();

        // return $request->sort;
        if ($request->sort == 1) {
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($request->sort == 2) {
            $products = $products->orderBy('created_at', 'asc');
        } elseif ($request->sort == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($request->sort == 4) {
            $products = $products->orderBy('new_price', 'asc');
        } elseif ($request->sort == 5) {
            $products = $products->orderBy('name', 'asc');
        } elseif ($request->sort == 6) {
            $products = $products->orderBy('name', 'desc');
        } else {
            $products = $products->latest();
        }

        $min_price = $products->min('new_price');
        $max_price = $products->max('new_price');
        if ($request->min_price && $request->max_price) {
            $products = $products->where('new_price', '>=', $request->min_price);
            $products = $products->where('new_price', '<=', $request->max_price);
        }

        $selectedSubcategories = $request->input('subcategory', []);
        $products = $products->when($selectedSubcategories, function ($query) use ($selectedSubcategories) {
            return $query->whereHas('subcategory', function ($subQuery) use ($selectedSubcategories) {
                $subQuery->whereIn('id', $selectedSubcategories);
            });
        });

        $products = $products->paginate(24);
        return view('frontEnd.layouts.pages.category', compact('category', 'products', 'subcategories', 'min_price', 'max_price'));
    }

    public function subcategory($slug, Request $request)
    {
        $subcategory = Subcategory::where(['slug' => $slug, 'status' => 1])->first();
        $products = Product::where(['status' => 1, 'subcategory_id' => $subcategory->id])
            ->select('id', 'name', 'stock', 'slug', 'new_price', 'old_price', 'category_id', 'subcategory_id', 'stock')
            ->withCount('variable')
            ->with('reviews', 'variable');
        $childcategories = Childcategory::where('subcategory_id', $subcategory->id)->get();

        // return $request->sort;
        if ($request->sort == 1) {
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($request->sort == 2) {
            $products = $products->orderBy('created_at', 'asc');
        } elseif ($request->sort == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($request->sort == 4) {
            $products = $products->orderBy('new_price', 'asc');
        } elseif ($request->sort == 5) {
            $products = $products->orderBy('name', 'asc');
        } elseif ($request->sort == 6) {
            $products = $products->orderBy('name', 'desc');
        } else {
            $products = $products->latest();
        }

        $min_price = $products->min('new_price');
        $max_price = $products->max('new_price');
        if ($request->min_price && $request->max_price) {
            $products = $products->where('new_price', '>=', $request->min_price);
            $products = $products->where('new_price', '<=', $request->max_price);
        }

        $selectedChildcategories = $request->input('childcategory', []);
        $products = $products->when($selectedChildcategories, function ($query) use ($selectedChildcategories) {
            return $query->whereHas('childcategory', function ($subQuery) use ($selectedChildcategories) {
                $subQuery->whereIn('id', $selectedChildcategories);
            });
        });

        $products = $products->paginate(24);
        // return $products;
        $impproducts = Product::where(['status' => 1, 'topsale' => 1])
            ->with('image', 'variable')
            ->withCount('variable')
            ->limit(6)
            ->select('id', 'name', 'stock', 'slug')
            ->get();

        return view('frontEnd.layouts.pages.subcategory', compact('subcategory', 'products', 'impproducts', 'childcategories', 'max_price', 'min_price'));
    }

    public function products($slug, Request $request)
    {
        $childcategory = Childcategory::where(['slug' => $slug, 'status' => 1])->first();
        $childcategories = Childcategory::where('subcategory_id', $childcategory->subcategory_id)->get();
        $products = Product::where(['status' => 1, 'childcategory_id' => $childcategory->id])->with('category')
            ->select('id', 'name', 'stock', 'slug', 'new_price', 'old_price', 'category_id', 'subcategory_id', 'childcategory_id', 'stock')
            ->withCount('variable')
            ->with('reviews', 'variable');

        // return $request->sort;
        if ($request->sort == 1) {
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($request->sort == 2) {
            $products = $products->orderBy('created_at', 'asc');
        } elseif ($request->sort == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($request->sort == 4) {
            $products = $products->orderBy('new_price', 'asc');
        } elseif ($request->sort == 5) {
            $products = $products->orderBy('name', 'asc');
        } elseif ($request->sort == 6) {
            $products = $products->orderBy('name', 'desc');
        } else {
            $products = $products->latest();
        }

        $min_price = $products->min('new_price');
        $max_price = $products->max('new_price');
        if ($request->min_price && $request->max_price) {
            $products = $products->where('new_price', '>=', $request->min_price);
            $products = $products->where('new_price', '<=', $request->max_price);
        }

        $products = $products->paginate(24);
        // return $products;
        $impproducts = Product::where(['status' => 1, 'topsale' => 1])
            ->with('image', 'reviews', 'variable')
            ->limit(6)
            ->select('id', 'name', 'stock', 'slug')
            ->get();

        return view('frontEnd.layouts.pages.childcategory', compact('childcategory', 'products', 'impproducts', 'min_price', 'max_price', 'childcategories'));
    }


    public function brand($slug, Request $request)
    {
        $brand = Brand::where(['slug' => $slug, 'status' => 1])->first();
        $products = Product::where(['status' => 1, 'brand_id' => $brand->id])
            ->with('variable')
            ->select('id', 'name', 'stock', 'slug', 'new_price', 'old_price', 'type', 'brand_id')->withCount('variable');
        if ($request->sort == 1) {
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($request->sort == 2) {
            $products = $products->orderBy('created_at', 'asc');
        } elseif ($request->sort == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($request->sort == 4) {
            $products = $products->orderBy('new_price', 'asc');
        } elseif ($request->sort == 5) {
            $products = $products->orderBy('name', 'asc');
        } elseif ($request->sort == 6) {
            $products = $products->orderBy('name', 'desc');
        } else {
            $products = $products->latest();
        }
        $products = $products->paginate(30)->withQueryString();
        return view('frontEnd.layouts.pages.brand', compact('brand', 'products'));
    }

    public function bestdeals(Request $request)
    {

        $products = Product::where(['status' => 1, 'topsale' => 1])
            ->orderBy('id', 'DESC')
            ->select('id', 'name', 'stock', 'slug', 'new_price', 'old_price', 'type')
            ->with('variable')
            ->withCount('variable');

        if ($request->sort == 1) {
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($request->sort == 2) {
            $products = $products->orderBy('created_at', 'asc');
        } elseif ($request->sort == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($request->sort == 4) {
            $products = $products->orderBy('new_price', 'asc');
        } elseif ($request->sort == 5) {
            $products = $products->orderBy('name', 'asc');
        } elseif ($request->sort == 6) {
            $products = $products->orderBy('name', 'desc');
        } else {
            $products = $products->latest();
        }
        $products = $products->paginate(30)->withQueryString();

        return view('frontEnd.layouts.pages.bestdeals', compact('products'));
    }


    public function details($slug)
    {

        $details = Product::where(['slug' => $slug, 'status' => 1])
            ->with('image', 'images', 'category', 'subcategory', 'childcategory', 'variable')
            ->withCount('variableimages', 'variable')
            ->firstOrFail();
        // return $details;
        $products = Product::where(['category_id' => $details->category_id, 'status' => 1])
            ->with('image', 'variable')
            ->select('id', 'name', 'stock', 'slug', 'status', 'category_id', 'new_price', 'old_price', 'type')
            ->withCount('variable')
            ->get();

        $shippingcharge = ShippingCharge::where('status', 1)->get();
        $reviews = Review::where('product_id', $details->id)->get();

        $productcolors = ProductVariable::where('product_id', $details->id)->where('stock', '>', 0)
            ->whereNotNull('color')
            ->select('color')
            ->distinct()
            ->get();

        $productsizes = ProductVariable::where('product_id', $details->id)->where('stock', '>', 0)
            ->whereNotNull('size')
            ->select('size')
            ->distinct()
            ->get();

        if (Session::get('recentview') == '') {
            Session::put('recentview', []);
        }
        $newsession[] = $details->id;
        $oldsession   = array_reverse(Session::get('recentview'));
        $recentall    = array_merge($newsession, $oldsession);
        $recentall    = array_unique($recentall);
        $recentall    = array_reverse($recentall);
        if (count(Session::get('recentview')) > 9) {
            array_shift($recentall);
        }
        Session::put('recentview', $recentall);
        if (Session::get('recentview')) {
            $recent_products = Product::where(['status' => 1])
                ->whereIn('products.id', Session::get('recentview'))
                ->orderBy('id', 'DESC')
                ->select('id', 'name', 'stock', 'slug', 'new_price', 'old_price', 'type')
                ->with('variable')
                ->withCount('variable')->get();
        } else {
            $recent_products = [];
        }

        return view('frontEnd.layouts.pages.details', compact('details', 'products', 'shippingcharge', 'productcolors', 'productsizes', 'reviews', 'recent_products'));
    }
    public function recent_view(Request $request)
    {
        if (Session::get('recentview')) {
            $products = Product::where(['status' => 1])
                ->whereIn('products.id', Session::get('recentview'))
                ->orderBy('id', 'DESC')
                ->select('id', 'name', 'stock', 'slug', 'new_price', 'old_price', 'type')
                ->with('variable')
                ->withCount('variable');

            if ($request->sort == 1) {
                $products = $products->orderBy('created_at', 'desc');
            } elseif ($request->sort == 2) {
                $products = $products->orderBy('created_at', 'asc');
            } elseif ($request->sort == 3) {
                $products = $products->orderBy('new_price', 'desc');
            } elseif ($request->sort == 4) {
                $products = $products->orderBy('new_price', 'asc');
            } elseif ($request->sort == 5) {
                $products = $products->orderBy('name', 'asc');
            } elseif ($request->sort == 6) {
                $products = $products->orderBy('name', 'desc');
            } else {
                $products = $products->latest();
            }
            $products = $products->paginate(30)->withQueryString();
            return view('frontEnd.layouts.pages.recent_view', compact('products'));
        } else {
            Toastr::error('You have no item add in recently viewed');
            return back();
        }
    }
    public function stock_check(Request $request)
    {
        $product = ProductVariable::where(['product_id' => $request->id, 'color' => $request->color, 'size' => $request->size])->first();

        $status = $product ? true : false;
        $response = [
            'status' => $status,
            'product' => $product
        ];
        return response()->json($response);
    }
    public function quickview(Request $request)
    {
        $data['data'] = Product::where(['id' => $request->id, 'status' => 1])->with('images', 'variable')->withCount('reviews', 'variable')->first();
        $data = view('frontEnd.layouts.ajax.quickview', $data)->render();
        if ($data != '') {
            echo $data;
        }
    }
    public function livesearch(Request $request)
    {
        $products = Product::select('id', 'name', 'stock', 'slug', 'new_price', 'old_price', 'type')
            ->withCount('variable')
            ->where('status', 1)
            ->with('image', 'variable');
        if ($request->keyword) {
            $products = $products->where('name', 'LIKE', '%' . $request->keyword . "%");
        }
        if ($request->category) {
            $products = $products->where('category_id', $request->category);
        }
        $products = $products->get();

        if (empty($request->category) && empty($request->keyword)) {
            $products = [];
        }
        return view('frontEnd.layouts.ajax.search', compact('products'));
    }
    public function search(Request $request)
    {
        $products = Product::select('id', 'name', 'stock', 'slug', 'new_price', 'old_price', 'type')
            ->withCount('variable')
            ->where('status', 1)
            ->with('image', 'variable');
        if ($request->keyword) {
            $products = $products->where('name', 'LIKE', '%' . $request->keyword . "%");
        }
        if ($request->category) {
            $products = $products->where('category_id', $request->category);
        }
        $products = $products->paginate(36);
        $keyword = $request->keyword;
        return view('frontEnd.layouts.pages.search', compact('products', 'keyword'));
    }


    public function shipping_charge(Request $request)
    {
        if ($request->id == NULL) {
            Session::put('shipping', 0);
        } else {
            $shipping = District::where(['id' => $request->id])->first();
            $subtotal = Cart::instance('shopping')->subtotal();
            $subtotal = str_replace(',', '', $subtotal);
            $subtotal = str_replace('.00', '', $subtotal);
            if ($shipping) {
                $shipping_fee = $shipping->shippingfee;
                Session::put('shipping', $shipping_fee);
            } else {
                Session::put('shipping', 0);
            }
        }

        return view('frontEnd.layouts.ajax.cart');
    }


    public function contact(Request $request)
    {
        return view('frontEnd.layouts.pages.contact');
    }

    public function page($slug)
    {
        $page = CreatePage::where('slug', $slug)->firstOrFail();
        return view('frontEnd.layouts.pages.page', compact('page'));
    }
    public function districts(Request $request)
    {
        $areas = District::where(['district' => $request->id])->pluck('area_name', 'id');
        return response()->json($areas);
    }
    public function blogs()
    {
        $blogs = Blog::where('status', 1)->paginate(20);
        return view('frontEnd.layouts.pages.blogs', compact('blogs'));
    }
    public function blog_details($slug)
    {
        $details = Blog::where('status', 1)->where('slug', $slug)->first();
        $blogs = Blog::where('status', 1)->limit(10)->get();
        return view('frontEnd.layouts.pages.blog_details', compact('details', 'blogs'));
    }
    public function campaign($slug, Request $request)
    {

        $campaign = Campaign::where('slug', $slug)->with('images')->first();

        $product = Product::select('id', 'name', 'stock', 'slug', 'new_price', 'old_price', 'purchase_price', 'type', 'stock')->where(['id' => $campaign->product_id])->first();
        // return $product;
        $productcolors = ProductVariable::where('product_id', $campaign->product_id)->where('stock', '>', 0)
            ->whereNotNull('color')
            ->select('color')
            ->distinct()
            ->get();

        $productsizes = ProductVariable::where('product_id', $campaign->product_id)->where('stock', '>', 0)
            ->whereNotNull('size')
            ->select('size')
            ->distinct()
            ->get();

        Cart::instance('shopping')->destroy();


        $var_product = ProductVariable::where(['product_id' => $campaign->product_id])->first();
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

        $qty = 1;
        $cartitem = Cart::instance('shopping')->content()->where('id', $product->id)->first();

        Cart::instance('shopping')->add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => $qty,
            'weight' => 1,
            'price' => $new_price,
            'options' => [
                'slug' => $product->slug,
                'image' => $product->image->image,
                'old_price' => $new_price,
                'purchase_price' => $purchase_price,
                'product_size' => $request->product_size,
                'product_color' => $request->product_color,
                'type' => $product->type
            ],
        ]);
        $shippingcharge = ShippingCharge::where('status', 1)->get();
        $select_charge = ShippingCharge::where('status', 1)->first();
        Session::put('shipping', $select_charge->amount);
        $districts = District::distinct()->select('district')->orderBy('district', 'asc')->get();
        return view('frontEnd.layouts.pages.campaign.campaign', compact('districts', 'campaign', 'productsizes', 'productcolors', 'shippingcharge', 'old_price', 'new_price'));
    }
    public function campaign_stock(Request $request)
    {
        $product = Product::select('id', 'name', 'stock', 'slug', 'new_price', 'old_price', 'purchase_price', 'type', 'stock')->where(['id' => $request->id])->first();

        $variable = ProductVariable::where(['product_id' => $request->id, 'color' => $request->color, 'size' => $request->size])->first();
        $qty = 1;
        $status = $variable ? true : false;

        if ($status == true) {
            // return $variable;
            // return "wait";
            Cart::instance('shopping')->destroy();
            Cart::instance('shopping')->add([
                'id' => $product->id,
                'name' => $product->name,
                'qty' => $qty,
                'price' => $variable->new_price,
                'options' => [
                    'slug' => $product->slug,
                    'image' => $product->image->image,
                    'old_price' => $variable->new_price,
                    'purchase_price' => $variable->purchase_price,
                    'product_size' => $request->size,
                    'product_color' => $request->color,
                    'type' => $product->type
                ],
            ]);
        }
        $data = Cart::instance('shopping')->content();
        return response()->json($status);
    }

    public function payment_success(Request $request)
    {
        $order_id = $request->order_id;
        $shurjopay_service = new ShurjopayController();
        $json = $shurjopay_service->verify($order_id);
        $data = json_decode($json);

        if ($data[0]->sp_code != 1000) {
            Toastr::error('Your payment failed, try again', 'Oops!');
            if ($data[0]->value1 == 'customer_payment') {
                return redirect()->route('home');
            } else {
                return redirect()->route('home');
            }
        }

        if ($data[0]->value1 == 'customer_payment') {

            $customer = Customer::find(Auth::guard('customer')->user()->id);

            // order data save
            $order = new Order();
            $order->invoice_id = $data[0]->id;
            $order->amount = $data[0]->amount;
            $order->customer_id = Auth::guard('customer')->user()->id;
            $order->order_status = $data[0]->bank_status;
            $order->save();

            // payment data save
            $payment = new Payment();
            $payment->order_id = $order->id;
            $payment->customer_id = Auth::guard('customer')->user()->id;
            $payment->payment_method = 'shurjopay';
            $payment->amount = $order->amount;
            $payment->trx_id = $data[0]->bank_trx_id;
            $payment->sender_number = $data[0]->phone_no;
            $payment->payment_status = 'paid';
            $payment->save();
            // order details data save
            foreach (Cart::instance('shopping')->content() as $cart) {
                $order_details = new OrderDetails();
                $order_details->order_id = $order->id;
                $order_details->product_id = $cart->id;
                $order_details->product_name = $cart->name;
                $order_details->purchase_price = $cart->options->purchase_price;
                $order_details->sale_price = $cart->price;
                $order_details->qty = $cart->qty;
                $order_details->save();
            }

            Cart::instance('shopping')->destroy();
            Toastr::error('Thanks, Your payment send successfully', 'Success!');
            return redirect()->route('home');
        }

        Toastr::error('Something wrong, please try agian', 'Error!');
        return redirect()->route('home');
    }
    public function payment_cancel(Request $request)
    {
        $order_id = $request->order_id;
        $shurjopay_service = new ShurjopayController();
        $json = $shurjopay_service->verify($order_id);
        $data = json_decode($json);

        Toastr::error('Your payment cancelled', 'Cancelled!');
        if ($data[0]->sp_code != 1000) {
            if ($data[0]->value1 == 'customer_payment') {
                return redirect()->route('home');
            } else {
                return redirect()->route('home');
            }
        }
    }
}
