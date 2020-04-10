<?php
namespace App\CheckersClass;

class draw
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
        return $result[$item[4]-1] == 3? 1:0;
    }
      public function totalResult($item, $result)
    {
        
        if ($result == 3) {
            self::$_instance = null;
            return 1;
        } else {
            self::$_instance = null;
            return 0;
        }
    }
}
