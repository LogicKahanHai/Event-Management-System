@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="bg-white shadow-lg rounded-lg">
            <div class="bg-indigo-600 text-white text-xl font-bold p-4 rounded-t-lg">
                {{ __('Dashboard') }}
            </div>

            <div class="p-6">
                @if (session('status'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <p class="text-gray-700">{{ __('You are logged in!') }}</p>
            </div>
        </div>
    </div>
@endsection
