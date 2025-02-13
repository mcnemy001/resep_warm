<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'RecipeWarm') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- SweetAlert2 -->
        <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                background-color: #FFF5E6; /* Latar belakang lembut */
            }
            
            /* Custom scrollbar */
            ::-webkit-scrollbar {
                width: 8px;
            }
            ::-webkit-scrollbar-track {
                background: #FFF5E6;
            }
            ::-webkit-scrollbar-thumb {
                background: #FFA500;
                border-radius: 4px;
            }
            ::-webkit-scrollbar-thumb:hover {
                background: #FF8C00;
            }
            h1, h2, h3 {
                color: #FF6B35; /* Warna orange cerah */
                font-weight: bold;
            }
            .nav-link {
                color: #FF6B35;
                font-weight: bold;
                transition: color 0.3s ease;
            }
            .nav-link:hover {
                color: #FF8C35;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-orange-50 text-amber-900">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-orange-100 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif
            

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
        
        <script>
            // Fungsi notifikasi umum
            function showNotification(type, message) {
                Swal.fire({
                    icon: type,
                    title: message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    background: type === 'success' ? '#D1FAE5' : '#FEE2E2',
                    iconColor: type === 'success' ? '#10B981' : '#EF4444',
                });
            }

            // Tangkap pesan flash dari session
            @if(session('success'))
                showNotification('success', '{{ session('success') }}');
            @endif

            @if(session('error'))
                showNotification('error', '{{ session('error') }}');
            @endif
        </script>

        @stack('scripts')
    </body>
</html>