<nav x-data="{ open: false, findRecipes: false, aiRecipes: false }" class="bg-white border-b border-gray-100 text-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex items-center">
                    <!-- Dashboard -->
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-red-600 hover:text-red-800 font-bold h-16 flex items-center">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <!-- Find Recipes Dropdown -->
                    <div class="relative h-16 flex items-center" x-data="{ open: false }">
                        <button @click="open = !open" class="inline-flex items-center text-red-600 hover:text-red-800 font-bold">
                            {{ __('Find Recipes') }}
                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" 
                             @click.away="open = false"
                             class="absolute left-0 w-48 mt-32 py-2 bg-white rounded-md shadow-lg z-50">
                            <x-nav-link :href="route('recipes.index')" :active="request()->routeIs('recipes.index')" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                {{ __('Recipe Search') }}
                            </x-nav-link>
                            <x-nav-link :href="route('recipes.recommend')" :active="request()->routeIs('recipes.recommend')" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                {{ __('Search by Ingredients') }}
                            </x-nav-link>
                            <x-nav-link :href="route('favorites.index')" :active="request()->routeIs('favorites.index')" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                {{ __('Favorite Recipes') }}
                            </x-nav-link>
                        </div>
                    </div>

                    <!-- My Recipes -->
                    <x-nav-link :href="route('resep-saya.index')" :active="request()->routeIs('resep-saya.index')" class="text-red-600 hover:text-red-800 font-bold h-16 flex items-center">
                        {{ __('My Recipes') }}
                    </x-nav-link>

                    <!-- AI & Image Recipes Dropdown -->
                    <div class="relative h-16 flex items-center" x-data="{ open: false }">
                        <button @click="open = !open" class="inline-flex items-center text-red-600 hover:text-red-800 font-bold">
                            {{ __('AI & Image Recipes') }}
                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" 
                             @click.away="open = false"
                             class="absolute left-0 w-48 mt-32 py-2 bg-white rounded-md shadow-lg z-50">
                            <x-nav-link :href="route('image-to-recipe.index')" :active="request()->routeIs('image-to-recipe.index')" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                {{ __('Recipe from Image') }}
                            </x-nav-link>
                            <x-nav-link :href="route('favorites.index_ai')" :active="request()->routeIs('favorites.index_ai')" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                {{ __('AI Favorite Recipes') }}
                            </x-nav-link>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Logout Button -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center text-white bg-red-600 hover:bg-red-700 px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                        {{ __('Logout') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>