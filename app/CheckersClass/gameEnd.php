<?php

namespace App\CheckersClass;

use App\CheckersClass\updateOrder;
use App\CheckersClass\resultCompare;

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
    public function end($item, $gameResult, $orderid)
    {
        $resultCompare = resultCompare::getInstance();
        $result = $resultCompare->compare($item, $gameResult);
        $this->alterData($result, $item, $orderid);
        array_push($item, $result);
        
        return $item;
    }

    public function alterData($result, $item, $orderid)
    {
        $updatorder = updateOrder::getInstance();

        if ($result) {
            $array = $updatorder->update($orderid, $item, 'win'); //更改訂單狀態 為贏
            if ($array[0] == false) {
                echo $array[1];
            }
        } else {
            $array = $updatorder->update($orderid, $item, 'lost'); //更改訂單狀態 為輸
            if ($array[0] == false) {
                echo $array[1];
            }
        }
    }
}
