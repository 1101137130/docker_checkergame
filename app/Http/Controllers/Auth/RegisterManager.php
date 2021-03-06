<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\checkersValidator;

class RegisterManager extends Controller
{
    public function __construct()
    {
        $this->middleware('ManagerCreator');
    }

    public function showRegistrationForm()
    {
        return view('auth.managerRegister');
    }

    protected function validator(array $data)
    {
        $checkersValidator = new checkersValidator();
        return Validator::make($data, [
            'username' => 'required|string|max:20|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ],$checkersValidator->messages());
    }

    public function createManager(Request $data)
    {
        $this->validator($data->all())->validate();
        try {
            User::create([
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'status' => 1,
                'view_orders' => in_array('view_orders', $data['authorities'])? 1:0,
                'manager_editor' => in_array('manager_editor', $data['authorities'])? 1:0,
                'manage_rate' => in_array('manage_rate', $data['authorities'])? 1:0,
                'deposit_able' => in_array('deposit_able', $data['authorities'])? 1:0,
                'order_amount_arrangement' => in_array('order_amount_arrangement', $data['authorities'])? 1:0,
            ]);
            $data->session()->flash('status', '建立成功！');

            return redirect('home');
        } catch (Exception $error) {
            throw $error;
        }
    }
}
