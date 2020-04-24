<?php

namespace App\Http\Controllers;

use App\CheckersClass\redisGetSet;
use App\CheckersClass\selectOrders;
use App\CheckersClass\selectRaterecords;
use Illuminate\Http\Request;

class RaterecordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getUsers()
    {
        $re = selectRaterecords::getInstance();

        return $re->getUsers();
    }
    public function getItems()
    {
        $re = selectRaterecords::getInstance();

        return $re->getItems();
    }

    public function getData(Request $data)
    {
        $re = selectRaterecords::getInstance();

        return $re->dataSelect($data->all());
    }
    public function index()
    {
        return view('raterecord.index');
    }
}
