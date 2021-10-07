<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Deposit extends Model
{
    use HasFactory;

    protected $with = ['user'];

    /**
     * Get the user that owns the deposit.
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
