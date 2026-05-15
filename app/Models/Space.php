<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Space extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'capacity',
        'type',
        'price_per_hour',
        'image_path',
        'is_active'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }

    public function blockedSlots()
    {
        return $this->hasMany(BlockedSlot::class);
    }
}