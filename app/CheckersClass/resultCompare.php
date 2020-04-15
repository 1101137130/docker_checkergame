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
        
        if ($itemrule->status == 1) {
            return $this->singleCompare((int)$item[4]-1, $result, $itemrule);
        }
        if ($itemrule->status == 2) {
            return $this->specialCards((int)$item[4]-1, $result, $itemrule->special_cards);
        }
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
    public function singleCompare($objectClient, $result, $itemrule)
    {
        $objectOpponet = $objectClient == 1? 0 : 1;
        $resultCount = 0;
        
        for ($i = 0 ;$i <=2 ;$i++) {
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
        if ($resultCount >=2) {
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
