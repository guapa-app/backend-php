<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="rtl">

    @include('frontend.includes.head-section')

    <body>

        @include('frontend.includes.header')

        @include('alert-message')

        @yield('content')

        @include('frontend.includes.footer')

    </body>

    @include('frontend.includes.scripts-section')

</html>
