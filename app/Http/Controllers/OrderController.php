<?php

namespace App\Http\Controllers;

use App\CheckersClass\updateOrder;
use Illuminate\Http\Request;
use App\Order;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;

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
    public function ordersSelector($data)
    {
        $temp =0;
        $userid= $data->userid == 'NaN'? null :$data->userid;
        $itemid= $data->itemid == 'NaN'? null :$data->itemid;
        $startdate= $data->startdate == 'NaN'? 0 :$data->startdate;
        $enddate= $data->enddate == 'NaN'? time() :$data->enddate;
        $status= $data->status == 'NaN'? null :$data->status;
        $betobject = $data->betobject =='NaN' ? null : $data->betobject;

        $data->temp == 0 ? $temp = 0 : $temp = $data->temp*100;
        $orders=DB::table('orders')->get()
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
        $data=$orders->slice($temp)->take(100);
        return json_decode($data, true);
    }
    public function getUserName()
    {
        if (Redis::get('isOrderUsersSetyet')==true) {
            $orderUsers = Redis::get('OrderUsers');
            $data = json_decode($orderUsers, true);
            return $data ;
        } else {
            $data = DB::table('orders')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->select('orders.user_id', 'users.username')
            ->distinct('user_id')
            ->get();
            Redis::set('OrderUsers', $data);
            Redis::set('isOrderUsersSetyet', true);
            return $data;
        }
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
        return view('order.all');
    }
    public function getData(Request $request)
    {
        $orders = $this->ordersSelector($request);
        
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
