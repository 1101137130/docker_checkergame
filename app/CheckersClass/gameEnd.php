<?php

namespace App\CheckersClass;

use App\CheckersClass\updateOrder;
use App\CheckersClass\convertStatus;

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
        if ($item[0] == '贏') {
            if ($item[4] == $result[3]) {
                $this->alterData(1, $item);

                return 1;
            } else {
                $this->alterData(0, $item);

                return 0;
            }
        }
        if ($item[0] == '輸') {
            if ($item[4] == $result[3]) {
                $this->alterData(0, $item);

                return 0;
            } else {
                $this->alterData(1, $item);

                return 1;
            }
        }
        if ($item[0] == '大') {
            $result = $this->getBig($result, $item[4]);

            $this->alterData($result, $item);
            
            return $result;
        }
        if ($item[0] == '小') {
            //這裡+ ！ 指的是要回傳反向的  因為客戶壓小 而程式只須跑一次輸贏 剛好只要反向就好
            $result = !$this->getBig($result, $item[4]);
            $this->alterData($result, $item);

            return $result;
        }
        if ($item[0] == '單') {
            $result = $this->getSingle($result, $item[4]);
            $this->alterData($result, $item);

            return $result;
        }
        if ($item[0] == '雙') {
            //這裡+ ！ 指的是要回傳反向的  因為客戶壓小 而程式只須跑一次輸贏 剛好只要反向就好
            $result = !$this->getSingle($result, $item[4]);
            $this->alterData($result, $item);

            return $result;
        }
    }

    public function getBig($result, $object) //只取加起來是大的 壓小的話 就反向
    {
        $ob = 0;

        for ($i = 0; $i <= 2; $i++) {
            $ob += $result[$i][$object - 1];  //這裡的-1是剛好是位置為 傳進來的object （1莊家或2閒家）的少一的位置
        }

        if ($ob > 9 && $ob !=9) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getSingle($result, $object) //只取加起來是單數的 壓雙數的話 就反向
    {
        $ob = 0;

        for ($i = 0; $i <= 2; $i++) {
            $ob += $result[$i][$object - 1];
        }
        if ($ob % 2 == 1) {
            return 1;
        } else {
            return 0;
        }
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
