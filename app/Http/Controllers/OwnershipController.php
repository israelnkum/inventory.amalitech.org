<?php

namespace App\Http\Controllers;

use App\Ownership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OwnershipController extends Controller
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
        $ownerships = Ownership::all();
        return view('pages.config.ownership.index',compact('ownerships'));
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
        $check = Ownership::where('description',$request->description)->first();
        if (!empty($check)){
            return back()->with('error','Ownership already exit');
        }
        DB::beginTransaction();
        try{
            $ownership = new Ownership();
            $ownership->description = $request->description;
            $ownership->user_id = Auth::user()->id;

            if ($ownership->save()){
                DB::commit();
                return back()->with('success','Ownership Added');
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
            $ownership =  Ownership::find($id);
            $ownership->description = $request->description;
//            $category->user_id = Auth::user()->id;
            if ($ownership->save()){
                DB::commit();
                return back()->with('success','Ownership Updated');
            }
        }catch (\Exception $exception)
        {
            DB::rollBack();
            return back()->with('warning','Something went wrong, Try Again!');
        }
    }

    public function deleteOwnership(Request $request){
        $selected_id = explode(',',$request->input('selected_ids'));;
        foreach ($selected_id as $value){
            $ownership = Ownership::find($value);
            $ownership->delete();
        }
        return back()->with('success',count($selected_id).' Ownership Deleted');
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
