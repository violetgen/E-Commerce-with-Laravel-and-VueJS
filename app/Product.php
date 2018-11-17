<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // public function presentPrice()
    // {
    //     return money_format('$%i', $this->price / 100);
    // }

    //let the pice not be in cents
    public function presentPrice()
    {
        return  money_format('$%i', $this->price / 100);
    }

    //since a particular query was repeated in two controllers about Product, we put it here:

    public function scopeMightAlsoLike($query)
    {
        return $query->inRandomOrder()->take(4);
    }

    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }
}
