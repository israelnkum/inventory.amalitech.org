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
                                <img src="{{asset('assets/qr_codes/items/'.$item->qr_code)}}" alt="">
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
                    <div class="card-body">
                        <div class="d-flex  align-items-center">
                            No Activity Yet
                        </div>
                    </div>
                </div>
                <div class="card mt-5 bg-transparent border-0 shadow-sm">
                    @if($item->status->name == "In-Service")
                        <div class="card-header p-2">
                            Receive Item
                        </div>
                    @elseif($item->status->name == "In-Store")
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
                                                        <option value="{{$types->id}}">{{$types->country.", ".$types->city_town}}</option>
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
                                                <input name="staff_trainee_number" type="text" id="staff_registration_number"  required class="form-control" placeholder="Enter Trainee or Staff ID Number">
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
                                <form action="">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <img class="img-fluid" src="{{asset('assets/img/profile/staff/'.$checkResults->profile)}}" alt="">
                                            <img class="img-fluid" src="{{asset('assets/img/profile/trainees/'.$checkResults->profile)}}" alt="">
                                        </div>
                                        <div class="col-md-6 text-center">
                                           <span class="font-weight-bold"> {{$checkResults->first_name." ".$checkResults->other_name." ".$checkResults->last_name}}</span>
                                            <br>
                                            {{$checkResults->staff_number}} {{$checkResults->student_number}}
                                            <br>
                                            <img  src="{{asset('assets/qr_codes/staff/'.$checkResults->qr_code)}}" alt="">
                                            <img  src="{{asset('assets/qr_codes/trainees/'.$checkResults->qr_code)}}" alt="">
                                        </div>
                                        <div class="col-md-12">
                                            <label for="issues_remarks">Remarks</label>
                                            <textarea class="form-control mt-0" name="issue_remarks" id="issues_remarks"  rows="3"></textarea>
                                        </div>
                                    </div>
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
