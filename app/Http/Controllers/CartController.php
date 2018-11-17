<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function index()
    {
        // return Cart::content();

        // $mightAlsoLike = Product::inRandomOrder()->take(4)->get();
        $mightAlsoLike = Product::mightAlsoLike()->get();

        // return $mightAlsoLike;

        return view('cart', compact('mightAlsoLike'));
    }

    public function store(Request $request)
    {
        //check for duplicates 
        $duplicates = Cart::search(function($cartItem, $rowId) use($request){
            return $cartItem->id === $request->id;
        });
        if($duplicates->isNotEmpty()){
            return redirect()->route('cart.index')->with('success_message', 'Item is already in your cart!');
        }

        //the id of the product, its name, number of quantity and the price, then associate it with the product model
        Cart::add($request->id, $request->name, 1, $request->price)
            ->associate('App\Product');

        // return $result;

        return redirect()->route('cart.index')->with('success_message', 'Item added');
    }

    public function destroy($id)
    {
        Cart::remove($id);

        return back()->with('success_message', 'Item has been removed from cart');
    }

     public function switchToSaveForLater($id){

        $item = Cart::get($id);

        Cart::remove($id);

         //check for duplicates 
         $duplicates = Cart::instance('saveForLater')->search(function($cartItem, $rowId) use($id){
            return $rowId === $id;
        });
        if($duplicates->isNotEmpty()){
            return redirect()->route('cart.index')->with('success_message', 'Item is already saved for later!');
        }

        //specify the cart instance to save it in
        Cart::instance('saveForLater')->add($item->id, $item->name, 1, $item->price)
            ->associate('App\Product');

        return redirect()->route('cart.index')->with('success_message', 'Item has been saved for later');

     }
   
}
