<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Raterecord extends Model
{
    protected $fillable = [
        'rate','user_id', 'item_id',
    ];

    //把時間格式進行轉換
    protected function getDateFormat()
    {
        return 'U';
    }
}
