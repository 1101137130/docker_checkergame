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
    public function convertWinLostStatus($status)
    {
        if ($status == 'win') {
            return 2;
        }
        if ($status == 'lost') {
            return 3;
        }
    }
}
