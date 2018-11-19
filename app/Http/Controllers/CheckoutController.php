<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cartalyst\Stripe\Stripe;
use Gloudemans\Shoppingcart\Facades\Cart;
use Cartalyst\Stripe\Exception\CardErrorException;
use App\Http\Requests\CheckoutRequest;
use App\Order;
use App\OrderProduct;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user() && request()->is('guestCheckout')) {
            return redirect()->route('checkout.index');
        }
        return view('checkout')->with([
            'discount' => $this->getNumbers()->get('discount'),
            'newSubtotal' => $this->getNumbers()->get('newSubtotal'),
            'newTax' => $this->getNumbers()->get('newTax'),
            'newTotal' => $this->getNumbers()->get('newTotal'),
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
                'amount' => $this->getNumbers()->get('newTotal'),
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

            $this->addToOrdersTable($request, null); //this null passed in means there are no errors
            //when payment is successful, destroy the cart content, so that that item will be removed from the cart
            Cart::instance('default')->destroy();
            session()->forget('coupon'); //also remove coupon

            return redirect()->route('confirmation.index')->with('success_message', 'payment successful');
        } catch (CardErrorException $e) {
            $this->addToOrdersTable($request, $e->getMessage());
            return back()->withErrors('Error! ' . $e->getMessage());
        }
    }

    protected function addToOrdersTable($request, $error)
    {
        $order = Order::create([
            'user_id' => auth()->user() ? auth()->user()->id : null,
            'billing_email' => $request->email,
            'billing_name' => $request->name,
            'billing_address' => $request->address,
            'billing_city' => $request->city,
            'billing_province' => $request->province,
            'billing_postalcode' => $request->postalcode,
            'billing_phone' => $request->phone,
            'billing_name_on_card' => $request->name_on_card,
            'billing_discount' => $this->getNumbers()->get('discount'),
            'billing_discount_code' => $this->getNumbers()->get('code'),
            'billing_subtotal' => $this->getNumbers()->get('newSubtotal'),
            'billing_tax' => $this->getNumbers()->get('newTax'),
            'billing_total' => $this->getNumbers()->get('newTotal'),
            'error' => $error,
        ]);

        foreach (Cart::content() as $item) {
            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $item->model->id,
                'quantity' => $item->qty,
            ]);
        }
    }

    public function getNumbers()
    {
        $tax = config('cart.tax') / 100;
        $discount = session()->get('coupon')['discount'] ?? 0;
        $code = session()->get('coupon')['name'] ?? null;
        $newSubtotal = (Cart::subtotal() - $discount);
        $newTax = $newSubtotal * $tax;
        // $newTotal = $newSubtotal + $newTax;
        // or
        $newTotal = $newSubtotal * (1 + $tax);

        //we used collect so that when retrieving we can use: $this->getNumber()->get('discount) instead of using the array syntax:  $this->getNumber()['discount']
        return collect([
            'tax' => $tax,
            'discount' => $discount,
            'code' => $code,
            'newSubtotal' => $newSubtotal,
            'newTax' => $newTax,
            'newTotal' => $newTotal,
        ]);
    }
}
