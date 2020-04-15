<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Itemrule extends Model
{
    protected $fillable = [
        'item_id','one', 'two', 'three','four','five','status','special_cards','operator','total','extend_exist_rule'
    ];
    //把時間格式進行轉換
    protected function getDateFormat()
    {
        return 'U';
    }
}
