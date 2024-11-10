@extends('layouts.app')

@section('title', $event->title)

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <!-- Event Header -->
        <div class="relative">
            @if ($event->image_path)
                <div class="h-96 w-full overflow-hidden">
                    <img src="{{ Storage::url($event->image_path) }}" alt="{{ $event->title }}"
                        class="w-full h-full object-cover">
                </div>
            @else
                <div class="h-96 w-full bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-400 text-lg">No image available</span>
                </div>
            @endif

            <!-- Event Status Badge -->
            <div class="absolute top-4 right-4">
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                @if ($event->status === 'published') bg-green-100 text-green-800
                @elseif($event->status === 'draft') bg-yellow-100 text-yellow-800
                @else bg-red-100 text-red-800 @endif">
                    {{ ucfirst($event->status) }}
                </span>
            </div>
        </div>

        <!-- Event Information -->
        <div class="px-4 py-5 sm:px-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $event->title }}</h1>
                    <p class="mt-2 text-sm text-gray-500">
                        Created {{ $event->created_at->diffForHumans() }}
                    </p>
                </div>
                @auth
                    @if (Auth::user()->isAdmin())
                        <div class="flex space-x-3">
                            <a href="{{ route('events.edit', $event) }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Edit Event
                            </a>
                            <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700"
                                    onclick="return confirm('Are you sure you want to delete this event?')">
                                    Delete Event
                                </button>
                            </form>
                            @if ($event->status === 'draft')
                                <form action="{{ route('events.publish', $event) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700"
                                        onclick="return confirm('Are you sure you want to publish this event?')">
                                        Publish Event
                                    </button>
                                </form>
                            @elseif($event->status === 'published')
                                <form action="{{ route('events.archive', $event) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700"
                                        onclick="return confirm('Are you sure you want to archive this event?')">
                                        Archive Event
                                    </button>
                            @endif
                        </div>
                    @endif
                @endauth
            </div>
        </div>

        <!-- Event Details Grid -->
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="mt-1 text-sm text-gray-900 space-y-4">
                        {!! nl2br(e($event->description)) !!}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Date & Time</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <div class="space-y-1">
                            <p>
                                <span class="font-medium">Starts:</span>
                                {{ $event->start_date->format('M d, Y @ h:i A') }}
                            </p>
                            <p>
                                <span class="font-medium">Ends:</span>
                                {{ $event->end_date->format('M d, Y @ h:i A') }}
                            </p>
                            <p class="text-sm text-gray-500">
                                Duration: {{ $event->start_date->diffForHumans($event->end_date, true) }}
                            </p>
                        </div>
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Venue</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $event->venue }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Capacity</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        @if ($event->capacity)
                            {{ number_format($event->capacity) }} attendees
                        @else
                            No Limit
                        @endif
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Price</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        @if ($event->price > 0)
                            ₹{{ number_format($event->price, 2) }}
                        @else
                            Free
                        @endif
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Registration/Action Section -->
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    @if ($event->capacity)
                        @if ($event->availableSpots() > 0)
                            <p>{{ number_format($event->availableSpots()) }} spots available</p>
                        @else
                            <p class="text-red-600">Event is fully booked</p>
                        @endif
                    @endif
                </div>
                @auth
                    @if (!$event->isRegisteredByUser(auth()->id()))
                        @if ($event->availableSpots() > 0 || !$event->capacity)
                            <form action="{{ route('registrations.store', ['event' => $event->id]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="ticket_quantity" value="1">
                                <input type="hidden" name="special_requests" value="">
                                <input type="hidden" name="event_id" value="{{ $event->id }}">
                                <button type="submit"
                                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                                    Register for Event
                                </button>
                            </form>
                        @else
                            <button type="button" disabled
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gray-400 cursor-not-allowed">
                                Event Full
                            </button>
                        @endif
                    @else
                        <span
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-green-700 bg-green-100">
                            Already Registered
                        </span>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                        Login to Register
                    </a>
                @endauth
            </div>
        </div>

        <!-- Registration Modal -->
        <div x-show="showRegistrationModal" class="fixed inset-0 z-10 overflow-y-auto" x-cloak>
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    @click="showRegistrationModal = false" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0">
                </div>

                <!-- Modal panel -->
                {{-- <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Register for {{ $event->title }}
                            </h3>

                            <div class="mt-4">
                                <form action="{{ route('events.register', $event) }}" method="POST">
                                    @csrf
                                    <div class="space-y-4">
                                        <div>
                                            <label for="ticket_quantity" class="block text-sm font-medium text-gray-700">
                                                Number of Tickets
                                            </label>
                                            <input type="number" name="ticket_quantity" id="ticket_quantity" min="1"
                                                @if ($event->capacity) max="{{ $event->availableSpots() }}" @endif
                                                value="1"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @error('ticket_quantity')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="special_requests" class="block text-sm font-medium text-gray-700">
                                                Special Requests
                                            </label>
                                            <textarea name="special_requests" id="special_requests" rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                            @error('special_requests')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        @if ($event->price > 0)
                                            <div class="bg-gray-50 px-4 py-3 rounded-md">
                                                <div class="flex justify-between">
                                                    <span class="text-sm text-gray-500">Price per ticket:</span>
                                                    <span
                                                        class="text-sm font-medium">${{ number_format($event->price, 2) }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                        <button type="submit"
                                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2">
                                            Confirm Registration
                                        </button>
                                        <button type="button" @click="showRegistrationModal = false"
                                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>

        <!-- You might want to add a related events section -->
        {{-- <div class="mt-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Other Upcoming Events</h2>
            <!-- Add related events here -->
        </div> --}}
    @endsection
