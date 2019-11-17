<?php

namespace App\Http\Controllers;

use App\Location;
use App\Program;
use App\Session;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
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
        $students = Student::with('location')->simplePaginate(4);
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
        $prefix = $findLocation->country_prefix."-".$findLocation->city_town_prefix."-".$findProgram->prefix."-";

        $countStudents = Student::where('location_id',$request->location_id)
            ->where('program_id',$request->program)->get()->count();

        if ($countStudents == 0){
            $registration_number =  $prefix.substr($findSession->name,5).'001';
            $student_number =substr($findSession->name,5). '001';
        }else{
            $lastStudentRecord = Student::where('location_id',$request->location_id)
                ->where('program_id',$request->program)->latest('id')->first();
            if (empty($lastStudentRecord)){
                $registration_number = $prefix.substr($findSession->name,5).'001';
            } else {
                if ($lastStudentRecord->student_number <9){
                    $std_num = $lastStudentRecord->student_number+1;
                    $student_number =substr($findSession->name,5)."00".$std_num;
                    $registration_number =   $prefix.$student_number;
                }else if ($lastStudentRecord->student_number >=9 && $lastStudentRecord->student_number <99){
                    $std_num = $lastStudentRecord->student_number+1;
                    $student_number =substr($findSession->name,5)."0".$std_num;
                    $registration_number =   $prefix.substr($findSession->name,5)."0".$student_number;
                }else{
                    $std_num = $lastStudentRecord->student_number+1;
                    $student_number =$std_num;
                    $registration_number =   $prefix.substr($findSession->name,5).$student_number;
                }
            }
        }
        $image = $request->file('image_file');

        if ($image != '') {
            $image_name = $registration_number . '.' . $image->getClientOriginalExtension();
            $path = public_path('assets/img/profile/' . $image_name);
            Image::make($image->getRealPath())->resize(413, 531)->save($path);
        }
        /*
         * Generate QR Code
         */
        QrCode::format('png')->size(100)->generate($registration_number,public_path('assets/qr_codes/'.$registration_number.'.png'));
        DB::beginTransaction();
        try{
            $student = new Student();
            $student->location_id = $request->location_id;
            $student->program_id = $request->program;
            $student->session_id = $request->session_id;
            $student->first_name = $request->first_name;
            $student->last_name = $request->last_name;
            $student->other_name = $request->other_name;
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
            $path = public_path('assets/img/profile/' . $image_name);
            Image::make($image->getRealPath())->resize(413, 531)->save($path);
        }
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
