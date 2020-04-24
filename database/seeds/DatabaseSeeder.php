<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() //這個是env database = laravel_test 在用的run
    {
        Cache::store("redis")->flush();
        $this->call(TestUserSeeder::class);
        $this->call(TestItemSeeder::class);
        $this->call(TestUserAmountSeeder::class);
        $this->call(TestGameResultSeeder::class);
        $this->call(TestOrdersSeeder::class);

    }
    //請修改env database = laravel 再使用下面run
    // public function run()
    // {
    //     Cache::store("redis")->flush();

    //     $this->call(ItemSeeds::class);
    //     $this->call(RootUserSeeds::class);
    // }
}
