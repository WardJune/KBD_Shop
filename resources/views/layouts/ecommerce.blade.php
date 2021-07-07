<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'KBD Shop') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href="{{ asset('assets') }}/vendor/nucleo/css/nucleo.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('assets') }}/img/brand/kbd1.png" type="image/x-icon">
    <!-- Argon CSS -->
    <link type="text/css" href="{{ asset('assets') }}/css/argon.css?v=1.1.0" rel="stylesheet">
    <link type="text/css" href="{{ asset('assets') }}/css/own.css" rel="stylesheet">

</head>

<body class="{{ $class ?? '' }}">
    @if (auth('customer')->check() && !auth('customer')->user()->email_verified_at)
        <div class="alert alert-warning rounded-0 mb-0 text-center mb-n1" role="alert">
            Please Verify your Email,
            <a href="" onclick=" event.preventDefault(); document.getElementById('requestForm').submit();">
                click here to request another</a>
            <form class="d-none" action="{{ route('verification.request') }}" method="POST" id="requestForm">
                @csrf
            </form>
        </div>
    @endif
    {{-- header area --}}
    @include('layouts.ecommerce.nav.navbar')
    {{-- end of header area --}}

    {{-- start content area --}}
    @yield('content')
    {{-- end of content area --}}

    @include('layouts.ecommerce.footers.footer')

    <script src="{{ asset('assets/vendor/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/js-cookie/js.cookie.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/dropzone/dist/min/dropzone.min.js') }}"></script>
    @stack('js')

    <!-- Argon JS -->
    <script src="{{ asset('assets') }}/js/argon.js?v=1.1.0"></script>

</body>

</html>
