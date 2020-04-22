<?php

use App\AmountRecord;
use App\Amount;
use Illuminate\Database\Seeder;

class TestUserAmountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            Amount::create(['user_id' => 1, 'amount' => 0]);
            AmountRecord::create(['user_id'=>1,'status'=>4,'amount'=>100000]);
    }
}
