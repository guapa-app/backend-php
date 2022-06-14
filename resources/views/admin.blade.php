<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="/admin" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Cosmo App') }}</title>

    <!-- Styles -->
    <!--<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.css" />-->
    <link rel="stylesheet" type="text/css" href="{{ mix('/css/admin.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
        window.baseUrl = "{!! config('app.url') !!}";
    </script>
    <style type="text/css">
        html, body { margin: 0; padding: 0; }
    </style>
</head>
<body>
<div id="admin"></div>
<!-- Scripts -->
<script src="{{mix('/js/admin.js')}}"></script>
</body>
</html>
