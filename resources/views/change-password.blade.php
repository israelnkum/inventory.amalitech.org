@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center mt-5">
            {{--<div class="col-md-4 mt-5">
                <div class="card-header text-center bg-transparent">
                    @component('partials.hero')
                        <i style="font-size: 50px;" class="fa fa-user"></i><br>
                       Update your Information
                    @endcomponent
                </div>
                <div class="card-body d-inline-flex  justify-content-md-center text-center">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="{{route('password-change')}}" method="post" class="needs-validation" novalidate>
                                @csrf
                                <div class="form-group row">
                                    <div class="col-md-12 mb-2">
                                        <input id="password" minlength="8" placeholder="New Password" type="password" class="form-control " name="password" required autocomplete="new-password">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-2">
                                        <input id="password-confirm" minlength="8" placeholder="Confirm Password" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                    </div>

                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-block btn-primary">
                                            {{ __('Change Password') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>--}}
            <div class="col-md-4 mt-5">
                <div class="card bg-transparent shadow-sm border-0">
                    <div class="card bg-transparent shadow-sm border-0">
                        <div class="card-header text-center bg-transparent">
                            @component('partials.hero')
                                <i style="font-size: 50px;" class="fa fa-lock"></i><br>
                                Change Password
                            @endcomponent
                        </div>
                        <div class="card-body d-inline-flex  justify-content-md-center text-center">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="{{route('password-change')}}" method="post" class="needs-validation" novalidate>
                                        @csrf
                                        <div class="form-group row">
                                            <div class="col-md-12 mb-2">
                                                <input id="password" minlength="8" placeholder="New Password" type="password" class="form-control " name="password" required autocomplete="new-password">
                                                @error('password')
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-md-12 mb-2">
                                                <input id="password-confirm" minlength="8" placeholder="Confirm Password" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                            </div>

                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-block btn-primary">
                                                    {{ __('Change Password') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-5  text-danger">
                <h4 class="text-dark">Tips for choosing a strong password</h4>
                Password Must be at least 8 characters
                <br>
                Password must contain special characters Eg: !@#$%^&*(_)<br>
                Use a mixture of upper- and lowercase <br>
                Use a combination of letters and numbers
            </div>
        </div>
    </div>
@endsection
