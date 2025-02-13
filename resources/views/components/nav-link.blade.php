@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center border-orange-500 text-orange-600 focus:outline-none focus:border-orange-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center border-transparent text-orange-600 hover:text-orange-800 hover:border-orange-300 focus:outline-none focus:text-orange-800 focus:border-orange-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>