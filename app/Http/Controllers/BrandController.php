<?php

namespace App\Http\Controllers;

use App\Area;
use App\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller
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
        $brands = Brand::all();
        return view('pages.config.brands.index',compact('brands'));
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
        $check = Brand::where('name',$request->brand_name)->first();
        if (!empty($check)){
            return back()->with('error','Brand already exit');
        }
        DB::beginTransaction();
        try{
            $brand = new Brand();
            $brand->name = $request->brand_name;
            $brand->user_id = Auth::user()->id;

            if ($brand->save()){
                DB::commit();
                return back()->with('success','New Brand Added');
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
            $brand =  Brand::find($id);
            $brand->name = $request->brand_name;
//            $category->user_id = Auth::user()->id;
            if ($brand->save()){
                DB::commit();
                return back()->with('success','Brand Updated');
            }
        }catch (\Exception $exception)
        {
            DB::rollBack();
            return back()->with('warning','Something went wrong, Try Again!');
        }
    }

    public function deleteBrand(Request $request){
        $selected_id = explode(',',$request->input('selected_ids'));;
        foreach ($selected_id as $value){
            $brand = Brand::find($value);
            $brand->delete();
        }
        return back()->with('success',count($selected_id).' Brands Deleted');
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
