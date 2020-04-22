<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Itemrule extends Model
{
    private static $_instance  = null ;
   
    public static function getInstanceByid($id)
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance->find($id);
    }
    protected $fillable = [
        'item_id','one', 'two', 'three','four','five','status','special_one',
        'special_two','special_three','operator','total','extend_exist_rule'
    ];
    //把時間格式進行轉換
    protected function getDateFormat()
    {
        return 'U';
    }
}
