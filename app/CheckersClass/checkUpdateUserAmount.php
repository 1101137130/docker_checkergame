<?php

namespace App\CheckersClass;

use Exception;
use App\Amount;
use App\AmountRecord;

class checkUpdateUserAmount
{
    private static $_instance  = null ;
   
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    public function checkAmount($userID, $amount)
    {
        $amountmodel = Amount::where('user_id', $userID)->first();

        if ($amountmodel == null) {
            $error = '找不到您的金額紀錄';
            $data = array(false, $error);

            return  $data;
        } elseif ($amountmodel->amount <= 0 or $amountmodel->amount < $amount) {
            $error = '您的存款不足';
            $data = array(false, $error);

            return  $data;
        } else {
            $data = array(true, '');

            return  $data;
        }
    }

    public function update($userID, $amount, $status)
    {
        try {
            Amountrecord::create(['user_id' => $userID, 'amount' => $amount, 'status' => $status]);
            $data = array(true, '');

            return  $data;
        } catch (Exception $error) {
            $data = array(false, $error);

            return  $data;
        }
    }
    public function create($userID, $request)
    {
        //判斷是否初次儲值
        $clientamount = Amount::where('user_id', $userID)->first();
        $convertStatus = convertStatus::getInstance();
        $storeAmountStatus = $convertStatus->convertAmountStatus('store');
        
         //如果不是則建立新的金額紀錄
        if ($clientamount != null) {       
            $result = $this->update($userID, $request->amount, $storeAmountStatus);

            return $result;
        } else {                            //如果是 則建立新的amount 並預設金額為0 然後在新增金額紀錄
            try {
                Amount::create(['user_id' => $userID, 'amount' => 0]);
                $result = $this->update($userID, $request->amount, $storeAmountStatus);
                
                return $result;
            } catch (Exception $e) {
                throw $e;
            }
        }
    }
}
