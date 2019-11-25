<?php

namespace App\Http\Controllers;

use App\Area;
use App\Brand;
use App\Category;
use App\Item;
use App\ItemType;
use App\Ownership;
use App\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
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
        $allItems = Item::simplePaginate(50);
        $item_types = ItemType::all();
        $categories = Category::all();
        $brands = Brand::all();
        $areas = Area::all();
        $ownership = Ownership::all();
        $status = Status::all();
        return view('pages.items.index',
            compact('allItems','item_types','categories','brands','areas','ownership','status'));
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
        session()->flashInput($request->input());
        return view('pages.items.index',
            compact('allItems','item_types','categories','brands','areas','ownership','status'))
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
        QrCode::format('png')->generate($request->asset_tag_number,
            public_path('assets/qr_codes/items/'.$request->asset_tag_number.'.png'));
        DB::beginTransaction();
        try{
            $item = new Item();
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
            $item->picture = $image_name;
            $item->user_id = Auth::user()->id;
            if ($item->save()){
                DB::commit();
                return back()->with('success','New items Created');
            }
        }catch (\Exception $exception){
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
        $item = Item::with('category','brand','item_type','ownership','area','status')->find($id);
        $item_types = ItemType::all();
        $categories = Category::all();
        $brands = Brand::all();
        $areas = Area::all();
        $ownership = Ownership::all();
        $status = Status::all();
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
        QrCode::format('png')->generate($request->asset_tag_number,
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
