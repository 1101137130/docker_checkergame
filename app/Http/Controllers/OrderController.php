<?php

namespace App\Http\Controllers;

use App\CheckersClass\updateOrder;
use App\CheckersClass\redisGetSet;
use App\CheckersClass\selectOrders;
use Illuminate\Http\Request;
use App\CheckersClass\getItemName;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function deleteorder(Request $request)
    {
        $updateOrder = updateOrder::getInstance();
        $result = $updateOrder->cancel($request->id, $request->status);

        return $result;
    }
   
    public function getUserName()
    {
        $data = redisGetSet::getInstance();

        return $data->getOrderedUsername();
    }
    public function all()
    {
        return view('order.all');
    }
    public function getData(Request $request)
    {
        $selectOrders = selectOrders::getInstance();
        $orders = $selectOrders->ordersSelector($request->all());
        
        return $orders;
    }
    public function getItemName()
    {
        $getItemName = getItemName::getInstance();
        
        return $getItemName->getData();
    }
}
