<?php

namespace App\Http\Controllers;

use App\Http\Resources\WorkoutResource;
use App\Utilities\WorkoutImport\Parsers\ParserFactory;
use DebugBar\DebugBar;
use Illuminate\Http\Request;
use App\Models\Workout;
use App\Models\Point;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\Utilities\WorkoutImport\Parsers\Gpx;

class WorkoutsController extends Controller
{
    public function __construct()
    {
        //$this->middleware( 'auth' );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $workouts = Workout::with('points')
            ->orderBy('created_at', 'desc')
            ->get();
//            ->paginate(10);

        return view('workouts.index', compact('workouts'));
    }

    /**
     * @return \Illuminate\Http\Response
     *
     */
    public function search(Request $request)
    {
        $type = $request->get('type');
        $from = $request->get('from');
        $to = $request->get('to');

        $workouts = Workout::select('id')
            ->when($type, function ($query) use ($type) {
                return $query->where('type', $type);
            })
            ->when(!empty($from), function ($query) use ($from) {
                $query->whereDate('time', '>=', $from);
            })
            ->when(!empty($to), function ($query) use ($to) {
                $query->whereDate('time', '<=', $to);
            })
            ->limit(10)
            ->orderBy('time', 'desc')
            ->get();

        return new JsonResource($workouts);
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
    public function store(Request $request)
    {
        $path = $request->workout_file->storeAs('workouts', $request->workout_file->getClientOriginalName());

        $path = storage_path('app/' . $path);

        $parser = ParserFactory::create($path);
        $data = $parser->parse($path);

        $workout = [
            'title' => $parser->getType() ?? 'New workout',
            'type' => $request->type,
            'import_filename' => $path,
            'time' => $parser->getTime(),
            'user_id' => Auth::id(),
            'status' => Workout::STATUS_ACTIVE
        ];

        $workout = Workout::create($workout);
        $workout->savePoints($parser);

        return redirect(action('WorkoutsController@edit', [ 'workout' => $workout ]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $workout = Workout::with('points')
            ->where(['id' => $id])
            ->first();

        if ($request->ajax()) {
            return new WorkoutResource($workout);
        }

        return view('workouts.show', compact('workout'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function geoMock(Request $request, $id)
    {
        $workout = Workout::with('points')
            ->where(['id' => $id])
            ->first();

        return ( new WorkoutResource($workout) )->toGeoMockJson($request);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $workout = Workout::with('points')
            ->where(['id' => $id])
            ->first();

        return view('workouts.edit', compact('workout'));
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
        $workout = Workout::where(['id' => $id])->first();

        $workout->fill([
            'title' => $request->title,
            'type' => $request->type,
            'status' => Workout::STATUS_ACTIVE
        ]);

        if ($workout->save()) {
            $request->session()->flash('status', 'Workout saved!');
        } else {
            $request->session()->flash('status', 'Unable to save workout!');
        }

        return redirect(action('WorkoutsController@edit', ['workout' => $workout]));
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
        if ($workout->delete()) {
            $request->session()->flash('status', 'Workout deleted!');
        } else {
            $request->session()->flash('status', 'Unable to delete workout!');
        }

        return redirect(action('WorkoutsController@index'));
    }
}
