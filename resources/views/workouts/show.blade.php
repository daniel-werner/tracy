@extends('layouts.app')

@section('title', 'Workout details')

@section('content')
<main role="main" class="container">
    <div class="workout-details card mb-5 box-shadow bg-light">
        <div class="card-header">
            <h5>{{$workout->title}} on <span class="small">{{$workout->points[0]->time}}</span></h5>
        </div>
        <div class="row m-0">
            <div class="col-7 p-0">
                <div class="workout-map" id="workout-map-{{$workout->id}}"></div>
            </div>
            <div class="col-5">
                <div class="card-body pt-1 pl-0">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
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
