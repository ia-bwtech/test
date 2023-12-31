<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>

<body>
    @include('layouts.dashboard.nav')
    @include('layouts.dashboard.sidenav')
    <main class="content">
        
        @include('layouts.dashboard.topbar')
        @yield('content')
        @include('layouts.dashboard.footer')
    </main>

    @yield('scripts')
</body>

</html>
