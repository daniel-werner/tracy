@extends('layouts.app')

@section('title', 'Workouts')

@section('content')
    <main role="main" class="container">

        @foreach ($workouts as $workout)
            <div class="card mb-5 box-shadow">
                <div class="card-header">
                    <h5>{{$workout->title}} on <span class="small">{{$workout->points[0]->time}}</span></h5>
                </div>
                    <div class="row bg-light m-0">
                        <div class="col-6 p-0">
                            <div class="workout-map" id="workout-map-{{$workout->id}}"></div>
                        </div>
                        <div class="col-6">
                            <div class="card-body pt-3 pl-0">
                                <div class="row">
                                    <div class="col-6">
                                        <div>Distance: <span>{{$workout->distance}} km</span></div>
                                        <div>Duration: <span>{{$workout->duration}}</span></div>
                                        <div>Average speed: <span>{{$workout->duration}}</span></div>
                                        <div>Max speed: <span>{{$workout->duration}}</span></div>
                                        <div>Min speed: <span>{{$workout->duration}}</span></div>
                                    </div>
                                    <div class="col-6">
                                        <div>Ascent: <span>{{$workout->duration}}</span></div>
                                        <div>Descent: <span>{{$workout->duration}}</span></div>
                                        <div>Average heart rate: <span>{{$workout->duration}}</span></div>
                                        <div>Max heart rate: <span>{{$workout->duration}}</span></div>
                                        <div>Min heart rate: <span>{{$workout->duration}}</span></div>
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
        document.addEventListener('DOMContentLoaded', function(){
            var workouts = new Workouts();
            var data = {!!$workouts->toJson()!!};
            workouts.setWokroutData(data);
            workouts.init();
        }, false);
    </script>

@endsection