<?php

namespace Tests\Unit;

use App\Http\Controllers\OrderController;
use Tests\TestCase;

class ItemTest extends TestCase
{
    public function testGetItemName()
    {
        $itemsnamearray = array('id'=>1,'itemname'=>'贏');
        
        $itemcontrller = new OrderController();
        $array = $itemcontrller->getItemName();
        $this->assertEquals($itemsnamearray, $array[0]);
    }
 
}
