@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @component('partials.breadcrumb')
                <li class="breadcrumb-item" aria-current="page">
                    <a href="{{route('config')}}">Config</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{route('item-type.index')}}">Item Type</a>
                </li>
            @endcomponent
            <div class="col-md-4">
                <div class="card  border-0 shadow-sm bg-transparent">
                    <div class="card-header p-2">
                        New Item Type
                    </div>
                    <div class="card-body">
                        <form action="{{route('item-type.store')}}" method="post" class="needs-validation" novalidate>
                            @csrf
                            <div class="form-row">
                                <div class="col-md-12 mb-3">
                                    <label for="item_type_name">Item Type</label>
                                    <input type="text"  class="form-control" name="item_type" id="item_type_name" placeholder="Item Type"  required>
                                    <div class="invalid-feedback">
                                        Item Type name required
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Type</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card border-0 shadow-sm bg-transparent">
                    <form method="post" onsubmit="return  confirm('Confirm Delete')" id="delete-item-type-form" action="{{route('delete-item-type')}}">
                        @csrf
                        <input type="hidden" id="item_type_ids" class="form-control-sm" name="selected_ids">
                        <div class="card-header text-danger p-2">
                            All Item Types
                            <div class="dropleft float-right">
                                <button disabled id="btn-delete-item-type" class="btn btn-link text-danger text-decoration-none text-right" type="submit">Delete</button>

                                <a href="javascript:void(0)" role="button" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-dot-circle"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">Action</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="item_type_table" class="table-borderless table-striped table">
                                <thead>
                                <tr>
                                    <td></td>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Item Type</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($i=1)
                                @foreach($item_types as $type)
                                    <tr>
                                        <td></td>
                                        <td>{{$i}}</td>
                                        <td>{{$type->id}}</td>
                                        <td>{{$type->name}}</td>
                                        <td>
                                            <a title="Edit {{$type->name}}" href="javascript:void(0)" class="edit text-decoration-none">
                                                Edit <i class="fa fa-pen"></i>
                                            </a>
                                        </td>
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
    <div class="modal fade" id="edit-item-type-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="edit-item-type-form" method="post" class="needs-validation" novalidate>
                    @csrf
                    {!! method_field('put') !!}
                    <div class="modal-header">
                        <h5 class="modal-title" id="item-type-title">New Program</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="edit-item-type-name">Item Type</label>
                                <input type="text"  class="form-control" name="item_type_name" id="edit-item_type_name" placeholder="item_type name"  required>
                                <div class="invalid-feedback">
                                    Item Type required
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Edit Type</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
