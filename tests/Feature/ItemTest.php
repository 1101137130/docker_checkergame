<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
require('ItemController.php');
class ItemTest extends TestCase
{
    public function testgetData()
    {
        $this->assertTrue(true);
    }
}
