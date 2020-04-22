<?php
namespace App\CheckersClass;

use App\Item;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;

class redisGetSet
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
            $itemname = $item->itemname;
            Redis::set($itemname, $item);
            Redis::set('isItemSetyet', true);
        }
    }
    public function getOrderedUsername()
    {
        if (Redis::get('isOrderUsersSetyet')==true) {
            $orderUsers = Redis::get('OrderUsers');
            $data = json_decode($orderUsers, true);

            return $data ;
        } else {
            $data = DB::table('orders')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->select('orders.user_id', 'users.username')
            ->distinct('user_id')
            ->get();
            Redis::set('OrderUsers', $data);
            Redis::set('isOrderUsersSetyet', true);

            return $data;
        }
    }
}
