<?php

namespace App\Http\Controllers;

use App\Workout;
use Illuminate\Http\Request;

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
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $types= json_encode((new Workout())->types);

        return view('home', compact('types'));
//        return $workouts;
    }
}
