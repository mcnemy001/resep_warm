@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- @if($welcomeMessage)
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ $welcomeMessage }}
        </div>
    @endif --}}
    {{-- @if(session('welcome'))
    <div id="welcome-notification" class="bg-green-500 bg-opacity-80 text-white px-4 py-2 rounded mb-4">
        {{ session('welcome') }}
    </div>
    @endif --}}
    @if(session('welcome'))
<div id="welcome-notification" class="fixed top-4 right-4 z-50 bg-green-100 border-green-400 text-green-700 px-6 py-3 rounded-lg shadow-lg transition-all duration-500 ease-in-out transform hover:scale-105">
    <div class="flex items-center">
        <svg class="w-6 h-6 mr-2 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="font-medium">{{ session('welcome') }}</span>
    </div>
</div>
@endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Kartu Profil Pengguna -->
        <div class="bg-white shadow-md rounded-lg p-6 md:col-span-1">
            <div class="flex items-center space-x-4 mb-6">
                <!-- Ikon Pengguna -->
                <div class="bg-orange-100 p-3 rounded-full">
                    <svg class="w-12 h-12 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-amber-800">{{ $user->name }}</h2>
                    <p class="text-gray-500">{{ $user->email }}</p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="bg-orange-100 p-4 rounded-lg space-y-4">
                    <div>
                        <h3 class="text-lg font-semibold mb-2 text-amber-800">Recipe Statistics</h3>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-amber-700">Total Favorites:</span>
                        <span class="font-bold text-orange-600">{{ $totalFavorites }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-amber-700">Total My Recipes:</span>
                        <span class="font-bold text-orange-600">{{ $totalMyRecipes }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resep Favorit Terbaru -->
        <div class="bg-white shadow-md rounded-lg p-6 md:col-span-2">
            <h2 class="text-2xl font-bold mb-6 text-amber-600">Recent Favorite Recipes</h2>
            
            @if($recentFavorites->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($recentFavorites as $favorite)
                        <div class="bg-orange-50 rounded-lg overflow-hidden shadow-sm">
                            <img 
                                src="{{ $favorite->recipe_image }}" 
                                alt="{{ $favorite->recipe_title }}" 
                                class="w-full h-40 object-cover"
                            >
                            <div class="p-4">
                                <h3 class="font-semibold mb-2 truncate text-amber-800">{{ $favorite->recipe_title }}</h3>
                                <a 
                                    href="{{ route('favorites.show', $favorite->id) }}" 
                                    class="text-orange-600 hover:text-orange-800 font-medium"
                                >
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center">There is no favorite recipe yet</p>
            @endif
        </div>

        <!-- Recommended Recipes -->
        <div class="bg-white shadow-md rounded-lg p-6 col-span-full">
            <h2 class="text-2xl font-bold mb-6 text-amber-600">Recommended Recipes</h2>
            
            @if(!empty($recommendedRecipes))
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($recommendedRecipes as $recipe)
                        <div class="bg-orange-50 rounded-lg overflow-hidden shadow-sm">
                            <img 
                            src="{{ $recipe['image'] ?? 'https://via.placeholder.com/300x200?text=Resep+Tidak+Tersedia' }}" 
                            alt="{{ $recipe['title'] ?? 'Resep Tidak Dikenal' }}" 
                            class="w-full h-40 object-cover"
                        >
                            <div class="p-4">
                                <h3 class="font-semibold mb-2 truncate text-amber-800">{{ $recipe['title'] }}</h3>
                                <a 
                                    href="{{ route('recipes.details', $recipe['id']) }}" 
                                    class="text-orange-600 hover:text-orange-800 font-medium">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center">There is no recommended recipe</p>
            @endif
        </div>
    </div>
</div>
@endsection