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
    <!-- Argon CSS -->
    <link type="text/css" href="{{ asset('assets') }}/css/argon.css?v=1.0.0" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets') }}/vendor/owl-carousel/owl.carousel.min.css">
    <link rel="stylesheet" href="{{asset('assets') }}/vendor/owl-carousel/owl.theme.green.min.css">

</head>

<body class="{{ $class ?? ""}}">
    {{-- header area --}}
    @include('layouts.ecommerce.nav.navbar')
    {{-- end of header area --}}

    {{-- start content area --}}
    @yield('content')
    {{-- end of content area --}}
   
    @include('layouts.ecommerce.footers.footer')

    <script src="{{asset('assets/vendor/owl-carousel/owl.carousel.js')}}"></script>
    <script src="{{asset('assets/vendor/owl-carousel/owl.carousel.min.js')}}"></script>
    <script src="{{ asset('assets/vendor/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('assets/vendor/js-cookie/js.cookie.js')}}"></script>
    <script src="{{ asset('assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js')}}"></script>
    <script src="{{ asset('assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js')}}"></script>

    @stack('js')

    <!-- Argon JS -->
    <script src="{{ asset('assets') }}/js/argon.js?v=1.0.0"></script>

</body>

</html>