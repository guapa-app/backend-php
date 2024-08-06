<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="rtl">

    @include('frontend.includes.head-section')

    <body>

    @if(!Route::is(['login', 'register.form']))
        @include('frontend.includes.header')
    @endif

    @yield('content')

    @if(!Route::is(['login', 'register.form']))
        @include('frontend.includes.footer')
    @endif

    </body>

    @include('frontend.includes.scripts-section')

</html>
