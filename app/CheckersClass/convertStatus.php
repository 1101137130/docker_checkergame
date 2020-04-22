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
        switch ($status) {
            case 'win':
            
                return 2;
            case 'lost':
            
                return 3;
            case 'cancel':
            
                return 4;
            case 'discard':
            
                return 5;
            case 'new':
                
                return 1;
        }
    }
    public function convertAmountStatus($status)
    {
                switch ($status) {
            case 'win':
            
                return 2;
            case 'lost':
            
                return 3;
            case 'store':
            
                return 4;
            case 'withdraw':
            
                return 5;
            case 'error_restore':
                
                return 6;
            case 'play':
                
                return 1;
        }

    }
}
