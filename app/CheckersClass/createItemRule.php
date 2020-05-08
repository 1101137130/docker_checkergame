<?php

namespace App\CheckersClass;

use Illuminate\Http\Request;
use App\Itemrule;
use Exception;

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
        $status = (int)$request->typeStatus;
        $special_one = null;
        $special_two = null;
        $special_three = null;
        $one = null;
        $two  = null;
        $three  = null;
        $four  = null;
        $five  = null;
        $operator = null;
        $total = null;
        $extend_exist_rule = null;
        switch ($status) {
            case 1:
            $one = $this->dataConverter($request->winRequire1);
            $two = $this->dataConverter($request->winRequire2);
            $three = $this->dataConverter($request->winRequire3);
            $four = $this->dataConverter($request->winRequire4);
            $five = $this->dataConverter($request->winRequire5);
                break;
            case 2:
            $special_one = $this->dataConverter($request->specialCards1);
            $special_two = $this->dataConverter($request->specialCards2);
            $special_three = $this->dataConverter($request->specialCards3);
                break;
            case 3:
                $operator = $request->operator;
                $total = $request->total;
                break;
            case 4:
            $extend_exist_rule = $request->selectFirst.','.$request->selectSecond.','.$request->selectThird ;
                break;
        }
        try {
            Itemrule::create([
            'item_id'=>$itemid,
            'special_one'=>$special_one,
            'special_two'=>$special_two,
            'special_three'=>$special_three,
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
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function dataConverter($data)
    {
        $temp =1;
        $result =0;
        $t = sizeof($data)-1;
        for ($i = $t ; $i>=0 ; $i--) {
            $result=$result+(int)$data[$i]*$temp;
            $temp=$temp*10;
        }
        
        return $result;
    }
}
