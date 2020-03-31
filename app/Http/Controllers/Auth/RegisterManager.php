<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterManager extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

    }

    public function update(Request $request)
    {
        $user = User::fund($request->id);

        try {
            $user->update([
                'view_orders' => 1,
                'manager_editor' => 1,
                'manage_rate' => 1,
                'deposit_able' => 1,
                'order_amount_arrangement' => 1,
            ]);
            // $request->session()->flash('error', $data[1]);

            return redirect('home');
        } catch (Exception $error) {
            throw $error;
        }
    }

}
