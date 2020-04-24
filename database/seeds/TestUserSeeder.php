<?php

use Illuminate\Database\Seeder;
use App\User;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'username' =>'root',
            'email' => 'root@hhh.com',
            'password' => bcrypt('123456'),
            'status' => 1,
            'view_orders' => 1,
            'manager_editor' => 1,
            'manage_rate' => 1,
            'deposit_able' => 1,
            'order_amount_arrangement' => 1
        ]);

    }
}
