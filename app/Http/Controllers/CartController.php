<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Log; // Ensure this is correctly placed

class CartController extends Controller
{
    public function index()
    {
        $items = Cart::instance('cart')->content();
        return view('cart', compact('items'));
    }

    public function add_to_cart(Request $request)
    {
        Log::info('Add to cart request:', $request->all());
        Cart::instance('cart')->add(
            $request->id,
            $request->name,
            $request->quantity,  // Quantity harus di posisi ketiga
            $request->price
        )->associate('App\Models\Product');
        return redirect()->back();
    }

    public function update_cart_quantity(Request $request, $rowId)
    {
        $quantity = max(1, (int) $request->quantity); // Biar ga bisa kurang dari 1
        Cart::instance('cart')->update($rowId, $quantity);
        return redirect()->back()->with('success', 'Quantity updated!');
    }


    public function increase_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $quantity = $product->qty + 1;
        Cart::instance('cart')->update($rowId, $quantity);
        return redirect()->back();
    }

    public function decrease_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $quantity = $product->qty - 1;
        Cart::instance('cart')->update($rowId, $quantity);
        return redirect()->back();
    }
    public function remove_from_cart($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        return redirect()->back()->with('success', 'Item removed!');
    }
}
