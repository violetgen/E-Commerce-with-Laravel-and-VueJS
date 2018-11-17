<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Coupon;
use Gloudemans\Shoppingcart\Facades\Cart;

class CouponsController extends Controller
{
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return 'adding coupon';
        $coupon = Coupon::where('code', $request->coupon_code)->first();

        //note, we cant use return  here, since it not an axios call
        // dd($coupon);
        if(!$coupon){
            return redirect()->route('checkout.index')->withErrors('invalid coupon code. Please try again.');
        }

        //if it finds a coupon, lets put the value in a session where the key is called "coupon" and the value is an array
        session()->put('coupon', [
            'name' => $coupon->code,
            'discount'=> $coupon->discount(Cart::subtotal()),
        ]);

        return redirect()->route('checkout.index')->with('success_message', 'coupon has been applied');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //lets remove the coupon key from the session
        session()->forget('coupon');

        return redirect()->route('checkout.index')->with('success_message', 'Coupn has been removed');
    }
}
