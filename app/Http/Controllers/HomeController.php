<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\CheckersClass\gameStart;
use App\CheckersClass\gameEnd;
use App\CheckersClass\createOrders;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use App\CheckersClass\setItemname;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
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
        if (!Redis::get('isItemSetyet')) {
            $setitemname = setItemname::getInstance();
            $setitemname->setItemname();
        }
        $data = Redis::get('Item');
        $array = json_decode($data, true);
        $data = array();
        for ($i = 0; $i < sizeof($array); $i++) {
            array_push($data, array($array[$i]['id'], $array[$i]['itemname'], $array[$i]['rate']));
        }

        return $data;
    }

    public function clientorder(Request $request)
    {
        $user = Auth::user();
        $gamestart =  gameStart::getInstance();
        $createOrders = createOrders::getInstance();

        $order = $request->order;
        
        if ($order != "true") {
            foreach ($order as $item) {
                $data = $createOrders->new($item);

                if ($data[0] != true) {
                    $request->session()->flash('error', $data[1]);

                    return json_encode($data[1]);
                }
            }

            $gameend = gameEnd::getInstance();

            $result = $gamestart->start();


            array_push($result, $gameend->end($order, $result));
            $winamount = Redis::get($user->username . $user->id);
            $winamount != null ? array_push($result, $winamount) : array_push($result, 0);

            return $result;
        } else {
            $result = $gamestart->start();

            return $result;
        }
    }
}
