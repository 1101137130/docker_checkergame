<?php

namespace App\CheckersClass;

use Exception;
use App\Amount;
use App\AmountRecord;

class checkUpdateUserAmount
{
    public function check($user, $amount)
    {
        $amountmodel = Amount::where('user_id', $user->id)->first();

        if ($amountmodel == null) {
            $error = '找不到您的金額紀錄';
            $data = array(false, $error);

            return  $data;
        } elseif ($amountmodel->amount <= 0 or $amountmodel->amount < $amount) {
            $error = '您的存款不足';
            $data = array(false, $error);

            return  $data;
        } else {

            return $this->update($user, $amount);
        }
    }

    public function update($user, $amount)
    {
        $amount = $amount * -1;
        try {
            Amountrecord::create(['user_id' => $user->id, 'amount' => $amount, 'status' => 1]);
            $data = array(true, '');

            return  $data;
        } catch (Exception $error) {
            $data = array(false, $error);

            return  $data;
        }
    }

    public function create($user, $request)
    {

        //判斷是否初次儲值
        $clientamount = Amount::where('user_id', $user->id)->first();

        if ($clientamount != null) {        //如果不是則建立新的金額紀錄 
            try {
                AmountRecord::create([
                    'user_id' => $user->id,
                    'amount' => $request->amount,
                    'status' => 4           //status:4代表以儲值方式加錢
                ]);

                $request->session()->flash('status', '儲值成功！');

                return redirect('game');
            } catch (Exception $e) {
                echo $e;

                return view('amount.store');
            }
        } else {                            //如果是 則建立新的amount 並預設金額為0 然後在新增金額紀錄 
            try {
                AmountRecord::create([
                    'user_id' => $user->id,
                    'amount' => 0,
                    'status' => 4           //status:4代表以儲值方式加錢
                ]);
                //建立新的金額紀錄 
                Amount::create(['user_id' => $user->id, 'amount' => $request->amount]);
                $request->session()->flash('status', '儲值成功！');

                return redirect('game');
            } catch (Exception $e) {
                echo $e;

                return view('amount.store');
            }
        }
    }
}
