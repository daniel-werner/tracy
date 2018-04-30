@extends('layouts.app')

@section('title', 'Workouts')

@section('content')
<main role="main" class="container">
    <form method="POST" action="/workouts" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="custom-file form-group">
            <input type="file" class="custom-file-input" name="workout_file" id="workout_file">
            <label class="custom-file-label" for="customFile">Choose file</label>
        </div>
        <div class="form-group"></div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    @foreach ($workouts as $workout)
        <div class="jumbotron">
            <h1>{{$workout->title}}</h1>
            <p class="lead">{{$workout->created_at}}</p>
            <div class="workout-map" id="workout-map-{{$workout->id}}">

            </div>
            <a class="btn btn-lg btn-primary" href="../../components/navbar/" role="button">View navbar docs &raquo;</a>
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