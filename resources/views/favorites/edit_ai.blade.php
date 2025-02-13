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
            <h1 class="text-3xl font-bold mb-6 text-gray-800">Edit Recipe: {{ $favorite->food_name }}</h1>

            <form action="{{ route('favorites.update_ai', $favorite->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Image Section -->
                <div class="space-y-4">
                    <div class="mb-6">
                        @if($favorite->recipe_image)
                            <div class="mb-4">
                                <img 
                                    src="{{ $favorite->recipe_image }}" 
                                    alt="{{ $favorite->food_name }}" 
                                    class="w-48 h-48 object-cover rounded-lg shadow-md"
                                >
                            </div>
                        @endif
                    </div>

                    <!-- Image Upload -->
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Upload New Image</label>
                        <input 
                            type="file" 
                            name="new_image" 
                            accept="image/jpeg,image/png,image/jpg"
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        <p class="text-sm text-gray-500 mt-1">Supported formats: JPEG, PNG, JPG (max 2MB)</p>
                    </div>
                </div>

                <!-- Food Name -->
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Recipe Name</label>
                    <input 
                        type="text" 
                        name="food_name" 
                        value="{{ old('food_name', $favorite->food_name) }}" 
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                </div>

                <!-- Recipe Details -->
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Cook Time</label>
                        <input 
                            type="text" 
                            name="cook_time" 
                            value="{{ old('cook_time', $favorite->cook_time) }}" 
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="e.g., 30 minutes"
                        >
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Serving Size</label>
                        <input 
                            type="text" 
                            name="serving_size" 
                            value="{{ old('serving_size', $favorite->serving_size) }}" 
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="e.g., 4 servings"
                        >
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Dish Type</label>
                        <input 
                            type="text" 
                            name="dish_type" 
                            value="{{ old('dish_type', $favorite->dish_type) }}" 
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="e.g., Main Course"
                        >
                    </div>
                </div>

                <!-- Ingredients -->
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Ingredients</label>
                    <div id="ingredients-container">
                        @foreach(is_array($favorite->ingredients) ? $favorite->ingredients : json_decode($favorite->ingredients, true) ?? [] as $index => $ingredient)
                            <div class="flex mb-2">
                                <input 
                                    type="text" 
                                    name="ingredients[]" 
                                    value="{{ $ingredient }}"
                                    class="flex-grow px-3 py-2 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                >
                                <button 
                                    type="button"
                                    onclick="removeIngredient(this)"
                                    class="bg-red-500 text-white px-3 py-2 rounded-r-md hover:bg-red-600"
                                >
                                    Remove
                                </button>
                            </div>
                        @endforeach
                    </div>
                    <button 
                        type="button"
                        onclick="addIngredient()"
                        class="mt-2 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
                    >
                        Add Ingredient
                    </button>
                </div>

                <!-- Instructions -->
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Cooking Instructions</label>
                    <div id="instructions-container">
                        @foreach(is_array($favorite->instructions) ? $favorite->instructions : json_decode($favorite->instructions, true) ?? [] as $index => $instruction)
                            <div class="flex mb-2">
                                <input 
                                    type="text" 
                                    name="instructions[]" 
                                    value="{{ $instruction }}"
                                    class="flex-grow px-3 py-2 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                >
                                <button 
                                    type="button"
                                    onclick="removeInstruction(this)"
                                    class="bg-red-500 text-white px-3 py-2 rounded-r-md hover:bg-red-600"
                                >
                                    Remove
                                </button>
                            </div>
                        @endforeach
                    </div>
                    <button 
                        type="button"
                        onclick="addInstruction()"
                        class="mt-2 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
                    >
                        Add Instruction
                    </button>
                </div>



                <!-- Submit Buttons -->
                <div class="flex space-x-4 mt-6">
                    <button 
                        type="submit" 
                        class="flex-1 bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition duration-150"
                    >
                        Update Recipe
                    </button>
                    <a 
                        href="{{ route('favorites.show_ai', $favorite->id) }}" 
                        class="flex-1 bg-gray-200 text-gray-800 py-2 px-4 rounded hover:bg-gray-300 text-center transition duration-150"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function addIngredient() {
        const container = document.getElementById('ingredients-container');
        const newIngredient = document.createElement('div');
        newIngredient.classList.add('flex', 'mb-2');
        newIngredient.innerHTML = `
            <input 
                type="text" 
                name="ingredients[]" 
                class="flex-grow px-3 py-2 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                placeholder="Enter ingredient"
                required
            >
            <button 
                type="button"
                onclick="removeIngredient(this)"
                class="bg-red-500 text-white px-3 py-2 rounded-r-md hover:bg-red-600"
            >
                Remove
            </button>
        `;
        container.appendChild(newIngredient);
    }

    function removeIngredient(button) {
        const container = document.getElementById('ingredients-container');
        if (container.children.length > 1) {
            button.parentElement.remove();
        }
    }

    function addInstruction() {
        const container = document.getElementById('instructions-container');
        const newInstruction = document.createElement('div');
        newInstruction.classList.add('flex', 'mb-2');
        newInstruction.innerHTML = `
            <input 
                type="text" 
                name="instructions[]" 
                class="flex-grow px-3 py-2 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                placeholder="Enter instruction"
                required
            >
            <button 
                type="button"
                onclick="removeInstruction(this)"
                class="bg-red-500 text-white px-3 py-2 rounded-r-md hover:bg-red-600"
            >
                Remove
            </button>
        `;
        container.appendChild(newInstruction);
    }

    function removeInstruction(button) {
        const container = document.getElementById('instructions-container');
        if (container.children.length > 1) {
            button.parentElement.remove();
        }
    }
</script>
@endpush
@endsection