<!DOCTYPE html>
<!--
Template Name: Midone - HTML Admin Dashboard Template
Author: Left4code
Website: http://www.left4code.com/
Contact: muhammadrizki@left4code.com
Purchase: https://themeforest.net/user/left4code/portfolio
Renew Support: https://themeforest.net/user/left4code/portfolio
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="{{ app()->getLocale() }}">
    <!-- BEGIN: Head -->
    <head>
    	<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<title>@yield('titulo') | App HotelMoche</title>

        <meta charset="utf-8">
        <link href="{{ asset('dist/images/logo.svg') }}" rel="shortcut icon">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Midone admin is super flexible, powerful, clean & modern responsive tailwind admin template with unlimited possibilities.">
        <meta name="keywords" content="admin template, Midone admin template, dashboard template, flat admin template, responsive admin template, web app">
        <meta name="author" content="LEFT4CODE">

        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="{{ asset('dist/css/app.css') }}" />
        <!-- END: CSS Assets-->

        <script> 
            var _URL_HOME = '{{ URL_HOME }}';
            var _TokenUser = '{{ Auth::user()->api_token }}', _SessionToken = '{{ session('session_token') }}';
            var _UserCurrent = '{{ Auth::user()->name }}';

          </script>

        @section('losCSS')@show

    </head>
    <!-- END: Head -->
    <body class="app">
        <!-- BEGIN: Mobile Menu -->
        @include("layouts.menu")
        <!-- END: Mobile Menu -->
        <div class="flex">
            <!-- BEGIN: Side Menu -->
            @include("layouts.sidebar")
            <!-- END: Side Menu -->
            <!-- BEGIN: Content -->
            <div class="content">
                <!-- BEGIN: Top Bar -->
                @include("layouts.topBar")
                <!-- END: Top Bar -->

                <!-- #################################################### -->
                <!-- #################################################### -->
                <!-- #################################################### -->
                @yield('content')
                <!-- #################################################### -->
                <!-- #################################################### -->
                <!-- #################################################### -->

            </div>
            <!-- END: Content -->
        </div>
        <!-- BEGIN: JS Assets-->
        <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key={{ env("MAPS_KEY") }}&libraries=places"></script>
        <script src="{{ asset('dist/js/app.js') }}"></script>
        <!-- END: JS Assets-->

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>

        @section('scripts')@show

    </body>
</html>


