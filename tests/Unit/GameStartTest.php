<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\CheckersClass\gameStart;

class GameStartTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStart()
    {
        $shouldBeArray = gettype(array('test'=>1));

        $start = new gameStart();
        $result = gettype($start->start());
        $this->assertEquals($shouldBeArray, $result);
    }
    /**
     * @dataProvider compareProvider
     */
    public function testCompare($banker, $player, $shouldBe)
    {
        $compare = new gameStart();
        $result = $compare->compare($banker, $player);
        $this->assertEquals($shouldBe, $result);
    }
    public function compareProvider()
    {
        return [
            [2, 1, array(2,1,1)],
            [4, 1, array(4,1,1)],
            [1, 5, array(1,5,1)],
            [1, 1, array(1,1,3)],
            [4, 5, array(4,5,2)]
        ];
    }
    /**
     * @dataProvider getResultProvider
     */

    public function testGetResult($data, $shouldBe)
    {
        $getResult = new gameStart();
        $result = $getResult->getResult($data);
        $this->assertEquals($shouldBe, $result);
    }
    
    public function getResultProvider()
    {
        return [
            [array(array(1,1,3),array(2,3,2),array(3,2,1)),3],
            [array(array(1,5,1),array(3,2,1),array(4,4,3)),1],
            [array(array(5,1,2),array(4,4,3),array(2,3,2)),2],
            [array(array(4,4,3),array(3,3,3),array(3,2,1)),3]
        ];
    }
}
