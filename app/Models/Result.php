<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = [
        'year',
        'day',
        'step',
        'result',
        'time_taken_ms',
    ];
}
