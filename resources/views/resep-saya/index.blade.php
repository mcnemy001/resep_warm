@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- @if(session('status'))
        <div class="fixed top-20 right-6 z-50 animate-slide-in">
            <div class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-xl flex items-center">
                <svg class="w-6 h-6 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        </div>
    @endif --}}

    @if(session('status'))
    <div id="status-notification" class="fixed top-4 right-4 z-50 bg-green-100 border-green-400 text-green-700 px-6 py-3 rounded-lg shadow-lg transition-all duration-500 ease-in-out transform hover:scale-105">
        <div class="flex items-center">
            <svg class="w-6 h-6 mr-2 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-medium">{{ session('status') }}</span>
        </div>
    </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-red-600">My Recipes</h1>
        <a href="{{ route('resep-saya.create') }}" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">
            Create New Recipe
        </a>
    </div>

    @if($resep_saya->isEmpty())
        <div class="text-center bg-white shadow rounded-lg p-8">
            <p class="text-gray-600 mb-4">You don't have any personal recipe yet.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($resep_saya as $resep)
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    @if($resep->gambar)
                        <img src="{{ asset('storage/'.$resep->gambar) }}" alt="{{ $resep->nama_makanan }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-500">No image</span>
                        </div>
                    @endif
                    <div class="p-4">
                        <h2 class="text-xl font-semibold text-red-600 mb-2">{{ $resep->nama_makanan }}</h2>
                        <div class="flex justify-between text-gray-600 mb-4">
                            <span>🕒 {{ $resep->waktu_memasak }} minutes</span>
                            <span>👥 {{ $resep->porsi }} portion</span>
                        </div>
                        <a href="{{ route('resep-saya.show', $resep->id) }}" class="w-full block text-center bg-red-600 text-white py-2 rounded hover:bg-red-700 transition">
                            View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection