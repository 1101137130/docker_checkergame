<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\CheckersClass\checkUpdateUserAmount;

class CheckUpdateUserAmountTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    /**
     * @dataProvider checkProvider
     */
    public function testCheck($userID, $amount, $shouldBe)
    {
        $tester = new checkUpdateUserAmount();
        $checker = $tester->check($userID, $amount);
        $this->assertEquals($shouldBe, $checker);
    }
    
    public function checkProvider()
    {
        return
        [
            [1,10000000,array(false,'您的存款不足')],
            [2,1000000,array(false,'找不到您的金額紀錄')],
            [1, 1000, array(true,'')]
        ];
    }
}
