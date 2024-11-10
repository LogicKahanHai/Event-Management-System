<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'venue',
        'capacity',
        'price',
        'image_path',
        'status'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'price' => 'decimal:2'
    ];

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function isRegisteredByUser($userId)
    {
        return $this->registrations()->where('user_id', $userId)->exists();
    }

    public function availableSpots()
    {
        if (!$this->capacity) {
            return null; // Unlimited capacity
        }

        $registeredCount = $this->registrations()
            ->where('status', '!=', 'cancelled')
            ->sum('ticket_quantity');

        return max(0, $this->capacity - $registeredCount);
    }

}
