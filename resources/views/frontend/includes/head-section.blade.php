<head>
    <meta charset="utf-8" />
    <meta name="description" content="guapa app" />
    <meta name="author" content="guapa app" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}" />

    <!-- Meta Tags -->
    <meta name="description"
          content="قوابا - عالم الجمال. منصة تهدف إلى تمكين الوصول لجميع ما يتعلق بعالم الجمال والاطلاع على أفضل العروض الخاصة بالإجراءات التجميلية الجراحية وغير الجراحية.">
    <meta name="keywords" content="قوابا, عالم الجمال, خدمات تجميلية, إجراءات تجميلية, منتجات تجميلية, عروض تجميلية">
    <meta property="og:title" content="قوابا - عالم الجمال">
    <meta property="og:description"
          content="قوابا منصة تهدف إلى تمكين الوصول لجميع ما يتعلق بعالم الجمال والاطلاع على أفضل العروض الخاصة بالإجراءات التجميلية الجراحية وغير الجراحية.">
    <meta property="og:image" content="{{ asset('frontend/assets/images/intro/intro.svg') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <title> قوابا - منصة الجمال والإجراءات التجميلية في السعودية | Guapa </title>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-11502298872"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'AW-11502298872');
    </script>

    <link rel="shortcut icon" href="{{ asset('frontend/assets/images/logo/icon.png" type="image/x-icon') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/lib/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/lib/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/lib/swiper-bundle.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/lib/jquery.fancybox.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}" />

    @yield('heads')
</head>
