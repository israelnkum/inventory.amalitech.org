@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row ">
            @component('partials.breadcrumb')
                <li class="breadcrumb-item" aria-current="page">
                    <a href="{{route('all-staff')}}">All Staff</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{route('staff.edit',$trainer->id)."?".Hash::make(time())}}">{{$trainer->first_name." ".$trainer->other_name." ".$trainer->last_name}}</a>
                </li>
            @endcomponent
            <div class="col-md-7">
                <div class="card bg-transparent border-0 shadow-sm">
                    <div class="card-header p-2">
                        Staff Info
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#addStudent" class="btn btn-link p-0 float-right">Edit info</a>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-end">
                            <div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <img height="70" width="70" src="{{asset('assets/img/profile/staff/'.$trainer->profile)}}" alt="" class="img-fluid">
                                    </div>
                                    <div class="col-md-8">
                                        <p>
                                            {{$trainer->first_name." ".$trainer->other_name."".$trainer->last_name}}<br>
                                            {{--                                            {{$trainer->registration_number}}<br>--}}
                                        </p>
                                        <small><i class="fa fa-map-marker-alt"></i> {{$trainer->location->country.", ".$trainer->location->city_town}}<br></small>
                                        <small><i class="fa fa-phone-alt"></i> {{$trainer->phone_number}}</small><br>
                                        <small><i class="fa fa-envelope"></i> {{$trainer->email}}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <form onsubmit="return confirm('Do you really want to delete?')" action="{{route('staff.destroy',$trainer->id)}}" method="post">
                                    @csrf
                                    {!! @method_field('delete') !!}
                                    <button class="btn btn-link text-danger text-decoration-none" type="submit"><i class="fa fa-trash-alt"></i> Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card bg-transparent border-0 shadow-sm">
                    <div class="card-header p-2">
                        Issued Items
                    </div>
                    <div class="card-body">
                        <div class="d-flex  align-items-center">
                            <div class="ml-3">
                                <p>
                                    {{$trainer->first_name." ".$trainer->other_name."".$trainer->last_name}}<br>
                                    {{$trainer->registration_number}}<br>
                                </p>
                                <small>{{$trainer->location->country.", ".$trainer->location->city_town}}<br></small>
                                <small class="mb-0">{{$trainer->phone_number}}</small><br>
                                <small class="mt-0">{{$trainer->email}}</small>
                            </div>
                        </div>
                        <div class="">
                            <p class="font-weight-bold">Remarks</p>
                            {{$trainer->remarks}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card bg-transparent border-0 shadow-sm">
                    <div class="card-header p-2 text-danger">
                        Subject(s) Teaching
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush ">
                            @foreach($trainer->program_teaching as $subject)
                                <li class="list-group-item bg-transparent p-0 d-flex justify-content-between align-items-center">
                                    {{$subject->program->name}}
                                    <form class="p-0" action="{{route('delete-staff-subject')}}" onsubmit="return confirm('Please confirm Delete!')" method="post">
                                        @csrf
                                        <input type="hidden" value="{{$subject->id}}" name="subject_id">
                                        <input type="hidden" value="{{$trainer->id}}" name="staff_id">
                                        <button class="p-0 btn btn-link text-decoration-none text-danger" type="submit">Delete</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="addStudent" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{route('staff.update',$trainer->id)}}" enctype="multipart/form-data" method="post" class="needs-validation" novalidate>
                    @csrf
                    {!! method_field('put') !!}
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New Student</h5>
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
                                        <img src="{{asset('assets/img/profile/staff/'.$trainer->profile)}}" class="picture-src img-fluid" id="wizardPicturePreview" title="Click to select picture" />
                                        <input  type="file" class="form-control" name="image_file" accept="image/*"  id="wizard-picture">
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
                                        <input type="text" value="{{$trainer->first_name}}" required  class="form-control form-control-sm" name="first_name" id="first_name" placeholder="First name"  >
                                        <div class="invalid-feedback">
                                            First name required
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="last_name" class="mb-0">Last name</label>
                                        <input type="text" value="{{$trainer->last_name}}" required class="form-control form-control-sm" name="last_name" id="last_name" placeholder="Last name" >
                                        <div class="invalid-feedback">
                                            Last name required
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="other_name" class="mb-0">Other name</label>
                                        <input type="text" value="{{$trainer->other_name}}" class="form-control form-control-sm" name="other_name" id="other_name" placeholder="Other name">
                                        <div class="invalid-feedback">
                                            Other name
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="email" class="mb-0">Date of Birth</label>
                                        <input type="date" value="{{$trainer->dob}}" name="date_of_birth" required class="form-control form-control-sm" placeholder="Date of Birth">
                                        <div class="invalid-feedback">
                                            Date of Birth required
                                        </div>
                                    </div>
                                    <div class="col-md-4 ">
                                        <label for="email" class="mb-0">Email</label>
                                        <input type="email" value="{{$trainer->email}}" required class="form-control form-control-sm" name="email" id="email" placeholder="Email">
                                        <div class="invalid-feedback">
                                            Email required
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="designation"  class="mb-0">Designation</label>
                                        <input type="text" value="{{$trainer->designation}}" required class="form-control form-control-sm" name="designation" id="designation" placeholder="Designation">
                                        <div class="invalid-feedback">
                                            Designation required
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label for="program" class="mb-0">Specialization</label>
                                        <select id="program" multiple style="width: 100%" name="program[]" class="form-control form-control-lg select2">
                                            @foreach($programs as $program)
                                                @if(in_array($program->id,$subjects))
{{--                                                    <option readonly value="{{$program->id}}">{{$program->name}}</option>--}}
                                                @else
                                                    <option value="{{$program->id}}">{{$program->name}}</option>
                                                @endif
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
                                            <option @if($trainer->gender == "Male") selected @endif value="Male">Male</option>
                                            <option @if($trainer->gender == "Female") selected @endif value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="last_name" class="mb-0">Phone Number</label>
                                        <input type="text" required value="{{$trainer->phone_number}}" class="form-control form-control-sm" name="phone_number" id="phone_number" placeholder="Phone Number">
                                        <div class="invalid-feedback">
                                            Phone Number required
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="location" class="mb-0">Location</label>
                                        <select required id="location" style="width: 100%" name="location_id" class="form-control form-control-lg select2">
                                            @foreach($locations as $location)
                                                @if($trainer->location_id == $location->id)
                                                    <option selected value="{{$location->id}}">{{$location->country.", ".$location->city_town}}</option>
                                                @else
                                                    <option value="{{$location->id}}">{{$location->country.", ".$location->city_town}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Location required
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="joining_date" class="mb-0">Joining Date</label>
                                        <input type="date" id="joining_date" value="{{$trainer->joining_date}}" name="joining_date" required class="form-control form-control-sm" placeholder="Joining Date">
                                        <div class="invalid-feedback">
                                            Date  required
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="contract_valid_till" class="mb-0">Contract Valid Till</label>
                                        <input type="date" id="contract_valid_till" value="{{$trainer->contract_valid_till}}" name="contract_valid_till" required class="form-control form-control-sm" placeholder="Contract Valid Till">
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
                        <button type="submit" class="btn btn-primary">Update Info</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
