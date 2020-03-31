<?php

namespace App\Http\Controllers;

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

    public function show()
    {
        return view('order.show');
    }

    public function getOrder()
    {
        $user = Auth::user();
        $orders = Order::all();
        $order = $orders->where('user_id', $user->id);
        return json_encode($order,true);
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
