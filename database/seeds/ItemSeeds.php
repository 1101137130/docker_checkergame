<?php

use Illuminate\Database\Seeder;
use App\Item;
use App\Itemrule;
use Illuminate\Support\Facades\Redis;

class ItemSeeds extends Seeder
{
    public function run()
    {
        $item =Item::create([
            'itemname' => '贏',
            'rate' => 2,
            'limit_amount' => 10000
        ]);
        Itemrule::create([
            'item_id'=>$item->id,
            'special_one'=>null,
            'special_two'=>null,
            'special_three'=>null,
            'extend_exist_rule'=>null,
            'one'=>234,
            'two'=>345,
            'three'=>45,
            'four'=>5,
            'five'=>1,
            'operator'=>null,
            'total'=>null,
            'status'=>1
            ]);
        $item =Item::create([
            'itemname' => '輸',
            'rate' => 2,
            'limit_amount' => 10000
        ]);
        Itemrule::create([
            'item_id'=>$item->id,
            'special_one'=>null,
            'special_two'=>null,
            'special_three'=>null,
            'extend_exist_rule'=>null,
            'one'=>15,
            'two'=>12,
            'three'=>123,
            'four'=>1234,
            'five'=>2345,
            'operator'=>null,
            'total'=>null,
            'status'=>1
            ]);
        Redis::set('isItemSetyet', false); //修改redis資料
    }
}
