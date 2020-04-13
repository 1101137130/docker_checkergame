<?php

namespace App\CheckersClass;

use App\Item;

class checkRateTheSame
{
    private static $_instance  = null ;
   
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    public static function check($id, $rate)
    {
        $itemrate=Item::find($id);
        if ($itemrate->rate == $rate) {
            return true;
        } else {
            return false;
        }
    }
}
