<?php

namespace App\Http\Controllers;

use App\Item;
use App\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (!Gate::allows('canLogin')) {
            abort(503,'Account Deactivated! Contact your Administrator');
        }
        if (Gate::allows('hasUpdated')) {
            if (Gate::allows('isSuperAdmin')) {
                $allItems = Item::all()->count();
                $allStudents = Student::all()->count();
            } else {
                $allItems = Item::where('location_id', Auth::user()->location_id)->count();
                $allStudents = Student::where('location_id', Auth::user()->location_id)->count();
            }
            return view('home', compact('allItems', 'allStudents'));
        } else {
            return view('change-password');
        }
    }
}
