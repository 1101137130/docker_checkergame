<?php
namespace App\CheckersClass;

class lost
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
        return $item[4] != (string)$result[2] ? 1 : 0;
    }
    public function totalResult($item, $result)
    {
        if ($item[4] != $result[3]) {
            self::$_instance = null;
            return 1;
        } else {
            self::$_instance = null;
            return 0;
        }
    }
}
