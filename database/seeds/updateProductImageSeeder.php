<?php

use Illuminate\Database\Seeder;

class updateProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    // public function run()
    // {
    //     //
    // }
    public function run()
    {
        $products = App\Product::all();

        foreach ($products as $product) {
            if ($product->id <= 84) {
                DB::table('products')
                    ->where('id', $product->id)
                    ->update([
                        'image' => 'products/November2018/' . $product->slug . '.jpg'
                    ]);
            }
        }
    }
}
