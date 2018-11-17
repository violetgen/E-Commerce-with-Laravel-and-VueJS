<?php

namespace App\Http\Controllers;

class ConfirmationController extends Controller
{
    public function index()
    {
        if (!session()->has('success_message')) {
            return redirect('/');
        }

        return view('thankyou');
    }
}
