<?php

namespace App\CheckersClass;


use Exception;
use Illuminate\Support\Facades\Auth;
use App\CheckersClass\convertStatus;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;

class updateOrder
{
    private static $_instance  = null ;
   
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    public function update($item, $status)
    {
        $user = Auth::user();
        
        if ($status == 'win') {
            $this->redisUpdate($user, $item);
        }

        $convertOrdersStatus = convertStatus::getInstance();
        $status = $convertOrdersStatus->convertOrdersStatus($status);

        $order = DB::table('orders');
        try {
            $order
                ->where('item_id', $item[1])
                ->where('user_id', $user->id)
                ->where('bet_object', $item[4])
                ->orderBy('created_at','desc')
                ->first()
                ->update(['status' => $status]);

            return array(true, '');
        } catch (Exception $e) {
            $array = array(false, $e);

            return $array;
        }
    }
    public function cancel($id, $status)
    {
        $convertOrdersStatus = convertStatus::getInstance();
        $status = $convertOrdersStatus->convertOrdersStatus($status);
        $order = DB::table('orders');
        try {
            $order
                ->where('id', $id)
                ->update(['status' => $status]);

            return array(true, '註銷成功');
        } catch (Exception $e) {
            $array = array(false, $e);

            return $array;
        }
    }
    private function redisUpdate($user, $item)
    {
        $usert = $user->username . (string) $user->id;
        $redisUser = Redis::get($usert);
            
        if ($redisUser == null) {
            Redis::set($usert, $item[2] * $item[3]); //item[2]=賠率 item[3]=金額
        } else {
            Redis::set($usert, $redisUser + $item[2] * $item[3]);
        }
    }
}
