<!DOCTYPE html>
<html dir="rtl">
  <head>
    <meta charset="utf-8" />
    <meta name="description" content="...." />
    <meta name="author" content="misara adel" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <title>{{ ucfirst(__(config('app.name'))) }} . @yield('title') </title>
    <link
      rel="shortcut icon"
      href="{{ asset('interest/assets/images/logo/icon.png') }}"
      type="image/x-icon"
    />

    <link rel="stylesheet" href="{{ asset('interest/assets/css/lib/animate.css') }}"/>
    <link rel="stylesheet" href="{{ asset('interest/assets/css/lib/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('interest/assets/css/lib/swiper-bundle.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('interest/assets/css/lib/jquery.fancybox.css') }}"/>
    <link rel="stylesheet" href="{{ asset('interest/assets/css/style.css') }}"/>

  </head>
  <body>


    @yield('content')

    <script src="{{ asset('interest/assets/js/lib/jquery4.js') }}"></script>
    <script src="{{ asset('interest/js/lib/popper.js') }}"></script>
    <script src="{{ asset('interest/js/lib/bootstrap.js') }}"></script>
    <script src="{{ asset('interest/js/lib/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('interest/js/lib/jquery.fancybox.js') }}"></script>
    <script src="{{ asset('interest/js/main.js') }}"></script>

  </body>
</html>
