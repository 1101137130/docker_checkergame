<?php
namespace App\CheckersClass;

class convertStatus
{
    private static $_instance  = null ;
   
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    public function convertOrdersStatus($status)
    {
        if ($status == 'win') {
            self::$_instance  = null;
            return 2;
        }
        if ($status == 'lost') {
            self::$_instance  = null;
            return 3;
        }
        if ($status =='cancel') {
            self::$_instance  = null;
            return 4;
        }
        if ($status =='discard') {
            self::$_instance  = null;
            return 5;
        }
        if ($status =='new') {
            self::$_instance  = null;
            return 1;
        }
    }
}
