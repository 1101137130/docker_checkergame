<?php

use Illuminate\Database\Seeder;
use App\Item;
use App\Itemrule;
use App\CheckersClass\redisGetSet;
class TestItemSeeder extends Seeder
{
    public static function run()
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

        $item =Item::create([
            'itemname' => '總數大於9',
            'rate' => 3,
            'limit_amount' => 10000
        ]);
        Itemrule::create([
            'item_id'=>$item->id,
            'special_one'=>null,
            'special_two'=>null,
            'special_three'=>null,
            'extend_exist_rule'=>null,
            'one'=>null,
            'two'=>null,
            'three'=>null,
            'four'=>null,
            'five'=>null,
            'operator'=>3,
            'total'=>9,
            'status'=>3
            ]);
        $item =Item::create([
            'itemname' => '平',
            'rate' => 4,
            'limit_amount' => 10000
        ]);
        Itemrule::create([
            'item_id'=>$item->id,
            'special_one'=>null,
            'special_two'=>null,
            'special_three'=>null,
            'extend_exist_rule'=>null,
            'one'=>1,
            'two'=>2,
            'three'=>3,
            'four'=>4,
            'five'=>5,
            'operator'=>null,
            'total'=>null,
            'status'=>1
            ]);

        $item =Item::create([
            'itemname' => '總數小於9',
            'rate' => 3,
            'limit_amount' => 10000
        ]);
        Itemrule::create([
            'item_id'=>$item->id,
            'special_one'=>null,
            'special_two'=>null,
            'special_three'=>null,
            'extend_exist_rule'=>null,
            'one'=>null,
            'two'=>null,
            'three'=>null,
            'four'=>null,
            'five'=>null,
            'operator'=>1,
            'total'=>9,
            'status'=>3
            ]);
        $item =Item::create([
            'itemname' => '特殊-123',
            'rate' => 5,
            'limit_amount' => 10000
        ]);
        Itemrule::create([
            'item_id'=>$item->id,
            'special_one'=>1,
            'special_two'=>2,
            'special_three'=>3,
            'extend_exist_rule'=>null,
            'one'=>null,
            'two'=>null,
            'three'=>null,
            'four'=>null,
            'five'=>null,
            'operator'=>null,
            'total'=>null,
            'status'=>2
            ]);
        $item =Item::create([
            'itemname' => '輸贏平',
            'rate' => 5,
            'limit_amount' => 10000
        ]);
        Itemrule::create([
            'item_id'=>$item->id,
            'special_one'=>null,
            'special_two'=>null,
            'special_three'=>null,
            'extend_exist_rule'=>'2,1,4',
            'one'=>null,
            'two'=>null,
            'three'=>null,
            'four'=>null,
            'five'=>null,
            'operator'=>null,
            'total'=>null,
            'status'=>4
            ]);
            $setItemname =redisGetSet::getInstance();
            $setItemname->setItemname();
    }
}
