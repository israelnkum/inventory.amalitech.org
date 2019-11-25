<?php

namespace App\Http\Controllers;

use App\Designation;
use App\Location;
use App\Program;
use App\ProgramTeaching;
use App\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class StaffController extends Controller
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
        $staff = Staff::with('location')->simplePaginate(50);
        $programs = Program::all();
        $locations = Location::all();
        $designations = Designation::all();
        return view('pages.staff.index',
            compact('programs','locations','staff','designations'));
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
        $checkEmail = Staff::where('email',$request->email)->first();
        if (!empty($checkEmail)){
            return back()->with('error','A Staff with the same Email Exist');
        }

        $checkNameAndEmail = Staff::where('first_name',$request->first_name)
            ->where('last_name',$request->last_name)
            ->where('email',$request->email)->first();

        if (!empty($checkNameAndEmail)){
            return back()->with('error','A Staff with the same Name Exist');
        }


        /*
         * Generate Staff Number
         */
        $findLocation = Location::find($request->location_id);

        //get prefix Eg: GH-TD | Country and City/Town
        $prefix = $findLocation->country_prefix."-";

        //get student prefix EG: SV-01 | Software Verification and Session
        $student_number_prefix = $findLocation->city_town_prefix."-";

        //count the total number of students based on location,specialization and session they belong to
        $countStudents = Staff::where('location_id',$request->location_id)
            ->get()->count();

        if ($countStudents == 0){
            $registration_number =  $prefix.$student_number_prefix.'00001';
            $student_number =$student_number_prefix. '00001';
        }else{
            $lastStudentRecord = Staff::where('location_id',$request->location_id)
                ->latest('id')->first();
            if (empty($lastStudentRecord)){
                $registration_number =  $prefix.$student_number_prefix.'0001';
                $student_number =$student_number_prefix. '0001';
            } else {

                $lastStudentIdNumber= substr($lastStudentRecord->staff_number,strpos($lastStudentRecord->staff_number,'-')+1) ;

//                return $lastStudentIdNumber;
                if ($lastStudentIdNumber <9){
                    $std_num = $lastStudentIdNumber+1;
                    $student_number =$student_number_prefix."0000".$std_num;
                    $registration_number =   $prefix.$student_number;
                }else if ($lastStudentIdNumber >=9 && $lastStudentIdNumber <99){
                    $std_num = $lastStudentIdNumber+1;
                    $student_number =$student_number_prefix."000".$std_num;
                    $registration_number =   $prefix.$student_number;
                }else if ($lastStudentIdNumber >=99 && $lastStudentIdNumber <999){
                    $std_num = $lastStudentIdNumber+1;
                    $student_number =$student_number_prefix."00".$std_num;
                    $registration_number =   $prefix.$student_number;
                }else if ($lastStudentIdNumber >=999 && $lastStudentIdNumber <9999){
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

//        return $student_number;
        $image = $request->file('image_file');

        if ($image != '') {
            $image_name = $request->first_name . '.' . $image->getClientOriginalExtension();
            $path = public_path('assets/img/profile/staff/' . $image_name);
            Image::make($image->getRealPath())->resize(413, 531)->save($path);

            /** Generate QR Code*/
            QrCode::format('png')->size(100)
                ->generate($request->first_name.' '.$request->other_name.' '.$request->last_name.' | '.$student_number,
                    public_path('assets/qr_codes/staff/'.$registration_number.'.png'));
        }
        DB::beginTransaction();
        try{
            $staff = new Staff();
            $staff->registration_number = $registration_number;
            $staff->staff_number = $student_number;
            $staff->location_id = $request->location_id;
            $staff->first_name = ucfirst($request->first_name);
            $staff->last_name = ucfirst($request->last_name);
            $staff->other_name = ucfirst($request->other_name);
            $staff->dob = $request->date_of_birth;
            $staff->gender = $request->gender;
            $staff->email = $request->email;
            $staff->phone_number = $request->phone_number;
            $staff->designation_id =$request->designation;
            $staff->joining_date = $request->joining_date;
            $staff->contract_valid_till = $request->contract_valid_till;
            $staff->profile = $image_name;
            $staff->qr_code = $registration_number.'.png';
            $staff->remarks = $request->remarks;
            $staff->save();
            foreach ($request->program as $program){
                $subjectTeaching = new ProgramTeaching();
                $subjectTeaching->staff_id =$staff->id;
                $subjectTeaching->program_id =$program;
                $subjectTeaching->user_id = Auth::user()->id;
                $subjectTeaching->save();
            }
            if ($staff->save()){
                DB::commit();
                return back()->with('success','New Staff Created');
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
        $designations = Designation::all();
        $trainer = Staff::find($id);
        $subjects = [];
        foreach($trainer->program_teaching as $subject){
            array_push($subjects,$subject->program->id);
        }

//        return $subjects;

        return view('pages.staff.edit',compact('designations','programs','locations','trainer','subjects'));

    }

    public function deleteSubject(Request $request){
        $subject_teaching = ProgramTeaching::find($request->subject_id);

        if ($subject_teaching->delete()){
            return back()->with('success','Subject Deleted');
        }else{
            return back()->with('warning','Something went wrong, Try Again!');
        }
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
        $staff = Staff::find($id);
        $image = $request->file('image_file');

        if ($image != '') {
            $image_name = $request->first_name . '.' . $image->getClientOriginalExtension();
            $path = public_path('assets/img/profile/staff/' . $image_name);
            Image::make($image->getRealPath())->resize(413, 531)->save($path);
        }

        /** Generate QR Code*/
        QrCode::format('png')->size(300)
            ->generate($request->first_name.' '.$request->other_name.' '.$request->last_name.' | '.$staff->staff_number,
                public_path('assets/qr_codes/staff/'.$staff->registration_number.'.png'));

        DB::beginTransaction();
        try{
            $staff->location_id = $request->location_id;
            $staff->first_name = ucfirst($request->first_name);
            $staff->last_name = ucfirst($request->last_name);
            $staff->other_name = ucfirst($request->other_name);
            $staff->dob = $request->date_of_birth;
            $staff->gender = $request->gender;
            $staff->email = $request->email;
            $staff->phone_number = $request->phone_number;
            $staff->designation_id =$request->designation;
            $staff->joining_date = $request->joining_date;
            $staff->contract_valid_till = $request->contract_valid_till;
            $staff->qr_code = $staff->registration_number.'.png';
            if ($image != '') {$staff->profile = $image_name;}
            $staff->remarks = $request->remarks;
            $staff->save();
            if ($staff->save()){
                DB::commit();
                return back()->with('success','New Staff Created');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            return back()->with('warning','Something went wrong, Try Again!');
        }
    }




    public function filterStaff(Request $request, Staff $staffQuery){

        $staffQuery = $staffQuery::query();

        if($request->has('location_id')&& $request->input('location_id') != '' )
        {
            $staffQuery->where('location_id', $request->input('location_id'));
        }
        if($request->has('gender')&& $request->input('gender') != '' )
        {
            $staffQuery->where('gender', $request->input('gender'));
        }

        if($request->has('programs')&& $request->input('programs') != '' )
        {
            $staffQuery->where('program_id', $request->input('programs'));
        }

        if($request->has('designation')&& $request->input('designation') != '' )
        {
            $staffQuery->where('designation', $request->input('designation'));
        }

        $staff = $staffQuery->simplePaginate(50);
        $programs = Program::all();
        $locations = Location::all();
        $designations = Designation::all();
        session()->flashInput($request->input());
        return view('pages.staff.index',
            compact('designations','staff','programs','locations'))->withInput($request->all);
    }




    public function addProgram(Request $request, $id){
        DB::beginTransaction();
        try{
            foreach ($request->program as $program){
                $subjectTeaching = new ProgramTeaching();
                $subjectTeaching->staff_id =$id;
                $subjectTeaching->program_id =$program;
                $subjectTeaching->user_id = Auth::user()->id;
                $subjectTeaching->save();
            }
            DB::commit();
            return back()->with('success','Program(s) Added');
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
        $staff = Staff::find($id);
        DB::beginTransaction();
        try{
            $staff->delete();

            $teaching_subject = ProgramTeaching::where('staff_id',$staff->id)->get();
            foreach ($teaching_subject as $subject){
                $subject->delete();
            }
            DB::commit();
            return redirect()->route('staff.index')->with('success','Staff Deleted');
        }catch (\Exception $exception){
            DB::rollBack();
            return back()->with('warning','Something went wrong, Try Again!');
        }
    }
}
