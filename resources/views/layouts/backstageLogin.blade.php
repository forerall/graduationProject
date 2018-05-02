<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="{{url('/box/css/bootstrap.min.css')}}" rel="stylesheet"/>
    <!-- ace styles -->
    <link rel="stylesheet" href="{{url('/assets/css/ace.min.css')}}"/>
    @yield('style')
</head>
<body class="login-layout">
@yield('body')
<script src="{{url('/box/jquery-2.0.3.min.js')}}"></script>
@yield('script')
</body>
</html>
