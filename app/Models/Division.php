<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    //
    protected $fillable = [
        'name',
    ];


    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}
