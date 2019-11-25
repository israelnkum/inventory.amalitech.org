<?php

namespace App\Http\Controllers;

use App\Area;
use App\Brand;
use App\Category;
use App\Item;
use App\ItemType;
use App\Location;
use App\Ownership;
use App\Staff;
use App\Status;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
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
        $allItems = Item::where('status_id',1)->orwhere('status_id',2)->simplePaginate(50);
        $item_types = ItemType::all();
        $categories = Category::all();
        $brands = Brand::all();
        $areas = Area::all();
        $ownership = Ownership::all();
        $status = Status::all();
        return view('pages.store.index',
            compact('allItems','item_types','categories','brands','areas','ownership','status'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $locations = Location::all();
        $checkResults =[];
        $item = Item::with('category','brand','item_type','ownership','area','status')->find($id);
        return view('pages.store.show',compact('item','locations','checkResults'));
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

    public function checkItem(Request $request){

        $item = Item::find($request->item_id);
        $locations = Location::all();
        if (Auth::user()->user_type == 'Admin'){
            $findLocation = Location::find(Auth::user()->location_id);
            if ($request->staff_student == "Staff"){
                $location_prefix = $findLocation->country_prefix."-";
                $checkResults = Staff::where('registration_number', 'like', '%' . $location_prefix.$request->staff_trainee_number . '%')
                    ->where('location_id',$findLocation->id)->get();
            }else{
                $location_prefix = $findLocation->country_prefix."-".$findLocation->city_town_prefix;

                $checkResults = Student::where('registration_number', 'like', '%' . strtoupper($location_prefix.'-'.$request->staff_trainee_number) . '%')
                    ->where('location_id',$findLocation->id)->get();
            }
        }else{
            $findLocation = Location::find($request->location_id);
            if ($request->staff_student == "Staff"){
                $location_prefix = $findLocation->country_prefix."-";
                $checkResults = Staff::where('registration_number', 'like', '%' . $location_prefix.$request->staff_trainee_number . '%')
                    ->where('location_id',$findLocation->id)->get();
            }else{
                $location_prefix = $findLocation->country_prefix."-".$findLocation->city_town_prefix;

                $checkResults = Student::where('registration_number', 'like', '%' . strtoupper($location_prefix.'-'.$request->staff_trainee_number) . '%')
                    ->where('location_id',$findLocation->id)->get();
            }
        }

        if (count($checkResults) <=0){
            return back()->with('error','No Data Found, Try Again!');
        }elseif (count($checkResults) >1){
            return "more than one";
        }else{
            return view('pages.store.show',compact('item','locations'))
                ->with('checkResults',$checkResults[0]);
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
