<?php
namespace App\CheckersClass;

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
    public function dataSelect($condition =null, $id = null, $temp = null, $date =null)
    {
        $skip=0;
        $record= DB::table('raterecords')
            ->join('users', 'users.id', '=', 'raterecords.user_id')
            ->join('items', 'items.id', '=', 'raterecords.item_id')
            ->select('raterecords.id', 'users.username', 'items.itemname', 'raterecords.rate', 'raterecords.created_at')
            ->orderBy('raterecords.created_at', 'DESC');

        if ($condition=='user') {
            $condition='raterecord.user_id';
            $record->where($condition, '=', $id);
        }
        if ($condition=='item') {
            $condition='raterecord.item_id';
            $record->where($condition, '=', $id);
        }
        if ($temp !=null) {
            $skip=$temp*100;
        }
        if ($date!=null) {
            $record->where('raterecords.created_at', '>=', $date->startdate)->where('raterecords.created_at', '<=', $date->enddate);
        }
        $data = $record->skip($skip)->take(100)->get();

        return $data;
    }
}
