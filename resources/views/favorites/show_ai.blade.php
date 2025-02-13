@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('status'))
        <div class="fixed top-4 right-4 z-50 bg-green-100 border-green-400 text-green-700 px-6 py-3 rounded-lg shadow-lg">
            {{ session('status') }}
        </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Recipe Image -->
                <div>
                    @if($favorite->recipe_image)
                        <img 
                            src="{{ $favorite->recipe_image }}" 
                            alt="{{ $favorite->food_name }}" 
                            class="w-full rounded-lg shadow-md object-cover h-96"
                        >
                    @else
                        <div class="w-full h-96 bg-gray-200 flex items-center justify-center rounded-lg">
                            <span class="text-gray-500 text-xl">No Image</span>
                        </div>
                    @endif
                </div>

                <!-- Recipe Information -->
                <div>
                    <h1 class="text-3xl font-bold mb-4">{{ $favorite->food_name }}</h1>
                    
                    <!-- Recipe Summary with Icons -->
                    <div class="grid grid-cols-3 gap-4 mb-6 text-center">
                        <div class="bg-blue-50 p-3 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto mb-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <strong class="block text-gray-700 mb-1">Cook Time</strong>
                            <span class="text-gray-900">{{ $favorite->cook_time ?? 'N/A' }}</span>
                        </div>
                        <div class="bg-green-50 p-3 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto mb-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.768-.231-1.477-.623-2.134M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.768.231-1.477.623-2.134M14 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <strong class="block text-gray-700 mb-1">Serving Size</strong>
                            <span class="text-gray-900">{{ $favorite->serving_size ?? 'N/A' }}</span>
                        </div>
                        <div class="bg-purple-50 p-3 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto mb-2 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                            <strong class="block text-gray-700 mb-1">Dish Type</strong>
                            <span class="text-gray-900">{{ $favorite->dish_type ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <!-- Edit and Delete Buttons -->
                    <div class="flex space-x-4 mb-4">
                        <a 
                            href="{{ route('favorites.edit_ai', $favorite->id) }}" 
                            class="flex-1 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-center"
                        >
                            Edit Recipe
                        </a>
                        <form 
                            action="{{ route('favorites.remove_ai', $favorite->id) }}" 
                            method="POST" 
                            class="flex-1"
                        >
                            @csrf
                            @method('DELETE')
                            <button 
                                type="submit" 
                                class="w-full bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
                                onclick="return confirm('Are you sure you want to delete this recipe?')"
                            >
                                Delete Recipe
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        <!-- Ingredients -->
        <div class="mt-8">
            <h2 class="text-2xl font-bold mb-4">Ingredients</h2>
            <div class="grid md:grid-cols-2 gap-4">
                @foreach(is_array($favorite->ingredients) ? $favorite->ingredients : json_decode($favorite->ingredients, true) ?? [] as $ingredient)
                    <div class="flex items-center bg-gray-100 p-3 rounded">
                        <span>{{ $ingredient }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Instructions -->
        <div class="mt-8">
            <h2 class="text-2xl font-bold mb-4">Cooking Instructions</h2>
            <ol class="list-decimal list-inside space-y-2">
                @foreach(is_array($favorite->instructions) ? $favorite->instructions : json_decode($favorite->instructions, true) ?? [] as $instruction)
                    <li class="bg-gray-100 p-3 rounded">{{ $instruction }}</li>
                @endforeach
            </ol>
        </div>

        </div>
    </div>
</div>
@endsection