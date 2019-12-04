<?php

namespace App\Http\Controllers;

use App\ItemType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ItemTypeController extends Controller
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
        $item_types = ItemType::all();
        return view('pages.config.item_type.index',compact('item_types'));
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
        $check = ItemType::where('name',$request->item_type)->first();
        if (!empty($check)){
            return back()->with('error','Item Type already exist');
        }
        DB::beginTransaction();
        try{
            $item_type = new ItemType();
            $item_type->name = $request->item_type;
            $item_type->user_id = Auth::user()->id;

            if ($item_type->save()){
                DB::commit();
                return back()->with('success','New Item Type Added');
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
            $item_type =  ItemType::find($id);
            $item_type->name = $request->item_type_name;
//            $category->user_id = Auth::user()->id;
            if ($item_type->save()){
                DB::commit();
                return back()->with('success','Item Type Updated');
            }
        }catch (\Exception $exception)
        {
            DB::rollBack();
            return back()->with('warning','Something went wrong, Try Again!');
        }
    }

    public function deleteType(Request $request){
        $selected_id = explode(',',$request->input('selected_ids'));;
        foreach ($selected_id as $value){
            $item_type = ItemType::find($value);
            $item_type->delete();
        }
        return back()->with('success',count($selected_id).' Item Type Deleted');
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
