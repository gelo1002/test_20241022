<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statistics extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'statistics';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'visitor_id',
        'jyv',
        'badmail',
        'disable',
        'date_shipment',
        'date_open',
        'opens',
        'opens_viral',
        'date_click',
        'clicks',
        'clicks_viral',
        'links',
        'ips',
        'browsers',
        'platforms'
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
