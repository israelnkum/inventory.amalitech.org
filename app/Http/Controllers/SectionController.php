<?php

namespace App\Http\Controllers;

use App\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class SectionController extends Controller
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
        $sessions = Session::all();
        return view('pages.config.session.index',compact('sessions'));
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
        $check = Session::where('name',$request->session_name)->first();
        if (!empty($check)){
            return back()->with('error','Session already exist');
        }
        DB::beginTransaction();
        try{
            $area = new Session();
            $area->name = $request->session_name;
            $area->user_id = Auth::user()->id;

            if ($area->save()){
                DB::commit();
                return back()->with('success','New Session Added');
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
            $area =  Session::find($id);
            $area->name = $request->session_name;
//            $category->user_id = Auth::user()->id;
            if ($area->save()){
                DB::commit();
                return back()->with('success','Session Updated');
            }
        }catch (\Exception $exception)
        {
            DB::rollBack();
            return back()->with('warning','Something went wrong, Try Again!');
        }
    }

    public function deleteSession(Request $request){
        $selected_id = explode(',',$request->input('selected_ids'));;
        foreach ($selected_id as $value){
            $session = Session::find($value);
            $session->delete();
        }
        return back()->with('success',count($selected_id).' Session Deleted');
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
