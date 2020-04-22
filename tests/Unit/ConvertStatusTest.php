<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\CheckersClass\convertStatus;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ConvertStatusTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    /**
     * @dataProvider ConvertOrdersProvider
     */
    public function testConvertOrdersStatus($status, $shouldBe)
    {
        $convertStatus = new convertStatus();
        $result = $convertStatus->convertOrdersStatus($status);
        $this->assertEquals($shouldBe, $result);
    }
    /**
     * @dataProvider ConvertAmountProvider
     */
    public function testConvertAmountStatus($status, $shouldBe)
    {
        $convertStatus = new convertStatus();
        $result = $convertStatus->convertAmountStatus($status);
        $this->assertEquals($shouldBe, $result);
    }
    public function ConvertOrdersProvider()
    {
        return[['win',2],['cancel',4]];
    }
    public function ConvertAmountProvider()
    {
        return[['play',1],['withdraw',5]];
    }
}
