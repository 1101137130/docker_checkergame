<?php
namespace App\CheckersClass;

use Illuminate\Support\Facades\DB;

class selectOrders
{
    private static $_instance  = null ;
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    public function ordersSelector($data)
    {
        $temp =0;
        $userid= $data['userid'];
        $itemid= $data['itemid'];
        $startdate= $data['startdate'] == 'NaN'? 0 :$data['startdate'];
        $enddate= $data['enddate'] == 'NaN'? time() :$data['enddate'];
        $status= $data['status'];
        $betobject = $data['betobject'];

        $data['temp'] == 0 ? $temp = 0 : $temp = $data['temp']*100;

        $orders=DB::table('orders')
        ->join('users', 'users.id', '=', 'orders.user_id')
        ->join('items', 'items.id', '=', 'orders.item_id')
        ->select('orders.user_id', 'orders.item_id', 'orders.id', 'users.username', 'bet_object', 'items.itemname', 'orders.status', 'orders.amount', 'orders.item_rate', 'orders.created_at')
        ->get()
        ->where('created_at', '<=', $enddate)
        ->where('created_at', '>=', $startdate);
        if ($userid != null) {
            $orders = $orders->where('user_id', $userid);
        }
        if ($itemid != null) {
            $orders = $orders->where('item_id', $itemid);
        }
        if ($status != null) {
            $orders = $orders->where('status', $status);
        }
        if ($betobject != null) {
            $orders = $orders->where('bet_object', $betobject);
        }
        $result = $orders
        ->slice($temp)->take(100);

        return json_decode($result, true);
    }
}
