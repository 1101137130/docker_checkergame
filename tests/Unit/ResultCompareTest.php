<?php

namespace Tests\Unit;

use App\CheckersClass\resultCompare;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;

class ResultCompareTest extends TestCase
{
    /**
     * @dataProvider totalCompareProvider
     */
    public function testTotalCompare($objectClient, $result, $itemruleID, $shouldBe)
    {
        $compare = new resultCompare();
        $itemrule = DB::table('itemrules')->where('id', $itemruleID)->first();
        $r = $compare->totalCompare($objectClient, $result, $itemrule);
        $this->assertEquals($r, $shouldBe);
    }
    public function totalCompareProvider()
    {
        $result1 = array(array(1,1,3),array(5,4,1),array(4,2,1),1);
        $result2 = array(array(2,2,3),array(5,4,1),array(3,2,1),1);

        return[[1,$result1,3,false],[0,$result2,3,true]];
    }
    /**
     * @dataProvider extendCompareProvider
     */
    public function testExtendCompare($objectClient, $result, $itemruleID, $shouldBe)
    {
        $compare = new resultCompare();
        $itemrule = DB::table('itemrules')->where('id', $itemruleID)->first();
        $r = $compare->extendCompare($objectClient, $result, $itemrule);
        $this->assertEquals($r, $shouldBe);
    }
    public function extendCompareProvider()
    {
        $result1 = array(array(1,3,2),array(5,4,1),array(2,2,3),3);
        $result2 = array(array(2,2,3),array(5,4,1),array(3,2,1),1);

        return[[0,$result1,7,true],[0,$result2,7,false]];
    }
    /**
    * @dataProvider singleCompareFunctionProvider
    */
    public function testSingleCompareFunction($clientResult, $data, $shouldBe)
    {
        $compare = new resultCompare();
        $r = $compare->singleCompareFunction($clientResult, $data);
        $this->assertEquals($r, $shouldBe);
    }
    public function singleCompareFunctionProvider()
    {
        return[[1,123,true],
        [4,123,false],
        [5,1234,false],
        [2,1234,true]];
    }
    /**
     * @dataProvider singleCompareProvider
     */
    public function testSingleCompare($objectClient, $result, $itemruleID, $shouldBe)
    {
        $compare = new resultCompare();
        $itemrule = DB::table('itemrules')->where('id', $itemruleID)->first();
        $r = $compare->singleCompare($objectClient, $result, $itemrule);
        $this->assertEquals($r, $shouldBe);
    }
    public function singleCompareProvider()
    {
        $result1 = array(array(1,1,3),array(5,4,1),array(3,2,1),1);
        $result2 = array(array(2,2,3),array(5,4,1),array(3,2,1),1);

        return[[1,$result1,1,false],[0,$result2,1,true],[1,$result2,2,true]];
    }
    /**
     * @dataProvider specialCardsProvider
     */
    public function testSpecialCards($objectClient, $result, $itemruleID, $shouldBe)
    {
        $compare = new resultCompare();
        $itemrule = DB::table('itemrules')->where('id', $itemruleID)->first();
        $r = $compare->specialCards($objectClient, $result, $itemrule);
        $this->assertEquals($r, $shouldBe);
    }
    public function specialCardsProvider()
    {
        $result1 = array(array(1,1,3),array(2,4,2),array(3,2,1),3);
        $result2 = array(array(2,1,1),array(5,2,1),array(3,3,3),1);

        return[[0,$result1,6,true],[0,$result2,6,false],[1,$result2,6,true]];
    }
}
