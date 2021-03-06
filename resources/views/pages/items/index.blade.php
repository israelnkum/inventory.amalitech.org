@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @component('partials.breadcrumb')
                <li class="breadcrumb-item " aria-current="page">
                    <a href="{{route('config')}}">Config</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{route('items.index')}}">Items</a>
                </li>
            @endcomponent

            <div class="col-md-6">
                <form class="needs-validation" novalidate>
                    <div class="form-row align-items-center">
                        <div class="col-md-12">
                            <div class="input-group mb-2">
                                <input type="text"   class="form-control" id="search-input" placeholder="Type Search in items">
                                <div class="input-group-prepend">
                                    <button type="submit" class="input-group-text">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-3">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#addStudent">Add Item</button>
                    <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#uploadTrainee">Upload Item(s)</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-transparent border-0 shadow-sm">
                    <div class="card-body bg-transparent ">
                        <form action="{{route('filter-items')}}" method="post" id="filter-items-form">
                            @csrf
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="filter-items-category" class="mb-0">Category</label>
                                    <select required id="filter-items-category" style="width: 100%" name="category" class="form-control form-control-lg select2">
                                        <option value=""></option>
                                        @foreach($categories as $types)
                                            <option {{ old('category') == $types->id ? 'selected' : '' }}  value="{{$types->id}}">{{$types->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Items Type required
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="filter-items-type" class="mb-0">Item Type</label>
                                    <select required id="filter-items-type" style="width: 100%" name="type" class="form-control form-control-lg select2">
                                        <option value=""></option>
                                        @foreach($item_types as $types)
                                            <option {{ old('type') == $types->id ? 'selected' : '' }}  value="{{$types->id}}">{{$types->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Items Type required
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="filter-items-brand" class="mb-0">Brand</label>
                                    <select required id="filter-items-brand" style="width: 100%" name="brand" class="form-control form-control-lg select2">
                                        <option value=""></option>
                                        @foreach($brands as $types)
                                            <option {{ old('brand') == $types->id ? 'selected' : '' }}  value="{{$types->id}}">{{$types->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Brand required
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="filter-items-area" class="mb-0">Area</label>
                                    <select required id="filter-items-area" style="width: 100%" name="area" class="form-control form-control-lg select2">
                                        <option value=""></option>
                                        @foreach($areas as $types)
                                            <option {{ old('area') == $types->id ? 'selected' : '' }}  value="{{$types->id}}">{{$types->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Area required
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="filter-items-ownership" class="mb-0">Ownership</label>
                                    <select required id="filter-items-ownership" style="width: 100%" name="ownership" class="form-control form-control-lg select2">
                                        <option value=""></option>
                                        @foreach($ownership as $types)
                                            <option {{ old('ownership') == $types->id ? 'selected' : '' }}  value="{{$types->id}}">{{$types->description}}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Ownership required
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="filter-items-status" class="mb-0">Status</label>
                                    <select required id="filter-items-status" style="width: 100%" name="status" class="form-control form-control-lg select2">
                                        <option value=""></option>
                                        @foreach($status as $types)
                                            <option {{ old('status') == $types->id ? 'selected' : '' }}  value="{{$types->id}}">{{$types->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Status required
                                    </div>
                                </div>
                                @can('isSuperAdmin')
                                    <div class="col-md-12">
                                        <label for="filter-items-locations" class="mb-0">Location</label>
                                        <select  required id="filter-items-locations" style="width: 100%" name="location_id" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            @foreach($locations as $types)
                                                <option {{ old('location_id') == $types->id ? 'selected' : '' }}  value="{{$types->id}}">{{$types->country.", ".$types->city_town}}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Items Type required
                                        </div>
                                    </div>
                                @endcan
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                @if(count($allItems) == 0)
                    <div class="text-center mt-5 col-md-12">
                        <img src="{{'no_result.png'}}" alt="">
                        <h4 class="display font-weight-lighter text-danger">Oops! No item(s) Found</h4>
                    </div>
                @else
                    <div class="card bg-transparent border-0 shadow-sm">
                        <div class="card-header bg-transparent  p-0">
                            <span class="float-right">{!! $allItems->fragment(Hash::make(time()))->render() !!}</span>
                        </div>
                        <div class="card-body">
                            <table class="table  table-hover">
                                <thead>
                                <tr>
                                    <th>Picture</th>
                                    <th>Asset Tag/Description</th>
                                    <th>Type/Area</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($allItems as $items)
                                    <tr>
                                        <td>
                                            <img height="50" width="50" src="{{asset('assets/img/items/'.$items->picture)}}" alt="" class="img-fluid">
                                        </td>
                                        <td class="text-uppercase">
                                            <a class="text-decoration-none text-dark" href="{{route('items.edit',$items->id)."?".Hash::make(time())}}">

                                                {{$items->asset_tag_number}}<br>
                                                {{$items->description}}
                                            </a>
                                        </td>
                                        <td>
                                            {{$items->item_type->name}}<br>
                                            <span class="badge badge-dark p-1">{{$items->area->name}}</span>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer bg-transparent pb-0">
                            <span class="float-right">{!! $allItems->fragment(Hash::make(time()))->render() !!}</span>
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
                <form action="{{route('items.store')}}" enctype="multipart/form-data" method="post" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New items</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <p class="text-danger text-center" id="displayItemError"> <b><span id="boldertext"></span></b></p>
                                        <div class="picture-container">
                                            <div class="picture">
                                                <img alt="" src="{{asset('item-default.jpg')}}" class="picture-src img-fluid" id="itemPicturePreview" title="Click to select picture" />
                                                <input  type="file" class="form-control" name="image_file" accept="image/*"   id="item-picture">
                                            </div>
                                            <h6>Choose Item Picture</h6>
                                            <small class="text-danger">
                                                Max Size - 500KB
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-12 ">
                                        <label for="location_id">Location</label>
                                        <select  required id="location_id" style="width: 100%" name="location_id" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            @foreach($locations as $types)
                                                <option {{ old('location_id') == $types->id ? 'selected' : '' }}  value="{{$types->id}}">{{$types->country.", ".$types->city_town}}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Items Type required
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="add-in-charge">In-Charge</label>
                                        <select required id="add-in-charge" style="width: 100%" name="in_charge" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            @foreach($staff as $types)
                                                <option value="{{$types->id}}">{{$types->first_name." ".$types->other_name." ".$types->last_name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            In-Charge required
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-row">
                                    <div class="col-md-4 mb-2">
                                        <label for="asset_tag_number" class="mb-0">Asset Tag/Serial Number</label>
                                        <input type="text" required  class="form-control form-control-sm" name="asset_tag_number" id="asset_tag_number" placeholder="Asset Tag/Serial"  >
                                        <div class="invalid-feedback">
                                            Asset Tag/Serial Number required
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="type" class="mb-0">Item Type</label>
                                        <select required id="type" style="width: 100%" name="type" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            @foreach($item_types as $types)
                                                <option value="{{$types->id}}">{{$types->name}}</option>
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
                                                <option value="{{$types->id}}">{{$types->name}}</option>
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
                                                <option value="{{$types->id}}">{{$types->name}}</option>
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
                                                <option value="{{$types->id}}">{{$types->description}}</option>
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
                                                @if($types->name != "In-Use")
                                                    <option value="{{$types->id}}">{{$types->name}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Status required
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="description" class="mb-0">Description</label>
                                        <textarea class="form-control" required name="description" id="description" cols="5" rows="3"></textarea>
                                        <div class="invalid-feedback">
                                            Description required
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="remarks" class="mb-0">Remarks</label>
                                        <textarea class="form-control"  name="remarks" id="remarks" cols="5" rows="3"></textarea>
                                        <div class="invalid-feedback">
                                            Remarks required
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <label for="date_purchased" class="mb-0">Date Purchased</label>
                                        <input type="date" required class="form-control form-control-sm" name="date_purchased" id="date_purchased" placeholder="Date Purchased">
                                        <div class="invalid-feedback">
                                            Date Purchased required
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="category" class="mb-0">Category</label>
                                        <select required id="category" style="width: 100%" name="category" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            @foreach($categories as $types)
                                                <option value="{{$types->id}}">{{$types->name}}</option>
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
                        <button type="submit" class="btn btn-primary">Add Item</button>
                    </div>
                </form>
            </div>

        </div>
    </div>


    {{--upload modal--}}
    <div class="modal fade" id="uploadTrainee" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{route('upload-items')}}" enctype="multipart/form-data" method="post" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Upload Item(s)</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="col-md-4 mb-2">
                                <label for="upload-type" class="mb-0">Item Type</label>
                                <select required id="upload-type" style="width: 100%" name="type" class="form-control form-control-lg select2">
                                    <option value=""></option>
                                    @foreach($item_types as $types)
                                        <option value="{{$types->id}}">{{$types->name}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Items Type required
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="upload-brand" class="mb-0">Brand</label>
                                <select required id="upload-brand" style="width: 100%" name="brand" class="form-control form-control-lg select2">
                                    <option value=""></option>
                                    @foreach($brands as $types)
                                        <option value="{{$types->id}}">{{$types->name}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Brand required
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="upload-area" class="mb-0">Area</label>
                                <select required id="upload-area" style="width: 100%" name="area" class="form-control form-control-lg select2">
                                    <option value=""></option>
                                    @foreach($areas as $types)
                                        <option value="{{$types->id}}">{{$types->name}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Area required
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="upload-ownership" class="mb-0">Ownership</label>
                                <select required id="upload-ownership" style="width: 100%" name="ownership" class="form-control form-control-lg select2">
                                    <option value=""></option>
                                    @foreach($ownership as $types)
                                        <option value="{{$types->id}}">{{$types->description}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Ownership required
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="upload-status" class="mb-0">Status</label>
                                <select required id="upload-status" style="width: 100%" name="status" class="form-control form-control-lg select2">
                                    <option value=""></option>
                                    @foreach($status as $types)
                                        @if($types->name != "In-Use")
                                            <option value="{{$types->id}}">{{$types->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Status required
                                </div>
                            </div>

                            <div class="col-md-4 mb-2">
                                <label for="upload-category" class="mb-0">Category</label>
                                <select required id="upload-category" style="width: 100%" name="category" class="form-control form-control-lg select2">
                                    <option value=""></option>
                                    @foreach($categories as $types)
                                        <option value="{{$types->id}}">{{$types->name}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Category required
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="upload-location">Location</label>
                                <select required id="upload-location" style="width: 100%" name="location_id" class="form-control form-control-lg select2">
                                    <option value=""></option>
                                    @foreach($locations as $location)
                                        <option value="{{$location->id}}">{{$location->city_town.','.$location->country}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Location required
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="in-charge">In-Charge</label>
                                <select required id="in-charge" style="width: 100%" name="in_charge" class="form-control form-control-lg select2">
                                    <option value=""></option>
                                    @foreach($staff as $types)
                                        <option value="{{$types->id}}">{{$types->first_name." ".$types->other_name." ".$types->last_name}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    In-Charge required
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="excelFile">Select Excel File</label>
                                <input required name="file" class="form-control-file p-1" id="excelFile" style="width:100%; border-radius: 0;border: dashed black 1px;" type="file">
                                <div class="invalid-feedback">
                                    Select File
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="selectPictures">Select Picture(s)</label>
                                <input required class="form-control-file p-1" name="pictures[]" multiple="multiple" accept="image/*" id="selectPictures" style="width:100%; border-radius: 0;border: dashed black 1px;" type="file">
                                <div class="invalid-feedback">
                                    Select Picture(s)
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="row p-2 border-top">
                        <div class="col-md-6">
                            <a href="{{route('format-items')}}" class="text-decoration-none text-danger ml-2">Download upload Format</a>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
    {{--end modal--}}
@endsection
