<?php
namespace App\CheckersClass;

use App\Item;
use Illuminate\Support\Facades\Redis;

class setItemname
{
    private static $_instance  = null ;
   
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    public function setItemname()
    {
        $items = Item::all();
        Redis::set('Item', $items);
        foreach ($items as $item) {
            //$total = 0;
            $itemname = $item->itemname;
            Redis::set($itemname, $item);
            Redis::set('isItemSetyet', true);
            //Redis::set('itemtotal', $total);
        }
    }
}
