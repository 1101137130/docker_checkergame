<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Requests\checkersValidator;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | valation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
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
    
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

  
    protected function create(array $data)
    {
        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'status' => 1
        ]);
        if($user->id == 1){
            Cache::store("redis")->flush();
            $user->update([
                'view_orders' => 1,
                'manager_editor' => 1,
                'manage_rate' => 1,
                'deposit_able' => 1,
                'order_amount_arrangement' => 1]);
        }
        return $user;
    }
}
