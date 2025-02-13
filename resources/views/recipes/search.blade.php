@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Search Results</h1>
    
    <!-- Form Pencarian -->
    <form action="{{ route('recipes.search') }}" method="GET" class="mb-6">
        <div class="flex">
            <input 
                type="text" 
                name="query" 
                placeholder="Search recipe..." 
                value="{{ request('query') }}"
                class="form-input rounded-l-md border-r-0 w-full"
            >
            <button 
                type="submit" 
                class="px-4 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600"
            >
                Search
            </button>
        </div>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($recipes as $recipe)
            <div class="bg-white shadow-md rounded-lg overflow-hidden transform transition duration-300 hover:scale-105">
                <img 
                    src="{{ $recipe['image'] ?? 'https://via.placeholder.com/350x200' }}" 
                    alt="{{ $recipe['title'] }}" 
                    class="w-full h-48 object-cover"
                >
                <div class="p-4">
                    <h2 class="text-xl font-semibold mb-2 truncate">
                        {{ $recipe['title'] }}
                    </h2>
                    <div class="flex justify-between items-center mt-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $recipe['readyInMinutes'] ?? 'N/A' }} minute
                        </div>
                        <a 
                            href="{{ route('recipes.details', $recipe['id']) }}" 
                            class="text-blue-500 hover:text-blue-700 font-medium"
                        >
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline">No recipes found for "{{ request('query') }}".</span>
            </div>
        @endforelse
    </div>
</div>
@endsection