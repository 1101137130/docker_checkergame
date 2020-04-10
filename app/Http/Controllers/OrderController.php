<?php

namespace App\Http\Controllers;

use App\CheckersClass\updateOrder;
use Illuminate\Http\Request;
use App\Order;
use Exception;
use Illuminate\Support\Facades\Auth;

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
    public function ordersSelector($userid = null, $itemid=null, $temp=null, $date=null)
    {
        $orders=Order::getInstance()->all();
        if($temp != null){
        $temp = $temp->temp * 100;
        $orders->slice($temp);

        }
        if ($date != null) {
            $startdate=$date->startdate;
            $enddate=$date->enddate;
      
            $orders->whereBetween('created_at', [$startdate,$enddate]);
        }
        
    }
    public function getOrdersDataBytime(Request $re)
    {
        $startdate=$re->startdate;
        $enddate=$re->enddate;
        
        $orders=Order::whereBetween('created_at', [$startdate,$enddate])->take(100)->get();

        return $orders;
    }
    public function getOrder()
    {
        $user = Auth::user();
        $orders = Order::all();
        $order = $orders->where('user_id', $user->id)->take(100);
        return json_encode($order, true);
    }
    public function all()
    {
        $user = Auth::user();
        if ($user['view_orders'] == 1) {
            return view('order.all');
        }
    }
    public function getData()
    {
        $orders = Order::all()->take(100);
        
        return json_encode($orders, true);
    }
    public function tempData(Request $re)
    {
        if ($re->temp==0) {
            self::getData();
        }
        $data = $re->temp * 100;
        $orders = Order::all()->slice($data)->take(100);
        
        return json_encode($orders, true);
    }
    public function store(Request $request)
    {
        try {
            Order::create($request);
        } catch (Exception $e) {
            return $e;
        }
    }
}
