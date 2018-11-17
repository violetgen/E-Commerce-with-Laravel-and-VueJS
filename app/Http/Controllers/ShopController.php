<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pagination = 9;
        $categories = Category::all();
    //we want to filter all products based on the slug of the category passed in, if no slug is passed in, just display all the products
        //lets check the query string:
        //so when we click on laptop category, we see all the products in that category, etc
        //using collection:
    // if(request()->category){
    //     $products = Product::with('categories')->whereHas('categories', function($query){
    //         $query->where('slug', request()->category);
    //     })->get();
        //using query  builder:
    if(request()->category){
        $products = Product::with('categories')->whereHas('categories', function($query){
            $query->where('slug', request()->category);
        });
        //should incase we dont get the query, we would return null instead of breaking the page using the "optional" helper
        $categoryName = optional($categories->where('slug', request()->category)->first())->name;
    }
    else{
        //Here no query string is given, show all the products
        // $products = Product::inRandomOrder()->take(12);
        // $products = Product::take(12); //instance of query builder
        // $products = Product::all(); //instance of collection
        $products = Product::where('featured', true);
        $categoryName = "Featured";
    }

    //if the query string contains "sort" based on the below condition:
        if(request()->sort == 'low_high'){
            // $products = $products->sortBy('price'); //sortBy is for collection, orderBy is for query builder
            $products = $products->orderBy('price')->paginate($pagination);
        }else if(request()->sort == 'high_low'){
            // $products = $products->sortByDesc('price');
            $products = $products->orderBy('price', 'desc')->paginate($pagination);

        }else{
            $products = $products->paginate($pagination);  //pagination only works on the query builder
        }

        return view('shop')->with([
            'products' => $products,
            'categories' => $categories,
            'categoryName' => $categoryName,
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {


        $product = Product::where('slug', $slug)->firstOrFail();
        $mightAlsoLike = Product::where('slug', '!=', $slug)->mightAlsoLike()->get();

        return view('product')->with([
            'product' => $product,
            'mightAlsoLike' => $mightAlsoLike,
        ]);
    }
}
