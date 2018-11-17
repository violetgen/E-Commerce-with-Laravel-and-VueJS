<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cartalyst\Stripe\Stripe;
use Gloudemans\Shoppingcart\Facades\Cart;
use Cartalyst\Stripe\Exception\CardErrorException;
use App\Http\Requests\CheckoutRequest;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('checkout')->with([
            'discount' => $this->getNumber()->get('discount'),
            'newSubtotal' => $this->getNumber()->get('newSubtotal'),
            'newTax' => $this->getNumber()->get('newTax'),
            'newTotal' => $this->getNumber()->get('newTotal'),
        ]);
    }

    public function store(CheckoutRequest $request)
    {
        $contents = Cart::content()->map(function ($item) {
            return $item->model->slug . ', ' . $item->qty;
        })->values()->toJson();
        try {
            $stripe = Stripe::make(config('services.stripe.secret'));

            // $charge = Stripe::charges()->create([
            $charge = $stripe->charges()->create([
                // 'amount' => Cart::total() / 100, //convert to dollars from cents. a new total is applied based on discount
                'amount' => $this->getNumber()->get('newTotal'),
                'currency' => 'CAD',
                'source' => $request->stripeToken,
                'description' => 'Order',
                'receipt_email' => $request->email,
                'metadata' => [
                    //since no table yet, lets save the cart content into metadata
                    //change to order ID after we start using DB, we will create an Orders table later
                    'contents' => $contents,
                    'quantity' => Cart::instance('default')->count(),
                    'discount' => collect(session()->get('coupon'))->toJson(),
                ],
            ]);
            //when payment is successful, destroy the cart content, so that that item will be removed from the cart
            Cart::instance('default')->destroy();
            session()->forget('coupon'); //also remove coupon

            return redirect()->route('confirmation.index')->with('success_message', 'payment successful');
        } catch (CardErrorException $e) {
            return back()->withErrors('Error! ' . $e->getMessage());
        }
    }

    public function getNumber(){

        $tax = config('cart.tax') / 100;
        $discount = session()->get('coupon')['discount'] ?? 0;
        $newSubtotal = (Cart::subtotal() - $discount);
        $newTax = $newSubtotal * $tax;
        // $newTotal = $newSubtotal + $newTax;
        // or
        $newTotal = $newSubtotal * (1 + $tax);

        //we used collect so that when retrieving we can use: $this->getNumber()->get('discount) instead of using the array syntax:  $this->getNumber()['discount']
        return collect([
            'tax' => $tax,
            'discount' => $discount,
            'newSubtotal' => $newSubtotal,
            'newTax' => $newTax,
            'newTotal' => $newTotal,
        ]);
    }
}
