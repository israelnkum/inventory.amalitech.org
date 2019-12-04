@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row ">
            @component('partials.breadcrumb')
                <li class="breadcrumb-item">
                    <a href="{{route('items.index')}}">Items</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{route('items.edit',$item->id)."?".Hash::make(time())}}">{{$item->asset_tag_number}}</a>
                </li>
            @endcomponent
            <div class="col-md-8">
                <div class="card bg-transparent border-0 shadow-sm">
                    <div class="card-header p-2">
                        Item Detail
                        @can('isSuperAdmin')
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#addStudent" class="btn btn-link p-0 float-right">Edit info</a>
                        @endcan
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-4">
                                        <img src="{{asset('assets/img/items/'.$item->picture)}}" alt="" class="img-fluid">
                                    </div>
                                    <div class="col-md-7">
                                        <p>
                                            {{$item->asset_tag_number}}<br>
                                            {{$item->item_type->name}}<br>
                                            <span class="badge badge-primary">{{$item->status->name}}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <img class="img-fluid" height="auto" width="100"  src="{{asset('assets/qr_codes/items/'.$item->qr_code)}}" alt="">
                                @can('isSuperAdmin')
                                    <form onsubmit="return confirm('Do you really want to delete?')" action="{{route('items.destroy',$item->id)}}" method="post">
                                        @csrf
                                        {!! @method_field('delete') !!}
                                        <button  @if($item->status->name == "In-Use") disabled @endif class="btn btn-link text-danger text-decoration-none" type="submit"><i class="fa fa-trash-alt"></i> Delete</button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                        <hr>
                        <div class="">
                            <blockquote class=" blockquote">
                                <p class="mb-0 font-weight-bold">Description</p>
                                {{$item->description}}
                            </blockquote>
                            <blockquote class="mt-3 blockquote">
                                <p class="mb-0 font-weight-bold">Remarks</p>
                                {{$item->remarks}}
                            </blockquote>
                            <div class="mt-3 blockquote font-weight-lighter">
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
                            <div class="mt-3 blockquote">
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
            <div class="col-md-4">
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
                                        {{$student_issue->date_collected}}  <span class="bg-dark text-white p-1">Trainee</span>
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
                                @endforeach
                                <hr>
                                @foreach($item->staff_issued_items as $staff_issue)
                                    <a href="javascript:void(0)" class="font-weight-bold text-dark text-decoration-none" data-toggle="collapse" data-target="#stf{{$staff_issue->id}}" aria-expanded="true" aria-controls="collapseOne">
                                        {{$staff_issue->date_collected}}  <span class="bg-dark text-white p-1">Staff</span>
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

                    <div class="card-header p-2">
                        In-Charge
                    </div>
                    <div class="card-body ">
                        {{$item->staff->first_name." ".$item->staff->other_name." ".$item->staff->last_name}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="addStudent" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{route('items.update',$item->id)}}" enctype="multipart/form-data" method="post" class="needs-validation" novalidate>
                    @csrf
                    {!! method_field('put') !!}
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-3">
                                <p class="text-danger text-center" id="displayItemError"> <b><span id="boldertext"></span></b></p>
                                <div class="picture-container">
                                    <div class="picture">
                                        <img alt="" src="{{asset('assets/img/items/'.$item->picture)}}" class="picture-src img-fluid" id="itemPicturePreview" title="Click to select picture" />
                                        <input  type="file" class="form-control" name="image_file" accept="image/*"   id="item-picture">
                                    </div>
                                    <h6>Choose Item Picture</h6>

                                    <small class="text-danger">
                                        Max Size - 500KB
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-row">
                                    <div class="col-md-4 mb-2">
                                        <label for="asset_tag_number" class="mb-0">Asset Tag/Serial Number</label>
                                        <input type="text" required value="{{$item->asset_tag_number}}" class="form-control form-control-sm" name="asset_tag_number" id="asset_tag_number" placeholder="Asset Tag/Serial"  >
                                        <div class="invalid-feedback">
                                            Asset Tag/Serial Number required
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="type" class="mb-0">Item Type</label>
                                        <select required id="type" style="width: 100%" name="type" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            @foreach($item_types as $types)
                                                <option @if($item->item_type_id == $types->id) selected @endif value="{{$types->id}}">{{$types->name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Items Type required
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="brand" class="mb-0">Brand</label>
                                        <select required id="brand" style="width: 100%" name="brand" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            @foreach($brands as $types)
                                                <option @if($item->brand_id == $types->id) selected @endif value="{{$types->id}}">{{$types->name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Brand required
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="area" class="mb-0">Area</label>
                                        <select required id="area" style="width: 100%" name="area" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            @foreach($areas as $types)
                                                <option @if($item->area_id == $types->id) selected @endif value="{{$types->id}}">{{$types->name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Area required
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="ownership" class="mb-0">Ownership</label>
                                        <select required id="ownership" style="width: 100%" name="ownership" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            @foreach($ownership as $types)
                                                <option @if($item->ownership_id == $types->id) selected @endif value="{{$types->id}}">{{$types->description}}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Ownership required
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="status" class="mb-0">Status</label>
                                        <select required id="status" style="width: 100%" name="status" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            @foreach($status as $types)
                                                <option @if($item->status_id == $types->id) selected @endif value="{{$types->id}}">{{$types->name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Status required
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="description" class="mb-0">Description</label>
                                        <textarea class="form-control" required name="description" id="description" cols="5" rows="3">{{$item->description}}</textarea>
                                        <div class="invalid-feedback">
                                            Description required
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="remarks" class="mb-0">Remarks</label>
                                        <textarea class="form-control"  name="remarks" id="remarks" cols="5" rows="3">{{$item->remarks}}</textarea>
                                        <div class="invalid-feedback">
                                            Remarks required
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <label for="date_purchased" class="mb-0">Date Purchased</label>
                                        <input type="date" required class="form-control form-control-sm" name="date_purchased" value="{{$item->date_purchased}}" id="date_purchased" placeholder="Date Purchased">
                                        <div class="invalid-feedback">
                                            Date Purchased required
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="category" class="mb-0">Category</label>
                                        <select required id="category" style="width: 100%" name="category" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            @foreach($categories as $types)
                                                <option @if($item->category_id == $types->id) selected @endif value="{{$types->id}}">{{$types->name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Category required
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Item</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
