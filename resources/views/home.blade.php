@extends('layouts.app')

@section('title', 'Workouts')

@section('content')
    <main role="main" class="container">
        <workout-filter :types='{!!$types!!}'></workout-filter>
        <workout-list></workout-list>
    </main>
@endsection