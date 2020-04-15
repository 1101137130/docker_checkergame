<?php

namespace App\CheckersClass;

use App\Itemrule;
use Exception;
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
}
