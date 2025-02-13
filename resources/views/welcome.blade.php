@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-orange-50 flex flex-col">
    <!-- Combined Hero and Auth Section -->
    <div class="relative w-full h-[70vh] flex flex-col justify-center items-center text-center px-4">
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1547592180-85f173990554?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D')] bg-cover bg-center opacity-20"></div>
        <div class="relative z-10 max-w-2xl mx-auto">
            <h1 class="text-5xl font-bold mb-6 text-orange-600">ResepWarm</h1>
            <p class="text-xl text-gray-700 mb-8">
                Find, Save, and Share Your Favorite Recipes!
            </p>
            <div class="flex justify-center space-x-4">
                <a href="{{ route('login') }}" class="bg-orange-500 text-white px-8 py-3 rounded-lg hover:bg-orange-600 transition-colors duration-300 shadow-lg">
                    Login
                </a>
                <a href="{{ route('register') }}" class="bg-amber-500 text-white px-8 py-3 rounded-lg hover:bg-amber-600 transition-colors duration-300 shadow-lg">
                    Register
                </a>
            </div>
        </div>
    </div>

    <!-- About Section -->
    <div class="w-full bg-white py-16">
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-4xl font-bold text-center text-orange-600 mb-8">About ResepWarm</h2>
            <p class="text-xl text-gray-700 text-center mb-12">
                ResepWarm is your ultimate companion in the kitchen. Whether you're a seasoned chef or a beginner, our app helps you discover, save, and share delicious recipes with ease. Powered by Spoonacular API and Gemini AI, ResepWarm offers a seamless cooking experience.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-orange-50 p-6 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-bold text-orange-600 mb-4">Search Recipes</h3>
                    <p class="text-gray-700">
                        Find recipes based on available ingredients or specific categories like vegetarian, vegan, and gluten-free.
                    </p>
                </div>
                <div class="bg-orange-50 p-6 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-bold text-orange-600 mb-4">Recipe Details</h3>
                    <p class="text-gray-700">
                        Get detailed information about recipes, including ingredients, cooking steps, nutritional facts, and more.
                    </p>
                </div>
                <div class="bg-orange-50 p-6 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-bold text-orange-600 mb-4">AI-Powered Search</h3>
                    <p class="text-gray-700">
                        Use AI to find recipes by uploading images of ingredients or dishes.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="w-full bg-orange-50 py-16">
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-4xl font-bold text-center text-orange-600 mb-8">Features</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-bold text-orange-600 mb-4">User Authentication</h3>
                    <p class="text-gray-700">
                        Register, login, and manage your account securely. Logout and session management are also supported.
                    </p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-bold text-orange-600 mb-4">Recipe Management</h3>
                    <p class="text-gray-700">
                        Create, read, update, and delete your own recipes. Organize your recipes with ease.
                    </p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-bold text-orange-600 mb-4">Favorites & Categories</h3>
                    <p class="text-gray-700">
                        Save your favorite recipes and categorize them for quick access.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection