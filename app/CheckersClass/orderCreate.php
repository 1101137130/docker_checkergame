<?php

namespace App\CheckersClass;

use App\Order;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\CheckersClass\checkUpdateUserAmount;
use App\CheckersClass\checkRateTheSame;

class orderCreate
{
    public function new($item) //order處理
    {
        $checkandUpadate = new checkUpdateUserAmount;
        //item[0]-> itemname; item[1]->itemid  
        //item[2]-> rate ; item[3] -> amount 
        //item[4]-> ocject 1:莊家 2:閒家

        $user = Auth::user();
        $data = $checkandUpadate->check($user, $item[3]);

        if ($data[0] == true) {
             
            $checkrate = checkRateTheSame::check($item[1], $item[2]);
            
            if ($checkrate == false) {
                $error = '賠率已變動請重新下單！';
                $data = array(false, $error);

                return $data;
            }
            try {
                Order::create([
                    'username' => $user->username,
                    'user_id' => $user->id,
                    'item_id' => $item[1],
                    'amount' => $item[3],
                    'bet_object' => $item[4],
                    'status' => 1, //新建
                    'item_rate' => $item[2]
                ]);
                $data = array(true, '');
                return $data;
            } catch (Exception $e) {
                $error = array(false, $e);

                return $error;
            }
        } else {

            return $data;;
        }
    }
}
