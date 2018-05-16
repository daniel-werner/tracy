@extends('layouts.app')

@section('title', 'Workout details')

@section('content')
<main role="main" class="container">
    <div class="workout-details card mb-5 box-shadow bg-light">
        <div class="card-header">
            <h5>{{$workout->title}} on <span class="small">{{$workout->points[0]->time}}</span></h5>
        </div>
        <div class="row m-0">
            <div class="col-8 p-0">
                <div class="workout-map" id="workout-map-{{$workout->id}}"></div>
            </div>
            <div class="col-4">
                <div class="card-body pt-1 pl-0">
                    <div class="row">
                        <div class="col-6">
                            <div>Distance: <h5>{{$workout->distance}} km</h5></div>
                            <div>Duration: <h5>{{$workout->duration}}</h5></div>
                            <div>Average speed: <h5>{{$workout->avgspeed}}</h5></div>
                        </div>
                        <div class="col-6">
                            <div>Min elevation: <h5>{{$workout->minelevation}}</h5></div>
                            <div>Max elevation: <h5>{{$workout->maxelevation}}</h5></div>
                            <div>Average heart rate: <h5>{{$workout->avghr}}</h5></div>
                            <div>Min heart rate: <h5>{{$workout->minhr}}</h5></div>
                            <div>Max heart rate: <h5>{{$workout->maxhr}}</h5></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="analysis-chart" id="analysis-chart-{{$workout->id}}"></div>
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
            workouts.init({
              mode: 'details'
            });
        }, false);
    </script>

@endsection
