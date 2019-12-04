@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @component('partials.breadcrumb')
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{route('students.index')}}">Trainees</a>
                </li>
            @endcomponent
            <div class="col-md-5 offset-md-2">
                <form class="needs-validation" novalidate>
                    <div class="form-row align-items-center">
                        <div class="col-md-12">
                            <div class="input-group mb-2">
                                <input type="text"   class="form-control" id="search-input" placeholder="Type Search in Trainees">
                                <div class="input-group-prepend">
                                    <button type="submit" class="input-group-text">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            @can('isSuperAdmin')
                <div class="col-md-3 text-right">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#addStudent">Add Trainee</button>
                        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#uploadTrainee">Upload Trainee(s)</button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-transparent border-0 shadow-sm">
                    <div class="card-header">
                        Filter Trainees
                    </div>
                    <div class="card-body bg-transparent ">
                        <form action="{{route('filter-students')}}" method="get" id="filter-students-form">
                            @csrf
                            <div class="form-group row">
                                <div class="form-group row">
                                    @can('isSuperAdmin')
                                        <div class="col-md-12">
                                            <label for="filter-students-locations" class="mb-0">Location</label>
                                            <select  required id="filter-students-locations" style="width: 100%" name="location_id" class="form-control form-control-lg select2">
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
                                    <div class="col-md-12 mb-2">
                                        <label for="filter-students-gender" class="mb-0">Gender</label>
                                        <select  required id="filter-students-gender" style="width: 100%" name="gender" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            <option {{ old('gender') == 'Male' ? 'selected' : '' }}  value="Male">Male</option>
                                            <option {{ old('gender') == 'Female' ? 'selected' : '' }}  value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="filter-students-programs" class="mb-0">Specialization</label>
                                        <select required id="filter-students-programs" style="width: 100%" name="programs" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            @foreach($programs as $types)
                                                <option {{ old('programs') == $types->id ? 'selected' : '' }} value="{{$types->id}}">{{$types->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="filter-students-sessions" class="mb-0">Session</label>
                                        <select  id="filter-students-sessions" style="width: 100%" name="sessions_id" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            @foreach($sessions as $types)
                                                <option {{ old('sessions_id') == $types->id ? 'selected' : '' }} value="{{$types->id}}">{{$types->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card bg-transparent border-0 shadow-sm">
                    @if($students->count() == 0)
                        <div class="text-center mt-5 mb-5">
                            <img src="{{asset('no_result.png')}}" class="img-fluid" alt="">
                            <h4 class="font-weight-light text-danger mb-5 mt-4">Oops! No data found</h4>
                        </div>
                    @else
                        <div class="card-header bg-transparent border-0 pb-0">
                            <span class="float-right">{{ $students->appends(Request::all())->links() }}</span>
                        </div>
                        <div class="card-body">
{{--                            {!! $students->getOptions() !!}--}}
                            <table id="students_table" class="table  table-hover">
                                <tbody>

                                @foreach ($students as $student)
                                    <tr>
                                        <td>
                                            <img height="auto" width="70" src="{{asset('assets/img/profile/trainees/'.$student->profile)}}" alt="" class="img-fluid">
                                        </td>
                                        <td class="text-uppercase">
                                            <a class="text-decoration-none text-dark" href="{{route('students.edit',$student->id)."?".Hash::make(time())}}">

                                                <b class="font-weight-bold">{{$student->first_name." ".$student->other_name." ".$student->last_name}}</b><br>
                                                {{$student->registration_number}}<br>{{$student->phone_number}}<br><span class="text-lowercase">{{$student->personal_email}}</span><br>
                                                <span class="text-lowercase">{{$student->work_email}}</span>
                                            </a>
                                        </td>
                                        {{--<td>
                                            <img height="70" width="70" src="{{asset('assets/qr_codes/trainees/'.$student->qr_code)}}" alt="" class="img-fluid">
                                        </td>--}}
                                        <td>
                                            <b>{{$student->program->name}}</b><br>
                                            {{$student->location->country}}<br>
                                            {{$student->location->city_town}}<br>
                                            {{$student->gender}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer bg-transparent pb-0">
                            <span class="float-right">{{ $students->appends(Request::all())->links() }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!--Add Modal -->
    <div class="modal fade" id="addStudent" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form onsubmit="return confirm('Are you sure all information provided are correct')" action="{{route('students.store')}}" enctype="multipart/form-data" method="post" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New Trainee</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-3">
                                <p class="text-danger text-center" id="displayError"> <b><span id="boldertext"></span></b></p>
                                <div class="picture-container">
                                    <div class="picture form-group">
                                        <img alt="" src="{{asset('avata.png')}}" class="picture-src img-fluid" id="wizardPicturePreview" title="Click to select picture" />
                                        <input required  type="file" class="form-control" name="image_file"  accept="image/*"  id="wizard-picture">
                                        <div class="invalid-feedback">
                                            image required
                                        </div>
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
                                        <input type="text" required  class="form-control form-control-sm" name="first_name" id="first_name" placeholder="First name"  >
                                        <div class="invalid-feedback">
                                            First name required
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="last_name" class="mb-0">Last name</label>
                                        <input type="text" required class="form-control form-control-sm" name="last_name" id="last_name" placeholder="Last name" >
                                        <div class="invalid-feedback">
                                            Last name required
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="other_name" class="mb-0">Other name</label>
                                        <input type="text"  class="form-control form-control-sm" name="other_name" id="other_name" placeholder="Other name">
                                        <div class="invalid-feedback">
                                            Other name
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="student_id_number" class="mb-0">Student ID Number</label>
                                        <input type="text" required  class="form-control form-control-sm" name="student_id_number" id="student_id_number" placeholder="ID Number">
                                        <div class="invalid-feedback">
                                            ID Number required
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="email" class="mb-0">Date of Birth</label>
                                        <input type="date" name="date_of_birth" required class="form-control form-control-sm" placeholder="Date of Birth">
                                        <div class="invalid-feedback">
                                            Date of Birth required
                                        </div>
                                    </div>
                                    <div class="col-md-4 ">
                                        <label for="personal_email" class="mb-0">Personal Email</label>
                                        <input type="email" required class="form-control form-control-sm" name="personal_email" id="personal_email" placeholder="Personal Email">
                                        <div class="invalid-feedback">
                                            Email required
                                        </div>
                                    </div>
                                    <div class="col-md-4 ">
                                        <label for="work_email" class="mb-0">Work Email</label>
                                        <input type="email"  class="form-control form-control-sm" name="work_email" id="work_email" placeholder="Work Email">
                                        <div class="invalid-feedback">
                                            Work required
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="gender" class="mb-0">Gender</label>
                                        <select required id="gender" style="width: 100%" name="gender" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Gender required
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="st_session" class="mb-0">Session</label>
                                        <select required id="st_session" style="width: 100%" name="session_id" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            @foreach($sessions as $session)
                                                <option value="{{$session->id}}">{{$session->name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Session required
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="phone_number" class="mb-0">Phone Number</label>
                                        <input type="text" required  class="form-control form-control-sm" name="phone_number" id="phone_number" placeholder="Phone Number">
                                        <div class="invalid-feedback">
                                            Phone Number required
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="Specialization" class="mb-0">Specialization</label>
                                        <select required id="Specialization" style="width: 100%" name="program" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            @foreach($programs as $program)
                                                <option value="{{$program->id}}">{{$program->name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Specialization required
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="st_location" class="mb-0">Location</label>
                                        <select required id="st_location" style="width: 100%" name="location_id" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            @foreach($locations as $location)
                                                <option value="{{$location->id}}">{{$location->country.", ".$location->city_town}}</option>
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
                        <button type="submit" class="btn btn-primary">Add Trainee</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    {{--End modal--}}

    {{--upload modal--}}
    <div class="modal fade" id="uploadTrainee" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{route('upload-student')}}" enctype="multipart/form-data" method="post" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Upload</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-row">
                                    <div class="col-md-12 mb-2">
                                        <label for="selectPictures">Select Picture(s)</label>
                                        <input required class="form-control-file p-1" name="pictures[]" multiple accept="image/*" id="selectPictures" style="width:100%; border-radius: 0;border: dashed black 1px;" type="file">
                                        <div class="invalid-feedback">
                                            Select Picture(s)
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label for="excelFile">Select Excel File</label>
                                        <input required name="file" class="form-control-file p-1" id="excelFile" style="width:100%; border-radius: 0;border: dashed black 1px;" type="file">
                                        <div class="invalid-feedback">
                                            Select Find
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-row">
                                    <div class="col-md-12 mb-2">
                                        <label for="program" class="mb-0">Specialization</label>
                                        <select required id="program" style="width: 100%" name="program" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            @foreach($programs as $program)
                                                <option value="{{$program->id}}">{{$program->name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Program required
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label for="session" class="mb-0">Session</label>
                                        <select required id="session" style="width: 100%" name="session_id" class="form-control form-control-lg select2">
                                            <option value=""></option>
                                            @foreach($sessions as $session)
                                                <option value="{{$session->id}}">{{$session->name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Session required
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label for="location" class="mb-0">Location</label>
                                        <select required id="location" style="width: 100%" name="location_id" class="form-control form-control-lg select2">
                                            @foreach($locations as $location)
                                                <option value=""></option>
                                                <option value="{{$location->id}}">{{$location->country.", ".$location->city_town}}</option>
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
                    <div class="row p-2 border-top">
                        <div class="col-md-6">
                            <a href="{{route('format-trainees')}}" class="text-decoration-none text-danger ml-2">Download upload Format</a>
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
