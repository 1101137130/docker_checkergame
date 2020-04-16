<?php
namespace App\CheckersClass;

use Illuminate\Support\Facades\DB;
use App\Itemrule;

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
        
        if ($itemrule->status == 1) {
            return $this->singleCompare((int)$item[4]-1, $result, $itemrule);
        }
        if ($itemrule->status == 2) {
            return $this->specialCards((int)$item[4]-1, $result, $itemrule->special_cards);
        }
        if ($itemrule->status == 3) {
            return $this->totalCompare((int)$item[4]-1, $result, $itemrule);
        }
        if ($itemrule->status == 4) {
            return $this->extendCompare((int)$item[4]-1, $result, $itemrule);
        }
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
            if ($operator == 0) {
                $r = $countTotal == $goalTotal ? true : false;
            }

            if ($operator == 1) {
                $r = $countTotal < $goalTotal ? true : false;
            }

            if ($operator == 2) {
                $r = $countTotal <= $goalTotal ? true : false;
            }

            if ($operator == 3) {
                $r = $countTotal > $goalTotal ? true : false;
            }

            if ($operator == 4) {
                $r = $countTotal >= $goalTotal ? true : false;
            }
            if ($r) {
                return true;
            }
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
        if($looptime == 0){
            $looptime =1;
        }
        for ($i = $start ;$i <= $compareTime ;$i++) {
            if ($result[$i][$objectOpponet] == 1) {
                $r = $this->singleCompareFunction($result[$i][$objectClient], $itemrule->one);
                $r == true ? $resultCount++ :'';
            }
            if ($result[$i][$objectOpponet] == 2) {
                $r = $this->singleCompareFunction($result[$i][$objectClient], $itemrule->two);
                $r == true ? $resultCount++ :'';
            }
            if ($result[$i][$objectOpponet] == 3) {
                $r = $this->singleCompareFunction($result[$i][$objectClient], $itemrule->three);
                $r == true ? $resultCount++ :'';
            }
            if ($result[$i][$objectOpponet] == 4) {
                $r = $this->singleCompareFunction($result[$i][$objectClient], $itemrule->four);
                $r == true ? $resultCount++ :'';
            }
            if ($result[$i][$objectOpponet] == 5) {
                $r =$this->singleCompareFunction($result[$i][$objectClient], $itemrule->five);
                $r == true ? $resultCount++ :'';
            }
        }
        
        if ($resultCount >= $looptime) {
            return true;
        } else {
            return false;
        }
    }
    public function specialCards($objectClient, $result, $specialcards)
    {
        $countCards=0;
        $countCards = $result[0][$objectClient]*100 + $result[1][$objectClient]*10 +$result[2][$objectClient];
        $r = $countCards == $specialcards ? true : false;

        return $r ;
    }
}
