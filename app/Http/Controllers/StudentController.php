<?php

namespace App\Http\Controllers;

use App\Imports\StudentImport;
use App\Location;
use App\Program;
use App\Session;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = Student::simplePaginate(20);
        $programs = Program::all();
        $locations = Location::all();
        $sessions = Session::all();
        return view('pages.students.index',
            compact('programs','locations','students','sessions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $checkEmail = Student::where('email',$request->email)->first();
        if (!empty($checkEmail)){
            return back()->with('error','A Student with the same Email Exist');
        }

        $checkNameAndEmail = Student::where('first_name',$request->first_name)
            ->where('last_name',$request->last_name)
            ->where('email',$request->email)->first();

        if (!empty($checkNameAndEmail)){
            return back()->with('error','A Student with the same Name Exist');
        }


        /*
         * Generate Student Number
         */
        $findLocation = Location::find($request->location_id);
        $findProgram = Program::find($request->program);
        $findSession = Session::find($request->session_id);

        //get prefix Eg: GH-TD | Country and City/Town
        $prefix = $findLocation->country_prefix."-".$findLocation->city_town_prefix."-";

        //get student prefix EG: SV-01 | Software Verification and Session
        $student_number_prefix = $findProgram->prefix."-".substr($findSession->name,5);

        //count the total number of students based on location,specialization and session they belong to
        $countStudents = Student::where('location_id',$request->location_id)
            ->where('program_id',$request->program)
            ->where('session_id',$request->session_id)
            ->get()->count();

        if ($countStudents == 0){
            $registration_number =  $prefix.$student_number_prefix.'001';
            $student_number =$student_number_prefix. '001';
        }else{
            $lastStudentRecord = Student::where('location_id',$request->location_id)
                ->where('program_id',$request->program)->latest('id')->first();
            if (empty($lastStudentRecord)){
                $registration_number =  $prefix.$student_number_prefix.'001';
                $student_number =$student_number_prefix. '001';
            } else {

                $lastStudentIdNumber= substr($lastStudentRecord->student_number,5) ;

                if ($lastStudentIdNumber <9){
                    $std_num = $lastStudentIdNumber+1;
                    $student_number =$student_number_prefix."00".$std_num;
                    $registration_number =   $prefix.$student_number;
                }else if ($lastStudentIdNumber >=9 && $lastStudentIdNumber <99){
                    $std_num = $lastStudentIdNumber+1;
                    $student_number =$student_number_prefix."0".$std_num;
                    $registration_number =   $prefix.$student_number;
                }else{
                    $std_num = $lastStudentIdNumber+1;
                    $student_number =$student_number_prefix.$std_num;
                    $registration_number =   $prefix.$student_number;
                }
            }
        }

        $image = $request->file('image_file');

        if ($image != '') {
            $image_name = $registration_number . '.' . $image->getClientOriginalExtension();
            $path = public_path('assets/img/profile/trainees/' . $image_name);
            Image::make($image->getRealPath())->resize(413, 531)->save($path);
        }
        /*
         * Generate QR Code
         */
        QrCode::format('png')
            ->generate($request->first_name.' '.$request->other_name.' '.$request->last_name.' | '.$student_number,
                public_path('assets/qr_codes/trainees/'.$registration_number.'.png'));
        DB::beginTransaction();
        try{
            $student = new Student();
            $student->location_id = $request->location_id;
            $student->program_id = $request->program;
            $student->session_id = $request->session_id;
            $student->first_name = ucfirst($request->first_name);
            $student->last_name = ucfirst($request->last_name);
            $student->other_name = ucfirst($request->other_name);
            $student->dob = $request->date_of_birth;
            $student->gender = $request->gender;
            $student->email = $request->email;
            $student->registration_number =$registration_number;
            $student->student_number = $student_number;
            $student->phone_number = $request->phone_number;
            $student->profile = $image_name;
            $student->qr_code = $registration_number.'.png';

            if ($student->save()){
                DB::commit();
                return back()->with('success','New Trainee Created');
            }
        }catch (\Exception $exception){
            DB::rollBack();

            return back()->with('warning','Something went wrong, Try Again!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $programs = Program::all();
        $locations = Location::all();
        $sessions = Session::all();
        $trainee = Student::with('location','program','session')->find($id);

        return view('pages.students.edit',compact('programs','locations','trainee','sessions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $student = Student::find($id);
        $image = $request->file('image_file');
        if ($image != '') {
            $image_name = $student->registration_number . '.' . $image->getClientOriginalExtension();
            $path = public_path('assets/img/profile/trainees/' . $image_name);
            Image::make($image->getRealPath())->resize(413, 531)->save($path);
        }
        QrCode::format('png')->size(100)
            ->generate($request->first_name.' '.$request->other_name.' '.$request->last_name.' | '.$student->registration_number,
                public_path('assets/qr_codes/trainees/'.$student->registration_number.'.png'));

        DB::beginTransaction();
        try{
            $student->location_id = $request->location_id;
            $student->program_id = $request->program;
            $student->first_name = $request->first_name;
            $student->last_name = $request->last_name;
            $student->other_name = $request->other_name;
            $student->dob = $request->date_of_birth;
            $student->email = $request->email;
            $student->phone_number = $request->phone_number;
            $student->session_id = $request->session_id;
            $student->gender = $request->gender;
            $student->qr_code = $student->registration_number.'.png';
            if ($image != '') {$student->profile = $image_name;}
            if ($student->save()){
                DB::commit();
                return back()->with('success','Trainee Info Updated');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            return back()->with('warning','Something went wrong, Try Again!');
        }
    }


    public function import(Request $request)
    {
        $student= Excel::toCollection(new StudentImport(), request()->file('file'));

        $students = $student[0];
        $countImages = count($request->file('pictures'));
        $countStudents = count($student[0]);


        if ($countImages != $countStudents){
            return back()->with('error','Number of Images should be equal to Trainees');
        }

        DB::beginTransaction();
        try{
            for ($i=0; $i<count($students); $i++) {
                /** Generate Student Number*/

                $findLocation = Location::find($request->location_id);
                $findProgram = Program::find($request->program);
                $findSession = Session::find($request->session_id);

                //get prefix Eg: GH-TD | Country and City/Town
                $prefix = $findLocation->country_prefix."-".$findLocation->city_town_prefix."-";

                //get student prefix EG: SV-01 | Software Verification and Session
                $student_number_prefix = $findProgram->prefix."-".substr($findSession->name,5);

                //count the total number of students based on location,specialization and session they belong to
                $countStudents = Student::where('location_id',$request->location_id)
                    ->where('program_id',$request->program)
                    ->where('session_id',$request->session_id)
                    ->get()->count();

                if ($countStudents == 0){
                    $registration_number =  $prefix.$student_number_prefix.'001';
                    $student_number =$student_number_prefix. '001';
                }
                else{
                    $lastStudentRecord = Student::where('location_id',$request->location_id)
                        ->where('program_id',$request->program)->latest('id')->first();
                    if (empty($lastStudentRecord)){
                        $registration_number = $prefix.$student_number_prefix.'001';
                        $student_number =$student_number_prefix. '001';
                    } else {

                        $lastStudentIdNumber= substr($lastStudentRecord->student_number,5) ;

                        if ($lastStudentIdNumber <9){
                            $std_num = $lastStudentIdNumber+1;
                            $student_number =$student_number_prefix."00".$std_num;
                            $registration_number =   $prefix.$student_number;
                        }else if ($lastStudentIdNumber >=9 && $lastStudentIdNumber <99){
                            $std_num = $lastStudentIdNumber+1;
                            $student_number =$student_number_prefix."0".$std_num;
                            $registration_number =   $prefix.$student_number;
                        }else{
                            $std_num = $lastStudentIdNumber+1;
                            $student_number =$student_number_prefix.$std_num;
                            $registration_number =   $prefix.$student_number;
                        }
                    }
                }
                $checkBeforeUpload = Student::where('email',$students[$i]['email'])->first();
                if ($checkBeforeUpload == ''){
                    $image_name[$i] = $registration_number . '.' . $request->file('pictures')[$i]->getClientOriginalExtension();
                    $path = public_path('assets/img/profile/trainees/' . $image_name[$i]);
                    Image::make($request->file('pictures')[$i]->getRealPath())->resize(413, 531)->save($path);

                    /*
                    * Generate QR Code
                    */
                    QrCode::format('png')
                        ->generate($students[$i]['first_name'].' '.$students[$i]['other_name'].' '.$students[$i]['last_name'].' | '.$registration_number,public_path('assets/qr_codes/trainees/'.$registration_number.'.png'));
                    if (substr($students[$i]['phone_number'],'0','1') !=0)
                    {
                        $phone_number = "0".$students[$i]['phone_number'];
                    }else{
                        $phone_number = $students[$i]['phone_number'];
                    }
                    $st = new Student();
                    $st->location_id = $request->location_id;
                    $st->program_id = $request->program;
                    $st->session_id = $request->session_id;
                    $st->first_name = ucfirst($students[$i]['first_name']);
                    $st->last_name = ucfirst($students[$i]['last_name']);
                    $st->other_name = ucfirst($students[$i]['other_name']);
                    $st->dob = $students[$i]['date_of_birth'];
                    $st->gender = $students[$i]['gender'];
                    $st->email = $students[$i]['email'];
                    $st->registration_number =$registration_number;
                    $st->student_number = $student_number;
                    $st->phone_number =$phone_number;
                    $st->profile = $image_name[$i];
                    $st->qr_code = $registration_number.'.png';
                    $st->save();
                }
            }
            DB::commit();
            return back()->with('success',count($student[0]).' Student(s) Uploaded Successfully');
        }catch (\Exception $exception){
            DB::rollBack();
            return back()->with('warning','Something went wrong, Try again.');
        }
    }


    public function filterStudents(Request $request, Student $studentQuery){

        $studentQuery = $studentQuery::query();

        if($request->has('location_id')&& $request->input('location_id') != '' )
        {
            $studentQuery->where('location_id', $request->input('location_id'));
        }
        if($request->has('gender')&& $request->input('gender') != '' )
        {
            $studentQuery->where('gender', $request->input('gender'));
        }

        if($request->has('programs')&& $request->input('programs') != '' )
        {
            $studentQuery->where('program_id', $request->input('programs'));
        }

        if($request->has('sessions_id')&& $request->input('sessions_id') != '' )
        {
            $studentQuery->where('session_id', $request->input('sessions_id'));
        }

        $students = $studentQuery->simplePaginate(20);
        $programs = Program::all();
        $locations = Location::all();
        $sessions = Session::all();
        session()->flashInput($request->input());
        return view('pages.students.index',
            compact('students','programs','locations','sessions'))->withInput($request->all);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
