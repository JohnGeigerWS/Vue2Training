<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'descr', 'completed',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'completed' => 'boolean',
    ];
}
