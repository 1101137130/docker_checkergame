<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
   
    protected $fillable = [
        'username', 'amount', 'user_id', 'item_id', 'bet_object', 'status', 'item_rate','result_id'
    ];

    //把時間格式進行轉換
    protected function getDateFormat()
    {
        return 'U';
    }
}
