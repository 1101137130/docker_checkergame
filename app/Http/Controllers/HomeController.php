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
        //每次撈資料前先去檢查是否有修改過
        //如果有就去抓最新的
        if (!Redis::get('isItemSetyet')) {
            $setitemname = setItemname::getInstance();
            $setitemname->setItemname();
        }
        $data = Redis::get('Item');
        $array = json_decode($data, true);
        $data = array();
        for ($i = 0; $i < sizeof($array); $i++) {
            array_push($data, array($array[$i]['id'], $array[$i]['itemname'], $array[$i]['rate'],$array[$i]['limit_amount']));
        }

        return $data;
    }

    public function clientorder(Request $request)
    {
        $user = Auth::user();
        $gamestart =  gameStart::getInstance();
        $createOrders = createOrders::getInstance();

        $order = $request->order;
        
        if ($order != "true") { //這是判定有沒有金額下注 如果沒有就只是跑一次遊戲給前臺
            $result = $gamestart->start();
            $gameend = gameEnd::getInstance();
            foreach ($order as $item) {
                $data = $createOrders->new($item);
                if ($data[0] != true) {
                    $request->session()->flash('error', $data[1]);

                    return json_encode($data[1]);
                } else {
                    $orderid = $data[1];   //data[1]是新增成功後的orderID
                    array_push($result, $gameend->end($order, $result, $orderid));
                    $winamount = Redis::get($user->username . $user->id);
                    $winamount != null ? array_push($result, $winamount) : array_push($result, 0);
                }
            }

            
            
            return $result;
        } else {
            $result = $gamestart->start();

            return $result;
        }
    }
}
