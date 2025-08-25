<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'wisedynamic') }}</title>

    {{-- App CSS/JS (optional). Use Vite only if manifest exists to avoid errors before Node/npm setup --}}
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- Fallback: Tailwind CSS and Font Awesome via CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <style>
            /* Minimal shared utilities used across pages */
            .gradient-bg { background: linear-gradient(90deg, #0976bc, #0e0f3e); }
            .gradient-text { background: linear-gradient(90deg, #0976bc, #0e0f3e); -webkit-background-clip: text; background-clip: text; color: transparent; }
            .theme-gradient { background: linear-gradient(135deg, #0976bc 0%, #0e0f3e 100%); }
            /* Override common Tailwind blue utilities with brand colors */
            .bg-blue-600 { background-color: #0976bc !important; }
            .hover\:bg-blue-700:hover { background-color: #0e0f3e !important; }
            .text-blue-600 { color: #0976bc !important; }
            .border-blue-600 { border-color: #0976bc !important; }

            /* Brand helpers */
            .service-icon { background-color: #0976bc; box-shadow: 0 10px 20px rgba(9,118,188,0.25); }
            .btn-primary { background-color: #0976bc; color: #fff; }
            .btn-primary:hover { background-color: #0e0f3e; }
            .btn-outline-primary { border: 2px solid #0976bc; color: #0976bc; background: transparent; }
            .btn-outline-primary:hover { background-color: #0976bc; color: #fff; }
            .price-highlight { color: #0e0f3e; }
            .card-hover { transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease; }
            .card-hover:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(14,15,62,0.12); border-color: #0976bc; }
            .section-divider { width: 5rem; height: 4px; background: linear-gradient(90deg, #0976bc, #0e0f3e); margin: 0 auto; border-radius: 9999px; }
        </style>
    @endif
    @stack('head')
</head>
<body>
    @yield('content')

    @stack('scripts')
</body>
</html>
