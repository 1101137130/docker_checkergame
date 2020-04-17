<?php
namespace App\CheckersClass;

use Illuminate\Support\Facades\DB;

class resultCompare
{
    private static $_instance  = null ;
   
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    public function compare($item, $result)
    {
        $itemrule = DB::table('itemrules')->where('item_id', $item[1])->first();
        switch ($itemrule->status) {
            case 1:
            $r = $this->singleCompare((int)$item[4]-1, $result, $itemrule);
                break;
            case 2:
            $r = $this->specialCards((int)$item[4]-1, $result, $itemrule);
                break;
            case 3:
            $r = $this->totalCompare((int)$item[4]-1, $result, $itemrule);
                break;
            case 4:
            $r = $this->extendCompare((int)$item[4]-1, $result, $itemrule);
                break;
        }

        return $r;
    }
    public function extendCompare($objectClient, $result, $itemrule)
    {
        $existRulesIDArray = explode(",", $itemrule->extend_exist_rule);
        $itemrules = DB::table('itemrules')->whereIn('id', $existRulesIDArray)->get();
        $i =0;
        $countResult = 0;
        foreach ($itemrules as $rule) {
            $r = $this->singleCompare($objectClient, $result, $rule, $i, $i);
            $i++;
            if ($r) {
                $countResult ++;
            }
        }
        return $countResult == 3? true :false;
    }
    public function totalCompare($objectClient, $result, $itemrule)
    {
        // operator->0:= , 1:< , 2:<=, 3:> , 4:>=
        $operator = (int)$itemrule->operator;
        
        $goalTotalArray = explode(",", $itemrule->total);
        $countTotal = 0;
        for ($i =0 ; $i<=2 ; $i++) {
            $countTotal = $countTotal + $result[$i][$objectClient];
        }
        foreach ($goalTotalArray as $goalTotal) {
            switch ($operator) {
                case 0:
                $r = $countTotal == $goalTotal;
                    break;
                case 1:
                $r = $countTotal < $goalTotal;
                    break;
                case 2:
                $r = $countTotal <= $goalTotal;
                    break;
                case 3:
                $r = $countTotal > $goalTotal;
                    break;
                case 4:
                $r = $countTotal >= $goalTotal;
                    break;
            }
            return $r;
        }
        return false;
    }
    public function singleCompareFunction($clientResult, $data)
    {
        for ($i = 1000 ; $i >= 1 ; $i=$i/10) {
            if ((int)$data / $i != 0) {
                $r = $clientResult == (int)($data / $i)? true :false;
                if ($r) {
                    return true;
                }
                $data = $data % $i;
            }
        }
        return false;
    }
    public function singleCompare($objectClient, $result, $itemrule, $start =0, $compareTime =2)
    {
        $objectOpponet = $objectClient == 1? 0 : 1;
        $resultCount = 0;
        $looptime = $compareTime - $start ;
        if ($looptime == 0) {
            $looptime =1;
        }
        $array =[ $itemrule->one, $itemrule->two , $itemrule->three, $itemrule->four, $itemrule->five];

        for ($i = $start ;$i <= $compareTime ;$i++) {
            $r = $this->singleCompareFunction($result[$i][$objectClient], $array[$result[$i][$objectOpponet]-1]);
            $r == true ? $resultCount++ :'';
        }
        
        if ($resultCount >= $looptime) {
            return true;
        } else {
            return false;
        }
    }
    public function specialCards($objectClient, $result, $itemrule)
    {
        $resultCount = 0;

        $r = $this->singleCompareFunction($result[0][$objectClient], $itemrule->special_one);
        $r == true ? $resultCount++ :'';
        $r = $this->singleCompareFunction($result[1][$objectClient], $itemrule->special_two);
        $r == true ? $resultCount++ :'';
        $r = $this->singleCompareFunction($result[2][$objectClient], $itemrule->special_three);
        $r == true ? $resultCount++ :'';

        return $resultCount==3 ? true :false ;
    }
}
