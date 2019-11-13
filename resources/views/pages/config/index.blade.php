@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @component('partials.breadcrumb')
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{route('config')}}">Config</a>
                </li>
            @endcomponent
            <div class="col-md-7">
                <div class="card bg-transparent shadow-sm border-0">
                    <div class="card bg-transparent shadow-sm border-0">
                        <div class="card-header bg-transparent">
                            @component('partials.hero')
                                Dashboard
                            @endcomponent
                        </div>
                        <div class="card-body bg-transparent text-center">
                            <div class="list-group bg-transparent list-group-horizontal">
                                <a class="list-group-item bg-transparent border-left-0 text-muted" href="{{route('users.index')}}">
                                    Users
                                </a>
                                <a class="list-group-item bg-transparent border-right-0 text-muted" href="{{route('programs.index')}}">
                                    Programs
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card bg-transparent shadow-sm border-0">
                    <div class="card-header bg-transparent">
                        @component('partials.hero')
                            Summary
                        @endcomponent
                    </div>
                    <div class="card-body bg-transparent text-center">
                        <ul class="list-group bg-transparent list-group-horizontal">
                            <li class="list-group-item bg-transparent border-left-0 text-muted">
                                <span class="font-weight-bold text-dark" style="font-size: 30px;">{{$users}}</span> <br>Total Users</li>
                            <li class="list-group-item bg-transparent border-left-0 text-muted">
                                <span class="font-weight-bold text-dark" style="font-size: 30px;">{{$programs}}</span> <br>Total Programs</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
