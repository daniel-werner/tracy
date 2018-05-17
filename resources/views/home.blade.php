@extends('layouts.app')

@section('title', 'Workouts')

@section('content')
    <main role="main" class="container">

        @foreach ($workouts as $workout)
            <div class="card mb-5 box-shadow">
                <div class="card-header">
                    <h5><a class="card-link" href="{{action('WorkoutsController@show', [ 'id' => $workout->id ])}}">{{$workout->title}}</a> on <span class="small">{{$workout->points[0]->time}}</span></h5>
                </div>
                    <div class="row bg-light m-0">
                        <div class="col-8 p-0">
                            <div class="workout-map" id="workout-map-{{$workout->id}}"></div>
                        </div>
                        <div class="col-4">
                            <div class="card-body pb-0 pt-2 pl-0">
                                <div class="row">
                                    <div class="col-6">
                                        <div>Distance: <h4>{{$workout->distance}} km</h4></div>
                                        <div>Duration: <h4>{{$workout->duration}}</h4></div>
                                        <div>Average speed: <h4>{{$workout->avgspeed}} km/h</h4></div>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{action('WorkoutsController@show', [ 'id' => $workout->id ])}}" class="btn btn-lg btn-primary mt-3">Show details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        @endforeach
    </main>

@endsection
@section('scripts')
    <script type="application/javascript">
        $(document).ready(function(e){
            var data = {!!$workouts->toJson()!!};
            var workouts = new Workouts(data);
            workouts.init({
                mode: 'list'
            });
        }, false);
    </script>

@endsection