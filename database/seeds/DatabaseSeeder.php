<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //put the categories first
        $this->call(CategoryTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        $this->call(CouponsTableSeeder::class);
        $this->call(updateProductImageSeeder::class);
    }
}
