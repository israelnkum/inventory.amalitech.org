@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-2">
                <button class="btn btn-link text-danger" data-toggle="modal" data-target="#addStudent">All Students</button>
            </div>
            <div class="col-md-6">
                <form class="needs-validation" novalidate>
                    <div class="form-row align-items-center">
                        <div class="col-md-12">
                            <div class="input-group mb-2">
                                <input type="text" required  class="form-control" id="search-input" placeholder="Type Search in Students">
                                <div class="input-group-prepend">
                                    <button type="submit" class="input-group-text">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary" data-toggle="modal" data-target="#addStudent">Add Student</button>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addStudent" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="" method="post" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New Student</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="first_name">First name</label>
                                <input type="text"  class="form-control" id="first_name" placeholder="First name"  required>
                                <div class="invalid-feedback">
                                    First name required
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="last_name">Last name</label>
                                <input type="text" class="form-control" id="last_name" placeholder="Last name" required>
                                <div class="invalid-feedback">
                                    Last name required
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="last_name">Other name</label>
                                <input type="text" class="form-control" id="last_name" placeholder="Last name">
                                <div class="invalid-feedback">
                                    Other name required
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Student</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
