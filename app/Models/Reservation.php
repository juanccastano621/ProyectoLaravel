<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'space_id',
        'slug',
        'start_time',
        'end_time',
        'status',
        'user_name',
        'user_email',
        'notes'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($reservation) {

            if (!$reservation->slug) {
                $reservation->slug = (string) Str::uuid();
            }

            if (!$reservation->status) {
                $reservation->status = 'pendiente';
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function space()
    {
        return $this->belongsTo(Space::class);
    }
}