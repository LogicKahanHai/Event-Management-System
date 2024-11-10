@extends('layouts.app')

@section('title', 'Create Event')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h1 class="text-2xl font-bold text-gray-900">Create New Event</h1>
        </div>

        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                        class="p-3 mt-1 block w-full rounded-md border border-gray-500 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="4"
                        class="p-3 mt-1 block w-full rounded-md border border-gray-500 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date & Time</label>
                        <input type="datetime-local" name="start_date" id="start_date" value="{{ old('start_date') }}"
                            class="p-3 mt-1 block w-full rounded-md border border-gray-500 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700">End Date & Time</label>
                        <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date') }}"
                            class="p-3 mt-1 block w-full rounded-md border border-gray-500 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="venue" class="block text-sm font-medium text-gray-700">Venue</label>
                    <input type="text" name="venue" id="venue" value="{{ old('venue') }}"
                        class="p-3 mt-1 block w-full rounded-md border border-gray-500 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('venue')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity</label>
                        <input type="number" name="capacity" id="capacity" value="{{ old('capacity', 0) }}"
                            class="p-3 mt-1 block w-full rounded-md border border-gray-500 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('capacity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                        <input type="number" step="0.01" name="price" id="price" value="{{ old('price', 0) }}"
                            class="p-3 mt-1 block w-full rounded-md border border-gray-500 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700">Event Image</label>
                    <input type="file" name="image" id="image"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Create Event
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
