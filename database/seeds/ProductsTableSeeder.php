<?php

use App\Product;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 30; $i++) {
            Product::create([
                'name' => 'Laptop' . $i,
                'slug' => 'laptop-' . $i,
                'details' => [13,14,15][array_rand([13,14,15])] . ' inch, ' . [1,2,3][array_rand([1,2,3])] . 'TB SSD, 32GB RAM',
                'price' => rand(149999, 249999),
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum temporibus iusto ipsa, asperiores voluptas unde aspernatur praesentium in? Aliquam, dolore!',
            ])->categories()->attach(1); //that one is Laptop
        }

        //now i want a laptop that have two categories,
        //lets create the second category
        $product = Product::find(1); //this is a laptop
        $product->categories()->attach(2); //attach desktop category to it

        for ($i = 1; $i <= 9; $i++) {
            Product::create([
                'name' => 'Desktop' . $i,
                'slug' => 'desktop-' . $i,
                'details' => [13,14,15][array_rand([13,14,15])] . ' inch, ' . [1,2,3][array_rand([1,2,3])] . 'TB SSD, 32GB RAM',
                'price' => rand(149999, 249999),
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum temporibus iusto ipsa, asperiores voluptas unde aspernatur praesentium in? Aliquam, dolore!',
            ])->categories()->attach(2);
        }

        for ($i = 1; $i <= 9; $i++) {
            Product::create([
                'name' => 'Phone' . $i,
                'slug' => 'phone-' . $i,
                'details' => [13,14,15][array_rand([13,14,15])] . ' inch, ' . [1,2,3][array_rand([1,2,3])] . 'TB SSD, 32GB RAM',
                'price' => rand(149999, 249999),
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum temporibus iusto ipsa, asperiores voluptas unde aspernatur praesentium in? Aliquam, dolore!',
            ])->categories()->attach(3);
        }

        for ($i = 1; $i <= 9; $i++) {
            Product::create([
                'name' => 'Tablet ' . $i,
                'slug' => 'tablet-' . $i,
                'details' => [13,14,15][array_rand([13,14,15])] . ' inch, ' . [1,2,3][array_rand([1,2,3])] . 'TB SSD, 32GB RAM',
                'price' => rand(149999, 249999),
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum temporibus iusto ipsa, asperiores voluptas unde aspernatur praesentium in? Aliquam, dolore!',
            ])->categories()->attach(4);
        }

        for ($i = 1; $i <= 9; $i++) {
            Product::create([
                'name' => 'TV ' . $i,
                'slug' => 'tv-' . $i,
                'details' => [13,14,15][array_rand([13,14,15])] . ' inch, ' . [1,2,3][array_rand([1,2,3])] . 'TB SSD, 32GB RAM',
                'price' => rand(149999, 249999),
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum temporibus iusto ipsa, asperiores voluptas unde aspernatur praesentium in? Aliquam, dolore!',
            ])->categories()->attach(5);
        }

        for ($i = 1; $i <= 9; $i++) {
            Product::create([
                'name' => 'Camera ' . $i,
                'slug' => 'camera-' . $i,
                'details' => [13,14,15][array_rand([13,14,15])] . ' inch, ' . [1,2,3][array_rand([1,2,3])] . 'TB SSD, 32GB RAM',
                'price' => rand(149999, 249999),
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum temporibus iusto ipsa, asperiores voluptas unde aspernatur praesentium in? Aliquam, dolore!',
            ])->categories()->attach(6);
        }

        for ($i = 1; $i <= 9; $i++) {
            Product::create([
                'name' => 'Appliance ' . $i,
                'slug' => 'appliance-' . $i,
                'details' => [13,14,15][array_rand([13,14,15])] . ' inch, ' . [1,2,3][array_rand([1,2,3])] . 'TB SSD, 32GB RAM',
                'price' => rand(149999, 249999),
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum temporibus iusto ipsa, asperiores voluptas unde aspernatur praesentium in? Aliquam, dolore!',
            ])->categories()->attach(7);
        }

        
    }
}
   