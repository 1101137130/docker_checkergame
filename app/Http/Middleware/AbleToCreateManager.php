<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AbleToCreateManager
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        
        if ($user['manager_editor'] == 1) {
            return $next($request);
        }
        $request->session()->flash('error', '您不能新增管理者');
        return redirect('/home');
    }
}
