<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\CheckersClass\selectOrders;

class SelectOrdersTest extends TestCase
{
    /**
     * @dataProvider ordersSelectorProvider
     */
    public function testOrdersSelector($data, $shouldBe)
    {
        $ordersSelector = new selectOrders();
        $re = $ordersSelector->ordersSelector($data);
        $this->assertEquals(count($re), $shouldBe);
    }
    public function ordersSelectorProvider()
    {
        $data = array( "startdate"=> "NaN", "enddate"=> "NaN","temp"=> "0", "userid"=> null ,"itemid"=> null ,"status"=> null ,"betobject"=> null);
        $shouldBe = 14;
        $datawin = array( "startdate"=> "NaN", "enddate"=> "NaN","temp"=> "0", "userid"=> null ,"itemid"=> null ,"status"=> 2 ,"betobject"=> null);
        $shouldBeWin = 6;
        $datawinBanker = array( "startdate"=> "NaN", "enddate"=> "NaN","temp"=> "0", "userid"=> null ,"itemid"=> null ,"status"=> 2 ,"betobject"=> 1);
        $shouldBeWinBanker = 2;
        $datawinBankeritem = array( "startdate"=> "NaN", "enddate"=> "NaN","temp"=> "0", "userid"=> null ,"itemid"=> 1 ,"status"=> 2 ,"betobject"=> 1);
        $shouldBeWinBankeritem = 0;
        $datawinBankeritemuser2 = array( "startdate"=> "NaN", "enddate"=> "NaN","temp"=> "0", "userid"=> 2 ,"itemid"=> 1 ,"status"=> 2 ,"betobject"=> 1);
        $shouldBeWinBankeritemuser2 = 0;
        $datatemp = array( "startdate"=> "NaN", "enddate"=> "NaN","temp"=> "1", "userid"=> null ,"itemid"=> null ,"status"=> null ,"betobject"=> null);
        $shouldBetemp = 0;

        return[
            [$data, $shouldBe],[$datawin, $shouldBeWin],
            [$datawinBanker, $shouldBeWinBanker],
            [$datawinBankeritem, $shouldBeWinBankeritem],
            [$datawinBankeritemuser2, $shouldBeWinBankeritemuser2],
            [$datatemp, $shouldBetemp]
    ];
    }
}
