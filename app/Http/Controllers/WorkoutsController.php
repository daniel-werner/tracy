<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Workout;
use Illuminate\Support\Facades\Auth;
use App\Utilities\WorkoutImport\Parsers\Gpx;


class WorkoutsController extends Controller
{
    public function __construct()
    {
        $this->middleware( 'auth' );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $workouts = Workout::all();

        return view( 'workouts.index' , compact( 'workouts' ));
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
     * @param  Gpx $gpx
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Gpx $gpx)
    {
        $path = $request->workout_file->storeAs('workouts', $request->workout_file->getClientOriginalName());

        $path = storage_path('app/' . $path);
        $data = $gpx->parse($path);

        dd($data);

        $workout = [
            'title' => 'New workout',
            'import_filename' => $path,
            'user_id' => Auth::id()
        ];

        Workout::create( $workout );

        return redirect( '/workouts' );
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
