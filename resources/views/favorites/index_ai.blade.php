@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('status'))
        <div class="fixed top-4 right-4 z-50 bg-green-100 border-green-400 text-green-700 px-6 py-3 rounded-lg shadow-lg">
            {{ session('status') }}
        </div>
        @endif

        @if(session('error'))
        <div class="fixed top-4 right-4 z-50 bg-red-100 border-red-400 text-red-700 px-6 py-3 rounded-lg shadow-lg">
            {{ session('error') }}
        </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h1 class="text-3xl font-bold mb-6 text-gray-800">My Favorite Recipes (AI)</h1>

            @if($favorites->isEmpty())
                <div class="text-center py-10 bg-gray-100 rounded-lg">
                    <p class="text-xl text-gray-600">No favorite recipes from AI yet</p>
                    <a href="{{ route('image-to-recipe.index') }}" 
                       class="mt-4 inline-block bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                        Generate a Recipe
                    </a>
                </div>
            @else
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($favorites as $favorite)
                        <div class="bg-white shadow-md rounded-lg overflow-hidden">
                            @if($favorite->recipe_image)
                                <img src="{{ $favorite->recipe_image }}" 
                                     alt="{{ $favorite->food_name }}" 
                                     class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400">No image available</span>
                                </div>
                            @endif
                            <div class="p-4">
                                <h2 class="text-xl font-semibold mb-2">
                                    {{ $favorite->food_name }}
                                </h2>
                                <div class="flex justify-between items-center mt-4">
                                    <a href="{{ route('favorites.show_ai', $favorite->id) }}" 
                                       class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                        View Details
                                    </a>
                                    <form action="{{ route('favorites.remove_ai', $favorite->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
                                                onclick="return confirm('Are you sure you want to remove this recipe from favorites?')">
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