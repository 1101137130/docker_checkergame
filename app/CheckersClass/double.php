<?php
namespace App\CheckersClass;

class double
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
        return $result[$item[4]-1] % 2==0 ? 1 : 0;
    }
    public function totalResult($item, $result)
    {
        $ob = 0;

        for ($i = 0; $i <= 2; $i++) {
            $ob += $result[$i][(int)$item[4] - 1];
        }
        if ($ob % 2 == 0) {
            self::$_instance = null;
            return 1;
        } else {
            self::$_instance = null;
            return 0;
        }
    }
}
