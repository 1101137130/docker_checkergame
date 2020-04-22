<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\CheckersClass\createItemRule;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DataConverterTest extends TestCase
{
    /**
     * @dataProvider DataConverterProvider
     */
    public function testDataConverter($data, $shouldBe)
    {
        $this->assertTrue(true);
        $dataConverter  = new createItemRule();
        $result = $dataConverter->dataConverter($data);
        $this->assertEquals($result, $shouldBe);
    }
    public function DataConverterProvider()
    {
        return[[array('3','5','1'),351],[array('5','4','3'),543]];
    }
}
