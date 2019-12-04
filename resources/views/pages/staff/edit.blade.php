@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row ">
            @component('partials.breadcrumb')
                <li class="breadcrumb-item" aria-current="page">
                    <a href="{{route('staff.index')}}">Staff</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{route('staff.edit',$trainer->id)."?".Hash::make(time())}}">{{$trainer->first_name." ".$trainer->other_name." ".$trainer->last_name}}</a>
                </li>
            @endcomponent
            <div class="col-md-8">
                <div class="card bg-transparent border-0 shadow-sm">
                    <div class="card-header p-2">
                        Staff Info
                        @can('isSuperAdmin')
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#addStudent" class="btn btn-link p-0 float-right">Edit info</a>
                        @endcan
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-end">
                            <div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <img src="{{asset('assets/img/profile/staff/'.$trainer->profile)}}" alt="" class="img-fluid">
                                    </div>
                                    <div class="col-md-9 d-flex justify-content-between">
                                        <div>
                                            <h3><i class="fa fa-user-alt" style="font-size: 12px"></i> {{$trainer->first_name." ".$trainer->other_name." ".$trainer->last_name}}<br></h3>
                                            <h5><i class="fa fa-map-marker-alt" style="font-size: 12px"></i> {{$trainer->location->city_town.", ".$trainer->location->country}}<br></h5>
                                            <h5><i class="fa fa-phone-alt" style="font-size: 12px"></i> {{$trainer->phone_number}}</h5>
                                            <h5><i class="fa fa-envelope" style="font-size: 12px"></i> {{$trainer->personal_email}}</h5>
                                            <h5><i class="fa fa-envelope" style="font-size: 12px"></i> {{$trainer->work_email}}</h5>
                                        </div>

                                        <div class="text-center">
                                            <p class="font-weight-bold text-danger">{{$trainer->registration_number}}</p>
                                            <img height="auto" width="150" src="{{asset('assets/qr_codes/staff/'.$trainer->qr_code)}}" alt="" class="img-fluid">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table p-0  table-borderless ">
                                    <tbody class="text-left">
                                    <tr>
                                        <td class="p-2 font-weight-bold">Date of Birth:</td>
                                        <td class="p-2">{{$trainer->dob}}</td>
                                    </tr>
                                    <tr>
                                        <td class="p-2 font-weight-bold">Joining Date: </td>
                                        <td class="p-2">{{$trainer->joining_date}}</td>
                                    </tr>
                                    <tr>
                                        <td class="p-2 font-weight-bold">Contract Valid Till: </td>
                                        <td class="p-2">{{$trainer->contract_valid_till}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-transparent border-0 ">
                                    <div class="card-header bg-transparent font-weight-bold p-1 m-0">
                                        Designation(s)
                                        @can('isSuperAdmin')
                                            <button type="submit" data-toggle="modal" data-target="#addDesignation" class="btn btn-primary btn-sm float-right">Add Designation</button>
                                        @endcan
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush ">
                                            @foreach($trainer->staff_designation as $designation)
                                                <li class="list-group-item bg-transparent p-0 d-flex justify-content-between align-items-center">
                                                    {{$designation->designation->designation}}
                                                    @can('isSuperAdmin')
                                                        <form class="p-0" action="{{route('delete-staff-designation')}}" onsubmit="return confirm('Please confirm Delete!')" method="post">
                                                            @csrf
                                                            <input type="hidden" value="{{$designation->id}}" name="designation_id">
                                                            <input type="hidden" value="{{$trainer->id}}" name="staff_id">
                                                            <button class="p-0 btn-sm btn text-danger text-decoration-none" title="Delete" type="submit">Delete</button>
                                                        </form>
                                                    @endcan
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <hr>
                                <p ><span class="font-weight-bold">Remarks</span> <br>{{$trainer->remarks}}</p>
                                <hr>
                            </div>
                            <div class="col-md-12">
                                @can('isSuperAdmin')
                                    <div class="">
                                        <form onsubmit="return confirm('Do you really want to delete?')" action="{{route('staff.destroy',$trainer->id)}}" method="post">
                                            @csrf
                                            {!! @method_field('delete') !!}
                                            <button class="btn btn-link text-danger text-decoration-none" type="submit">Delete Staff</button>
                                        </form>
                                    </div>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-transparent border-0 shadow-sm">
                    <div class="card-header p-2 font-weight-bold">
                        Subject(s) Teaching
                        @can('isSuperAdmin')
                            <button type="submit" data-toggle="modal" data-target="#addProgram" class="btn btn-primary btn-sm float-right">Add Program</button>
                        @endcan
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush ">
                            @foreach($trainer->program_teaching as $subject)
                                <li class="list-group-item bg-transparent p-0 d-flex justify-content-between align-items-center">
                                    {{$subject->program->name}}
                                    @can('isSuperAdmin')
                                        <form class="p-0" action="{{route('delete-staff-subject')}}" onsubmit="return confirm('Please confirm Delete!')" method="post">
                                            @csrf
                                            <input type="hidden" value="{{$subject->id}}" name="subject_id">
                                            <input type="hidden" value="{{$trainer->id}}" name="staff_id">
                                            <button class="p-0 btn-sm btn btn-link text-decoration-none text-danger" type="submit">Delete</button>
                                        </form>
                                    @endcan
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <hr>
                <div class="card bg-transparent border-0 shadow-sm">
                    <div class="card-header p-2 font-weight-bold">
                        Issued Items
                    </div>
                    <div class="card-body ">
                        @if(count($trainer->staff_issue_item) == 0)
                            <div class="text-center">
                                <span class="text-dark">No Item Issued</span>
                            </div>
                        @else
                            <div class="accordion" id="accordionExample">
                                @foreach($trainer->staff_issue_item as $staff_issue)
                                    <a href="javascript:void(0)" class="font-weight-bold text-dark text-decoration-none" data-toggle="collapse" data-target="#std{{$staff_issue->id}}" aria-expanded="true" aria-controls="collapseOne">
                                        {{$staff_issue->item->asset_tag_number}}
                                        <small class="text-right bg-dark text-white p-1"> {{$staff_issue->item->item_type->name}}</small>
                                    </a>
                                    <div id="std{{$staff_issue->id}}" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <span class="font-weight-bold">Issued By:</span>
                                            {{$trainer->issued_by}}
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="font-weight-bold">Date Issued:</span>
                                            {{$staff_issue->date_collected}}
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="font-weight-bold">Remarks:</span>
                                            {{$staff_issue->issue_remarks}}
                                        </div>
                                        <hr>
                                        @if($staff_issue->date_returned =="")
                                            <span class="text-danger">Not Yet Returned</span>
                                        @else
                                            <div class="d-flex justify-content-between">
                                                <span class="font-weight-bold">Date returned:</span>
                                                {{$staff_issue->date_returned}}
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="font-weight-bold">Received By:</span>
                                                {{$staff_issue->received_by}}
                                            </div>
                                        @endif
                                        <div class="text-right">
                                            <a href="{{route('items.edit',$staff_issue->item->id)}}" class="btn btn-sm btn-primary">View Item</a>
                                        </div>
                                    </div>
                                    <hr>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                <hr>
                <div class="card bg-transparent border-0 shadow-sm">
                    <div class="card-header p-2 font-weight-bold">
                        In-Charge
                    </div>
                    <div class="card-body ">
                        @if(count($trainer->items) == 0)
                            <div class="text-center">
                                <span class="text-dark">No Item</span>
                            </div>
                        @else
                            <div class="accordion" id="accordionExample">
                                @foreach($trainer->items as $item)
                                    <a href="javascript:void(0)" class="font-weight-bold text-dark text-decoration-none" data-toggle="collapse" data-target="#std{{$item->id}}" aria-expanded="true" aria-controls="collapseOne">
                                        {{$item->asset_tag_number}}
                                        <small class="text-right bg-dark text-white p-1"> {{$item->item_type->name}}</small>
                                    </a>
                                    <div id="std{{$item->id}}" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                        <hr>
                                        {{--<div class="text-right">
                                            <a href="{{route('items.edit',$staff_issue->item->id)}}" class="btn btn-sm btn-primary">View Item</a>
                                        </div>--}}
                                    </div>
                                    <hr>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Program Modal -->
    <div class="modal fade" id="addProgram" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <form action="{{route('add-program',$trainer->id)}}" method="post" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Program</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="col-md-12 mb-2">
                                <label for="program" class="mb-0">Specialization</label>
                                <select required id="program" multiple style="width: 100%" name="program[]" class="form-control form-control-lg select2">
                                    @foreach($programs as $program)
                                        @if(!in_array($program->id,$subjects))
                                            <option value="{{$program->id}}">{{$program->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Program Required
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


    <!-- Add Designations Modal -->
    <div class="modal fade" id="addDesignation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <form action="{{route('add-designation',$trainer->id)}}" method="post" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Designation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="col-md-12 mb-2">
                                <label for="designations" class="mb-0">Designations</label>
                                <select required id="designations" multiple style="width: 100%" name="designations[]" class="form-control form-control-lg select2">
                                    @foreach($designations as $designation)
                                        @if(!in_array($designation->id,$st_designations))
                                            <option value="{{$designation->id}}">{{$designation->designation}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Designation Required
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Designation</button>
                    </div>
                </form>
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
                                        <label for="personal_email" class="mb-0">Personal Email</label>
                                        <input type="email" value="{{$trainer->personal_email}}" required class="form-control form-control-sm" name="personal_email" id="personal_email" placeholder="Personal Email">
                                        <div class="invalid-feedback">
                                           Personal Email required
                                        </div>
                                    </div>
                                    <div class="col-md-4 ">
                                        <label for="work_email" class="mb-0">Work Email</label>
                                        <input type="email" value="{{$trainer->work_email}}"  class="form-control form-control-sm" name="work_email" id="work_email" placeholder="Work Email">
                                        <div class="invalid-feedback">
                                            Email required
                                        </div>
                                    </div>
                                    {{--<div class="col-md-4">
                                        <label for="designation" class="mb-0">Designation</label>
                                        <select required id="designation" style="width: 100%" name="designation" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            @foreach($designations as $types)
                                                <option @if($trainer->designation_id == $types->id) selected @endif value="{{$types->id}}">{{$types->designation}}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Designation required
                                        </div>
                                    </div>--}}
                                    {{--<div class="col-md-12 mb-2">
                                        <label for="program" class="mb-0">Specialization</label>
                                        <select id="program" multiple style="width: 100%" name="program[]" class="form-control form-control-lg select2">
                                            @foreach($programs as $program)
                                                @if(in_array($program->id,$subjects))
--}}{{--                                                    <option readonly value="{{$program->id}}">{{$program->name}}</option>--}}{{--
                                                @else
                                                    <option value="{{$program->id}}">{{$program->name}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Program required
                                        </div>
                                    </div>--}}
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
                                                <option @if($trainer->location_id == $location->id) selected @endif value="{{$location->id}}">{{$location->country.", ".$location->city_town}}</option>
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
                                        <textarea  maxlength="500" class="form-control"  name="remarks" id="remarks" cols="5" rows="2">{{$trainer->remarks}}</textarea>
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
