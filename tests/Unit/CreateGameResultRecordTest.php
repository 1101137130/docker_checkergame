<?php

namespace Tests\Feature;

use App\CheckersClass\createGameResultRecord;
use Tests\TestCase;
use App\CheckersClass\gameStart;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateGameResultRecordTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    /**
     * @dataProvider createProvider
     */
    public function testCreate($result, $bankerShouldBe, $playerShouldBe, $resultShouldBe)
    {
        $create =new createGameResultRecord();
        $record = $create->create($result);
        $this->assertEquals($bankerShouldBe, $record->banker);
        $this->assertEquals($playerShouldBe, $record->player);
        $this->assertEquals($resultShouldBe, $record->result);
    }
    public function createProvider()
    {
        $result=[array(1,2,2),array(3,2,1),array(5,1,2)];
        $bankerShouldBe = 135;
        $playerShouldBe = 221;
        $resultShouldBe = 212;
        return[[$result,$bankerShouldBe,$playerShouldBe,$resultShouldBe]];
    }
}
