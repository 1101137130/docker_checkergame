<?php

namespace App\CheckersClass;

class gameStart
{

    public function start()
    {
        $banker = array(1, 2, 3, 4, 5);
        $player = array(1, 2, 3, 4, 5);
        shuffle($banker);
        shuffle($player);

        $result = array();

        for ($i = 0; $i <= 2; $i++) {
            //$result[$i] = $this->compare($banker[$i], $player[$i]);
            $result[$i] = $this->compare($banker[$i], $player[$i]);
        }

        $finalresult = $this->getResult($result);
        
        array_push($result, $finalresult); //將結果一起併入回傳陣列

        return $result;
    }

    public function compare($banker, $player)
    {
        if ($banker == $player) {
            $result = array($banker, $player, 3);   //平手 設為3

            return $result;
        }

        if ($banker == 5 && $player == 1) {
            $result = array($banker, $player, 2);   //閒家 贏設為2

            return $result;
        }

        if ($banker == 1 && $player == 5) {
            $result = array($banker, $player, 1);   //莊家 贏設為1

            return $result;
        }

        if ($banker > $player) {
            $result = array($banker, $player, 1);   //莊家 贏設為1

            return $result;
        }

        if ($banker < $player) {
            $result = array($banker, $player, 2);   //閒家 贏設為2

            return $result;
        }
    }

    public function getResult($result) //取得總賽果
    {
        $bankercount = 0;                   
        $playercount = 0;
        for ($i = 0; $i <= 2; $i++) {
            if ($result[$i][2] == 1) {
                $bankercount++;
            }
            if ($result[$i][2] == 2) {
                $playercount++;
            }
        }
        
        if ($bankercount >= 2) {

            return 1;
        } elseif ($playercount >= 2) {

            return 2;
        } else {

            return 3;
        }
    }
    
    // public function compare($ob1, $ob2)
    // {
    //     $arrayob = array(
    //         'object1' => array('card' => $ob1),
    //         'object2' => array('card' => $ob2)
    //     );
    //     if ($ob1 == $ob2) {
    //         $result = array('result' => '平手');
    //         array_push($arrayob, $result);

    //         return $arrayob;
    //     }
    //     if ($ob1 == 1 && $ob2 == 5) {
    //         $result = array('result' => '莊家');
    //         array_push($arrayob, $result);

    //         return $arrayob;
    //     } elseif ($ob1 == 5 && $ob2 == 1) {
    //         $result = array('result' => '閒家');
    //         array_push($arrayob, $result);

    //         return $arrayob;
    //     } else {
    //         if ($ob1 - $ob2 > 0) {
    //             $result = array('result' => '莊家');
    //             array_push($arrayob, $result);

    //             return $arrayob;
    //         } else {
    //             $result = array('result' => '閒家');
    //             array_push($arrayob, $result);

    //             return $arrayob;
    //         }
    //     }
    // }
}
