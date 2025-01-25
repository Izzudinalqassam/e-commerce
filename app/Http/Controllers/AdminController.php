<?php

namespace App\Http\Controllers;


use App\Models\Brands;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Log; // Importing Log facade

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function brands()
    {
        $brands = Brands::orderBy('id', 'desc')->paginate(10);
        return view('admin.brand', compact('brands'));
    }

    public function add_brand()
    {
        return view('admin.brand-add');
    }

    public function brand_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'mimes:jpg,jpeg,png',
        ]);
        $brand = new Brands();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $image = $request->file('image');
        $file_extension = $request->file('image')->extension();
        $file_name =Carbon::now()->timestamp . '.' . $file_extension;
        $this->GenerateBrandThumbnailIsImage($image, $file_name);
        $brand->image = $file_name;
        $brand->save();
        return redirect()->route('admin.brand')->with('status', 'Brand Added Successfully');

    }

    public function GenerateBrandThumbnailIsImage($image, $imageName)
    {
        $destinationPath = public_path('/uploads/brands');  // upload path
        $img = Image::read($image->path());
        $img->cover(124,124,"top");
        $img->resize(124,124,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);
    }
}
