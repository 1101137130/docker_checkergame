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
    public function end($request, $gameResult)
    {
        $i = 0;
        foreach ($request as $item) {
            $resultCompare = resultCompare::getInstance();
            $result = $resultCompare->compare($item, $gameResult);
            $this->alterData($result, $item);
            array_push($request[$i], $result);
            $i++;
        }
        
        return $request;
    }

    public function alterData($result, $item)
    {
        $updatorder = updateOrder::getInstance();

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
