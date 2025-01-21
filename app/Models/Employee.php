<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //
    protected $fillable = [
        'name',
        'status',
        'job_id',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
