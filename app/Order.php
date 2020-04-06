<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    private static $_instance  = null ;
   
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    protected $fillable = [
        'username', 'amount', 'user_id', 'item_id', 'bet_object', 'status', 'item_rate'
    ];

    //把時間格式進行轉換
    protected function getDateFormat()
    {
        return 'U';
    }
}
