@extends('layouts.app')

@section('content')
<header class="theme-gradient text-white pt-28 pb-12">
    <div class="container mx-auto px-6">
        <h1 class="text-3xl md:text-4xl font-extrabold">Contact Us</h1>
        <p class="mt-2 text-white/90">We usually respond within 2 hours during business time</p>
    </div>
</header>

<section class="py-12 bg-white">
    <div class="container mx-auto px-6">
        @include('frontend.home.sections.contact')
    </div>
</section>
@endsection
