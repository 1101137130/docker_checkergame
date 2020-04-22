<?php

use Illuminate\Database\Seeder;
use App\CheckersClass\gameStart;
use App\CheckersClass\createGameResultRecord;

class TestGameResultSeeder extends Seeder
{
    public function run()
    {
        $gamestart =new gameStart();
        $result = $gamestart->start();
        $creategameresult =new createGameResultRecord();
        $creategameresult->create($result);
    }
}
