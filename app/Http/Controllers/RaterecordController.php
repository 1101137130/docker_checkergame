<?php

namespace App\Http\Controllers;

use App\Raterecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RaterecordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
    public function getDataByUser(Request $user)
    {
        return $this->dataSelect('user', $user->id, $user->temp);
    }
    public function getDataByItem(Request $item)
    {
        return $this->dataSelect('item', $item->id, $item->temp);
    }
    public function getDataAll(Request $temp)
    {
        if ($temp->temp == 0) {
            return $this->dataSelect();
        }
        return $this->dataSelect(null, null, $temp->temp);
    }
    public function getDataByDate(Request $date)
    {
        $data=$this->dataSelect(null, null, null, $date);
    
        return $data;
    }
    public function index()
    {
        return view('raterecord.index');
    }
}
