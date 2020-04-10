<?php

namespace App\CheckersClass;

use App\CheckersClass\updateOrder;
use Illuminate\Support\Facades\Auth;
use App\Order;

class gameEnd
{
    private static $_instance  = null ;
   
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    public function end($request, $result)
    {
        $i = 0;
        
        foreach ($request as $item) {
            $clientresult = $this->toClientBet($item, $result);
            array_push($request[$i], $clientresult);
            $i++;
        }

        return $request;
    }

    public function toClientBet($item, $result)
    {
        $compare = compareResults::getInstance();

        if (strlen($item[0])==9) {
            $countresult = $compare->oneByone($item, $result);

            if ($countresult == 3) {
                $this->alterData(1, $item);
                
                return 1;
            } else {
                $this->alterData(0, $item);
               
                return 0;
            }
        } else {
            $re =  $compare->total($item, $result);

            $this->alterData($re, $item);
            return $re;
        }
    }

    public function alterData($result, $item)
    {
        $updatorder = updateOrder::getInstance();
        $user = Auth::user();
        $order =  Order::getInstance();
        $order
                ->where('item_id', $item[1])
                ->where('user_id', $user->id)
                ->where('bet_object', $item[4])
                ->orderBy('created_at', 'desc')
                ->first();
        if ($result) {
            $array = $updatorder->update($item, 'win'); //更改訂單狀態 為贏
            if ($array[0] == false) {
                echo $array[1];
            }
        } else {
            $array = $updatorder->update($item, 'lost'); //更改訂單狀態 為輸
            if ($array[0] == false) {
                echo $array[1];
            }
        }
    }
}
