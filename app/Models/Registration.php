<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
        'registration_number',
        'status',
        'ticket_quantity',
        'total_amount',
        'special_requests'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($registration) {
            $registration->registration_number = 'REG-' . strtoupper(uniqid());
        });
    }
}
