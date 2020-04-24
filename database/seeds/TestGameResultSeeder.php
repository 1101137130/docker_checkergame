<?php

use Illuminate\Database\Seeder;
use App\Resultrecord;
class TestGameResultSeeder extends Seeder
{
    public function run()
    {
        Resultrecord::create(['banker'=>543, 'player'=>513, 'result'=>313]);
    }
}
