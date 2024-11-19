@extends('layouts.app')

@section('title', 'Events')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">Events</h1>
            {{-- <a href="{{ route('events.create') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                Create Event
            </a> --}}
        </div>

        <div class="bg-gray-50 px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($events as $event)
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        @if ($event->image_path)
                            <img src="{{ Storage::url($event->image_path) }}" alt="{{ $event->title }}"
                                class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400">No image available</span>
                            </div>
                        @endif
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                {{ $event->title }}
                            </h3>
                            <div class="mt-2 max-w-xl text-sm text-gray-500">
                                <p>{{ Str::limit($event->description, 100) }}</p>
                            </div>
                            <div class="mt-3 text-sm">
                                <p class="text-gray-600">
                                    <i class="fas fa-calendar"></i>
                                    {{ $event->start_date->format('M d, Y h:i A') }}
                                </p>
                                <p class="text-gray-600">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ $event->venue }}
                                </p>
                                <p class="text-gray-600">
                                    <i class="fas fa-ticket"></i>
                                    ₹{{ number_format($event->price, 2) }}
                                </p>
                            </div>
                            <div class="mt-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if ($event->status === 'published') bg-green-100 text-green-800
                            @elseif($event->status === 'draft') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-4 sm:px-6">
                            <div class="flex justify-end space-x-3">
                                @auth
                                    @if (Auth::user()->isAdmin())
                                        <a href="{{ route('events.edit', $event) }}"
                                            class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    @endif
                                @endauth
                                {{-- <a href="{{ route('events.edit', $event) }}"
                                    class="text-indigo-600 hover:text-indigo-900">Edit</a> --}}
                                <a href="{{ route('events.show', $event) }}"
                                    class="text-gray-600 hover:text-indigo-900">View
                                    Details</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                No events found
                            </h3>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $events->links() }}
            </div>
        </div>
    </div>
@endsection
