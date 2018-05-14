@extends('layouts.app')

@section('title', 'Workouts')

@section('content')
<main role="main" class="container">
    <div class="mb-3">
        <form method="POST" action="/workouts" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="custom-file form-group">
                <input type="file" class="custom-file-input" name="workout_file" id="workout_file">
                <label class="custom-file-label" for="customFile">Choose file</label>
            </div>
            <div class="form-group"></div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    @foreach ($workouts as $workout)
        <div class="jumbotron p-3">
            <h2>{{$workout->title}}</h2>
            <p class="small">{{$workout->points[0]->time}}</p>
            <div class="row">
                <div class="col-sm-4">
                    <div class="workout-map" id="workout-map-{{$workout->id}}"></div>
                </div>
                <div class="col-sm-6">
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