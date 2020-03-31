<?php

namespace App\CheckersClass;

use App\Item;

class checkRateTheSame
{
    public static function check($id, $rate)
    {
        $itemrate=Item::find($id);
        if($itemrate->rate == $rate){

            return true;
        }else{
            
            return false;
        }
    }
}
