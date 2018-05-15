@extends('layouts.app')

@section('title', 'Workouts')

@section('content')
    <main role="main" class="container">

        @foreach ($workouts as $workout)
            <div class="jumbotron p-3">
                <h4>{{$workout->title}}</h4>
                <p class="small">{{$workout->points[0]->time}}</p>
                <div class="row">
                    <div class="col-4">
                        <div class="workout-map" id="workout-map-{{$workout->id}}"></div>
                    </div>
                    <div class="col-6">
                        <div>Distance: <span>{{$workout->distance}} km</span></div>
                        <div>Duration: <span>{{$workout->duration}}</span></div>
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