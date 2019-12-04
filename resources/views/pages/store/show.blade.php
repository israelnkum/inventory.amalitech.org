@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row ">
            @component('partials.breadcrumb')
                <li class="breadcrumb-item">
                    <a href="{{route('stores.index')}}">Store</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{route('stores.show',$item->id)."?".Hash::make(time())}}">{{$item->asset_tag_number}}</a>
                </li>
            @endcomponent
            <div class="col-md-7">
                <div class="card bg-transparent border-0 shadow-sm">
                    <div class="card-header p-2">
                        Item Detail
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-4">
                                        <img height="auto" width="85" src="{{asset('assets/img/items/'.$item->picture)}}" alt="" class="img-fluid">
                                    </div>
                                    <div class="col-md-7">
                                        <p>
                                            {{$item->asset_tag_number}}<br>
                                            {{$item->item_type->name}}<br>
                                            <span class="badge badge-success">{{$item->status->name}}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <img height="auto" width="120" src="{{asset('assets/qr_codes/items/'.$item->qr_code)}}" alt="">
                            </div>
                        </div>
                        <hr>
                        <div class="">
                            <div class="mt-5">
                                <p class="mb-0 font-weight-bold">Description</p>
                                {{$item->description}}
                            </div>
                            <div class="mt-3">
                                <p class="mb-0 font-weight-bold">Remarks</p>
                                {{$item->remarks}}
                            </div>
                            <div class="mt-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-0 font-weight-bold">Ownership</p>
                                        {{$item->ownership->description}}
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-0 font-weight-bold">Area</p>
                                        {{$item->area->name}}
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-0 font-weight-bold">Date Purchased</p>
                                        {{$item->date_purchased}}
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-0 font-weight-bold">Category</p>
                                        {{$item->category->name}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card bg-transparent border-0 shadow-sm">
                    <div class="card-header p-2">
                        Activity
                    </div>
                    <div class="card-body ">
                        @if(count($item->staff_issued_items) == 0 && count($item->student_issued_items) ==0)
                            No Activity Yet
                        @else
                            <div class="accordion" id="accordionExample">
                                @foreach($item->student_issued_items as $student_issue)
                                    <a href="javascript:void(0)" class="font-weight-bold text-dark text-decoration-none" data-toggle="collapse" data-target="#std{{$student_issue->id}}" aria-expanded="true" aria-controls="collapseOne">
                                        {{$student_issue->date_collected}}
                                        <span class="bg-dark text-white p-1">Trainee</span>
                                    </a>
                                    <div id="std{{$student_issue->id}}" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <span class="font-weight-bold">Name:</span>
                                                {{$student_issue->student->first_name." ".$student_issue->student->other_name." ".$student_issue->student->last_name}}
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="font-weight-bold">Student ID:</span>
                                                {{$student_issue->student->student_number}}
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="font-weight-bold">Issued By:</span>
                                                {{$student_issue->issued_by}}
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
                                                    <span class="font-weight-bold">Remarks:</span>
                                                    {{$student_issue->return_remarks}}
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <span class="font-weight-bold">Received By:</span>
                                                    {{$student_issue->received_by}}
                                                </div>
                                            @endif
                                            <div class="text-right">
                                                <a href="{{route('students.edit',$student_issue->student->id)}}" class="btn btn-sm btn-dark">View Trainee</a>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                @endforeach
                                @foreach($item->staff_issued_items as $staff_issue)
                                    <a href="javascript:void(0)" class="font-weight-bold text-dark text-decoration-none" data-toggle="collapse" data-target="#stf{{$staff_issue->id}}" aria-expanded="true" aria-controls="collapseOne">
                                        {{$staff_issue->date_collected}}
                                        <span class="bg-dark text-white p-1">Staff</span>
                                    </a>

                                    <div id="stf{{$staff_issue->id}}" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                        <div class="d-flex justify-content-between">
                                            <span class="font-weight-bold">Name:</span>
                                            {{$staff_issue->staff->first_name." ".$staff_issue->staff->other_name." ".$staff_issue->staff->last_name}}
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="font-weight-bold">Staff ID:</span>
                                            {{$staff_issue->staff->staff_number}}
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="font-weight-bold">Issued By:</span>
                                            {{$staff_issue->issued_by}}
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
                                                <span class="font-weight-bold">Remarks:</span>
                                                {{$staff_issue->return_remarks}}
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="font-weight-bold">Received By:</span>
                                                {{$staff_issue->received_by}}
                                            </div>
                                        @endif
                                        <div class="text-right">
                                            <a href="{{route('staff.edit',$staff_issue->staff->id)}}" class="btn btn-sm btn-dark">View Staff</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card mt-5 bg-transparent border-0 shadow-sm">
                    @if($item->status->name == "In-Use")
                        <div class="card-header p-2">
                            Current Person
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    @if($current_status == "Staff")
                                        <img class="img-fluid" height="auto" width="100" src="{{asset('assets/img/profile/staff/'.$currentPerson->staff->profile)}}" alt="">
                                    @else
                                        <img class="img-fluid" height="auto" width="100" src="{{asset('assets/img/profile/trainees/'.$currentPerson->student->profile)}}" alt="">
                                    @endif
                                </div>
                                <div class="col-md-6 text-center">
                                    @if($current_status == "Staff")
                                        <span class="font-weight-bold"> {{$currentPerson->staff->first_name." ".$currentPerson->staff->other_name." ".$currentPerson->staff->last_name}}</span>
                                        <br>
                                        {{$currentPerson->staff->staff_number}}
                                        <br>
                                        <img class="img-fluid" height="auto" width="100" src="{{asset('assets/qr_codes/staff/'.$currentPerson->staff->qr_code)}}" alt="">
                                    @else
                                        <span class="font-weight-bold"> {{$currentPerson->student->first_name." ".$currentPerson->student->other_name." ".$currentPerson->student->last_name}}</span>
                                        <br>
                                        {{$currentPerson->student->student_number}}
                                        <br>
                                        <img class="img-fluid" height="auto" width="100" src="{{asset('assets/qr_codes/trainees/'.$currentPerson->student->qr_code)}}" alt="">
                                    @endif
                                </div>
                                <div class="col-md-12 mt-2">
                                    <div class="card-header p-2 text-danger">
                                        Receive Item
                                    </div>
                                    <form onsubmit="return confirm('Please Confirm')" action="{{route('receive-item')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="issue_id" value="{{$currentPerson->id}}">
                                        <input type="hidden" name="current_status" value="{{$current_status}}">
                                        <textarea class="form-control mt-0" placeholder="Remarks" name="return_remarks" id="issues_remarks"  rows="3"></textarea>
                                        <button class="btn btn-primary btn-sm float-right mt-2">Receive</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($item->area->name == "Store" && $item->status->id != 1)
                        <form  action="{{route('check-item')}}" class="needs-validation" novalidate>
                            @csrf
                            <div class="card-header p-2 justify-content-between d-flex">
                                <span class="font-weight-bold text-danger" style="font-size: larger; font-family: 'Arial',serif">Issue Item</span>
                            </div>
                            <div class="card-body">
                                <div class="d-flex  align-items-center">
                                    <div class="row">
                                        <input type="hidden" name="item_id" value="{{$item->id}}">
                                        @can('isSuperAdmin')
                                            <div class="col-md-6 mb-2">
                                                <label for="locations">Location</label>
                                                <select  required id="locations" style="width: 100%" name="location_id" class="form-control form-control-lg select2">
                                                    <option value=""></option>
                                                    @foreach($locations as $types)
                                                        <option {{ old('location_id') == $types->id ? 'selected' : '' }}  value="{{$types->id}}">{{$types->country.", ".$types->city_town}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">
                                                    Location required
                                                </div>
                                            </div>
                                        @endcan
                                        <div class="col-md-6 mb-2">
                                            <label for="staff-student">Filter</label>
                                            <select required id="staff-student" style="width: 100%" name="staff_student" class="form-control form-control-lg select2">
                                                <option value=""></option>
                                                <option {{ old('staff_student') == "Staff" ? 'selected' : '' }}  value="Staff">Staff</option>
                                                <option {{ old('staff_student') == "Trainee" ? 'selected' : '' }}  value="Trainee">Trainee</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Choose one
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="input-group mb-2">

                                                <input value="{{ old('staff_trainee_number')}}" name="staff_trainee_number" type="text" id="staff_trainee_number"  required class="form-control text-uppercase" placeholder="Enter Trainee or Staff ID Number">
                                                <div class="input-group-prepend">
                                                    <button type="submit" class="input-group-text bg-dark text-white">
                                                        <i class="fa fa-check-circle"></i>&nbsp;Check
                                                    </button>
                                                </div>
                                                <div class="invalid-feedback">
                                                    Trainee or Staff ID Number required
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr>
                        <div class="card-body">
                            @if(!empty($checkResults))
                                <form onsubmit="return confirm('Please Confirm')" action="{{route('issue-item')}}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4">
                                            @if($status == "Staff")
                                                <img class="img-fluid" height="auto" width="100" src="{{asset('assets/img/profile/staff/'.$checkResults->profile)}}" alt="">
                                            @else
                                                <img class="img-fluid" height="auto" width="100" src="{{asset('assets/img/profile/trainees/'.$checkResults->profile)}}" alt="">
                                            @endif
                                        </div>
                                        <div class="col-md-6 text-center">
                                            <span class="font-weight-bold"> {{$checkResults->first_name." ".$checkResults->other_name." ".$checkResults->last_name}}</span>
                                            <br>
                                            {{$checkResults->staff_number}} {{$checkResults->student_number}}
                                            <br>
                                            @if($status == "Staff")
                                                <img class="img-fluid" height="auto" width="100" src="{{asset('assets/qr_codes/staff/'.$checkResults->qr_code)}}" alt="">
                                            @else
                                                <img class="img-fluid" height="auto" width="100" src="{{asset('assets/qr_codes/trainees/'.$checkResults->qr_code)}}" alt="">
                                            @endif
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <label for="issues_remarks">Remarks</label>
                                            <textarea class="form-control mt-0" name="issue_remarks" id="issues_remarks"  rows="3"></textarea>
                                        </div>
                                    </div>
                                    <input type="hidden" name="status" value="{{$status}}">
                                    <input type="hidden" class="form-control" name="item_id" value="{{$item->id}}">
                                    <input type="hidden" class="form-control" name="staff_student_id" value="{{$checkResults->id}}">
                                    <button class="btn btn-success btn-sm mt-3 float-right ">Issue Item</button>
                                </form>
                            @endif
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
@endsection
