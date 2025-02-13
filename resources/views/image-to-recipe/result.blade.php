@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg p-6">
        <!-- @if(config('app.debug'))
            <div class="bg-gray-100 p-4 mb-4 rounded">
                <pre>{{ print_r($recipeData, true) }}</pre>
            </div>
        @endif -->

        <h2 class="text-3xl font-bold mb-6 text-center text-red-600 border-b pb-3">
            {{ $recipeData['food_name'] ?? 'Untitled Recipe' }}
        </h2>

        @if(!empty($recipeData['recipe_image']))
            <div class="mb-8">
                <div class="max-w-2xl mx-auto">
                    <img src="{{ $recipeData['recipe_image'] }}" 
                         alt="{{ $recipeData['food_name'] ?? 'Recipe Image' }}" 
                         class="w-full h-auto rounded-lg shadow-lg object-cover">
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            @foreach(['dish_type' => 'Dish Type', 'cook_time' => 'Cook Time', 'serving_size' => 'Serving Size'] as $key => $label)
                @if(!empty($recipeData[$key]))
                    <div class="text-center p-4 bg-gray-100 rounded-lg shadow">
                        <h3 class="font-semibold text-gray-700">{{ $label }}</h3>
                        <p class="text-gray-600">{{ $recipeData[$key] }}</p>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="space-y-6">
            @if(!empty($recipeData['ingredients']))
                <div class="bg-gray-50 p-6 rounded-lg shadow">
                    <h3 class="text-xl font-bold mb-4 text-red-600 border-b pb-2">Ingredients</h3>
                    <ul class="list-disc list-inside space-y-2">
                        @foreach($recipeData['ingredients'] as $ingredient)
                            <li class="text-gray-700">{{ $ingredient }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(!empty($recipeData['instructions']))
                <div class="bg-gray-50 p-6 rounded-lg shadow">
                    <h3 class="text-xl font-bold mb-4 text-red-600 border-b pb-2">Cooking Instructions</h3>
                    <ol class="list-decimal list-inside space-y-3">
                        @foreach($recipeData['instructions'] as $instruction)
                            <li class="text-gray-700">{{ $instruction }}</li>
                        @endforeach
                    </ol>
                </div>
            @endif
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="mt-6 flex justify-center space-x-4">
            <form action="{{ route('favorites.store_ai') }}" method="POST">
                @csrf
                <input type="hidden" name="food_name" value="{{ $recipeData['food_name'] ?? '' }}">
                <input type="hidden" name="ingredients" value="{{ json_encode($recipeData['ingredients'] ?? []) }}">
                <input type="hidden" name="instructions" value="{{ json_encode($recipeData['instructions'] ?? []) }}">
                <input type="hidden" name="dish_type" value="{{ $recipeData['dish_type'] ?? '' }}">
                <input type="hidden" name="cook_time" value="{{ $recipeData['cook_time'] ?? '' }}">
                <input type="hidden" name="serving_size" value="{{ $recipeData['serving_size'] ?? '' }}">
                <input type="hidden" name="recipe_image" value="{{ $recipeData['recipe_image'] ?? '' }}">

                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                    Add to Favorites
                </button>
            </form>

            <a href="{{ route('image-to-recipe.index') }}"
                class="inline-block bg-red-600 text-white py-2 px-6 rounded-lg hover:bg-red-700 transition-colors">
                Upload Another Image
            </a>
        </div>
    </div>
</div>
@endsection