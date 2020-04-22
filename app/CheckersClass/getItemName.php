<?php
namespace App\CheckersClass;

use Illuminate\Support\Facades\Redis;
class getItemName
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
        $data = Redis::get('Item');
        $data = json_decode($data, true);
        $array = array();
        $t=count($data)-1;
        for ($i = 0 ; $i<=$t ; $i++) {
            array_push($array, array('id'=>$data[$i]['id'],'itemname'=>$data[$i]['itemname'],));
        }
        return $array;

    }
}
