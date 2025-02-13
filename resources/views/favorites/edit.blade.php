@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-6">Edit Recipe Favorite</h1>

            <form id="editRecipeForm" action="{{ route('favorites.update', $favorite->id) }}" method="POST">
                @csrf
                @method('PUT')

                @php
                    $details = json_decode($favorite->recipe_details, true);
                @endphp

                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Informasi Dasar -->
                    <div>
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">cooking time (minutes)</label>
                            <input type="number" name="cooking_time" 
                                   value="{{ $details['readyInMinutes'] ?? '' }}" 
                                   class="w-full px-3 py-2 border rounded">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">servings</label>
                            <input type="number" name="servings" 
                                   value="{{ $details['servings'] ?? '' }}" 
                                   class="w-full px-3 py-2 border rounded">
                        </div>
                    </div>

                    <!-- Bahan-bahan -->
                    <div>
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">Ingredients</label>
                            <div id="ingredients-container">
                                @foreach(($details['extendedIngredients'] ?? []) as $index => $ingredient)
                                <div class="flex items-center mb-2 ingredient-row">
                                    <input type="text" name="ingredients[{{ $index }}][name]" 
                                           value="{{ $ingredient['name'] }}" 
                                           placeholder="Ingredient name" 
                                           class="flex-grow mr-2 px-3 py-2 border rounded">
                                    <input type="number" name="ingredients[{{ $index }}][amount]" 
                                           value="{{ $ingredient['amount'] }}" 
                                           placeholder="Amount" 
                                           class="w-20 mr-2 px-3 py-2 border rounded">
                                    <input type="text" name="ingredients[{{ $index }}][unit]" 
                                           value="{{ $ingredient['unit'] }}" 
                                           placeholder="Unit" 
                                           class="w-20 mr-2 px-3 py-2 border rounded">
                                    <button type="button" 
                                            class="remove-ingredient bg-red-500 text-white px-3 py-2 rounded">
                                        Remove
                                    </button>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" id="add-ingredient" 
                                    class="mt-2 bg-green-500 text-white px-4 py-2 rounded">
                                Add Ingredient
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Instruksi Memasak -->
                <div class="mt-6">
                    <label class="block text-gray-700 mb-2">Cooking Instructions</label>
                    <div id="instructions-container">
                        @foreach(($details['analyzedInstructions'][0]['steps'] ?? []) as $index => $step)
                        <div class="flex items-center mb-2 instruction-row">
                            <input type="text" name="instructions[{{ $index }}][step]" 
                                   value="{{ $step['step'] }}" 
                                   placeholder="Cooking instruction" 
                                   class="flex-grow mr-2 px-3 py-2 border rounded">
                            <button type="button" 
                                    class="remove-instruction bg-red-500 text-white px-3 py-2 rounded">
                                Remove
                            </button>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" id="add-instruction" 
                            class="mt-2 bg-green-500 text-white px-4 py-2 rounded">
                        Add Instruction
                    </button>
                </div>

                <div class="mt-6">
                    <button type="submit" 
                            class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ingredientsContainer = document.getElementById('ingredients-container');
    const instructionsContainer = document.getElementById('instructions-container');
    let ingredientIndex = ingredientsContainer.children.length;
    let instructionIndex = instructionsContainer.children.length;

    // Tambah Bahan
    document.getElementById('add-ingredient').addEventListener('click', function() {
        const newRow = document.createElement('div');
        newRow.className = 'flex items-center mb-2 ingredient-row';
        newRow.innerHTML = `
            <input type="text" name="ingredients[${ingredientIndex}][name]" 
                   placeholder="Nama bahan" 
                   class="flex-grow mr-2 px-3 py-2 border rounded">
            <input type="number" name="ingredients[${ingredientIndex}][amount]" 
                   placeholder="Jumlah" 
                   class="w-20 mr-2 px-3 py-2 border rounded">
            <input type="text" name="ingredients[${ingredientIndex}][unit]" 
                   placeholder="Satuan" 
                   class="w-20 mr-2 px-3 py-2 border rounded">
            <button type="button" 
                    class="remove-ingredient bg-red-500 text-white px-3 py-2 rounded">
                Hapus
            </button>
        `;
        ingredientsContainer.appendChild(newRow);
        ingredientIndex++;
        attachRemoveListeners();
    });

    // Tambah Instruksi
    document.getElementById('add-instruction').addEventListener('click', function() {
        const newRow = document.createElement('div');
        newRow.className = 'flex items-center mb-2 instruction-row';
        newRow.innerHTML = `
            <input type="text" name="instructions[${instructionIndex}][step]" 
                   placeholder="Langkah memasak" 
                   class="flex-grow mr-2 px-3 py-2 border rounded">
            <button type="button" 
                    class="remove-instruction bg-red-500 text-white px-3 py-2 rounded">
                Hapus
            </button>
        `;
        instructionsContainer.appendChild(newRow);
        instructionIndex++;
        attachRemoveListeners();
    });

    // Fungsi untuk menambah listener hapus
    function attachRemoveListeners() {
        document.querySelectorAll('.remove-ingredient').forEach(button => {
            button.removeEventListener('click', removeIngredientRow);
            button.addEventListener('click', removeIngredientRow);
        });

        document.querySelectorAll('.remove-instruction').forEach(button => {
            button.removeEventListener('click', removeInstructionRow);
            button.addEventListener('click', removeInstructionRow);
        });
    }

    // Hapus baris bahan
    function removeIngredientRow() {
        this.closest('.ingredient-row').remove();
    }

    // Hapus baris instruksi
    function removeInstructionRow() {
        this.closest('.instruction-row').remove();
    }

    // Inisialisasi listener
    attachRemoveListeners();
});
</script>
@endpush
@endsection