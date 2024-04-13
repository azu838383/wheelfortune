<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php
    use Carbon\Carbon;
@endphp
@php
    $now = Carbon::now();
    $setting_favicon = \App\Models\Setting::where('set_for', 'favicon')->first();
    $setting_title = \App\Models\Setting::where('set_for', 'seo_title')->first();
    $setting_keywords = \App\Models\Setting::where('set_for', 'seo_key_words')->first();
    $setting_description = \App\Models\Setting::where('set_for', 'seo_description')->first();
    $setting_hyperlink = \App\Models\Setting::where('set_for', 'hyperlink')->first();
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <title>{{ $setting_title->value }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('/storage/' . $setting_favicon->value) }}">

    <meta name="keywords" content="{{ $setting_keywords->value }}">
    <meta name="description" content="{{ $setting_description->value }}">

    <meta property="og:title" content="{{ $setting_title->value }}">
    <meta property="og:site_name" content="{{ $setting_title->value }}">
    <meta property="og:description" content="{{ $setting_description->value }}">
    <meta property="og:type" content="{{ $setting_keywords->value }}" />
    <meta property="og:image" content="{{ asset('/assets/img/logo.webp') }}">
    <meta property="og:url" content="{{ $setting_hyperlink->value }}">

    <meta property="fb:title" content="{{ $setting_title->value }}">
    <meta property="fb:site_name" content="{{ $setting_title->value }}">
    <meta property="fb:description" content="{{ $setting_description->value }}">
    <meta property="fb:type" content="{{ $setting_keywords->value }}" />
    <meta property="fb:image" content="{{ asset('/assets/img/logo.webp') }}">
    <meta property="fb:url" content="{{ $setting_hyperlink->value }}">

    <meta property="twitter:title" content="{{ $setting_title->value }}">
    <meta property="twitter:site_name" content="{{ $setting_title->value }}">
    <meta property="twitter:description" content="{{ $setting_description->value }}">
    <meta property="twitter:type" content="{{ $setting_keywords->value }}" />
    <meta property="twitter:image" content="{{ asset('/assets/img/logo.webp') }}">
    <meta property="twitter:url" content="{{ $setting_hyperlink->value }}">
    <meta name="twitter:card" content="summary_large_image">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="{{ asset('vendor/bladewind/css/animate.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/bladewind/css/bladewind-ui.min.css') }}" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="{{ asset('vendor/bladewind/js/helpers.js') }}"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('/assets/js/jquery.rotate.js') }}"></script>


    <link href="{{ asset('/assets/css/style.css') }}" rel="stylesheet" />

</head>

<body class="font-sans text-gray-900 antialiased bg-black bg-lucky-spin">
    <nav
        class="fixed z-[999] w-full py-2 px-3 md:py-4 md:px-4 flex items-center justify-between flex-wrap bg-black bg-opacity-80">
        <div class="flex items-center flex-shrink-0 text-black dark:text-white mr-6">
            <x-application-logo class="h-10 md:h-20 text-gray-500" />
        </div>

        <div class="w-fit text-black dark:text-white">
            <a href="{{ $setting_hyperlink->value }}" target="_blank">
                <button size="small"
                    class="py-1 px-2 md:py-2 md:px-8 leading-3 rounded-full w-24 md:w-fit uppercase font-bold text-sm md:text-lg border-2 border-yellow-600 !text-black bg-gradient-to-r from-yellow-600 to-yellow-300">
                    daftar disini
                </button>
            </a>
        </div>
    </nav>
    <div class="min-h-screen max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{ $slot }}
    </div>
    <section id="footer" class="fixed w-full bottom-0 bg-black bg-opacity-80 text-black dark:text-white py-2">
        <a href="{{ $setting_hyperlink->value }}" target="_blank">
            <div class="text-white w-full text-center">{{ '@' . $now->year }} Copyright, All right reserved</div>
        </a>
    </section>
</body>

</html>
