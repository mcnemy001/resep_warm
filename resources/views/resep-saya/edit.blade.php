@extends('layouts.form')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg p-8">
    <h1 class="text-3xl font-bold mb-6 text-red-600 text-center">Edit Recipe: {{ $resep->nama_makanan }}</h1>
    
    <form action="{{ route('resep-saya.update', $resep->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="grid gap-6">
            <div>
                <label class="block text-gray-700 font-bold mb-2">Recipe Name</label>
                <input 
                    type="text" 
                    name="nama_makanan" 
                    value="{{ old('nama_makanan', $resep->nama_makanan) }}" 
                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" 
                    required
                >
            </div>
            
            <div>
                <label class="block text-gray-700 font-bold mb-2">Recipe Image</label>
                <input 
                    type="file" 
                    name="gambar" 
                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                    accept="image/*"
                >
                @if($resep->gambar)
                    <div class="mt-2">
                        <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                        <img 
                            src="{{ asset('storage/'.$resep->gambar) }}" 
                            alt="{{ $resep->nama_makanan }}" 
                            class="w-40 h-40 object-cover rounded-md shadow-sm"
                        >
                    </div>
                @endif
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Cooking Time (minutes)</label>
                    <input 
                        type="number" 
                        name="waktu_memasak" 
                        value="{{ old('waktu_memasak', $resep->waktu_memasak) }}" 
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                    >
                </div>
                
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Portion</label>
                    <input 
                        type="number" 
                        name="porsi" 
                        value="{{ old('porsi', $resep->porsi) }}" 
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                    >
                </div>
            </div>
            
            <div>
                <label class="block text-gray-700 font-bold mb-2">Food Type</label>
                <input 
                    type="text" 
                    name="jenis_hidangan" 
                    value="{{ old('jenis_hidangan', $resep->jenis_hidangan) }}" 
                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                >
            </div>
            
            <!-- Bahan-bahan -->
            <div>
                <label class="block text-gray-700 font-bold mb-2">Ingredients</label>
                <div id="bahan-container">
                    @php
                        $bahan = old('bahan') ?? explode("\n", $resep->bahan);
                    @endphp
                    @foreach($bahan as $index => $item)
                        <div class="flex mb-2">
                            <input 
                                type="text" 
                                name="bahan[]" 
                                value="{{ trim($item) }}"
                                class="flex-grow px-3 py-2 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-red-500" 
                                placeholder="Contoh: 2 eggs"
                                required
                            >
                            <button 
                                type="button" 
                                onclick="hapusBahan(this)" 
                                class="bg-red-600 text-white px-3 py-2 rounded-r-md hover:bg-red-700"
                            >
                                Delete
                            </button>
                        </div>
                    @endforeach
                </div>
                <button 
                    type="button" 
                    onclick="tambahBahan()" 
                    class="mt-2 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
                >
                    Add Ingredient
                </button>
            </div>
            
            <!-- Instruksi Memasak -->
            <div>
                <label class="block text-gray-700 font-bold mb-2">Cooking Instructions</label>
                <div id="instruksi-container">
                    @php
                        $instruksi = old('instruksi_memasak') ?? explode("\n", $resep->instruksi_memasak);
                    @endphp
                    @foreach($instruksi as $index => $step)
                        <div class="flex mb-2">
                            <input 
                                type="text" 
                                name="instruksi_memasak[]" 
                                value="{{ trim($step) }}"
                                class="flex-grow px-3 py-2 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-red-500" 
                                placeholder="Example: Beat eggs until soft"
                                required
                            >
                            <button 
                                type="button" 
                                onclick="hapusInstruksi(this)" 
                                class="bg-red-500 text-white px-3 py-2 rounded-r-md hover:bg-red-600"
                            >
                                Delete
                            </button>
                        </div>
                    @endforeach
                </div>
                <button 
                    type="button" 
                    onclick="tambahInstruksi()" 
                    class="mt-2 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
                >
                    Add Instruction
                </button>
            </div>
        </div>
        
        <div class="mt-6 flex space-x-4">
            <button 
                type="submit" 
                class="flex-1 bg-red-600 text-white py-3 rounded-md hover:bg-red-700 transition"
            >
                Update Recipe
            </button>
            <a 
                href="{{ route('resep-saya.show', $resep->id) }}" 
                class="flex-1 bg-gray-200 text-gray-800 py-3 rounded-md hover:bg-gray-300 text-center"
            >
                Cancel
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Fungsi tambah dan hapus bahan
    function tambahBahan() {
        const container = document.getElementById('bahan-container');
        const newBahan = document.createElement('div');
        newBahan.classList.add('flex', 'mb-2');
        newBahan.innerHTML = `
            <input 
                type="text" 
                name="bahan[]" 
                class="flex-grow px-3 py-2 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-red-500" 
                placeholder="Example: 2 eggs"
                required
            >
            <button 
                type="button" 
                onclick="hapusBahan(this)" 
                class="bg-red-600 text-white px-3 py-2 rounded-r-md hover:bg-red-700"
            >
                Hapus
            </button>
        `;
        container.appendChild(newBahan);
    }

    function hapusBahan(button) {
        const container = document.getElementById('bahan-container');
        if (container.children.length > 1) {
            container.removeChild(button.parentElement);
        }
    }

    // Fungsi tambah dan hapus instruksi
    function tambahInstruksi() {
        const container = document.getElementById('instruksi-container');
        const newInstruksi = document.createElement('div');
        newInstruksi.classList.add('flex', 'mb-2');
        newInstruksi.innerHTML = `
            <input 
                type="text" 
                name="instruksi_memasak[]" 
                class="flex-grow px-3 py-2 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-red-500" 
                placeholder="Example: Beat eggs until soft"
                required
            >
            <button 
                type="button" 
                onclick="hapusInstruksi(this)" 
                class="bg-red-600 text-white px-3 py-2 rounded-r-md hover:bg-red-700"
            >
                Hapus
            </button>
        `;
        container.appendChild(newInstruksi);
    }

    function hapusInstruksi(button) {
        const container = document.getElementById('instruksi-container');
        if (container.children.length > 1) {
            container.removeChild(button.parentElement);
        }
    }
</script>
@endpush
@endsection