@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6 text-center text-red-600">
            {{ __('Image to Recipe') }}
        </h2>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        {{-- Make sure form has method="POST" and proper enctype --}}
        <form action="{{ route('image-to-recipe.analyze') }}" 
              method="POST" 
              enctype="multipart/form-data" 
              onsubmit="return true;"
              class="space-y-4">
            @csrf {{-- Don't forget the CSRF token --}}
            
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    {{ __('Upload Food Image') }}
                </label>
                <input type="file" 
                       name="image" 
                       accept="image/*" 
                       required
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-300 focus:ring focus:ring-red-200">
                @error('image')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                Analyze Image
            </button>

        </form>
    </div>
</div>
@endsection