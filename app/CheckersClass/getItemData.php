<?php
namespace App\CheckersClass;

use Illuminate\Support\Facades\Redis;

class getItemData
{
    private static $_instance  = null ;
   
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    public function getData()
    {
        if (!Redis::get('isItemSetyet')) {
            $setitemname = redisGetSet::getInstance();
            $setitemname->setItemname();
        }
        $data = Redis::get('Item');
        $array = json_decode($data, true);
        $data = array();
        $t = sizeof($array);
        for ($i = 0; $i < $t; $i++) {
            if ($array[$i]['status']==1) {
                array_push($data, array($array[$i]['id'], $array[$i]['itemname'], $array[$i]['rate'],$array[$i]['limit_amount']));
            }
        }

        return $data;
    }
}
