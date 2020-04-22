<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Amount;
use Exception;
use Illuminate\Http\Request;
use App\AmountRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redis;
use App\CheckersClass\checkUpdateUserAmount;

class AmountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function takeMoney()
    {
        $user = Auth::user();
        $usert = $user->username . (string) $user->id;

        $redisUser = Redis::get($usert);
        if ($redisUser == null) {
            $r = Session::flash('errors', '您沒有任何獲勝記錄');

            return $r;
        } else {
            try {
                $r = AmountRecord::create([
                    'user_id' => $user->id,
                    'amount' => $redisUser,
                    'status' => 5
                ]);
                if ($r) {
                    Redis::set($usert, 0);
                }
                $r = Session::flash('status', '成功領取'.$redisUser);

                return $r;
            } catch (Exception $e) {
                throw $e;
            }
        }
    }
    public function amount()
    {
        $user = Auth::user();
        $clientamount =  $this->getAmountByLoginUser($user->id);

        if ($clientamount == null) {
            return view('amount.store', ['total' => 0]);
        }

        return view('amount.store', ['total' => $clientamount->amount]);
    }

    public function getAmount()
    {
        $user = Auth::user();
        $clientamount = $this->getAmountByLoginUser($user->id);
        if ($clientamount == null) {
            return 0;
        }
        $winamount = Redis::get($user->username . $user->id);
        $winamount != null ? $data = array($clientamount->amount, $winamount): $data = array($clientamount->amount, 0);
        
        return $data;
    }
    public function getAmountByLoginUser($userID)
    {
        $UserAmount = Amount::where('user_id', $userID)->first();

        return $UserAmount;
    }
    public function store(Request $request)
    {
        $user = Auth::user();
        $updateUserAmount = checkUpdateUserAmount::getInstance();
        $result = $updateUserAmount->create($user->id, $request);
        if ($result[0]) {
            $request->session()->flash('status', '儲值成功！');

            return redirect('show');
        } else {
            $request->session()->flash('errors', $result[1]);

            return redirect('show');
        }
    }
}
