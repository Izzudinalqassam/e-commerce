<?php

namespace App\Http\Controllers;

use App\Models\Brand;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function brand()
    {
        $brand = Brand::orderBy('id', 'desc')->paginate(10);

        return view('admin.brand', compact('brand'));
    }
}
