@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row ">
            @component('partials.breadcrumb')
                <li class="breadcrumb-item" aria-current="page">
                    <a href="{{route('students.index')}}">Trainee</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{route('students.edit',$trainee->id)."?".Hash::make(time())}}">{{$trainee->registration_number}}</a>
                </li>
            @endcomponent
            <div class="col-md-7">
                <div class="card bg-transparent border-0 shadow-sm">
                    <div class="card-header p-2">
                        Trainee Info

                        <a href="javascript:void(0)" data-toggle="modal" data-target="#addStudent" class="btn btn-link p-0 float-right">Edit info</a>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-end">
                            <div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <img height="70" width="70" src="{{asset('assets/img/profile/trainees/'.$trainee->profile)}}" alt="" class="img-fluid">
                                    </div>
                                    <div class="col-md-8">
                                        <p>
                                            {{$trainee->first_name." ".$trainee->other_name." ".$trainee->last_name}}<br>
                                            {{$trainee->registration_number}}<br>
                                        </p>
                                        <small><i class="fa fa-map-marker-alt"></i> {{$trainee->location->country.", ".$trainee->location->city_town}}<br></small>
                                        <small><i class="fa fa-phone-alt"></i> {{$trainee->phone_number}}</small><br>
                                        <small><i class="fa fa-envelope"></i> {{$trainee->email}}</small><br>
                                        <small><i class="fa fa-calendar-alt"></i> {{$trainee->dob}}</small><br>
                                        <small><i class="fa fa-genderless"></i> {{$trainee->gender}}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <img height="70" width="70" src="{{asset('assets/qr_codes/trainees/'.$trainee->qr_code)}}" alt="" class="img-fluid">
                                <p>{{$trainee->program->name}}</p>
                                <p><span class="font-weight-bold">Session: </span>{{$trainee->session->name}}</p>
                                <form onsubmit="return confirm('Do you really want to delete?')" action="{{route('students.destroy',$trainee->id)}}" method="post">
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
                                    {{$trainee->first_name." ".$trainee->other_name." ".$trainee->last_name}}<br>
                                    {{$trainee->registration_number}}<br>
                                </p>
                                <small>{{$trainee->location->country.", ".$trainee->location->city_town}}<br></small>
                                <small class="mb-0">{{$trainee->phone_number}}</small><br>
                                <small class="mt-0">{{$trainee->email}}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="addStudent" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{route('students.update',$trainee->id)}}" enctype="multipart/form-data" method="post" class="needs-validation" novalidate>
                    @csrf
                    {!! method_field('put') !!}
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Info</h5>
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
                                        <img src="{{asset('assets/img/profile/trainees/'.$trainee->profile)}}" class="picture-src img-fluid" id="wizardPicturePreview" title="Click to select picture" />
                                        <input  type="file" class="form-control" name="image_file"   id="wizard-picture">
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
                                        <input type="text" value="{{$trainee->first_name}}" required  class="form-control form-control-sm" name="first_name" id="first_name" placeholder="First name"  >
                                        <div class="invalid-feedback">
                                            First name required
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="last_name" class="mb-0">Last name</label>
                                        <input type="text" value="{{$trainee->last_name}}" required class="form-control form-control-sm" name="last_name" id="last_name" placeholder="Last name" >
                                        <div class="invalid-feedback">
                                            Last name required
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="other_name" class="mb-0">Other name</label>
                                        <input type="text" value="{{$trainee->other_name}}" class="form-control form-control-sm" name="other_name" id="other_name" placeholder="Other name">
                                        <div class="invalid-feedback">
                                            Other name
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="email" class="mb-0">Date of Birth</label>
                                        <input type="date" value="{{$trainee->dob}}" name="date_of_birth" required class="form-control form-control-sm" placeholder="Date of Birth">
                                        <div class="invalid-feedback">
                                            Date of Birth required
                                        </div>
                                    </div>
                                    <div class="col-md-4 ">
                                        <label for="email" class="mb-0">Email</label>
                                        <input type="email" value="{{$trainee->email}}" required class="form-control form-control-sm" name="email" id="email" placeholder="Email">
                                        <div class="invalid-feedback">
                                            Email required
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="gender" class="mb-0">Gender</label>
                                        <select required id="gender" style="width: 100%" name="gender" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            <option @if($trainee->gender == "Male") selected @endif value="Male">Male</option>
                                            <option @if($trainee->gender == "Female") selected @endif value="Female">Female</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Gender required
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="program" class="mb-0">Specialization</label>
                                        <select required id="program" style="width: 100%" name="program" class="form-control form-control-lg select2">
                                            @foreach($programs as $program)
                                                <option @if($trainee->program_id == $program->id) selected @endif value="{{$program->id}}">{{$program->name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Specialization required
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="session" class="mb-0">Session</label>
                                        <select required id="session" style="width: 100%" name="session_id" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            @foreach($sessions as $session)
                                                <option @if($trainee->session_id == $session->id) selected @endif value="{{$session->id}}">{{$session->name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Session required
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="last_name" class="mb-0">Phone Number</label>
                                        <input type="text" required value="{{$trainee->phone_number}}" class="form-control form-control-sm" name="phone_number" id="phone_number" placeholder="Phone Number">
                                        <div class="invalid-feedback">
                                            Phone Number required
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="location" class="mb-0">Location</label>
                                        <select required id="location" style="width: 100%" name="location_id" class="form-control form-control-lg select2">
                                            @foreach($locations as $location)
                                                <option @if($trainee->location_id == $location->id) selected @endif value="{{$location->id}}">{{$location->country.", ".$location->city_town}}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Location required
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
