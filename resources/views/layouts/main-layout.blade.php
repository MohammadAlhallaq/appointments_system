<!--
=========================================================
* Material Dashboard 2 - v3.0.0
=========================================================

* Product Page: https://www.creative-tim.com/product/material-dashboard
* Copyright 2021 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en" dir="{{@app()->getLocale() == 'ar' ? 'rtl' : ''}}">

<head>
    @include('partials.header')
    @stack('js')
{{--    @inertiaHead--}}
</head>

<body class="g-sidenav-show {{@app()->getLocale() == 'ar' ? 'rtl' : ''}} bg-gray-200">
@include('partials.side-bar')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg {{@app()->getLocale() == 'ar' ? 'overflow-x-hidden' : ''}}">
    <!-- Navbar -->
    @include('partials.nav-bar')
    <!-- End Navbar -->
    <div class="container-fluid py-4">
        @yield('content')
    </div>
</main>
<!--   Core JS Files   -->
{{--@inertia--}}
@include('partials.footer')
@stack('js')
</body>

</html>
