<?php

namespace App\Http\Controllers;

use App\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AreaController extends Controller
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
        $areas = Area::all();
        return view('pages.config.areas.index',compact('areas'));
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
        $check = Area::where('name',$request->area_name)->first();
        if (!empty($check)){
            return back()->with('error','Area already exit');
        }
        DB::beginTransaction();
        try{
            $area = new Area();
            $area->name = $request->area_name;
            $area->user_id = Auth::user()->id;

            if ($area->save()){
                DB::commit();
                return back()->with('success','New Area Added');
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
            $area =  Area::find($id);
            $area->name = $request->area_name;
//            $category->user_id = Auth::user()->id;
            if ($area->save()){
                DB::commit();
                return back()->with('success','Area Updated');
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
            $area = Area::find($value);
            $area->delete();
        }
        return back()->with('success',count($selected_id).' Areas Deleted');
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
