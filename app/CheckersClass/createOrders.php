<?php

namespace App\CheckersClass;

use App\Order;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\CheckersClass\checkUpdateUserAmount;
use App\CheckersClass\checkRateTheSame;
use Illuminate\Support\Facades\Redis;

class createOrders
{
    private static $_instance  = null ;
   
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    public function new($item) //order處理
    {
        $checkandUpadate = checkUpdateUserAmount::getInstance();
        //item[0]-> itemname; item[1]->itemid
        //item[2]-> rate ; item[3] -> amount
        //item[4]-> ocject 1:莊家 2:閒家

        $user = Auth::user();
        $data = $checkandUpadate->check($user, $item[3]);
        $convertStatus = convertStatus::getInstance();
        $newOrdersStatus=$convertStatus->convertOrdersStatus('new');
        if ($data[0] == true) {
            $checkRateTheSame = checkRateTheSame::getInstance();
            $checkrate = $checkRateTheSame->check($item[1], $item[2]);

            if ($checkrate == false) {
                $error = '賠率已變動請重新下單！';
                $data = array(false, $error);

                return $data;
            }
            try {
                $order = Order::create([
                    'username' => $user->username,
                    'user_id' => $user->id,
                    'item_id' => $item[1],
                    'amount' => $item[3],
                    'bet_object' => $item[4],
                    'status' => $newOrdersStatus, 
                    'item_rate' => $item[2]
                ]);
                Redis::set('isOrderUsersSetyet', false);
                $data = array(true, $order->id);
                return $data;
            } catch (Exception $e) {
                $error = array(false, $e);
                $checkandUpadate->undo($user, $item[3]);
                return $error;
            }
        } else {
            return $data;
            ;
        }
    }
}
