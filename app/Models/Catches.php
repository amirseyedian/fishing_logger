<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catches extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'species',
        'bait',
        'quantity',
        'weight',
        'length',
        'depth',
        'water_temp',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'weight' => 'float',
        'length' => 'float',
        'depth' => 'float',
        'water_temp' => 'float',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}