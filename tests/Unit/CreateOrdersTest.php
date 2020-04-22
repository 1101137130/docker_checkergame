<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\CheckersClass\createOrders;
use App\CheckersClass\gameStart;
use Illuminate\Support\Facades\DB;

class CreateOrdersTest extends TestCase
{
    /**
     * @dataProvider newProvider
     */
    public function testProcess($order, $resultID, $shouldBe)
    {
        $createOrders = new createOrders();
        $user = DB::table('users')->first();
        $re = $createOrders->process($user,$order, $resultID);
        $this->assertEquals($re[0], $shouldBe[0]);
    }
    public function newProvider()
    {
        $item1 = array( '贏','1' ,'10.0000','1000','1' );
        $item2 = array('輸','2','2.0000','1000','2');

        return[
            [$item1,1,array(false,'賠率已變動請重新下單！')],
            [$item2,1,array(true,1)]
    ];
    }
}
