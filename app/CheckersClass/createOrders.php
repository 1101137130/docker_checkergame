<?php

namespace App\CheckersClass;

use App\Order;
use Exception;
use App\CheckersClass\checkUpdateUserAmount;
use App\CheckersClass\checkRateTheSame;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Auth;

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
    public function init($order)
    {
        $gamestart =  gameStart::getInstance();
        $result = $gamestart->start();
       
        if ($order['order'] != "true") { //這是判定有沒有金額下注 如果沒有就只是跑一次遊戲給前臺
            $user = Auth::user();
            $gameend = gameEnd::getInstance();
            $creategameresult =createGameResultRecord::getInstance();
            $resultID=$creategameresult->create($result);
            $resultID=$resultID->id;

            foreach ($order['order'] as $item) {
                $data = $this->process($user, $item, $resultID);
                if ($data[0]) {
                    array_push($result, $gameend->end($item, $result, $data[1]));
                } else {
                    return $data[1];
                }
            }
            $winamount = Redis::get($user->username . $user->id);
            $winamount != null ? array_push($result, $winamount) : array_push($result, 0);

            return $result;
        } else {
            return $result;
        }
    }
    public function process($user, $item, $resultID)
    {
        $checkandUpadate = checkUpdateUserAmount::getInstance();
        $convertStatus = convertStatus::getInstance();
        $checkRateTheSame = checkRateTheSame::getInstance();
        
        $check = $checkandUpadate->check($user->id, $item[3]);
        if ($check[0]) {
            $playAmountStatus = $convertStatus->convertAmountStatus('play');
            $checkrate = $checkRateTheSame->check($item[1], $item[2]);

            if ($checkrate[0]) {
                $amount = $item[3] * -1;
                $update = $checkandUpadate->update($user->id, $amount, $playAmountStatus);

                if ($update[0]) {
                    $data = $this->new($user, $item, $resultID, $convertStatus);

                    if ($data[0]) {
                        $orderID = $data[1];   //data[1]是新增成功後的orderID

                        return array(true,$orderID);
                    } else {
                        return $data;
                    }
                } else {
                    return $update;
                }
            } else {
                return $checkrate;
            }
        } else {
            return $check;
        }
    }
    public function new($user, $item, $resultID, $convertStatus) //order處理
    {
        //item[0]-> itemname
        //item[1]->itemid
        //item[2]-> rate
        //item[3] -> amount
        //item[4]-> ocject 1:莊家 2:閒家
        $userID = $user->id;
        $newOrdersStatus=$convertStatus->convertOrdersStatus('new');
        try {
            $order = Order::create([
                    'username' => $user->username,
                    'user_id' => $userID,
                    'item_id' => $item[1],
                    'amount' => $item[3],
                    'bet_object' => $item[4],
                    'status' => $newOrdersStatus,
                    'item_rate' => $item[2],
                    'result_id' => $resultID
                ]);
            Redis::set('isOrderUsersSetyet', false);
            $data = array(true, $order->id);
                    
            return $data;
        } catch (Exception $e) {
            $undoAmountStatus = $convertStatus->convertAmountStatus('error_restore');
            $checkandUpadate = checkUpdateUserAmount::getInstance();
            $checkandUpadate->update($userID, $item[3], $undoAmountStatus);
            throw $e;
            return array(false, $e);
        }
    }
}
