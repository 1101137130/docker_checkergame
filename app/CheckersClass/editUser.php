<?php
namespace App\CheckersClass;

use App\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegisterManager;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\checkersValidator;

class editUser extends RegisterManager
{
    private static $_instance  = null ;
   
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    protected function validatUser(array $data)
    {
        $checkersValidator = new checkersValidator();
        return Validator::make($data, [
            'username' => 'string|max:20|unique:users',
            'email' => 'string|email|max:255|unique:users',
        ],$checkersValidator->messages());
    }
    public function editUser(Request $request)
    {
        $this->validatUser($request->all())->validate();
        $user = User::find($request->id);
        
        if ($request->username == null) {
            $request->username = $user->username;
        }
        if ($request->email == null) {
            $request->email = $user->email;
        }
        try {
            $user->update($request->all());
            
            return redirect('home');
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
