<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'type_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //functions
    function updateBalance($amount,$action){
        
        switch ($action) {
            case '+':
                $this->balance += $amount;
                $this->save();
                return true;
            case '-':
                if ($amount > $this->balance) {
                    return false; // not enough $$$
                }
                $this->balance -= $amount;
                $this->save();
                return true;
            default:
                # code...
                break;
        }
        return false; // unknow erro
        
    }
}
