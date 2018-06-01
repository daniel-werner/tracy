@extends('layouts.app')

@section('title', 'Workout details')

@section('content')
    <main role="main" class="container">
        @includeWhen($workout->id, 'workouts.workout_details')
    </main>
@endsection
