<?php

namespace App\Http\Controllers;

use App\Area;
use App\Brand;
use App\Category;
use App\Imports\ItemImport;
use App\Item;
use App\ItemType;
use App\Location;
use App\Ownership;
use App\Staff;
use App\Status;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *0556696386
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


        if(Gate::allows('isSuperAdmin')){
            $allItems = Item::simplePaginate(50);
        }else{
            $allItems = Item::where('location_id',Auth::user()->location_id)->simplePaginate(50);
        }

        $item_types = ItemType::all();
        $categories = Category::all();
        $brands = Brand::all();
        $areas = Area::all();
        $ownership = Ownership::all();
        $status = Status::all();
        $locations = Location::all();
        $staff = Staff::all();
//        return $staff;
        return view('pages.items.index',
            compact('staff','allItems','item_types','categories','brands','areas','ownership','status','locations'));
    }

    public function filterItems(Request $request, Item $itemQuery){

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
        return view('pages.items.index',
            compact('staff','locations','allItems','item_types','categories','brands','areas','ownership','status'))
            ->withInput($request->all);
    }



    public function uploadFormat(){
        $pathToFile = public_path('Item_Upload_Format.xlsx');
        return response()->download($pathToFile, 'Item_Upload_Format.xlsx');
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
        $checkAssetTag = Item::where('asset_tag_number',$request->asset_tag_number)->first();
        if (!empty($checkAssetTag)){
            return back()->with('error','An items with the same Asset Tag Exist');
        }
        $image = $request->file('image_file');

        if ($image != '') {
            list($width, $height) = getimagesize($image);
            $image_name = $request->asset_tag_number . '.' . $image->getClientOriginalExtension();
            $path = public_path('assets/img/items/' . $image_name);
            Image::make($image->getRealPath())->resize($width, $height)->save($path);
        }

        /*
         * Generate QR Code
         */
        QrCode::format('png')->size(500)->errorCorrection('H')
            ->generate($request->asset_tag_number,
                public_path('assets/qr_codes/items/'.$request->asset_tag_number.'.png'));
        DB::beginTransaction();
        try{
            $item = new Item();
            $item->asset_tag_number = strtoupper($request->asset_tag_number);
            $item->category_id = $request->category;
            $item->item_type_id = $request->type;
            $item->location_id = $request->location_id;
            $item->brand_id = $request->brand;
            $item->area_id = $request->area;
            $item->ownership_id = $request->ownership;
            $item->status_id = $request->status;
            $item->description = $request->description;
            $item->remarks = $request->remarks;
            $item->date_purchased = $request->date_purchased;
            $item->qr_code = $request->asset_tag_number.'.png';
            if ($image != '') {  $item->picture = $image_name;}
            $item->staff_id = $request->in_charge;
            $item->user_id = Auth::user()->id;
            if ($item->save()){
                DB::commit();
                return back()->with('success','New items Created');
            }
        }catch (\Exception $exception){
            DB::rollBack();

            return  $exception;
            return back()->with('warning','Something went wrong, Try Again!');
        }
    }


    public function importItems(Request $request){
        $item= Excel::toCollection(new ItemImport(), request()->file('file'));

        $items = $item[0];
        $countImages = count($request->file('pictures'));

//        return $countImages;
        $countItems = count($item[0]);

        if ($countImages != $countItems){
            return back()->with('error','Number of Pictures should be equal to Items');
        }

        DB::beginTransaction();
        try{
            for ($i=0; $i<count($items); $i++) {

                $checkBeforeUpload = Item::where('asset_tag_number',$items[$i]['asset_tag_number'])->first();
                if ($checkBeforeUpload == ''){
                    if ($request->file('pictures')[$i] != ''){
                        list($width, $height) = getimagesize($request->file('pictures')[$i]);
                        $image_name[$i] = $items[$i]['asset_tag_number'] . '.' . $request->file('pictures')[$i]->getClientOriginalExtension();
                        $path = public_path('assets/img/items/' . $image_name[$i]);
                        Image::make($request->file('pictures')[$i]->getRealPath())->resize($width, $height)->save($path);
                    }
                    /*
                    * Generate QR Code
                    */
                    QrCode::format('png')->size(500)->errorCorrection('H')
                        ->generate($items[$i]['asset_tag_number'],public_path('assets/qr_codes/items/'.$items[$i]['asset_tag_number'].'.png'));

                    $itm = new Item();
                    $itm->asset_tag_number = strtoupper($items[$i]['asset_tag_number']);
                    $itm->location_id = $request->location_id;
                    $itm->category_id = $request->category;
                    $itm->item_type_id = $request->type;
                    $itm->brand_id = $request->brand;
                    $itm->area_id = $request->area;
                    $itm->ownership_id = $request->ownership;
                    $itm->status_id = $request->status;
                    $itm->description = $items[$i]['description'];
                    $itm->remarks = $items[$i]['remarks'];
                    $itm->date_purchased = Date::excelToDateTimeObject($items[$i]['date_purchased'])->format('Y-m-d');
                    $itm->qr_code = $items[$i]['asset_tag_number'].'.png';
                    $itm->picture = $image_name[$i];
                    $itm->user_id = Auth::user()->id;
                    $itm->staff_id = $request->in_charge;
                    $itm->save();
                }
            }
            DB::commit();
            return back()->with('success',count($item[0]).' Item(s) Uploaded Successfully');
        }catch (\Exception $exception){
            DB::rollBack();
            return back()->with('warning','Something went wrong, Try again.');
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
        $item = Item::with('staff','category','brand','item_type','ownership','area','status','student_issued_items.student',
            'staff_issued_items.staff')->find($id);
        $item_types = ItemType::all();
        $categories = Category::all();
        $brands = Brand::all();
        $areas = Area::all();
        $ownership = Ownership::all();
        $status = Status::all();
//        return $item;
        return view('pages.items.edit',
            compact('item','item_types','categories','brands','areas','ownership','status'));
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
            list($width, $height) = getimagesize($image);
            $image_name = $request->asset_tag_number . '.' . $image->getClientOriginalExtension();
            $path = public_path('assets/img/items/' . $image_name);
            Image::make($image->getRealPath())->resize($width, $height)->save($path);
        }

        /*
         * Generate QR Code
         */
        QrCode::format('png')->size(500)->errorCorrection('H')->generate($request->asset_tag_number,
            public_path('assets/qr_codes/items/'.$request->asset_tag_number.'.png'));
        DB::beginTransaction();
        try{
            $item =  Item::find($id);
            $item->asset_tag_number = strtoupper($request->asset_tag_number);
            $item->category_id = $request->category;
            $item->item_type_id = $request->type;
            $item->brand_id = $request->brand;
            $item->area_id = $request->area;
            $item->ownership_id = $request->ownership;
            $item->status_id = $request->status;
            $item->description = $request->description;
            $item->remarks = $request->remarks;
            $item->date_purchased = $request->date_purchased;
            $item->qr_code = $request->asset_tag_number.'.png';
            if ($image != '') {$item->picture = $image_name;}
            $item->user_id = Auth::user()->id;
            if ($item->save()){
                DB::commit();
                return back()->with('success','Item Updated');
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
        //
    }
}
