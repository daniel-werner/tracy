@extends('layouts.app')

@section('title', 'Workouts')

@section('content')
    <main role="main" class="container">
        @foreach ($workouts as $workout)
            <workout-list-item :id="{{$workout->id}}"></workout-list-item>
        @endforeach
    </main>

@endsection