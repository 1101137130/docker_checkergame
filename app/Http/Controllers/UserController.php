<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CheckersClass\editUser;
use Illuminate\Support\Facades\Auth;
use App\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getUser()
    {
        return json_encode(Auth::user(), true);
    }
    
    public function editUser(Request $request)
    {
        $editUser = editUser::getInstance();
        return $editUser->editUser($request);
    }
    
}
