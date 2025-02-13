@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 to-orange-100 flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-md bg-white shadow-2xl rounded-xl overflow-hidden">
        <div class="p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-orange-600 mb-2">Reset Password</h2>
                <p class="text-gray-500 text-sm">
                    Enter your email to receive a password reset link
                </p>
            </div>

            <!-- Notifikasi Status -->
            @if(session('status'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                    <p class="font-bold">Success</p>
                    <p>{{ session('status') }}</p>
                </div>
            @endif

            <!-- Error Handling -->
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                    <p class="font-bold">Error</p>
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Form Reset Password -->
            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                        </div>
                        <input 
                            type="email" 
                            name="email" 
                            required 
                            placeholder="you@example.com"
                            class="pl-10 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                        >
                    </div>
                </div>

                <div>
                    <button 
                        type="submit" 
                        class="w-full bg-orange-500 text-white py-3 rounded-md hover:bg-orange-600 transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50"
                    >
                        Send Reset Link
                    </button>
                </div>
            </form>

            <!-- Link Kembali ke Login -->
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm text-orange-500 hover:text-orange-600 hover:underline transition">
                    Back to Login
                </a>
            </div>
        </div>
    </div>
</div>
@endsection