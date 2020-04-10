<?php
namespace App\CheckersClass;

class compareResults
{
    private static $_instance  = null ;
   
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    public function total($item, $result)
    {
        $re = $this->find($item[0]);
        if ($re != null) {
            $re = $re->totalResult($item, $result);
            return $re ;
        }
    }
    public function oneByone($item, $result)
    {
        $countResult=0;
        $j=0;
        for ($i=0;$i<2;$i++) {
            $spilt=substr($item[0], $j, $j+3);
            $re = $this->find($spilt);
            if ($re != null) {
                $re = $re->gResult($item, $result[$i]);
                $re == true ? $countResult++ :'' ;
            }
            $j+=3;
        }
        self::$_instance = null;
    
        return $countResult;
    }
    public function find($spilt)
    {
        if ($spilt =='贏') {
            $re=win::getInstance();

            return $re;
        }
        if ($spilt =='輸') {
            $re=lost::getInstance();
            
            return $re;
        }
        if ($spilt =='雙') {
            $re=double::getInstance();
            
            return $re;
        }
        if ($spilt =='單') {
            $re=single::getInstance();
            
            return $re;
        }
        if ($spilt =='大') {
            $re=big::getInstance();
            
            return $re;
        }
        if ($spilt =='小') {
            $re=small::getInstance();
            
            return $re;
        }
        if ($spilt =='平') {
            $re=draw::getInstance();
            
            return $re;
        }
    }
}
