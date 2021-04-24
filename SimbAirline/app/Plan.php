<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'id',
        'routing',
        'pax',
        'date',
        'from',
        'etd',
        'eta',
        'to',
        'eet',
        'ground_time',
        'night_stop',
        'routing_nature1',
        'routing_nature2',
        'call_sign',
        'flight_number'
    ];

    protected $table = 'plan';
}
