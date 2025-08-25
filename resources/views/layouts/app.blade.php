<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'wisedynamic') }}</title>

    {{-- App CSS/JS (optional). Use Vite only if manifest exists to avoid errors before Node/npm setup --}}
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    @stack('head')
</head>
<body>
    @yield('content')

    @stack('scripts')
</body>
</html>
