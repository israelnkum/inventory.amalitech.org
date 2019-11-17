<?php

namespace App\Http\Controllers;

use App\Area;
use App\Brand;
use App\Category;
use App\ItemType;
use App\Location;
use App\Ownership;
use App\Program;
use App\Status;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProgramController extends Controller
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
        $allPrograms = Program::all();
        return view('pages.config.programs.index',compact('allPrograms'));
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

    public function config(){
        $users = User::all()->count();
        $programs = Program::all()->count();
        $locations = Location::all()->count();
        $categories = Category::all()->count();
        $areas = Area::all()->count();
        $itemType = ItemType::all()->count();
        $brand = Brand::all()->count();
        $ownership = Ownership::all()->count();
        $status = Status::all()->count();
        return view('pages.config.index',
            compact('users','programs','locations','categories','areas','itemType',
            'brand','ownership','status'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $program = new Program();
            $program->name = $request->program_name;
            $program->prefix = strtoupper($request->prefix);
            $program->user_id = Auth::user()->id;

            $check = Program::where('name',$request->program_name)->first();
            if (!empty($check)){
                return back()->with('error','Program Already Exist');
            }
            if ($program->save()){
                DB::commit();
                return back()->with('success','New Program Created');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            return back()->with('warning','Something went wrong, Try Again');
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
        //
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
