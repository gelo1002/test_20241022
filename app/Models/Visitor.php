<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'visitors';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'date_first_visit',
        'date_last_visit',
        'total_visits',
        'visits_current_year',
        'visits_current_month',
        'current_year',
        'current_month',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];
}
