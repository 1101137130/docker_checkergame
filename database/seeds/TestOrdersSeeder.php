<?php

use Illuminate\Database\Seeder;
use App\Order;
use App\AmountRecord;
use Illuminate\Support\Facades\Redis;

class TestOrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $itemid = 0;
        $status = array(3,3,2,2,2,3,2,2,3,3,3,3,2,3);
        $itemrate = array(2,2,2,2,3,3,4,4,3,3,5,5,5,5);
        for ($i = 0; $i<14; $i++) {
            $amount = rand(0, 10000);
            $betObj = 1;
            if ($i%2==0) {
                $itemid++;
                $betObj = 2;
            }
            AmountRecord::create([
                'user_id' => 1,
                'status' => 1,
                'amount' => $amount * -1
            ]);
            if ($status[$i]==2) {
               
                $usert = 'root1';
                $redisUser = Redis::get($usert);
        
                if ($redisUser == null) {
                    Redis::set($usert, $itemrate[$i] * $amount); //item[2]=賠率 item[3]=金額
                } else {
                    Redis::set($usert, $redisUser + $itemrate[$i] * $amount);
                }
            }
            Order::create([
                'username' => 'root',
                'amount' => $amount,
                'user_id' => 1,
                'item_id' => $itemid,
                'bet_object' => $betObj,
                'status' => $status[$i],
                'item_rate' => $itemrate[$i],
                'result_id' => 1
   ]);
        }
    }
}
