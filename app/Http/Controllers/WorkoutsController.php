<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Workout;
use App\Point;
use Illuminate\Support\Facades\Auth;
use App\Utilities\WorkoutImport\Parsers\Gpx;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Session\Session;


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
        $workouts = Workout::with('points')->orderBy( 'created_at', 'desc')->paginate(15);

        return view( 'workouts.index' , compact( 'workouts' ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('workouts.create', ['workout' => new Workout()]);
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
        \Debugbar::disable();
        $path = $request->workout_file->storeAs('workouts', $request->workout_file->getClientOriginalName());

        $path = storage_path('app/' . $path);
        $data = $gpx->parse($path);

        $workout = [
            'title' => $gpx->getType() ?? 'New workout',
            'type' => $request->type,
            'import_filename' => $path,
            'user_id' => Auth::id(),
            'status' => Workout::STATUS_ACTIVE
        ];

        $workout = Workout::create( $workout );

        $points = [];

        foreach( $gpx as $point ){
            $points[] = new Point([
                'workout_id' => $workout->id,
                'coordinates' => $point,
                'heart_rate' => $point->getHeartRate(),
                'elevation' => $point->getEvelation(),
                'time' => $point->getTime()
            ]);
        }

        $workout->points()->saveMany( $points );

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
        $workout = Workout::with('points')
            ->where(['id' => $id] )
            ->first();


        return view( 'workouts.show' , compact( 'workout' ));
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
    public function destroy(Request $request, $id)
    {
        $workout = Workout::findOrFail($id);
        if($workout->delete()){
            $request->session()->flash('status', 'Workout deleted!');
        }
        else{
            $request->session()->flash('status', 'Unable to delete workout!');
        }

        return redirect(action('WorkoutsController@index'));
    }
}
