<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'itemname', 'rate', 'status'
    ];
    //把時間格式進行轉換
    protected function getDateFormat()
    {
        return 'U';
    }
}
