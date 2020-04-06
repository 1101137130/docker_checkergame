<?php

namespace App\CheckersClass;

use App\Order;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\CheckersClass\convertStatus;
use Illuminate\Support\Facades\Redis;

class updateOrder
{
    private static $_instance  = null ;
   
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    public function update($item, $status)
    {
        $user = Auth::user();
        
        if ($status == 'win') {
            $usert = $user->username . (string) $user->id;
            $redisUser = Redis::get($usert);
            
            if ($redisUser == null) {
                Redis::set($usert, $item[2] * $item[3]); //item[2]=賠率 item[3]=金額
            } else {
                Redis::set($usert, $redisUser + $item[2] * $item[3]);
            }
        }

        $convertstatus = convertStatus::getInstance();
        $status = $convertstatus->convertWinLostStatus($status);

        $order = Order::getInstance();
        try {
            $order
                ->where('item_id', $item[1])
                ->where('user_id', $user->id)
                ->where('bet_object', $item[4])
                ->orderBy('created_at', 'desc')
                ->first()
                ->update(['status' => $status]);

            return array(true, '');
        } catch (Exception $e) {
            $array = array(false, $e);

            return $array;
        }
    }
}
