<?php

namespace App\Http\Controllers;

use App\Location;
use App\Program;
use App\ProgramTeaching;
use App\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

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
    { $staff = Staff::with('location')->simplePaginate(4);
        $programs = Program::all();
        $locations = Location::all();
        return view('pages.staff.index',
            compact('programs','locations','staff'));
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
        /* $findLocation = Location::find($request->location_id);

         $countStudents = Staff::where('location_id',$request->location_id)
             ->where('program_id',$request->program)->get()->count();

         if ($countStudents == 0){
             $registration_number =  $prefix.'001';
             $student_number = '001';
         }else{
             $lastStudentRecord = Staff::where('location_id',$request->location_id)
                 ->where('program_id',$request->program)->latest('id')->first();
             if (empty($lastStudentRecord)){
                 $registration_number = $prefix.'001';
             } else {
                 if ($lastStudentRecord->student_number <9){
                     $std_num = $lastStudentRecord->student_number+1;
                     $student_number ="00".$std_num;
                     $registration_number =   $prefix.$student_number;
                 }else if ($lastStudentRecord->student_number >=9 && $lastStudentRecord->student_number <99){
                     $std_num = $lastStudentRecord->student_number+1;
                     $student_number ="0".$std_num;
                     $registration_number =   $prefix."0".$student_number;
                 }else{
                     $std_num = $lastStudentRecord->student_number+1;
                     $student_number =$std_num;
                     $registration_number =   $prefix.$student_number;
                 }
             }
         }*/
        $image = $request->file('image_file');

        if ($image != '') {
            $image_name = $request->first_name . '.' . $image->getClientOriginalExtension();
            $path = public_path('assets/img/profile/staff/' . $image_name);
            Image::make($image->getRealPath())->resize(413, 531)->save($path);
        }
        DB::beginTransaction();
        try{
            $staff = new Staff();
            $staff->staff_id_number = "staff ID";
            $staff->location_id = $request->location_id;
            $staff->first_name = $request->first_name;
            $staff->last_name = $request->last_name;
            $staff->other_name = $request->other_name;
            $staff->dob = $request->date_of_birth;
            $staff->gender = $request->gender;
            $staff->email = $request->email;
            $staff->phone_number = $request->phone_number;
            $staff->designation =$request->designation;
            $staff->joining_date = $request->joining_date;
            $staff->contract_valid_till = $request->contract_valid_till;
            $staff->profile = $image_name;
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

        $trainer = Staff::with('location','program_teaching.program')->find($id);

        $subjects = [];
        foreach($trainer->program_teaching as $subject){
            array_push($subjects,$subject->program->id);
        }

        return view('pages.staff.edit',compact('programs','locations','trainer','subjects'));

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
        $image = $request->file('image_file');

        if ($image != '') {
            $image_name = $request->first_name . '.' . $image->getClientOriginalExtension();
            $path = public_path('assets/img/profile/staff/' . $image_name);
            Image::make($image->getRealPath())->resize(413, 531)->save($path);
        }
        DB::beginTransaction();
        try{
            $staff = Staff::find($id);
            $staff->staff_id_number = "staff ID";
            $staff->location_id = $request->location_id;
            $staff->first_name = $request->first_name;
            $staff->last_name = $request->last_name;
            $staff->other_name = $request->other_name;
            $staff->dob = $request->date_of_birth;
            $staff->gender = $request->gender;
            $staff->email = $request->email;
            $staff->phone_number = $request->phone_number;
            $staff->designation =$request->designation;
            $staff->joining_date = $request->joining_date;
            $staff->contract_valid_till = $request->contract_valid_till;
            $staff->remarks = $request->remarks;
            if ($image != '') {  $staff->profile = $image_name;}
            $staff->save();
            if ($request->has('program')){
                foreach ($request->program as $program){
                    $subjectTeaching = new ProgramTeaching();
                    $subjectTeaching->staff_id =$staff->id;
                    $subjectTeaching->program_id =$program;
                    $subjectTeaching->user_id = Auth::user()->id;

                    $subjectTeaching->save();
                }
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
