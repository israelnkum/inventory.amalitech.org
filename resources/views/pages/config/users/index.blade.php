@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @component('partials.breadcrumb')
                <li class="breadcrumb-item" aria-current="page">
                    <a href="{{route('config')}}">Config</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{route('users.index')}}">Users</a>
                </li>
            @endcomponent
            <div class="col-md-12">
                <button type="button" class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#addStudent">New User</button>
            </div>
            <div class="col-md-2">
                <div class="card bg-transparent border-0 shadow-sm">
                    <div class="card-header">
                        Quick Filter
                    </div>
                    <div class="card-body bg-transparent ">
                        <form action="{{route('filter-users')}}" method="get" id="filter-users-form">
                            @csrf
                            <div class="form-group row">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="filter-users-locations" class="mb-0">Location</label>
                                        <select  required id="filter-users-locations" style="width: 100%" name="location_id" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            @foreach($locations as $types)
                                                <option {{ old('location_id') == $types->id ? 'selected' : '' }}  value="{{$types->id}}">{{$types->country.", ".$types->city_town}}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Items Type required
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label for="filter-users-gender" class="mb-0">Gender</label>
                                        <select  required id="filter-users-gender" style="width: 100%" name="gender" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            <option {{ old('gender') == 'Male' ? 'selected' : '' }}  value="Male">Male</option>
                                            <option {{ old('gender') == 'Female' ? 'selected' : '' }}  value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label for="filter-user-type" class="mb-0">User Type</label>
                                        <select required id="filter-user-type" style="width: 100%" name="user_type" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            <option {{ old('user_type') == "Super Admin" ? 'selected' : '' }} value="Super Admin">Super Admin</option>
                                            <option {{ old('user_type') == "Admin" ? 'selected' : '' }} value="Admin">Admin</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label for="filter-users-status" class="mb-0">Status</label>
                                        <select  required id="filter-users-status" style="width: 100%" name="user_status" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            <option {{ old('user_status') == '1' ? 'selected' : '' }}  value="1">Active</option>
                                            <option {{ old('user_status') == '2' ? 'selected' : '' }}  value="2">Deactivated</option>
                                            <option {{ old('user_status') == '3' ? 'selected' : '' }}  value="3">Not Logged in Yet</option>
                                        </select>
                                    </div>
                                    {{--<div class="col-md-12">
                                        <label for="filter-trainer-designation" class="mb-0">Designation</label>
                                        <select  id="filter-trainer-designation" style="width: 100%" name="designation_id" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            @foreach($designations as $types)
                                                <option {{ old('designation_id') == $types->id ? 'selected' : '' }} value="{{$types->id}}">{{$types->designation}}</option>
                                            @endforeach
                                        </select>
                                    </div>--}}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-10">
                <div class="card border-0 shadow-sm bg-transparent">
                    <div class="card-header text-danger p-2">
                        All Users
                        <div class="dropleft float-right">
                            <a href="javascript:void(0)" role="button" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="{{asset('menu.png')}}" class="img-fluid text-info" alt="" height="auto" width="20">
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">Action</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="users_table" class="table-borderless table-striped table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>User Type</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($i=1)
                            @foreach($users as $user)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$user->id}}</td>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>{{$user->user_type}}</td>
                                    <td>{{$user->location->city_town.", ".$user->location->country}}</td>
                                    <td>
                                        @if($user->updated == 0)
                                            <label class="badge badge-info">Not Logged In</label>
                                        @elseif($user->updated == 1  && $user->status == 0)
                                            <label class="badge badge-success">Active</label>
                                        @elseif($user->updated == 1  && $user->status == 1)
                                            <label class="badge badge-danger">Deactivated</label>
                                        @endif
                                    </td>
                                    <td><a href="{{route('users.show',$user->id)}}?{{Hash::make(time())}}" class="edit btn btn-sm btn-dark">View</a></td>
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
                <form action="{{route('users.store')}}" method="post" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="name">Full Name</label>
                                <input type="text"  class="form-control" name="name" id="name" placeholder="Full Name"  required>
                                <div class="invalid-feedback">
                                    Full Name required
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
                                <div class="invalid-feedback">
                                    Email required
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="user_type" >User Type</label>
                                <select required id="user_type" style="width: 100%" name="user_type" class="form-control select2">
                                    <option value=""></option>
                                    <option value="Admin">Admin</option>
                                    <option value="Viewer">Viewer</option>
                                    <option value="Store Manager">Store Manager</option>
                                    <option value="Super Admin">Super Admin</option>
                                </select>
                                <div class="invalid-feedback">
                                    User Type required
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="locations">Location</label>
                                <select  required id="locations" style="width: 100%" name="location_id" class="form-control form-control-lg select2">
                                    <option value=""></option>
                                    @foreach($locations as $types)
                                        <option value="{{$types->id}}">{{$types->country.", ".$types->city_town}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Location required
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add User</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
