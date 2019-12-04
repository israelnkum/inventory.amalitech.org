@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @component('partials.breadcrumb')
                <li class="breadcrumb-item" aria-current="page">
                    <a href="{{route('stores.index')}}">Store</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="javascript:void(0)">Search Result</a>
                </li>
            @endcomponent
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-4 text-danger text-center">
                <h3 class="font-weight-lighter">{{count($checkResults)}} results found</h3>
            </div>
            <div class="col-md-4">
                <form class="needs-validation" novalidate>
                    <div class="form-row align-items-center">
                        <div class="col-md-12">
                            <div class="input-group mb-2">
                                <input type="text"   class="form-control" id="search-result-input" placeholder="Search in {{$status}}">
                                <div class="input-group-prepend">
                                    <button type="button" class="input-group-text"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-10 offset-md-1">
                <table class="table  table-hover" id="search-results-table">
                    <thead>
                    <tr>
                        <td>.</td>

                        <td>.</td>

                        <td>.</td>
                        <td>.</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($checkResults as $student)
                        <tr>
                            <td>
                                @if($status == "Staff")
                                    <img height="auto" width="70" src="{{asset('assets/img/profile/staff/'.$student->profile)}}" alt="" class="img-fluid">
                                @else
                                    <img height="auto" width="70" src="{{asset('assets/img/profile/trainees/'.$student->profile)}}" alt="" class="img-fluid">
                                @endif
                            </td>
                            <td class="text-uppercase">
                                <a class="text-decoration-none text-dark" href="javascript:void(0)">

                                    <b class="font-weight-bold">{{$student->first_name." ".$student->other_name." ".$student->last_name}}</b><br>
                                    {{$student->registration_number}}<br>{{$student->phone_number}}<br><span class="text-lowercase">{{$student->email}}</span>
                                </a>
                            </td>
                            {{--<td>
                                <img height="70" width="70" src="{{asset('assets/qr_codes/trainees/'.$student->qr_code)}}" alt="" class="img-fluid">
                            </td>--}}
                            <td>
                                @if($status != "Staff")
                                    <b>{{$student->program->name}}</b><br>
                                @endif
                                {{$student->location->country}}<br>
                                {{$student->location->city_town}}<br>
                                {{$student->gender}}
                            </td>
                            <td>
                                <form  action="{{route('check-item')}}" class="needs-validation" novalidate>
                                    @csrf
                                    <input type="hidden" name="item_id" value="{{$item->id}}">
                                    <div class="col-md-6 mb-2">
                                        <input value="{{$student->location->id}}"  name="staff_trainee_number" type="hidden" id="location_id"  required class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <input value="{{$status}}"  name="staff_student" type="hidden" id="staff_student"  required class="form-control">
                                    </div>
                                    @if($status == "Staff")
                                        <input value="{{$student->staff_number}}" name="staff_trainee_number" type="hidden" id="staff_trainee_number"  required class="form-control">
                                    @else
                                        <input value="{{$student->student_number}}" name="staff_trainee_number" type="hidden" id="staff_trainee_number"  required class="form-control">
                                    @endif
                                    <button type="submit" class="btn btn-sm  btn-dark">Select</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
