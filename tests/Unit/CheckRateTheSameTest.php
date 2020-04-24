<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\CheckersClass\CheckRateTheSame;

class CheckRateTheSameTest extends TestCase
{
    /**
    * @dataProvider checkProvider
    */

    public function testCheck($id, $rate, $shouldBe)
    {
        $check = new CheckRateTheSame();
        $re = $check->check($id, $rate);
        $this->assertEquals($shouldBe, $re);
    }
    public function checkProvider()
    {
        return[[1,500,array(false,'賠率已變動請重新下單！')]];
    }
}
