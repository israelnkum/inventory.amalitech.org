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
            <div class="col-md-10">
                <div class="card border-0 shadow-sm bg-transparent">
                    <div class="card-header text-danger p-2">
                        All Users
                        <button class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#addStudent">New User</button>
                    </div>
                    <div class="card-body">
                        <table id="programs" class="table-borderless table-striped table">
                            <thead>
                            <tr>
                                <th></th>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($i=1)
                            @foreach($users as $user)
                                <tr>
                                    <td></td>
                                    <td>{{$i}}</td>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->email}}</td>
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
            <div class="modal-content modal-sm">
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
                            <div class="col-md-12 mb-3">
                                <label for="name">Full Name</label>
                                <input type="text"  class="form-control" name="name" id="name" placeholder="Full Name"  required>
                                <div class="invalid-feedback">
                                    Full Name required
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
                                <div class="invalid-feedback">
                                    Email required
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="user_type" >User Type</label>
                                <select required id="user_type" style="width: 100%" name="user_type" class="form-control select2">
                                    <option value=""></option>
                                    <option value="Admin">Admin</option>
                                    <option value="Super Admin">Super Admin</option>
                                </select>
                                <div class="invalid-feedback">
                                    User Type required
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
