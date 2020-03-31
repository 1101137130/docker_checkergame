<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterManager extends Controller
{
    use RegistersUsers;

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function showRegistrationForm()
    {
        return view('auth.managerRegister');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|string|max:20|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }
    public function editUser($id, $item, $data)
    {
        $user = User::find($id)->first();
        try {
            $user->update([$item => $data]);
            return true;
        } catch (Exception $error) {
            throw $error;
        }
    }
    public function getUser()
    {
        return json_encode(Auth::user(), true);
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
