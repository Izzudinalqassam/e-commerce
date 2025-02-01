<?php

namespace App\Http\Controllers;

use App\Models\Brands;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
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

    public function brand_edit($id)
    {
        $brand = Brands::find($id);
        return view('admin.brand-edit', compact('brand'));
    }

    public function brand_update(Request $request, $id)
    {
        $brand = Brands::find($id);
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,' . $brand->id,
            'image' => 'mimes:jpg,jpeg,png|max:2048',
        ]);

        $brand = Brands::find($request->id);
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        if ($request->hasFile('image')) {
            if (File::exists(public_path('/uploads/brands') . '/' . $brand->image)) {
                File::delete(public_path('/uploads/brands') . '/' . $brand->image);
            }
            $image = $request->file('image');
            $file_extension = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;
            $this->GenerateBrandThumbnailIsImage($image, $file_name);
            $this->GenerateBrandThumbnailIsImage($image, $file_name);
            $brand->image = $file_name;
        }

        $brand->save();
        return redirect()->route('admin.brand')->with('status', 'Brand Update Successfully');
    }

    public function brand_delete($id)
    {
        $brand = Brands::find($id);
        if (File::exists(public_path('/uploads/brands') . '/' . $brand->image)) {
            File::delete(public_path('/uploads/brands') . '/' . $brand->image);
        }
        $brand->delete();
        return redirect()->route('admin.brand')->with('status', 'Brand Deleted Successfully');
    }

    public function brand_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'mimes:jpg,jpeg,png | max:2048',
        ]);
        $brand = new Brands();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $image = $request->file('image');
        $file_extension = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extension;
        $this->GenerateBrandThumbnailIsImage($image, $file_name);
        $brand->image = $file_name;
        $brand->save();
        return redirect()->route('admin.brand')->with('status', 'Brand Added Successfully');
    }

    public function GenerateBrandThumbnailIsImage($image, $imageName)
    {
        $destinationPath = public_path('/uploads/brands');  // upload path
        $img = Image::read($image->path());
        $img->cover(124, 124, "top");
        $img->resize(124, 124, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName);
    }

    public function categories()
    {
        $categories = Category::orderBy('id', 'desc')->paginate(10);
        return view('admin.categories', compact('categories'));
    }

    public function categories_add()
    {
        return view('admin.category-add');
    }

    public function categories_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug',
            'image' => 'mimes:jpg,jpeg,png | max:2048',
        ]);
        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $image = $request->file('image');
        $file_extension = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extension;
        $this->GenerateCategoryThumbnailIsImage($image, $file_name);
        $category->image = $file_name;
        $category->save();
        return redirect()->route('admin.categories')->with('status', 'Category Added Successfully');
    }
    public function GenerateCategoryThumbnailIsImage($image, $imageName)
    {
        $destinationPath = public_path('/uploads/categories');  // upload path
        $img = Image::read($image->path());
        $img->cover(124, 124, "top");
        $img->resize(124, 124, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName);
    }

    public function categories_edit($id)
    {
        $category = Category::find($id);
        return view('admin.category-edit', compact('category'));
    }

    public function categories_delete($id)
    {
        $category = Category::find($id);
        if (File::exists(public_path('/uploads/categories') . '/' . $category->image)) {
            File::delete(public_path('/uploads/categories') . '/' . $category->image);
        }
        $category->delete();
        return redirect()->route('admin.categories')->with('status', 'Category Deleted Successfully');
    }

    public function categories_update(Request $request, $id)
    {
        $category = Category::find($id);
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $category->id,
            'image' => 'mimes:jpg,jpeg,png|max:2048',
        ]);

        $category = Category::find($request->id);
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        if ($request->hasFile('image')) {
            if (File::exists(public_path('/uploads/categories') . '/' . $category->image)) {
                File::delete(public_path('/uploads/categories') . '/' . $category->image);
            }
            $image = $request->file('image');
            $file_extension = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;
            $this->GenerateCategoryThumbnailIsImage($image, $file_name);
            $category->image = $file_name;
        }
        $category->save();
        return redirect()->route('admin.categories')->with('status', 'Category Update Successfully');
    }

    public function products()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.products', compact('products'));
    }

    public function products_add()
    {
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brands::select('id', 'name')->orderBy('name')->get();
        return view('admin.product-add', compact('categories', 'brands'));
    }

    public function product_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug',
            'category_id' => 'required',
            'brand_id' => 'required',
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required|boolean',
            'quantity' => 'required|integer',
            'image' => 'required|mimes:png,jpg,jpeg|max:2048',
            'images.*' => 'mimes:jpg,png,jpeg|max:2048' // Validasi untuk galeri
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        $timestamp = now()->timestamp;

        // **Upload Gambar Utama**
        if ($request->hasFile('image')) {
            $file_name = $timestamp . '.' . $request->file('image')->extension();
            $path = $request->file('image')->storeAs('products', $file_name, 'public_uploads');
            $product->image = $path;
        }

        // **Upload Galeri Gambar**
        $gallery_paths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $gfilename = $timestamp . "-" . ($index + 1) . "." . $file->extension();
                $gpath = $file->storeAs('products/thumbnails', $gfilename, 'public_uploads');
                $gallery_paths[] = $gpath;
            }
        }
        $product->images = implode(',', $gallery_paths);

        $product->save();

        return redirect()->route('admin.products')->with('status', 'Product added successfully!');
    }


    // public function generateThumbnailImage($image, $file_name)
    // {
    //     $destinationPathThumbnails = public_path('storage/uploads/products/thumbails');
    //     $destinationPath = public_path('storage/uploads/products');  // upload path
    //     $img = Image::read($image->path());
    //     $img->cover(540, 689, "top");
    //     $img->resize(540, 689, function ($constraint) {
    //         $constraint->aspectRatio();
    //     })->save($destinationPath . '/' . $file_name);
    //     $img->resize(104, 104, function ($constraint) {
    //         $constraint->aspectRatio();
    //     })->save($destinationPathThumbnails . '/' . $file_name);
    // }

    public function product_edit($id)
    {
        $product = Product::find($id);
        $categories = Category::Select('id', 'name')->orderBy('name')->get();
        $brands = Brands::Select('id', 'name')->orderBy('name')->get();
        return view('admin.product-edit', compact('product', 'categories', 'brands'));
    }

    public function update_product(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return redirect()->route('admin.products')->with('error', 'Product not found!');
        }

        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug,' . $id,
            'category_id' => 'required',
            'brand_id' => 'required',
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'required',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            'quantity' => 'required',
            'image' => 'nullable|mimes:png,jpg,jpeg|max:2048', // Gambar opsional
        ]);

        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $current_timestamp = Carbon::now()->timestamp;

        // **Menghapus gambar utama jika ada dan menggantinya dengan gambar baru**
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image && Storage::disk('public_uploads')->exists($product->image)) {
                Storage::disk('public_uploads')->delete($product->image);
            }

            // Simpan gambar baru
            $file_extention = $request->file('image')->extension();
            $file_name = $current_timestamp . '.' . $file_extention;
            $path = $request->image->storeAs('products', $file_name, 'public_uploads');
            $product->image = $path;
        }

        // **Menghapus dan mengganti gallery images**
        if ($request->hasFile('images')) {
            // Hapus gambar lama jika ada
            if (!empty($product->images)) {
                $gallery_images = explode(',', $product->images); // Hapus spasi yang salah
                $gallery_images = array_map('trim', $gallery_images); // Bersihkan spasi tambahan

                // Hapus semua gambar dalam satu perintah
                Storage::disk('public_uploads')->delete($gallery_images);
            }

            // Simpan gambar baru di gallery
            $gallery_arr = [];
            $counter = 1;
            $allowedfileExtension = ['jpg', 'png', 'jpeg'];
            $files = $request->file('images');

            foreach ($files as $file) {
                $gextension = $file->getClientOriginalExtension();
                if (in_array($gextension, $allowedfileExtension)) {
                    $gfilename = $current_timestamp . "-" . $counter . "." . $gextension;
                    $gpath = $file->storeAs('products/thumbnails', $gfilename, 'public_uploads');
                    $gallery_arr[] = $gpath;
                    $counter++;
                }
            }

            $product->images = implode(',', $gallery_arr); // Perbaikan: tanpa spasi ekstra
        }

        $product->save();
        return redirect()->route('admin.products')->with('status', 'Product has been updated successfully!');
    }



    public function delete_product($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return redirect()->route('admin.products')->with('error', 'Product not found!');
        }

        // Hapus gambar utama jika ada
        if ($product->image && Storage::disk('public_uploads')->exists($product->image)) {
            Storage::disk('public_uploads')->delete($product->image);
        }

        // Hapus semua gambar galeri jika ada
        if (!empty($product->images)) {
            $gallery_images = explode(',', $product->images);
            $gallery_images = array_map('trim', $gallery_images); // Bersihkan spasi berlebih

            // Hapus semua file dalam satu perintah untuk efisiensi
            Storage::disk('public_uploads')->delete($gallery_images);
        }

        // Hapus data produk dari database
        $product->delete();

        return redirect()->route('admin.products')->with('status', 'Product has been deleted successfully!');
    }
}
