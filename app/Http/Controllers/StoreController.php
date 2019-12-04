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
use App\StaffIssueItem;
use App\Status;
use App\Student;
use App\StudentIssueItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

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
        if (!Gate::allows('canLogin')) {
            abort(503,'Account Deactivated! Contact your Administrator');
        }
        if (Gate::allows('isInCharge')) {
            abort(503,'You may not have access! Contact your Administrator');
        }
        if(Gate::allows('isSuperAdmin')){
            $allItems = Item::where('area_id',1)->simplePaginate(50);
        }else{
            $allItems = Item::where('area_id',1)
                ->where('location_id',Auth::user()->location_id)
                ->simplePaginate(50);
        }
        $item_types = ItemType::all();
        $categories = Category::all();
        $brands = Brand::all();
        $areas = Area::all();
        $ownership = Ownership::all();
        $status = Status::all();
        return view('pages.store.index',
            compact('allItems','item_types','categories','brands','areas','ownership','status'));
    }

    public function filterStoreItems(Request $request, Item $itemQuery){

        $itemQuery = $itemQuery::query();

        if($request->has('category')&& $request->input('category') != '' )
        {
            $itemQuery->where('category_id', $request->input('category'));
        }
        if($request->has('type')&& $request->input('type') != '' )
        {
            $itemQuery->where('item_type_id', $request->input('type'));
        }

        if($request->has('brand')&& $request->input('brand') != '' )
        {
            $itemQuery->where('brand_id', $request->input('brand'));
        }

        if($request->has('area')&& $request->input('area') != '' )
        {
            $itemQuery->where('area_id', $request->input('area'));
        }
        if($request->has('ownership')&& $request->input('ownership') != '' )
        {
            $itemQuery->where('ownership_id', $request->input('ownership'));
        }
        if($request->has('status')&& $request->input('status') != '' )
        {
            $itemQuery->where('status_id', $request->input('status'));
        }

        $allItems = $itemQuery->simplePaginate(50);
        $item_types = ItemType::all();
        $categories = Category::all();
        $brands = Brand::all();
        $areas = Area::all();
        $ownership = Ownership::all();
        $status = Status::all();
        $locations = Location::all();
        $staff = Staff::all();
        session()->flashInput($request->input());
        return view('pages.store.index',
            compact('staff','locations','allItems','item_types','categories','brands','areas','ownership','status'))
            ->withInput($request->all);
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
        $item = Item::with(
            'category',
            'brand',
            'item_type',
            'ownership',
            'area',
            'status',
            'student_issued_items.student',
            'staff_issued_items.staff'
        )->find($id);

        $data =explode(',',$item->current_person);

//        return $data;
        if (count($data) >1) {
            if ($data[2] == "Staff") {
                $currentPerson = StaffIssueItem::with('staff')->where('id', $data[0])->first();
                $current_status = "Staff";
            } else {
                $currentPerson = StudentIssueItem::with('student')->where('id', $data[0])->first();
                $current_status = "Student";
            }


            return view('pages.store.show',compact('item','current_status','locations','checkResults','currentPerson'));
        }else{
            return view('pages.store.show',compact('item','locations','checkResults'));
        }

    }

    public function receiveItem(Request $request){
        DB::beginTransaction();
        try{
            if ($request->current_status == "Staff"){
                $receive = StaffIssueItem::find($request->issue_id);
            }else{
                $receive = StudentIssueItem::find($request->issue_id);
            }


//            return $request;
            $receive->return_remarks = $request->return_remarks;
            $receive->date_returned = date('Y-m-d h:i:s A');
            $receive->received_by = Auth::user()->name;


            $item = Item::find($receive->item_id);
            $item->status_id = 2;
            $item->current_person = null;

            if ($receive->save() && $item->save()){
                DB::commit();
            }
            return back()->with('success','Item received');
        }catch (\Exception $exception){
            DB::rollBack();
            return back()->with('warning','Something went wrong, Try again');
        }


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
        }else{
            $findLocation = Location::find($request->location_id);
        }

        if ($request->staff_student == "Staff"){
            $location_prefix = $findLocation->country_prefix."-";
            $search= str_replace('|','',str_replace($location_prefix.'-','',strtoupper(substr($request->staff_trainee_number,strpos($request->staff_trainee_number,'|')))));


            $checkResults = Staff::where('registration_number', 'like', '%' . str_replace(' ','',$location_prefix.$search) . '%')
                ->where('location_id',$findLocation->id)->get();
            $status = "Staff";
        }else{
            $location_prefix = $findLocation->country_prefix."-".$findLocation->city_town_prefix;
            $search= str_replace('|','',str_replace($location_prefix.'-','',strtoupper(substr($request->staff_trainee_number,strpos($request->staff_trainee_number,'|')))));
            $checkResults = Student::where('registration_number', 'like', '%' .str_replace(' ','',$location_prefix.'-'.$search). '%')
                ->where('location_id',$findLocation->id)->get();
            $status = "Trainee";
        }
        session()->flashInput($request->input());
        if (count($checkResults) <=0){
            return back()->with('error','No Data Found, Try Again!')->withInput($request->all);
        }elseif (count($checkResults) >1){
            return view('pages.store.search-results',compact('item','checkResults','status'));
        }else{
            return view('pages.store.show',compact('item','locations','status'))
                ->with('checkResults',$checkResults[0]);
        }

    }


    //issue Item
    public function issueItem(Request $request){
        DB::beginTransaction();
        try{
            if ($request->status == "Staff"){
                $issue = new StaffIssueItem();
                $issue->staff_id = $request->staff_student_id;
            }else{
                $issue = new StudentIssueItem();
                $issue->student_id = $request->staff_student_id;
            }
            $issue->item_id = $request->item_id;
            $issue->issue_remarks = $request->issue_remarks;
            $issue->date_collected = date('Y-m-d h:m:i A');
            $issue->issued_by = Auth::user()->name;

            $issue->save();

            $item = Item::find($request->item_id);
            $item->status_id =1;
            $item->current_person = $issue->id.",".$request->staff_student_id.",".$request->status;
            $item->save();

            DB::commit();
            return redirect()->route('stores.show',$item->id)->with('success','Item Issued');
        }catch (\Exception $exception){
            DB::rollBack();
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
