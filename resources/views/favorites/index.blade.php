@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- @if(session('status'))
            <div class="fixed top-20 right-6 z-50 animate-slide-in">
                <div class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-xl flex items-center">
                    <svg class="w-6 h-6 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            </div>
        @endif --}}

        @if(session('status'))
        <div id="status-notification" class="fixed top-4 right-4 z-50 bg-green-100 border-green-400 text-green-700 px-6 py-3 rounded-lg shadow-lg transition-all duration-500 ease-in-out transform hover:scale-105">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">{{ session('status') }}</span>
            </div>
        </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h1 class="text-3xl font-bold mb-6 text-gray-800">Favorite Recipes</h1>

            @if($favorites->isEmpty())
                <div class="text-center py-10 bg-gray-100 rounded-lg">
                    <p class="text-xl text-gray-600">You don't have any favorite recipes</p>
                    <a href="{{ route('recipes.index') }}" 
                       class="mt-4 inline-block bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                        Find a Recipe
                    </a>
                </div>
            @else
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($favorites as $favorite)
                        <div class="bg-white shadow-md rounded-lg overflow-hidden transition hover:shadow-xl">
                            <img 
                                src="{{ $favorite->recipe_image }}" 
                                alt="{{ $favorite->recipe_title }}" 
                                class="w-full h-48 object-cover"
                            >
                            <div class="p-4">
                                <h2 class="text-xl font-semibold mb-2 truncate">
                                    {{ $favorite->recipe_title }}
                                </h2>
                                <div class="flex justify-between items-center mt-4">
                                    <a href="{{ route('favorites.show', $favorite->id) }}" 
                                       class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                        View Details
                                    </a>
                                    <form action="{{ route('favorites.remove', $favorite->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection