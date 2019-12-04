@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-sm-12">
                <div class="card bg-transparent shadow-sm border-0">
                    <div class="card bg-transparent shadow-sm border-0">
                        <div class="card-header bg-transparent">
                            @component('partials.hero')
                                Dashboard
                            @endcomponent
                        </div>
                        <div class="card-body text-center p-5">
                            <div class="row">
                                <div class="col-md-3 dash-list">
                                    <a href="{{route('students.index')}}" class="  text-decoration-none text-dark p-4">
                                        <i class="fa fa-users" style="font-size: 25px"></i> Trainees
                                    </a>
                                </div>
                                <div class="col-md-3 dash-list">
                                    <a href="{{route('staff.index')}}" class="  text-decoration-none text-dark p-4">
                                        <i class="fa fa-users" style="font-size: 25px"></i>
                                        Staff</a>
                                </div>
                                <div class="col-md-3 dash-list">
                                    <a href="{{route('items.index')}}" class="  text-decoration-none text-dark p-4">
                                        <i class="fa fa-boxes" style="font-size: 25px"></i>
                                        Inventory</a>
                                </div>
                                <div class="col-md-3 dash-list  text-center">
                                    <a href="{{route('stores.index')}}" class="text-decoration-none text-dark p-4">
                                        <i class="fa fa-store" style="font-size: 25px"></i> Store</a>
                                </div>
                                <div class="col-md-3 dash-list">
                                    @can('isSuperAdmin')
                                        <a href="{{route('config')}}" class="  text-decoration-none text-dark p-4">
                                            <i class="fa fa-cogs" style="font-size: 25px"></i> Config</a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{--<div class="col-md-4">
                <div class="card bg-transparent shadow-sm border-0">
                    <div class="card-header bg-transparent">
                        @component('partials.hero')
                            Summary
                        @endcomponent
                    </div>
                    <div class="card-body bg-transparent text-center">
                        <ul class="list-group bg-transparent list-group-horizontal">
                            <li class="list-group-item bg-transparent border-left-0 text-muted"><span class="font-weight-bold text-dark" style="font-size: 30px;">{{$allStudents}}</span> <br>Total Students</li>
                            <li class="list-group-item bg-transparent border-left-0 text-muted"><span class="font-weight-bold text-dark" style="font-size: 30px;">{{$allItems}}</span> <br>Total Items</li>
                            <li class="list-group-item bg-transparent border-right-0 text-muted"><span class="font-weight-bold text-dark" style="font-size: 30px;">1000</span> <br>Collected Items</li>
                        </ul>
                    </div>
                </div>
            </div>--}}
        </div>
    </div>
@endsection
