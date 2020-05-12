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
        $permission['manage_rate'] = $user['manage_rate'] ;
        $permission['manager_editor'] = $user['manager_editor'] ;
        $permission['view_orders'] = $user['view_orders'] ;

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
        $order = $request->all();
        $createOrders = createOrders::getInstance();
        $r = $createOrders->init($order);
       
        return $r;
    }
}
