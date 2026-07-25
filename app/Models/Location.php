<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
        'category',
        'wifi_speed_mbps',
        'noise_level',
        'outlet_density',
        'description',
        'image_url',
        'weather_summary',
        'weather_icon',
        'weather_temp',
        'created_by_user_id',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'wifi_speed_mbps' => 'integer',
        'weather_temp' => 'float',
    ];

    protected $appends = [
        'average_rating',
    ];

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews->avg('rating') ?? 4.5, 1);
    }
}
