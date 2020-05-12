<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CheckersClass\createOrders;
use App\CheckersClass\editUser;
use Illuminate\Support\Facades\Auth;
use App\CheckersClass\getItemData;
use App\User;
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
        $order = $request->all();
        $createOrders = createOrders::getInstance();
        $r = $createOrders->init($order);
       
        return $r;
    }
    public function getUser()
    {
        return json_encode(Auth::user(), true);
    }
    
    public function editUser(Request $request)
    {
        $editUser = editUser::getInstance();
        return $editUser->editUser($request);
    }

}
