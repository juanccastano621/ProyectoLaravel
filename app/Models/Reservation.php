<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'space_id', 'slug', 'start_time', 'end_time', 
        'status', 'user_name', 'user_email', 'notes'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($reservation) {
            $reservation->slug = (string) Str::uuid();
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}