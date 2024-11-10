<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    //
    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'ticket_quantity' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) use ($event) {
                    if ($event->capacity && $value > $event->availableSpots()) {
                        $fail('Not enough tickets available.');
                    }
                },
            ],
            'special_requests' => 'nullable|string|max:500',
            'event_id' => 'required|exists:events,id',
        ]);

        // Check if user is already registered
        if ($event->isRegisteredByUser(auth()->id())) {
            return back()->with('error', 'You are already registered for this event.');
        }

        // Calculate total amount
        $totalAmount = $event->price * $validated['ticket_quantity'];

        // Create registration
        $registration = $event->registrations()->create([
            'user_id' => auth()->id(),
            'event_id' => $event->id,
            'ticket_quantity' => $validated['ticket_quantity'],
            'total_amount' => $totalAmount,
            'special_requests' => $validated['special_requests'],
            'status' => 'confirmed'
        ]);

        return redirect()->route('events.show', $event)
            ->with('success', 'Successfully registered for the event!');
    }

    public function destroy(Event $event)
    {
        $event->registrations()->where('user_id', auth()->id())->delete();

        return redirect()->route('events.show', $event)
            ->with('success', 'Successfully unregistered from the event.');
    }
}
