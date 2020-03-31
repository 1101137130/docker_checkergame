<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Amount extends Model
{
    protected $fillable = [
        'user_id', 'amount'
    ];
     //把時間格式進行轉換
     protected function getDateFormat()
     {
         return 'U';
     }
}
