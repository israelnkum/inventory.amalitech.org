@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @component('partials.breadcrumb')
                <li class="breadcrumb-item" aria-current="page">
                    <a href="{{route('config')}}">Config</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{route('locations.index')}}">Locations</a>
                </li>
            @endcomponent
            <div class="col-md-10">
                <div class="card border-0 shadow-sm bg-transparent">
                    <div class="card-header text-danger p-2">
                        All Locations
                        <button class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#addStudent">New Location</button>
                    </div>
                    <div class="card-body">
                        <table id="programs" class="table-borderless table-striped table">
                            <thead>
                            <tr>
                                <th></th>
                                <th>#</th>
                                <th>Country</th>
                                <th>Country Prefix</th>
                                <th>City/Town</th>
                                <th>City/Town Prefix</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($i=1)
                            @foreach($locations as $location)
                                <tr>
                                    <td></td>
                                    <td>{{$i}}</td>
                                    <td>{{$location->country}}</td>
                                    <td>{{$location->country_prefix}}</td>
                                    <td>{{$location->city_town}}</td>
                                    <td>{{$location->city_town_prefix}}</td>
                                    <td>Delete/Edit</td>
                                </tr>
                                @php($i++)
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="addStudent" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{route('locations.store')}}" method="post" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New Program</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="col-md-6 mb-2">
                                <label for="country">Country</label>
                                <input type="text"  class="form-control form-control-sm" name="country" id="country" placeholder="Country name"  required>
                                <div class="invalid-feedback">
                                    Country name required
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="country_prefix">Country Prefix</label>
                                <input type="text" class="form-control form-control-sm" name="country_prefix" id="country_prefix" placeholder="Country Prefix" required>
                                <div class="invalid-feedback">
                                    Prefix required
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6 mb-2">
                                <label for="city_town">City/Town</label>
                                <input type="text"  class="form-control form-control-sm" name="city_town" id="city_town" placeholder="City/Town name"  required>
                                <div class="invalid-feedback">
                                    City/Town name required
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="city_town_prefix">City/Town Prefix</label>
                                <input type="text" class="form-control form-control-sm" name="city_town_prefix" id="city_town_prefix" placeholder="City/Town Prefix" required>
                                <div class="invalid-feedback">
                                    City/Town Prefix required
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Location</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
