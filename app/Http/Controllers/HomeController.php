<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CheckersClass\createOrders;
use Illuminate\Support\Facades\Auth;
use App\CheckersClass\getItemData;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $permission = [];
        if ($user['manage_rate'] == 1) {
            $permission['manage_rate'] = true;
        } else {
            $permission['manage_rate'] = false;
        }
        if ($user['manager_editor'] == 1) {
            $permission['manager_editor'] = true;
        } else {
            $permission['manager_editor'] = false;
        }
        if ($user['view_orders'] == 1) {
            $permission['view_orders'] = true;
        } else {
            $permission['view_orders'] = false;
        }

        return view('home', $permission);
    }

    public function game()
    {
        return view('game');
    }

    public function data()
    {
        $getItemData = getItemData::getInstance();

        return $getItemData->getData();
    }
    
    public function clientorder(Request $request)
    {
        $order = $request->order;
        $createOrders = createOrders::getInstance();

        return $createOrders->init($order);
    }
}
