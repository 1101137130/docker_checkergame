<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CheckersClass\amountClass;
class AmountController extends Controller
{
    protected static $amount;
    public function __construct()
    {
        $this->middleware('auth');
        $this::$amount = amountClass::getInstance();
    }
    
    public function takeMoney()
    {
        return $this::$amount->takeMoney();
    }
    public function amount()
    {
        return $this::$amount->amount();
    }
    public function getAmount()
    {
        return $this::$amount->getAmount();
    }
    public function store(Request $request)
    {
        return $this::$amount->store($request);
    }
}
