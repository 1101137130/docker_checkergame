<?php

namespace App;

use App\Http\Controllers\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'status',
        'view_orders',
        'manager_editor',
        'manage_rate',
        'deposit_able',
        'order_amount_arrangement',
    ];
    //把時間格式進行轉換
    protected function getDateFormat()
    {
        return 'U';
    }

    protected $hidden = [
        'password', 'remember_token',
    ];
}
