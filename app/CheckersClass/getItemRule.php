<?php

namespace App\CheckersClass;

use App\Itemrule;
use Exception;
use Illuminate\Support\Facades\DB;

class getItemRule
{
    private static $_instance  = null ;
   
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    public function getitemRules($itemid)
    {
        try {
            $itemRule = Itemrule::where('item_id', $itemid)->first();
        
            return $itemRule;
        } catch (Exception $errors) {
            throw $errors->getMessage();
        }
    }
    public function getItemRuleIdName()
    {
        $restult = DB::table('itemrules')
            ->join('items', 'items.id', '=', 'itemrules.item_id')
            ->select('itemrules.id', 'items.itemname')
            ->where('itemrules.status', '=', 1)
            ->get();
            
        return $restult;
    }
}
