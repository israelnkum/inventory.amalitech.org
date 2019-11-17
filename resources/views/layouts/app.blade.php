<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AMALITECH - INVENTORY') }}</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

</head>
<body style="background: #e9ecef @if(\Request::is('/')) url('{{asset('inventory.jpg')}}') center center no-repeat; background-size: cover; @endif">
<div id="app">
    @guest

    @else
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm sticky-top">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img height="auto" width="200" src="{{asset('logo.png')}}" alt="Amalitech-Inventory" class="img-fluid">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('students.index') }}">{{ __('Trainee') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('staff.index') }}">{{ __('Staff') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('items.index') }}">{{ __('Items') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('items.index') }}">{{ __('Store') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('config') }}">{{ __('Config') }}</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <img class="img-fluid rounded-circle" height="auto" width="30" src="{{asset('avata.png')}}" alt="">
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}">
                                    <i class="fa fa-cog"></i> Settings
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    <i class="fa fa-sign-in-alt"></i>  {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    @endguest

    <main class="py-4">
        @yield('content')
    </main>


    <div class="row fixed-bottom">
        <div class="col-md-12">
            @include('layouts.messages')
        </div>
    </div>
</div>
<script src="{{ mix('js/app.js') }}" defer></script>
</body>
</html>
