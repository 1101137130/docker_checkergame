<?php
namespace App\CheckersClass;

class big
{
    private static $_instance  = null ;
   
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    public function gResult($item, $result)
    {
        self::$_instance = null;
        return $result[$item[4]-1] > 3 ? 1 : 0;
    }
    public function totalResult($item, $result)
    {
        $ob = 0;

        for ($i = 0; $i <= 2; $i++) {
            $ob += $result[$i][(int)$item[4] - 1];  //這裡的-1是剛好是位置為 傳進來的object （1莊家或2閒家）的少一的位置
        }

        if ($ob > 9) {
            self::$_instance = null;
            return 1;
        } else {
            self::$_instance = null;
            return 0;
        }
    }
}
