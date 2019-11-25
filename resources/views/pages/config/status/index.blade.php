@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @component('partials.breadcrumb')
                <li class="breadcrumb-item" aria-current="page">
                    <a href="{{route('config')}}">Config</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{route('status.index')}}">Status</a>
                </li>
            @endcomponent
            <div class="col-md-4">
                <div class="card  border-0 shadow-sm bg-transparent">
                    <div class="card-header p-2">
                        New status
                    </div>
                    <div class="card-body">
                        <form action="{{route('status.store')}}" method="post" class="needs-validation" novalidate>
                            @csrf
                            <div class="form-row">
                                <div class="col-md-12 mb-3">
                                    <label for="status_name">Status</label>
                                    <input type="text"  class="form-control" name="status_name" id="status_name" placeholder="Status"  required>
                                    <div class="invalid-feedback">
                                        Status required
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary float-right btn-sm"><i class="fa fa-save"></i> Add Status</button>
                        </form>
                    </div>
                </div>

                <div class="card mt-5 border-0 shadow-sm bg-transparent">
                    <div class="card-header p-2">
                        Default status
                    </div>
                    <div class="card-body">
                        <table class="table-borderless table-striped table">
                            <tbody>
                            @php($i=1)
                            @foreach($statuses as $status)
                                @if($status->name == "In-Service" || $status->name == "In-Store")
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{$status->name}}</td>
                                    </tr>
                                @endif
                                @php($i++)
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card border-0 shadow-sm bg-transparent">
                    <form method="post" onsubmit="return  confirm('Confirm Delete')" id="delete-status-form" action="{{route('delete-status')}}">
                        @csrf
                        <input type="hidden" id="status_ids" class="form-control-sm" name="selected_ids">
                        <div class="card-header text-danger p-2">
                            All Statuses
                            <div class="dropleft float-right">
                                <button disabled id="btn-delete-status" class="btn btn-link text-danger text-decoration-none text-right" type="submit">Delete</button>

                                <a href="javascript:void(0)" role="button" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-dot-circle"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">Action</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="status_table" class="table-borderless table-striped table">
                                <thead>
                                <tr>
                                    <td></td>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($i= -1)
                                @foreach($statuses as $status)
                                    @if($status->name != "In-Service" && $status->name != "In-Store")
                                        <tr>
                                            <td></td>
                                            <td>{{$i}}</td>
                                            <td>{{$status->id}}</td>
                                            <td>{{$status->name}}</td>
                                            <td>
                                                <a  title="Edit {{$status->name}}" href="javascript:void(0)" class="edit btn btn-secondary btn-sm text-decoration-none">
                                                    Edit
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
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
    <div class="modal fade" id="edit-status-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="edit-status-form" method="post" class="needs-validation" novalidate>
                    @csrf
                    {!! method_field('put') !!}
                    <div class="modal-header">
                        <h5 class="modal-title" id="status-title">New Program</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="edit-status_name">Status</label>
                                <input type="text"  class="form-control" name="status_name" id="edit-status_name" placeholder="Status name"  required>
                                <div class="invalid-feedback">
                                    Status name required
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Edit Status</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
