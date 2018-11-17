<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    //find the code imputed
    public static function findByCode($code){
        return self::where('code', $code)->first();
    }

    //put in the total price to discount from
     public function discount($total)
    {
        if($this->type == 'fixed'){
            return $this->value;
        }elseif($this->type == 'percent'){
            return round(($this->percent_off / 100) * $total);
        }
         
        else{
            return 0;
        }
    }
}
