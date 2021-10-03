<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositStatus extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'name'
    ];

    //Getter (statics)

    static function getForPending(){
        return 1;
    }

    static function getForAccepted(){
        return 2;
    }

    static function getForRejected(){
        return 3;
    }
}
