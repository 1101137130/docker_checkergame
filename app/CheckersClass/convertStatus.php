<?php
namespace App\CheckersClass;

class convertStatus
{
    public function convertWinLostStatus($status)
    {
        if($status == 'win'){
            return $status = 2;
        }
        if($status == 'lost'){
            return $status = 3;
        }
    }
}