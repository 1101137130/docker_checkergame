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
        factory(App\User::class, 2)->create();
        $user=User::find(1);
        $user->update([
                'view_orders' => 1,
                'manager_editor' => 1,
                'manage_rate' => 1,
                'deposit_able' => 1,
                'order_amount_arrangement' => 1]);
    }
}
