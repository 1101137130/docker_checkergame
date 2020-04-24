<?php
namespace App\CheckersClass;

use App\Raterecord;
use Illuminate\Support\Facades\DB;

class selectRaterecords
{
    private static $_instance  = null ;
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    public function dataSelect($data)
    {
        $temp =0;
        $userid= $data['userid'];
        $itemid= $data['itemid'];
        $startdate= $data['startdate'] == 'NaN'? 0 :$data['startdate'];
        $enddate= $data['enddate'] == 'NaN'? time() :$data['enddate'];
        $data['temp'] == 0 ? $temp = 0 : $temp = $data['temp']*100;

        $record= DB::table('raterecords')
            ->join('users', 'users.id', '=', 'raterecords.user_id')
            ->join('items', 'items.id', '=', 'raterecords.item_id')
            ->select('raterecords.id', 'raterecords.item_id', 'raterecords.user_id', 'users.username', 'items.itemname', 'raterecords.rate', 'raterecords.created_at')
            ->orderBy('raterecords.created_at', 'DESC')
            ->get()
            ->where('created_at', '<=', $enddate)
            ->where('created_at', '>=', $startdate);
        if ($userid != null) {
            $condition='user_id';
            $record = $record->where($condition, '=', $userid);
        }
        if ($itemid != null) {
            $condition='item_id';
            $record = $record->where($condition, '=', $itemid);
        }
        $result = $record
        ->slice($temp)->take(100);

        return json_decode($result, true);
    }
    public function getUsers()
    {
        $users = DB::table('raterecords')
        ->join('users', 'users.id', '=', 'raterecords.user_id')
        ->select('raterecords.user_id', 'users.username')
        ->distinct('username')
        ->get();

        return $users;
    }
    public function getItems()
    {
        $items = DB::table('raterecords')
        ->join('items', 'items.id', '=', 'raterecords.item_id')
        ->select('raterecords.item_id', 'items.itemname')
        ->distinct('itemname')
        ->get();

        return $items;
    }
}
