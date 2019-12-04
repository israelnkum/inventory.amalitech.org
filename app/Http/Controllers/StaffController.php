<?php

namespace App\Http\Controllers;

use App\Designation;
use App\Location;
use App\Program;
use App\ProgramTeaching;
use App\Staff;
use App\StaffDesignation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
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
        if (!Gate::allows('canLogin')) {
            abort(503,'Account Deactivated! Contact your Administrator');
        }
        if (!Gate::allows('isSuperAdmin')) {
            abort(503,'You may not have access! Contact your Administrator');
        }
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
        $checkEmail = Staff::where('personal_email',$request->personal_email)
            ->orwhere('work_email',$request->work_email)->first();
        if (!empty($checkEmail)){
            return back()->with('error','A Staff with the same Email Exist');
        }

        $checkNameAndEmail = Staff::where('first_name',$request->first_name)
            ->where('last_name',$request->last_name)
            ->where('personal_email',$request->personal_email)
            ->orwhere('work_email',$request->work_email)->first();


        $checkStaffId = Staff::where('staff_number',strtoupper($request->staff_number))->first();
        if (!empty($checkStaffId)){
            return back()->with('error','A Staff with the same ID Exist');
        }

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

        /*if ($countStudents == 0){
            $registration_number =  $prefix.$student_number_prefix.'00001';
            $student_number =$student_number_prefix. '00001';
        }
        else{
            $lastStudentRecord = Staff::where('location_id',$request->location_id)
                ->latest('id')->first();
            if (empty($lastStudentRecord)){
                $registration_number =  $prefix.$student_number_prefix.'0001';
                $student_number =$student_number_prefix. '0001';
            }
            else {
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
        }*/

//        return $student_number;
        $image = $request->file('image_file');

        if ($image != '') {
            $image_name = $prefix.strtoupper($request->staff_number) . '.' . $image->getClientOriginalExtension();
            $path = public_path('assets/img/profile/staff/' . $image_name);
            Image::make($image->getRealPath())->resize(413, 531)->save($path);

            /** Generate QR Code*/
            QrCode::format('png')->size(500)->errorCorrection('H')
                ->generate($request->first_name.' '.$request->other_name.' '.$request->last_name.' | '.strtoupper($request->staff_number),
                    public_path('assets/qr_codes/staff/'.$prefix.strtoupper($request->staff_number).'.png'));
        }
        DB::beginTransaction();
        try{
            $staff = new Staff();
            $staff->registration_number = $prefix.strtoupper($request->staff_number);
            $staff->staff_number = $request->staff_number;
            $staff->location_id = $request->location_id;
            $staff->first_name = ucfirst($request->first_name);
            $staff->last_name = ucfirst($request->last_name);
            $staff->other_name = ucfirst($request->other_name);
            $staff->dob = $request->date_of_birth;
            $staff->gender = $request->gender;
            $staff->personal_email = $request->personal_email;
            $staff->work_email = $request->work_email;
            $staff->phone_number = $request->phone_number;
            $staff->joining_date = $request->joining_date;
            $staff->contract_valid_till = $request->contract_valid_till;
            $staff->profile = $image_name;
            $staff->qr_code = $prefix.strtoupper($request->staff_number).'.png';
            if ($request->has('can_login')){$staff->can_login = 1;}
            $staff->remarks = $request->remarks;
            $staff->save();

            if ($request->has('program')){
                foreach ($request->program as $program) {
                    $subjectTeaching = new ProgramTeaching();
                    $subjectTeaching->staff_id = $staff->id;
                    $subjectTeaching->program_id = $program;
                    $subjectTeaching->user_id = Auth::user()->id;
                    $subjectTeaching->save();
                }
            }

            if ($request->has('designations')){
                foreach ($request->designations as $designation) {
                    $staffDesignation = new StaffDesignation();
                    $staffDesignation->staff_id = $staff->id;
                    $staffDesignation->designation_id = $designation;
                    $staffDesignation->user_id = Auth::user()->id;
                    $staffDesignation->save();
                }
            }
            if ($request->has('can_login')){
                $user = new User();
                $user->name = ucfirst($request->first_name)." ".ucfirst($request->other_name)." ".ucfirst($request->last_name);
                if ($request->has('work_email')){
                    $user->email = $request->work_email;
                }else{
                    $user->email = $request->personal_email;
                }
                $user->password= Hash::make('11111111');
                $user->user_type = $request->user_type;
                $user->picture = $image_name;
                $user->updated = 0;
                $user->location_id = $request->location_id;
                $user->gender = $request->gender;
                $user->phone_number = $request->phone_number;
                $user->staff_id =  $staff->id;
                $user->save();
            }
            if ($request->has('program')){
                if ($staff->save() && $subjectTeaching->save() && $staffDesignation->save() && $user->save()){
                    DB::commit();
                }
            }else if ($request->has('can_login')){
                if ($staff->save()  && $staffDesignation->save() && $user->save()){
                    DB::commit();
                }
            }
            else{
                if ($staff->save()  && $staffDesignation->save()){
                    DB::commit();
                }
            }
            return back()->with('success','New Staff Created');

        }catch (\Exception $exception){

            DB::rollBack();

            return  $exception;
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
        $trainer = Staff::with('items.item_type','staff_designation.designation','staff_issue_item.item.item_type')->find($id);
        $subjects = [];

//        return $trainer;
        foreach($trainer->program_teaching as $subject){
            array_push($subjects,$subject->program->id);
        }

        $st_designations = [];
        foreach($trainer->staff_designation as $design){
            array_push($st_designations,$design->designation->id);
        }
//        return $st_designations;
        return view('pages.staff.edit',compact('st_designations','designations','programs','locations','trainer','subjects'));

    }

    //Delete Staff Program Teaching
    public function deleteSubject(Request $request){
        $subject_teaching = ProgramTeaching::find($request->subject_id);

        if ($subject_teaching->delete()){
            return back()->with('success','Subject Deleted');
        }else{
            return back()->with('warning','Something went wrong, Try Again!');
        }
    }

    public function deleteDesignation(Request $request){
        $st_designation = StaffDesignation::find($request->designation_id);
        if ($st_designation->delete()){
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
            $staff->personal_email = $request->personal_email;
            $staff->work_email = $request->work_email;
            $staff->phone_number = $request->phone_number;
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

        if($request->has('designation_id')&& $request->input('designation_id') != '' )
        {
            $staffQuery->where('designation_id', $request->input('designation_id'));
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

    public function addDesignation(Request $request, $id){
        DB::beginTransaction();
        try{
            foreach ($request->designations as $designation){
                $st_designation = new StaffDesignation();
                $st_designation->staff_id =$id;
                $st_designation->designation_id =$designation;
                $st_designation->user_id = Auth::user()->id;
                $st_designation->save();
            }
            DB::commit();
            return back()->with('success','Designation(s) Added');
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
