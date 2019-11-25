<?php

namespace App\Http\Controllers;

use App\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DesignationController extends Controller
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
        $designations = Designation::all();
        return view('pages.config.designation.index',compact('designations'));
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
        $check = Designation::where('Designation',$request->designation)->first();
        if (!empty($check)){
            return back()->with('error','Designation already exit');
        }
        DB::beginTransaction();
        try{
            $area = new Designation();
            $area->designation = $request->designation;
            $area->user_id = Auth::user()->id;

            if ($area->save()){
                DB::commit();
                return back()->with('success','New Designation Added');
            }
        }catch (\Exception $exception)
        {
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
        //
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

        DB::beginTransaction();
        try{
            $designation =  Designation::find($id);
            $designation->designation = $request->designation;
            if ($designation->save()){
                DB::commit();
                return back()->with('success','Designation Updated');
            }
        }catch (\Exception $exception)
        {
            DB::rollBack();
            return back()->with('warning','Something went wrong, Try Again!');
        }
    }

    public function deleteArea(Request $request){
        $selected_id = explode(',',$request->input('selected_ids'));;
        foreach ($selected_id as $value){
            $designation = Designation::find($value);
            $designation->delete();
        }
        return back()->with('success',count($selected_id).' Designation Deleted');
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
