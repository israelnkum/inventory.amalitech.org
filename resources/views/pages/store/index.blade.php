@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @component('partials.breadcrumb')
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{route('stores.index')}}">Store</a>
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
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="card-header">
                    Filter Items
                </div>
                <div class="card bg-transparent border-0 shadow-sm">
                    <div class="card-body bg-transparent ">
                        <form method="post" id="filter-store-form" action="{{route('filter-store-items')}}">
                            @csrf
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="filter-store-category" class="mb-0">Category</label>
                                    <select id="filter-store-category" style="width: 100%" name="category" class="form-control form-control-lg select2">
                                        <option value=""></option>
                                        @foreach($categories as $types)
                                            <option {{ old('category') == $types->id ? 'selected' : '' }} value="{{$types->id}}">{{$types->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label for="filter-store-type" class="mb-0">Item Type</label>
                                    <select id="filter-store-type" style="width: 100%" name="type" class="form-control form-control-lg select2">
                                        <option value=""></option>
                                        @foreach($item_types as $types)
                                            <option {{ old('type') == $types->id ? 'selected' : '' }} value="{{$types->id}}">{{$types->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label for="filter-store-brand" class="mb-0">Brand</label>
                                    <select  id="filter-store-brand" style="width: 100%" name="brand" class="form-control form-control-lg select2">
                                        <option value=""></option>
                                        @foreach($brands as $types)
                                            <option {{ old('brand') == $types->id ? 'selected' : '' }} value="{{$types->id}}">{{$types->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{--<div class="col-md-12">
                                    <label for="filter-store-area" class="mb-0">Area</label>
                                    <select id="filter-store-area" style="width: 100%" name="area" class="form-control form-control-lg select2">
                                        <option value=""></option>
                                        @foreach($areas as $types)
                                            <option {{ old('area') == $types->id ? 'selected' : '' }} value="{{$types->id}}">{{$types->name}}</option>
                                        @endforeach
                                    </select>
                                </div>--}}
                                <div class="col-md-12">
                                    <label for="filter-store-ownership" class="mb-0">Ownership</label>
                                    <select id="filter-store-ownership" style="width: 100%" name="ownership" class="form-control form-control-lg select2">
                                        <option value=""></option>
                                        @foreach($ownership as $types)
                                            <option {{ old('ownership') == $types->id ? 'selected' : '' }} value="{{$types->id}}">{{$types->description}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label for="filter-store-status" class="mb-0">Status</label>
                                    <select id="filter-store-status" style="width: 100%" name="status" class="form-control form-control-lg select2">
                                        <option value=""></option>
                                        @foreach($status as $types)
                                            <option {{ old('status') == $types->id ? 'selected' : '' }} value="{{$types->id}}">{{$types->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                @if(count($allItems) == 0)
                    <div class="text-center mt-5 col-md-12">
                        <img src="{{asset('no_result.png')}}" class="img-fluid" alt="">
                        <h4 class="display font-weight-lighter text-danger">Oops! No item(s) Found</h4>
                    </div>
                @else
                    <div class="card bg-transparent border-0 shadow-sm">
                        <div class="card-header  p-1">
                            <span class="float-right">{!! $allItems->fragment(Hash::make(time()))->render() !!}</span>
                        </div>
                        <div class="card-body">
                            <table class="table  table-hover">
                                <thead>
                                <tr>
                                    <th>Picture</th>
                                    <th>Asset Tag/Description</th>
                                    <th>Type/Status</th>
                                    <th>Issue/Receive</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($allItems as $items)
                                    <tr>
                                        <td>
                                            <img height="50" width="50" src="{{asset('assets/img/items/'.$items->picture)}}" alt="" class="img-fluid">
                                        </td>
                                        <td class="text-uppercase">
                                            <a class="text-decoration-none text-dark" href="{{route('stores.show',$items->id)}}?{{$items->asset_tag_number.Hash::make($items->asset_tag_number)}}">
                                                {{$items->asset_tag_number}}<br>
                                                {{$items->description}}
                                            </a>
                                        </td>
                                        <td>
                                            {{$items->item_type->name}}<br>
                                            {{$items->status->name}}
                                        </td>
                                        <td>
                                            @if($items->status->name == "In-Use")
                                                <a href="{{route('stores.show',$items->id)}}?{{$items->asset_tag_number.Hash::make($items->asset_tag_number)}}" class="btn btn-success btn-sm mt-3">Receive</a>
                                            @else
                                                <a href="{{route('stores.show',$items->id)}}?{{$items->asset_tag_number.Hash::make($items->asset_tag_number)}}" class="btn btn-primary btn-sm mt-3">Issue</a>
                                            @endif
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
                                <p class="text-danger text-center" id="displayItemError"> <b><span id="boldertext"></span></b></p>
                                <div class="picture-container">
                                    <div class="picture">
                                        <img alt="" src="{{asset('item-default.jpg')}}" class="picture-src img-fluid" id="itemPicturePreview" title="Click to select picture" />
                                        <input required  type="file" class="form-control" name="image_file" accept="image/*"   id="item-picture">
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
                                                <option value="{{$types->id}}">{{$types->name}}</option>
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
                        <button type="submit" class="btn btn-primary">Add items</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
