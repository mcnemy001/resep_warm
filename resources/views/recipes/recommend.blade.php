@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-4">Food Recommendations</h1>
            
            <!-- Form Pencarian Bahan -->
            <form action="{{ route('recipes.recommend') }}" method="GET" class="mb-6">
                <div class="flex">
                    <input 
                        type="text" 
                        name="ingredients" 
                        placeholder="Enter ingredients (separated by commas)" 
                        class="form-input rounded-l-md border-r-0 w-full"
                        value="{{ request('ingredients') }}"
                    >
                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600"
                    >
                        Search Recipes
                    </button>
                </div>
                <small class="text-gray-500">example: egg, rice, chicken</small>
            </form>

            <!-- Error Handling -->
            @if(isset($error))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    {{ $error }}
                </div>
            @endif

            <!-- Daftar Resep -->
            @if(empty($recipes))
                <div class="alert alert-info text-center py-4 bg-blue-100 text-blue-800 rounded">
                    No recipes found.
                </div>
            @else
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($recipes as $recipe)
                    <div class="bg-white shadow-md rounded-lg overflow-hidden">
                        <img 
                            src="{{ $recipe['image'] }}" 
                            alt="{{ $recipe['title'] }}" 
                            class="w-full h-48 object-cover"
                        >
                        <div class="p-4">
                            <h2 class="text-lg font-semibold mb-2 truncate">{{ $recipe['title'] }}</h2>
                            <div class="mt-4">
                                <div class="flex items-center text-sm text-gray-600 mb-2">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Ingredients that match: {{ $recipe['usedIngredientCount'] }}/{{ $recipe['usedIngredientCount'] + $recipe['missedIngredientCount'] }}
                                </div>
                                <a 
                                    href="{{ route('recipes.details', $recipe['id']) }}" 
                                    class="block w-full bg-blue-500 text-white text-center px-4 py-2 rounded hover:bg-blue-600 transition text-sm"
                                >
                                    View Details
                                </a>
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