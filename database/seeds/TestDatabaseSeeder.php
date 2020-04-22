<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class TestDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->call(TestUserSeeder::class);
        $this->call(TestItemSeeder::class);
        $this->call(TestUserAmountSeeder::class);
    }
}
