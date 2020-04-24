<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resultrecord extends Model
{
    protected $fillable = [
        'banker', 'player','result'
    ];

    //把時間格式進行轉換
    protected function getDateFormat()
    {
        return 'U';
    }
}
