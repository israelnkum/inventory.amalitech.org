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
            <div class="col-md-4">
                <div class="card-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Program</h5>
                </div>
                <div class="card-body">
                    <form action="{{route('programs.store')}}" method="post" class="needs-validation" novalidate>
                        @csrf
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
                                <input type="text" minlength="0" maxlength="3" class="form-control" name="prefix" id="last_name" placeholder="Prefix" required>
                                <div class="invalid-feedback">
                                    Prefix required
                                </div>
                            </div>
                            <div class="col-md-6 mt-4">
                                <button type="submit" class="btn btn-primary mt-3 btn-sm">Add Program</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card border-0 shadow-sm bg-transparent">
                    <form method="post" onsubmit="return  confirm('This will delete all Trainees and Trainers! \n\t\t Please Confirm Delete')" id="delete-program-form" action="{{route('delete-program')}}">
                        @csrf
                        <input type="hidden" id="program_ids" class="form-control-sm" name="selected_ids">
                        <div class="card-header text-danger p-2">
                            All sessions
                            <div class="dropleft float-right">
                                <button disabled id="btn-delete-program" class="btn btn-link text-danger text-decoration-none text-right" type="submit">Delete</button>

                                <a href="javascript:void(0)" role="button" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-dot-circle"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">Action</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="programs_table" class="table-borderless table-striped table">
                                <thead>
                                <tr>
                                    <td></td>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Program Name</th>
                                    <th>Prefix</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($i=1)
                                @foreach($allPrograms as $programs)
                                    <tr>
                                        <td></td>
                                        <td>{{$i}}</td>
                                        <td>{{$programs->id}}</td>
                                        <td>{{$programs->name}}</td>
                                        <td>{{$programs->prefix}}</td>
                                        <td><a href="javascript:void(0)" class="btn btn-success edit btn-sm">Edit</a></td>
                                    </tr>
                                    @php($i++)
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="edit-program-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="programs" id="edit-program-form" method="post" class="needs-validation" novalidate>
                    @csrf
                    {!! method_field('put') !!}
                    <div class="modal-header">
                        <h5 class="modal-title" id="program-title">New Program</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="first_name">Program name</label>
                                <input type="text"  class="form-control" name="program_name" id="edit-program-name" placeholder="Program name"  required>
                                <div class="invalid-feedback">
                                    Program name required
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="last_name">Prefix</label>
                                <input type="text" class="form-control" name="prefix" id="edit-prefix" placeholder="Prefix" required>
                                <div class="invalid-feedback">
                                    Prefix required
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Edit Program</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
