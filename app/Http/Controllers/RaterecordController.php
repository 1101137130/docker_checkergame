<?php

namespace App\Http\Controllers;

use App\CheckersClass\selectRaterecords;
use Illuminate\Http\Request;

class RaterecordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getDataByUser(Request $user)
    {
        $re = selectRaterecords::getInstance();
        return $re->dataSelect('user', $user->id, $user->temp);
    }
    public function getDataByItem(Request $item)
    {
        $re = selectRaterecords::getInstance();
        return $re->dataSelect('item', $item->id, $item->temp);
    }
    public function getDataAll(Request $temp)
    {
        if ($temp->temp == 0) {
            $temp->temp == null;
        }
        $re = selectRaterecords::getInstance();
        return $re->dataSelect(null, null, $temp->temp);
    }
    public function getDataByDate(Request $date)
    {
        $re = selectRaterecords::getInstance();
        $data= $re->dataSelect(null, null, null, $date);
    
        return $data;
    }
    public function index()
    {
        return view('raterecord.index');
    }
}
