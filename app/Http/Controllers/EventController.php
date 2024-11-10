<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        if (auth()->user() !== null && auth()->user()->isAdmin) {
            $events = Event::orderBy('start_date', 'asc')->paginate(10);
        } else {
            $events = Event::where('status', 'published')
                ->orderBy('start_date', 'asc')
                ->paginate(10);
        }
        return view('events.index', compact('events'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin) {
            return redirect()->route('events.index')
                ->with('error', 'You are not authorized to create events.');
        }
        return view('events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'venue' => 'required',
            'capacity' => 'nullable|integer|min:1',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            $validated['image_path'] = $path;
        }

        Event::create($validated);

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully.');
    }
    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    public function register(Request $request, Event $event)
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
        ]);

        // Check if user is already registered
        if ($event->isRegisteredByUser(auth()->id())) {
            return back()->with('error', 'You are already registered for this event.');
        }

        // Calculate total amount
        $totalAmount = $event->price * $validated['ticket_quantity'];

        dd($event);
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

    public function edit(Event $event)
    {
        if (!auth()->user()->isAdmin) {
            return redirect()->route('events.index')
                ->with('error', 'You are not authorized to edit events.');
        }
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'venue' => 'required',
            'capacity' => 'nullable|integer|min:1',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            $validated['image_path'] = $path;
        }

        $event->update($validated);

        return redirect()->route('events.index')
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        if (!auth()->user()->isAdmin) {
            return redirect()->route('events.index')
                ->with('error', 'You are not authorized to delete events.');
        }
        $event->delete();
        $event->registrations()->delete();
        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully.');
    }

    public function publish(Event $event)
    {
        if (!auth()->user()->isAdmin) {
            return redirect()->route('events.index')
                ->with('error', 'You are not authorized to publish events.');
        }
        $event->update(['status' => 'published']);
        return redirect()->route('events.index')
            ->with('success', 'Event published successfully.');
    }

    public function archive(Event $event)
    {
        if (!auth()->user()->isAdmin) {
            return redirect()->route('events.index')
                ->with('error', 'You are not authorized to archive events.');
        }
        $event->update(['status' => 'archived']);
        return redirect()->route('events.index')
            ->with('success', 'Event archived successfully.');
    }

}
