<?php

namespace App\CheckersClass;

use App\Order;
use Exception;
use App\CheckersClass\checkUpdateUserAmount;
use App\CheckersClass\checkRateTheSame;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Auth;

class createOrders extends Controller
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
                    return $data;
                }
            }
            $winamount = Redis::get($user->username . $user->id);
            $winamount != null ? array_push($result, $winamount) : array_push($result, 0);
            array_unshift($result,true);

            return $result;
        } else {
            array_unshift($result,true);

            return $result;
        }
    }
    //連續判斷 有金額->下單時的賠率跟當下賠率符合->確認扣款後->下單
    public function process($user, $item, $resultID)
    {
        $UserAmount = checkUpdateUserAmount::getInstance();
        $convertStatus = convertStatus::getInstance();
        $checkRateTheSame = checkRateTheSame::getInstance();
        
        $checkAmount = $UserAmount->checkAmount($user->id, $item[3]);
        if ($checkAmount[0]) {
            $playAmountStatus = $convertStatus->convertAmountStatus('play');

            $checkRate = $checkRateTheSame->check($item[1], $item[2]);

            if ($checkRate[0]) {
                $amount = $item[3] * -1;
                $checkUpdate = $UserAmount->update($user->id, $amount, $playAmountStatus);

                if ($checkUpdate[0]) {
                    $data = $this->new($user, $item, $resultID, $convertStatus);

                    if ($data[0]) {
                        $orderID = $data[1];   //data[1]是新增成功後的orderID

                        return array(true,$orderID);
                    } else {
                        return $data;
                    }
                } else {
                    return $checkUpdate;
                }
            } else {
                return $checkRate;
            }
        } else {
            return $checkAmount;
        }
    }
    //這段程式碼成功後會return 一個array  [0]代表成功於否 [1]若成功則回傳新增後的order->id
    public function new($user, $item, $resultID, $convertStatus) //order處理
    {
        //item[0]-> itemname
        //item[1]->itemid
        //item[2]-> rate
        //item[3] -> amount
        //item[4]-> ocject 1:莊家 2:閒家
        $userID = $user->id;
        $newOrdersStatus = $convertStatus->convertOrdersStatus('new');
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
            $data = array(false, $e->getMessage());

            return $data;
        }
    }
}
