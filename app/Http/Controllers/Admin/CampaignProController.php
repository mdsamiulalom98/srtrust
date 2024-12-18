<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Brian2694\Toastr\Facades\Toastr;
use Intervention\Image\Facades\Image;
use App\Models\Procampaign;
use App\Models\Product;

class CampaignProController extends Controller
{
    public function index(Request $request)
    {
        $show_data = Procampaign::orderBy('id', 'DESC')->get();
        return view('backEnd.procampaign.index', compact('show_data'));
    }
    public function create()
    {
        $products = Product::where(['status' => 1])->select('id', 'name', 'status')->get();
        return view('backEnd.procampaign.create', compact('products'));
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'date' => 'required',
            'name' => 'required',
            'status' => 'required',
        ]);

        $input = $request->except('product_ids');
        // image with intervention
        $image = $request->file('image');
        if ($image) {
            $name =  time() . '-' . $image->getClientOriginalName();
            $name = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $name);
            $name = strtolower(preg_replace('/\s+/', '-', $name));
            $uploadpath = 'public/uploads/category/';
            $imageUrl = $uploadpath . $name;
            $img = Image::make($image->getRealPath());
            $img->encode('webp', 90);
            $width = "";
            $height = "";
            $img->height() > $img->width() ? $width = null : $height = null;
            $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($imageUrl);
        } else {
            $imageUrl = null;
        }

        $input['image'] = $imageUrl;
        $input['slug'] = strtolower(Str::slug($request->name));
        $input['front_view'] = $request->front_view ? 1 : 0;
        $input['status'] = $request->status ? 1 : 0;
        $campaign = Procampaign::create($input);

        if (count($request->product_ids) === 1 && $request->product_ids[0] == 0) {
            $products = Product::all();
            foreach ($products as $product) {
                $product->campaign_id = $campaign->id;
                $product->save();
            }
        } else {
            foreach ($request->product_ids as $product_id) {
                $product = Product::find($product_id);
                if ($product) {
                    $product->campaign_id = $campaign->id;
                    $product->save();
                }
            }
        }

        Toastr::success('Success', 'Data insert successfully');
        return redirect()->route('proCampaign.index');
    }

    public function edit($id)
    {
        $edit_data = Procampaign::find($id);
        $select_products = Product::where('campaign_id', $id)->get();
        $products = Product::where(['status' => 1])->select('id', 'name', 'status')->get();
        return view('backEnd.procampaign.edit', compact('edit_data', 'products', 'select_products'));
    }

    public function update(Request $request)
    {
        // return $request->all();
        $this->validate($request, [
            'date' => 'required',
            'name' => 'required',
        ]);
        $update_data = Procampaign::find($request->hidden_id);
        $image = $request->file('image');
        if ($image) {
            // image with intervention
            $name =  time() . '-' . $image->getClientOriginalName();
            $name = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $name);
            $name = strtolower(preg_replace('/\s+/', '-', $name));
            $uploadpath = 'public/uploads/category/';
            $imageUrl = $uploadpath . $name;
            $img = Image::make($image->getRealPath());
            $img->encode('webp', 90);
            $width = "";
            $height = "";
            $img->height() > $img->width() ? $width = null : $height = null;
            $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
            File::delete($update_data->image);
            $img->save($imageUrl);
            $input['image'] = $imageUrl;
        } else {
            $input['image'] = $update_data->image;
        }

        $input = $request->except('hidden_id', 'product_ids');
        $input['slug'] = strtolower(Str::slug($request->name));
        $input['front_view'] = $request->front_view ? 1 : 0;
        $input['status'] = $request->status ? 1 : 0;
        $update_data->update($input);
        // return $update_data;

        foreach ($request->product_ids as $product_id) {
            $product = Product::find($product_id);
            $product->campaign_id = $update_data->id;
            $product->save();
        }

        Toastr::success('Success', 'Data update successfully');
        return redirect()->route('proCampaign.index');
    }

    public function inactive(Request $request)
    {
        $inactive = Procampaign::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success', 'Data inactive successfully');
        return redirect()->back();
    }
    public function active(Request $request)
    {
        $active = Procampaign::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success', 'Data active successfully');
        return redirect()->back();
    }
    public function destroy(Request $request)
    {

        $delete_data = Procampaign::find($request->hidden_id);
        $delete_data->delete();

        $campaign = Product::whereNotNull('campaign_id')->get();
        foreach ($campaign as $key => $value) {
            $product = Product::find($value->id);
            $product->campaign_id = null;
            $product->save();
        }
        Toastr::success('Success', 'Data delete successfully');
        return redirect()->back();
    }
}
