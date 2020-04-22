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
    public function check($id, $rate)
    {
        $itemrate=Item::find($id);

        if ($itemrate->rate == $rate) {
            return array(true,'');
        } else {
            return array(false,'賠率已變動請重新下單！');
        }
    }
}
