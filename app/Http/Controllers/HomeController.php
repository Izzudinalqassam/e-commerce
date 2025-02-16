<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brands;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::latest()->take(8)->get();
        $categories = Category::latest()->take(6)->get();
        $brands = Brands::latest()->take(6)->get();

        return view('home', compact('products', 'categories', 'brands'));
    }
}
