<?php

namespace App\CheckersClass;

use App\Resultrecord;
use Exception;

class createGameResultRecord
{
    private static $_instance  = null ;
   
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    public function create($orderID, $result)
    {
        $dataConverter = createItemRule::getInstance();
        $banker = [];
        $player = [];
        $gameResult=[];
        for ($i = 0 ; $i<=2 ; $i++) {
            array_push($banker, $result[$i][0]);
            array_push($player, $result[$i][1]);
            array_push($gameResult, $result[$i][2]);
        }
        $banker = $dataConverter->dataConverter($banker);
        $player = $dataConverter->dataConverter($player);
        $gameResult = $dataConverter->dataConverter($gameResult);
        try {
            $record = Resultrecord::create([
            'order_id'=>$orderID,
            'banker'=>$banker,
            'player'=>$player,
            'result'=>$gameResult
        ]);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
