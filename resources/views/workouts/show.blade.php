@extends('layouts.app')

@section('title', 'Workout details')

@section('content')
<main role="main" class="container">
    <div class="workout-details jumbotron p-3">
        <h4>{{$workout->title}}</h4>
        <p class="small">{{$workout->points[0]->time}}</p>
        <div class="row">
            <div class="col-8">
                <div class="workout-map" id="workout-map-{{$workout->id}}"></div>
            </div>
            <div class="col-4">
                <div>Distance: <span>{{$workout->distance}} km</span></div>
                <div>Duration: <span>{{$workout->duration}}</span></div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12">
                <div class="analysis-chart"></div>
            </div>
        </div>

    </div>
</main>

@endsection

@section('scripts')
    <script type="application/javascript">
        document.addEventListener('DOMContentLoaded', function(){
            var workouts = new Workouts();
            var data = {!!$workout->toJson()!!};
            workouts.setWokroutData([data]);
            workouts.init();
        }, false);
    </script>

@endsection
