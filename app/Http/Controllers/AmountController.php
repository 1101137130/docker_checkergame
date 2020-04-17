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
            $r = Session::flash('status' , '成功領取'.$redisUser);
            return $r;
            } catch (Exception $e) {
                throw $e;
            }
        }
    }
    public function amount()
    {
        $user = Auth::user();
        $clientamount = Amount::where('user_id', $user->id)->first();
        if ($clientamount == null) {
            return view('amount.store', ['total' => 0]);
        }

        return view('amount.store', ['total' => $clientamount->amount]);
    }

    public function getAmount()
    {
        $user = Auth::user();
        $clientamount = Amount::where('user_id', $user->id)->first();
        if ($clientamount == null) {
            return 0;
        }
        $winamount = Redis::get($user->username . $user->id);
        $winamount != null ? $data = array($clientamount->amount, $winamount): $data = array($clientamount->amount, 0);
        
        return $data;
    }
    public function store(Request $request)
    {
        $user = Auth::user();
        //判斷是否初次儲值
        $clientamount = Amount::where('user_id', $user->id)->first();

        if ($clientamount != null) {        //如果不是則建立新的金額紀錄
            try {
                AmountRecord::create([
                    'user_id' => $user->id,
                    'amount' => $request->amount,
                    'status' => 4           //status:4代表以儲值方式加錢
                ]);

                //修改客戶總金額
                $totalamount = $clientamount->amount + $request->amount;
                $clientamount->update(['amount' => $totalamount]);

                $request->session()->flash('status', '儲值成功！');
                return redirect('show');
            } catch (Exception $e) {
                echo $e;

                return view('amount.store');
            }
        } else {                            //如果是 則建立新的amount 並預設金額為0 然後在新增金額紀錄
            try {                           //改動amount裡的金額
                Amount::create(['user_id' => $user->id, 'amount' => $request->amount]);
                AmountRecord::create([
                    'user_id' => $user->id,
                    'amount' => $request->amount,
                    'status' => 4           //status:4代表以儲值方式加錢
                ]);
                $request->session()->flash('status', '儲值成功！');
                return redirect('show');
            } catch (Exception $e) {
                echo $e;

                return view('amount.store');
            }
        }
    }
}
