@extends('layouts.app')

@section('title', 'Workouts')

@section('content')
<main role="main" class="container">
    @if(Session::has('status'))
        <div class="alert alert-success">{{ Session::get('status') }}</div>
    @endif

    <table class="table table-borderless table-striped">
        <thead>
            <tr>
                <th>Title</th>
                <th>Date</th>
                <th>Distance</th>
                <th>Duration</th>
                <td>Actions</td>
            </tr>
        </thead>
        <tbody>
        @foreach ($workouts as $workout)
            <tr>
                <td><a href="{{action('WorkoutsController@show', [ 'id' => $workout->id ])}}">{{$workout->title}}</a></td>
                <td>{{$workout->points[0]->time}}</td>
                <td>{{$workout->distance}} km</td>
                <td>{{$workout->duration}}</td>
                <td>
                    <form method="POST"  action="{{action('WorkoutsController@destroy', [ 'id' => $workout->id ])}}">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE"></input>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            </div>
        @endforeach
        </tbody>
    </table>
    {{ $workouts->links() }}
</main>

@endsection

@section('scripts')

@endsection