<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AmountRecord extends Model
{
    protected $fillable = [
        'user_id', 'status', 'amount'
    ];
     //把時間格式進行轉換
     protected function getDateFormat()
     {
         return 'U';
     }
     protected $table = 'amountrecords';

}
