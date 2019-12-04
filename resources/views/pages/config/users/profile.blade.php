@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @component('partials.breadcrumb')
                @if(Auth::user()->id != $user->id)
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="{{route('users.index')}}">Users</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="{{route('users.show',$user->id)}}">Profile</a>
                    </li>
                @else
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="{{route('users.show',$user->id)}}">Profile</a>
                    </li>
                @endif
            @endcomponent
            <div class="col-md-3">
                <div class="card-header bg-transparent">
                    <span class="font-weight-bold">Info</span>
                </div>
                <div class="card bg-transparent border-0 shadow-sm">
                    <div class="card-body bg-transparent text-center">
                        <img class="img-fluid " height="auto" width="100" src="{{asset('assets/img/profile/staff/'.$user->picture)}}" alt="">
                        <h5 class="mt-1">{{$user->name}}</h5>
                        <h6>{{$user->email}}</h6>
                        <h6><span class="badge badge-dark">{{$user->user_type}}</span></h6>
                        <hr>
                        <h6>{{$user->location->city_town.",".$user->location->country}}</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card-header bg-transparent">
                    <span class="font-weight-bold">Update Info</span>
                </div>
                <div class="card border-0 shadow-sm bg-transparent">
                    <div class="card-body">
                        <form method="POST" class="needs-validation" enctype="multipart/form-data"  novalidate action="{{route('users.update',$user->id)}}">
                            @csrf
                            {!! method_field('put') !!}
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <p class="text-danger text-center" id="displayError"> <b><span id="boldertext"></span></b></p>
                                    <div class="picture-container">
                                        <div class="picture">
                                            <img src="{{asset('assets/img/profile/staff/avata.png')}}" class="picture-src img-fluid" id="wizardPicturePreview" title="Click to select picture" />
                                            <input  type="file" class="form-control" name="image_file"   id="wizard-picture">
                                        </div>
                                        <h6>Change Picture</h6>

                                        <small class="text-danger">
                                            413 x 513 pixels<br>
                                            Max Size - 500KB
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-12">
                                        {{--                                        <label for="name">{{ __('Name') }}</label>--}}
                                        <input id="name" type="text" class="form-control mb-2" name="name" value="{{$user->name }}" required >
                                        <div class="invalid-feedback">
                                            Name required
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        {{--                                        <label for="email">{{ __('Email') }}</label>--}}
                                        <input id="email" type="email" class="form-control mb-2" name="email" value="{{ $user->email}}" required>
                                        <div class="invalid-feedback" >
                                            Email required
                                        </div>
                                    </div>
                                    <div class="col-md-12  mb-2">
                                        {{--                                        <label for="password">{{ __('Gender') }}</label>--}}
                                        <select required id="gender" style="width: 100%" name="gender" class="form-control select2">
                                            <option value=""></option>
                                            <option @if($user->gender == "Male") selected @endif value="Male">Male</option>
                                            <option @if($user->gender == "Female") selected @endif value="Female">Female</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Gender required
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        {{--                                        <label for="phone_number" >Phone Number</label>--}}
                                        <input type="text" required value="{{$user->phone_number}}" class="form-control form-control-sm" name="phone_number" id="phone_number" placeholder="Phone Number">
                                        <div class="invalid-feedback">
                                            Phone Number required
                                        </div>
                                    </div>
                                    @if(Auth::user()->id != $user->id)
                                        <div class="col-md-12  mb-2">
                                            <select required id="user_type" style="width: 100%" name="user_type" class="form-control select2">
                                                <option value=""></option>
                                                <option  @if($user->user_type == "Admin") selected @endif value="Admin">Admin</option>
                                                <option  @if($user->user_type == "In-Charge") selected @endif value="In-Charge">In-Charge</option>
                                                <option  @if($user->user_type == "Store Manager") selected @endif value="Store Manager">Store Manager</option>
                                                <option  @if($user->user_type == "Super Admin") selected @endif value="Super Admin">Super Admin</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                User Type required
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-12 mt-2 text-right">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Update Info') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-transparent">
                    @if(Auth::user()->id != $user->id)
                        <div class="card-header bg-transparent">
                            <span class="font-weight-bold">Reset Password</span>
                        </div>
                        <div class="card-body">
                            <form action="{{route('reset-password')}}" method="post" class="needs-validation" novalidate>
                                @csrf
                                <div class="form-group row mb-0">
                                    <input type="hidden" class="form-control" name="user_id" value="{{$user->id}}">
                                    <div class="col-md-12">
                                        <button class="btn btn-dark">Reset User Password</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                        <div class="card-header bg-transparent">
                            @if($user->status == 1)
                                <span class="font-weight-bold">Activate</span>
                            @else
                                <span class="font-weight-bold">Deactivate</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <form action="{{route('deactivate-user')}}" method="post" class="needs-validation" novalidate>
                                @csrf
                                <div class="form-group row mb-0">
                                    <input type="hidden" class="form-control" name="user_id" value="{{$user->id}}">
                                    <div class="col-md-12">
                                        @if($user->status == 1)
                                            <input type="hidden" class="form-control" name="type" value="activate">
                                            <button type="submit" class="btn btn-block btn-success">
                                                {{ __('Activate Account') }}
                                            </button>
                                        @else
                                            <input type="hidden" class="form-control" name="type" value="deactivate">
                                            <button type="submit" class="btn btn-block btn-danger">
                                                {{ __('Deactivate Account') }}
                                            </button>

                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="card-header bg-transparent">
                            <span class="font-weight-bold">Change Password</span>
                        </div>
                        <div class="card-body">
                            <form action="{{route('password-change')}}" method="post" class="needs-validation" novalidate>
                                @csrf
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <input id="old-password" minlength="8" placeholder="Old Password" type="password" class="form-control " name="old_password" required autocomplete="old-password">

                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <input id="password" minlength="8" placeholder="New Password" type="password" class="form-control " name="password" required autocomplete="new-password">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <input id="password-confirm" minlength="8" placeholder="Confirm Password" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                    </div>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-block btn-primary">
                                            {{ __('Change Password') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
@endsection
