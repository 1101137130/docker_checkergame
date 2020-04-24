<?php

namespace Tests\Unit;

use App\CheckersClass\selectRaterecords;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RaterecordTest extends TestCase
{
    /**
     * @dataProvider dataSelectProvider
     */
    public function testDataSelect($data, $shouldBe)
    {
        $raterecords = new selectRaterecords();
        $re = $raterecords->dataSelect($data);
        $this->assertEquals(count($re), $shouldBe);
    }
    public function dataSelectProvider()
    {
        $data = array( "startdate"=> "NaN", "enddate"=> "NaN","temp"=> "0", "userid"=> 1 ,"itemid"=> 1);
        $shouldBe = 1;
        $data2 = array( "startdate"=> "NaN", "enddate"=> "NaN","temp"=> "0", "userid"=> 2 ,"itemid"=> null);
        $shouldBe2 = 0;
        $data3 = array( "startdate"=> "NaN", "enddate"=> "NaN","temp"=> "0", "userid"=> 1 ,"itemid"=> null);
        $shouldBe3 = 7;
        
        return[
            [$data, $shouldBe],[$data2, $shouldBe2],
            [$data3, $shouldBe3]
    ];
    }
}
