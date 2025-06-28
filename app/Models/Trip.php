<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'latitude',
        'longitude',
        'location',
        'date',
        'notes',
        'precipitation',
        'moon_phase',
        'wind_speed',
        'air_temp',
        'wind_direction',
        'action',
    ];

    protected $casts = [
        'date' => 'date',
        'latitude' => 'float',
        'longitude' => 'float',
        'precipitation' => 'float',
        'moon_phase' => 'string',
        'wind_speed' => 'float',
        'air_temp' => 'float',
        'wind_direction' => 'string',
    ];

    public function catches()
    {
        return $this->hasMany(Catches::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(TripImage::class);
    }
    public function getMainImageUrlAttribute()
    {
        $image = $this->images()->first();
        if ($image) {
            return Storage::url($image->image_path); // assumes 'public' disk
        }
        return null;
    }
}