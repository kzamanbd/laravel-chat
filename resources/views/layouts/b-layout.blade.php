<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('chat-ui/images/favicon.ico') }}" />
    <!-- magnific-popup css -->
    <link href="{{ asset('chat-ui/libs/magnific-popup/magnific-popup.css') }}" rel="stylesheet" />
    <!-- owl.carousel css -->
    <link rel="stylesheet" href="{{ asset('chat-ui/libs/owl.carousel/assets/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('chat-ui/libs/owl.carousel/assets/owl.theme.default.min.css') }}" />
    <!-- Bootstrap Css -->
    <link href="{{ asset('chat-ui/css/bootstrap.min.css') }}" rel="stylesheet" />
    <!-- Icons Css -->
    <link href="{{ asset('chat-ui/css/icons.min.css') }}" rel="stylesheet" />
    <!-- App Css-->
    <link href="{{ asset('chat-ui/css/app.css') }}" rel="stylesheet" />
    <link href="{{ asset('chat-ui/css/waves.css') }}" rel="stylesheet" />
</head>

<body>
    {{ $slot }}
    <!-- JAVASCRIPT -->
    <script src="{{ asset('chat-ui/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('chat-ui/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('chat-ui/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('chat-ui/libs/node-waves/waves.min.js') }}"></script>
    <!-- Magnific Popup-->
    <script src="{{ asset('chat-ui/libs/magnific-popup/jquery.magnific-popup.min.js') }}"></script>
    <!-- owl.carousel js -->
    <script src="{{ asset('chat-ui/libs/owl.carousel/owl.carousel.min.js') }}"></script>
    <!-- page init -->
    <script src="{{ asset('chat-ui/js/pages/index.init.js') }}"></script>
    <script src="{{ asset('chat-ui/js/app.js') }}"></script>
</body>

</html>
