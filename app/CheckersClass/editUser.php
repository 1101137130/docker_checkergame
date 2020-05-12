<?php
namespace App\CheckersClass;

use App\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegisterManager;

class editUser extends RegisterManager
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    private static $_instance  = null ;
   
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
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
