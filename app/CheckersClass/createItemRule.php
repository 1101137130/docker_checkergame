<?php

namespace App\CheckersClass;

use Illuminate\Http\Request;
use App\Item;
use App\Itemrule;

class createItemRule
{
    private static $_instance  = null ;
   
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function create(Request $request, $itemid)
    {
        $status = (int)$request->status;
        $special_cards = null;
        $one = null;
        $two  = null;
        $three  = null;
        $four  = null;
        $five  = null;
        $operator = null;
        $total = null;
        $extend_exist_rule = null;
     
        if ($status == 2) {
            $arrayCards = [];
            array_push($arrayCards, $request->specialCards1);
            array_push($arrayCards, $request->specialCards2);
            array_push($arrayCards, $request->specialCards3);
                
            $special_cards = $this->dataConverter($arrayCards);
        }

        if ($status == 1) {
            $one = $this->dataConverter($request->winRequire1);
            $two = $this->dataConverter($request->winRequire2);
            $three = $this->dataConverter($request->winRequire3);
            $four = $this->dataConverter($request->winRequire4);
            $five = $this->dataConverter($request->winRequire5);
        }
           
        if ($status == 3) {
            $operator = $request->operator;
            $total = $request->total;
        }
        if ($status == 4) {
            $selectFirst = $request->selectFirst;
            $selectSecond = $request->selectSecond;
            $selectThird = $request->selectThird;
            $extend_exist_rule = $selectFirst.','.$selectSecond.','.$selectThird ;
        }
        Itemrule::create([
            'item_id'=>$itemid,
            'special_cards'=>$special_cards,
            'extend_exist_rule'=>$extend_exist_rule,
            'one'=>$one,
            'two'=>$two,
            'three'=>$three,
            'four'=>$four,
            'five'=>$five,
            'operator'=>$operator,
            'total'=>$total,
            'status'=>$status
            ]);
    }
    public function dataConverter($data)
    {
        $temp =1;
        $result =0;
       
        for ($i = sizeof($data)-1 ; $i>=0 ; $i--) {
            $result=$result+(int)$data[$i]*$temp;
            $temp=$temp*10;
        }
        
        return $result;
    }
}
