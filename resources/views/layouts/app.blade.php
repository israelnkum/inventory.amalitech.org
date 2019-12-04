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
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

</head>
<body style="background: #e9ecef @if(\Request::is('/') || \Request::is('login')) url('{{asset('inventory.jpg')}}') center center no-repeat; background-size: cover; @endif">
<div id="app">
    @guest

    @else
        @if(Gate::allows('hasUpdated'))
            <nav style="font-size: 13px;" class="navbar navbar-expand-md navbar-light bg-white shadow-sm sticky-top text-uppercase font-weight-bold">
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
                                <a class="nav-link" href="{{ route('students.index') }}">{{ __('Trainees') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('staff.index') }}">{{ __('Staff') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('stores.index') }}">{{ __('Store') }}</a>
                            </li>
                            @can('isSuperAdmin')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('items.index') }}">{{ __('Inventory') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('config') }}">{{ __('Config') }}</a>
                                </li>
                            @endcan
                        </ul>
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <img class="img-fluid rounded-circle" height="auto" width="30" src="{{asset('assets/img/profile/staff/'.Auth::user()->picture)}}" alt="">
                                </a>

                                <div class="dropdown-menu dropdown-menu-right text-capitalize" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{route('users.show',Auth::user()->id)}}">
                                        <i class="fa fa-user"></i> Profile
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
        @endif
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
