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
                                Configurations
                            @endcomponent
                        </div>
                        <div class="card-body bg-transparent text-center">
                            <div class="list-group bg-transparent list-group-horizontal">
                                <a class="list-group-item text-decoration-none bg-transparent border-left-0 text-muted" href="{{route('users.index')}}">
                                    <i class="fa fa-user"></i><br>
                                    Users
                                </a>
                                <a class="list-group-item text-decoration-none bg-transparent border-right-0 text-muted" href="{{route('programs.index')}}">
                                    <i class="fa fa-book"></i><br>
                                    Programs
                                </a>
                                <a class="list-group-item text-decoration-none bg-transparent border-right-0 text-muted" href="{{route('locations.index')}}">
                                    <i class="fa fa-map-marker-alt"></i><br>
                                    Locations
                                </a>
                                <a class="list-group-item text-decoration-none bg-transparent border-right-0 text-muted" href="{{route('categories.index')}}">
                                    <i class="fa fa-list-alt"></i><br>
                                    Categories
                                </a>
                                <a class="list-group-item text-decoration-none bg-transparent border-right-0 text-muted" href="{{route('areas.index')}}">
                                    <i class="fa fa-map-marker-alt"></i><br>
                                    Areas
                                </a>
                            </div>
                            <hr>
                            <div class="list-group bg-transparent list-group-horizontal">
                                <a class="list-group-item text-decoration-none bg-transparent border-right-0 text-muted" href="{{route('item-type.index')}}">
                                    <i class="fa fa-object-group"></i><br>
                                    Item Type
                                </a>
                                <a class="list-group-item text-decoration-none bg-transparent border-right-0 text-muted" href="{{route('items.index')}}">
                                    <i class="fa fa-object-ungroup"></i><br>
                                    Items
                                </a>
                                <a class="list-group-item text-decoration-none bg-transparent border-right-0 text-muted" href="{{route('brands.index')}}">
                                    <i class="fa fa-copyright"></i><br>
                                    Brand
                                </a>
                                <a class="list-group-item text-decoration-none bg-transparent border-right-0 text-muted" href="{{route('ownerships.index')}}">
                                    <i class="fa fa-user"></i><br>
                                    Ownership
                                </a>
                                <a class="list-group-item text-decoration-none bg-transparent border-right-0 text-muted" href="{{route('status.index')}}">
                                    <i class="fa fa-check-circle"></i><br>
                                    Status
                                </a>
                                <a class="list-group-item text-decoration-none bg-transparent border-right-0 text-muted" href="{{route('sessions.index')}}">
                                    <i class="fa fa-dot-circle"></i><br>
                                    Sessions
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
                    <div class="card-body text-center bg-transparent justify-content-center">
                        <ul class="list-group bg-transparent list-group-horizontal">
                            <li class="list-group-item bg-transparent border-left-0 text-muted">
                                <span class="font-weight-bold text-dark" style="font-size: 30px;">{{$users}}</span> <br> Users</li>
                            <li class="list-group-item bg-transparent border-left-0 text-muted">
                                <span class="font-weight-bold text-dark" style="font-size: 30px;">
                                    {{$programs}}</span> <br>Programs
                            </li>
                            <li class="list-group-item bg-transparent border-left-0 text-muted">
                                <span class="font-weight-bold text-dark" style="font-size: 30px;">
                                    {{$locations}}</span> <br>Locations
                            </li>
                            <li class="list-group-item bg-transparent border-left-0 text-muted">
                                <span class="font-weight-bold text-dark" style="font-size: 30px;">
                                    {{$categories}}</span> <br>Categories</li>
                        </ul>
                        <ul class="list-group bg-transparent list-group-horizontal">
                            <li class="list-group-item bg-transparent border-left-0 text-muted">
                                <span class="font-weight-bold text-dark" style="font-size: 30px;">
                                    {{$areas}}</span> <br>Areas
                            </li>
                            <li class="list-group-item bg-transparent border-left-0 text-muted">
                                <span class="font-weight-bold text-dark" style="font-size: 30px;">
                                    {{$itemType}}</span> <br>Item Types
                            </li>
                            <li class="list-group-item bg-transparent border-left-0 text-muted">
                                <span class="font-weight-bold text-dark" style="font-size: 30px;">
                                    {{$brand}}</span> <br>Brands
                            </li>
                            <li class="list-group-item bg-transparent border-left-0 text-muted">
                                <span class="font-weight-bold text-dark" style="font-size: 30px;">
                                    {{$ownership}}</span> <br>Ownerships
                            </li>
                        </ul>
                        <ul class="list-group bg-transparent list-group-horizontal">
                            <li class="list-group-item bg-transparent border-left-0 text-muted">
                                <span class="font-weight-bold text-dark" style="font-size: 30px;">
                                    {{$status}}</span> <br>Statuses
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
