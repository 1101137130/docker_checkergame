<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AbleToEditItem
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    
    public function handle($request, Closure $next )
    {
        
        $user = Auth::user();
        
        if($user['manage_rate'] == 1){
            return $next($request);
        }
        $request->session()->flash('error', '您不是管理員');
        return redirect('/home');
    }
}
