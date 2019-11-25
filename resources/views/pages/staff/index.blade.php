@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @component('partials.breadcrumb')
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{route('staff.index')}}">Staff</a>
                </li>
            @endcomponent
            <div class="col-md-6 offset-md-2">
                <form class="needs-validation" novalidate>
                    <div class="form-row align-items-center">
                        <div class="col-md-12">
                            <div class="input-group mb-2">
                                <input type="text"   class="form-control" id="search-input" placeholder="Type Search in Staff">
                                <div class="input-group-prepend">
                                    <button type="submit" class="input-group-text">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary" data-toggle="modal" data-target="#addStudent">Add Staff</button>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-transparent border-0 shadow-sm">
                    <div class="card-header">
                        Quick Filter
                    </div>
                    <div class="card-body bg-transparent ">
                        <form action="{{route('filter-staff')}}" method="get" id="filter-trainers-form">
                            @csrf
                            <div class="form-group row">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="filter-trainer-locations" class="mb-0">Location</label>
                                        <select  required id="filter-trainer-locations" style="width: 100%" name="location_id" class="form-control form-control-lg select2">
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
                                        <label for="filter-trainer-gender" class="mb-0">Gender</label>
                                        <select  required id="filter-trainer-gender" style="width: 100%" name="gender" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            <option {{ old('gender') == 'Male' ? 'selected' : '' }}  value="Male">Male</option>
                                            <option {{ old('gender') == 'Female' ? 'selected' : '' }}  value="Female">Female</option>
                                        </select>
                                    </div>
                                    {{--<div class="col-md-12 mb-2">
                                        <label for="filter-trainer-programs" class="mb-0">Specialization</label>
                                        <select required id="filter-trainer-programs" style="width: 100%" name="programs" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            @foreach($programs as $types)
                                                <option {{ old('programs') == $types->id ? 'selected' : '' }} value="{{$types->id}}">{{$types->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>--}}
                                    <div class="col-md-12">
                                        <label for="filter-trainer-designation" class="mb-0">Designation</label>
                                        <select  id="filter-trainer-designation" style="width: 100%" name="designation_id" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            @foreach($designations as $types)
                                                <option {{ old('designation_id') == $types->id ? 'selected' : '' }} value="{{$types->id}}">{{$types->designation}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                @if(count($staff) == 0)
                    <div class="text-center mt-5 col-md-12">
                        <h4 class="display font-weight-lighter text-danger">Oops! No Data Found</h4>
                    </div>
                @else
                    <div class="card bg-transparent border-0 shadow-sm">
                        <div class="card-header bg-transparent border-0 pb-0">
                            <span class="float-right">{!! $staff->fragment(Hash::make(time()))->render() !!}</span>
                        </div>
                        <div class="card-body">
                            <table class="table  table-hover">
                                <tbody>
                                @foreach ($staff as $trainer)
                                    <tr >
                                        <td class="text-center">
                                            <img height="auto" width="90"  src="{{asset('assets/img/profile/staff/'.$trainer->profile)}}" alt="" class="img-fluid">
                                        </td>
                                        <td class="text-uppercase text-left">
                                            <a class="text-decoration-none text-dark" href="{{route('staff.edit',$trainer->id)."?".Hash::make(time())}}">
                                                <b>{{$trainer->first_name." ".$trainer->other_name." ".$trainer->last_name}}</b><br>
                                                {{$trainer->registration_number}}<br>
                                                {{$trainer->phone_number}}<br>
                                                <span class="text-lowercase">{{$trainer->email}}</span>
                                            </a>
                                        </td>
                                        <td>
                                            {{$trainer->location->country}}<br>
                                            {{$trainer->location->city_town}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer bg-transparent pb-0">
                            <span class="float-right">{!! $staff->fragment(Hash::make(time()))->render() !!}</span>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addStudent" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{route('staff.store')}}" enctype="multipart/form-data" method="post" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New Staff</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-3">
                                <p class="text-danger text-center" id="displayError"> <b><span id="boldertext"></span></b></p>
                                <div class="picture-container">
                                    <div class="picture">
                                        <img src="{{asset('avata.png')}}" class="picture-src img-fluid" id="wizardPicturePreview" title="Click to select picture" />
                                        <input required  type="file" class="form-control" name="image_file" accept="image/*"   id="wizard-picture">
                                    </div>
                                    <h6>Choose Picture</h6>

                                    <small class="text-danger">
                                        413 x 513 pixels<br>
                                        Max Size - 500KB
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-row">
                                    <div class="col-md-4 mb-2">
                                        <label for="first_name" class="mb-0">First name</label>
                                        <input type="text" required  class="form-control form-control-sm" name="first_name" id="first_name" placeholder="First name"  >
                                        <div class="invalid-feedback">
                                            First name required
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="last_name" class="mb-0">Last name</label>
                                        <input type="text" required class="form-control form-control-sm" name="last_name" id="last_name" placeholder="Last name" >
                                        <div class="invalid-feedback">
                                            Last name required
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="other_name" class="mb-0">Other name</label>
                                        <input type="text"  class="form-control form-control-sm" name="other_name" id="other_name" placeholder="Other name">
                                        <div class="invalid-feedback">
                                            Other name
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="email" class="mb-0">Date of Birth</label>
                                        <input type="date" name="date_of_birth" required class="form-control form-control-sm" placeholder="Date of Birth">
                                        <div class="invalid-feedback">
                                            Date of Birth required
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="email" class="mb-0">Email</label>
                                        <input type="email" required class="form-control form-control-sm" name="email" id="email" placeholder="Email">
                                        <div class="invalid-feedback">
                                            Email required
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="designation" class="mb-0">Designation</label>
                                        <select required id="designation" style="width: 100%" name="designation" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            @foreach($designations as $types)
                                                <option {{ old('designation_id') == $types->id ? 'selected' : '' }} value="{{$types->id}}">{{$types->designation}}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Designation required
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label for="program" class="mb-0">Subject(s) Teaching</label>
                                        <select multiple required id="program" style="width: 100%" name="program[]" class="form-control form-control-lg select2">
                                            @foreach($programs as $program)
                                                <option value="{{$program->id}}">{{$program->name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Program required
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="filter-gender" class="mb-0">Gender</label>
                                        <select required id="filter-gender" style="width: 100%" name="gender" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="last_name" class="mb-0">Phone Number</label>
                                        <input type="text" required  class="form-control form-control-sm" name="phone_number" id="phone_number" placeholder="Phone Number">
                                        <div class="invalid-feedback">
                                            Phone Number required
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="location" class="mb-0">Location</label>
                                        <select required id="location" style="width: 100%" name="location_id" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            @foreach($locations as $location)
                                                <option value="{{$location->id}}">{{$location->country.", ".$location->city_town}}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Location required
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="joining_date" class="mb-0">Joining Date</label>
                                        <input type="date" id="joining_date" name="joining_date" required class="form-control form-control-sm" placeholder="Joining Date">
                                        <div class="invalid-feedback">
                                            Date  required
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="contract_valid_till" class="mb-0">Contract Valid Till</label>
                                        <input type="date" id="contract_valid_till" name="contract_valid_till" required class="form-control form-control-sm" placeholder="Contract Valid Till">
                                        <div class="invalid-feedback">
                                            Date  required
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label for="remarks" class="mb-0">Remarks</label>
                                        <textarea  maxlength="500" class="form-control"  name="remarks" id="remarks" cols="5" rows="2"></textarea>
                                        <div class="invalid-feedback">
                                            Remarks required
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Staff</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
