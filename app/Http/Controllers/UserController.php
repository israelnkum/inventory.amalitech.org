<?php

namespace App\Http\Controllers;

use App\Location;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class UserController extends Controller
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
        $users = User::all();
        $locations = Location::all();
        return view('pages.config.users.index',compact('users','locations'));
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
        $check = User::where('email',$request->email)->first();
        if (!empty($check)){
            return back()->with('error','A User with the same email Exist');
        }
        DB::beginTransaction();
        try{
            $user = new User();
            $user->location_id = $request->location_id;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make('11111111');
            $user->user_type = $request->user_type;
            if ($user->save()){
                DB::commit();
                return back()->with('success','New '.$request->user_type.' Created');
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
        $user = User::find($id);

//        return $user;
        $locations = Location::all();
        return view('pages.config.users.profile',compact('user','locations'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    public function resetPassword(Request $request){
        DB::beginTransaction();
        try{
            $user = User::find($request->user_id);
            $user->password = Hash::make('11111111');
            $user->updated =0;
            if ($user->save()){
                DB::commit();
            }
            return back()->with('success','User Password Reseted');
        }catch (\Exception $exception){
            DB::rollBack();
            return back()->with('success','Something went wrong, Try again!');
        }
    }
    public function deactivateUser(Request $request){
        if ($request->type == 'activate'){
            DB::beginTransaction();
            try{
                $user = User::find($request->user_id);
                $user->status =0;
                if ($user->save()){
                    DB::commit();
                }
                return back()->with('success','User Account Activated');
            }catch (\Exception $exception){
                DB::rollBack();
                return back()->with('success','Something went wrong, Try again!');
            }
        }else{
            DB::beginTransaction();
            try{
                $user = User::find($request->user_id);
                $user->status =1;
                if ($user->save()){
                    DB::commit();
                }
                return back()->with('success','User Account Deactivated');
            }catch (\Exception $exception){
                DB::rollBack();
                return back()->with('success','Something went wrong, Try again!');
            }
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

        DB::beginTransaction();
        try{
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->gender = $request->gender;
            $user->phone_number = $request->phone_number;
            if ($request->has('user_type')){
                $user->user_type = $request->user_type;
            }
            $image = $request->file('image_file');
            if ($image != '') {
                $image_name = str_replace(' ', '_', $request->name) . time() . '.' . $image->getClientOriginalExtension();
                $path = public_path('assets/img/profile/staff/' . $image_name);
                Image::make($request->file('image_file')->getRealPath())->resize(413, 531)->save($path);

                $user->picture = $image_name;
            }
            if ($user->save()){
                DB::commit();
                return back()->with('success','New '.$request->user_type.' Created');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            return back()->with('warning','Something went wrong, Try Again!');
        }

    }

    public function filterUsers(Request $request, User $userQuery){

        $userQuery = $userQuery::query();

        if($request->has('location_id')&& $request->input('location_id') != '' )
        {
            $userQuery->where('location_id', $request->input('location_id'));
        }
        if($request->has('gender')&& $request->input('gender') != '' )
        {
            $userQuery->where('gender', $request->input('gender'));
        }

        if($request->has('user_type')&& $request->input('user_type') != '' )
        {
            $userQuery->where('user_type', $request->input('user_type'));
        }

        if($request->has('user_status')&& $request->input('user_status') != '' )
        {
            if ($request->user_status == 1){
                $userQuery->where('updated', 1)->where('status', 0);
            }elseif($request->user_status == 2){
                $userQuery->where('updated', 1)->where('status', 1);
            }
            elseif($request->user_status == 3){
                $userQuery->where('updated', 0);
            }
        }

        $users = $userQuery->simplePaginate(50);
        $locations = Location::all();

        session()->flashInput($request->input());
        return view('pages.config.users.index',
            compact('locations','users'))->withInput($request->all);
    }

    public function change_password(Request $request){
        $this->validate($request,[
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (Auth::User()->updated == 1){
//            return $request;
            if (!Hash::check($request['old_password'],Auth::user()->password)){
                return back()->with('error','Old Password is incorrect');
            }elseif(Hash::check($request['password'],Auth::user()->password)){
                return back()->with('error','New Password is the same as current');
            }else{
                $user = User::find(Auth::User()->id);

                $user->password = Hash::make($request->input('password'));
                $user->updated = 1;

                $user->save();
                return back()->with('success','Password Changed Successfully');
            }
        }else{

            $user = User::find(Auth::User()->id);

            $user->password = Hash::make($request->input('password'));
            $user->updated = 1;

            if ($user->save()){
                return redirect('/home')->with('success','Password Changed Successfully');
            }
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
