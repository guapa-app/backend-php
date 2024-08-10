<head>
    <meta charset="utf-8"/>
    <meta name="description" content="guapa app"/>
    <meta name="author" content="guapa app"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>

    <title>{{ ucfirst(__(config('app.name'))) }} . @yield('title') </title>

    <link rel="shortcut icon" href="{{ asset('frontend/assets/images/logo/icon.png" type="image/x-icon') }}"/>
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/lib/animate.css') }}"/>
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/lib/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/lib/swiper-bundle.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/lib/jquery.fancybox.css') }}"/>
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}"/>

    @yield('heads')
</head>
