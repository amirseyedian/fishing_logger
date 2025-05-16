<?php

namespace App\Models;
use App\Models\Catches;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    public function catches()
{
    return $this->hasMany(Catches::class);
}
    protected $fillable = [
        'user_id',
        'title',
        'location',
        'date',
        'notes',
        'weather_info',
    ];
}
