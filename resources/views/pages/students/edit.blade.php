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
            <div class="col-md-8">
                <div class="card bg-transparent border-0 shadow-sm">
                    <div class="card-header p-2">
                        Trainee Info
                        @can('isSuperAdmin')
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#addStudent" class="btn btn-link p-0 float-right">Edit info</a>
                        @endcan
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-content-end">
                            <div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <img height="auto" width="500" src="{{asset('assets/img/profile/trainees/'.$trainee->profile)}}" alt="" class="img-fluid">
                                    </div>
                                    <div class="col-md-9">
                                        <h3>{{$trainee->first_name." ".$trainee->other_name." ".$trainee->last_name}}</h3>
                                        <h5>{{$trainee->registration_number}}</h5>
                                        <h5>{{$trainee->program->name}}</h5>
                                        <h6><span class="font-weight-bold">Session: </span>{{$trainee->session->name}}</h6>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <img height="auto" width="150" src="{{asset('assets/qr_codes/trainees/'.$trainee->qr_code)}}" alt="" class="img-fluid">
                                <form onsubmit="return confirm('Do you really want to delete?')" action="{{route('students.destroy',$trainee->id)}}" method="post">
                                    @csrf
                                    {!! @method_field('delete') !!}
                                    @can('isSuperAdmin')
                                        <button class="btn btn-link text-danger text-decoration-none" type="submit"> Delete</button>
                                    @endcan
                                </form>
                            </div>
                        </div>
                       {{-- <ul class="list-group list-group-horizontal justify-content-between">
                            <li class="list-group-item bg-transparent border-top-0 border-left-0 border-bottom-0 border-right"> </li>
                            <li class="list-group-item bg-transparent border-top-0 border-left-0 border-bottom-0 border-right"></li>
                            <li class="list-group-item bg-transparent border-top-0 border-left-0 border-bottom-0 border-right"></li>
                        </ul>
                        <ul class="list-group list-group-horizontal ">
                            <li class="list-group-item bg-transparent border-top-0 border-left-0 border-bottom-0 border-right"></li>
                            <li class="list-group-item bg-transparent border-top-0 border-left-0 border-bottom-0 border-right"></li>
                        </ul>--}}
                        <hr>
                        <table class="table table-borderless">
                            <tbody>
                            <tr>
                                <td><i class="fa fa-map-marker-alt"></i> {{$trainee->location->country.", ".$trainee->location->city_town}}</td>
                                <td><i class="fa fa-phone-alt"></i> {{$trainee->phone_number}}</td>
                                <td><i class="fa fa-envelope"></i> {{$trainee->personal_email}} (Personal)</td>
                            </tr>
                            <tr>
                                <td> <i class="fa fa-calendar-alt"></i> {{$trainee->dob}}</td>
                                <td> <i class="fa fa-genderless"></i> {{$trainee->gender}}</td>
                                <td><i class="fa fa-envelope"></i> {{$trainee->work_email}} (Work)</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-transparent border-0 shadow-sm">
                    <div class="card-header p-2">
                        Issued Item(s)
                    </div>
                    <div class="card-body">
                        @if(count($trainee->student_issue_item) == 0)
                            <div class="text-center text-danger">
                                No Item Issued
                            </div>
                        @else
                            <div class="accordion" id="accordionExample">
                                @foreach($trainee->student_issue_item as $student_issue)
                                    <a href="javascript:void(0)" class="font-weight-bold text-dark text-decoration-none" data-toggle="collapse" data-target="#std{{$student_issue->id}}" aria-expanded="true" aria-controls="collapseOne">
                                        {{$student_issue->item->asset_tag_number}}
                                        <small class="text-right bg-dark text-white p-1"> {{$student_issue->item->item_type->name}}</small>
                                    </a>
                                    <div id="std{{$student_issue->id}}" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <span class="font-weight-bold">Issued By:</span>
                                            {{$student_issue->issued_by}}
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="font-weight-bold">Date Issued:</span>
                                            {{$student_issue->date_collected}}
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="font-weight-bold">Remarks:</span>
                                            {{$student_issue->issue_remarks}}
                                        </div>
                                        <hr>
                                        @if($student_issue->date_returned =="")
                                            <span class="text-danger">Not Yet Returned</span>
                                        @else
                                            <div class="d-flex justify-content-between">
                                                <span class="font-weight-bold">Date returned:</span>
                                                {{$student_issue->date_returned}}
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="font-weight-bold">Received By:</span>
                                                {{$student_issue->received_by}}
                                            </div>
                                        @endif
                                        <div class="text-right">
                                            <a href="{{route('items.edit',$student_issue->item->id)}}" class="btn btn-sm btn-dark">View Item</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
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
                                    {{--<div class="col-md-4 mb-2">
                                        <label for="student_id_number" class="mb-0">Student ID Number</label>
                                        <input type="text" required  class="form-control form-control-sm" name="student_id_number" id="student_id_number" placeholder="ID Number">
                                        <div class="invalid-feedback">
                                            ID Number required
                                        </div>
                                    </div>--}}
                                    <div class="col-md-4 mb-2">
                                        <label for="email" class="mb-0">Date of Birth</label>
                                        <input type="date" value="{{$trainee->dob}}" name="date_of_birth" required class="form-control form-control-sm" placeholder="Date of Birth">
                                        <div class="invalid-feedback">
                                            Date of Birth required
                                        </div>
                                    </div>
                                    <div class="col-md-4 ">
                                        <label for="personal_email" class="mb-0">Personal Email</label>
                                        <input type="email" value="{{$trainee->personal_email}}" required class="form-control form-control-sm" name="personal_email" id="personal_email" placeholder="Personal Email">
                                        <div class="invalid-feedback">
                                            Email required
                                        </div>
                                    </div>
                                    <div class="col-md-4 ">
                                        <label for="work_email" class="mb-0">Work Email</label>
                                        <input type="email" value="{{$trainee->work_email}}" required class="form-control form-control-sm" name="work_email" id="work_email" placeholder="Work Email">
                                        <div class="invalid-feedback">
                                           Work Email required
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
                                    <div class="col-md-4 mb-2">
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
                                    <div class="col-md-4 mb-2">
                                        <label for="last_name" class="mb-0">Phone Number</label>
                                        <input type="text" required value="{{$trainee->phone_number}}" class="form-control form-control-sm" name="phone_number" id="phone_number" placeholder="Phone Number">
                                        <div class="invalid-feedback">
                                            Phone Number required
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
