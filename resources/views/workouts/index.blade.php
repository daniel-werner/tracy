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

    <div class="jumbotron">
        <h1>Navbar example</h1>
        <p class="lead">This example is a quick exercise to illustrate how the top-aligned navbar works. As you scroll, this navbar remains in its original position and moves with the rest of the page.</p>
        <a class="btn btn-lg btn-primary" href="../../components/navbar/" role="button">View navbar docs &raquo;</a>
    </div>
</main>
@endsection