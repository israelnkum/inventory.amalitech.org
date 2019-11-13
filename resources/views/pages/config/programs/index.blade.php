@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @component('partials.breadcrumb')
                <li class="breadcrumb-item" aria-current="page">
                    <a href="{{route('config')}}">Config</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{route('programs.index')}}">Programs</a>
                </li>
            @endcomponent
            <div class="col-md-12 text-right">
                <button class="btn btn-primary" data-toggle="modal" data-target="#addStudent">New Program</button>
            </div>
            <div class="col-md-10">
                <div class="card border-0 shadow-sm bg-transparent">
                    <div class="card-header text-danger">
                        All Programs
                    </div>
                    <div class="card-body">
                        <table id="programs" class="table-borderless table-striped table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Program Name</th>
                                <th>Prefix</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($i=1)
                            @foreach($allPrograms as $programs)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$programs->name}}</td>
                                    <td>{{$programs->prefix}}</td>
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
                <form action="{{route('programs.store')}}" method="post" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New Program</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="first_name">Program name</label>
                                <input type="text"  class="form-control" name="program_name" id="first_name" placeholder="Program name"  required>
                                <div class="invalid-feedback">
                                    Program name required
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="last_name">Prefix</label>
                                <input type="text" class="form-control" name="prefix" id="last_name" placeholder="Prefix" required>
                                <div class="invalid-feedback">
                                    Prefix required
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Program</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
