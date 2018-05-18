@extends('layouts.app')

@section('title', 'Workouts')

@section('content')
<main role="main" class="container">
    @if(Session::has('status'))
        <div class="alert alert-success">{{ Session::get('status') }}</div>
    @endif

    <a href="{{action('WorkoutsController@create')}}" class="btn btn-success">Import workout</a>
    <table class="table table-borderless table-striped">
        <thead>
            <tr>
                <th>Title</th>
                <th>Type</th>
                <th>Date</th>
                <th>Distance</th>
                <th>Duration</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($workouts as $workout)
            <tr>
                <td><a href="{{action('WorkoutsController@show', [ 'id' => $workout->id ])}}">{{$workout->title}}</a></td>
                <td>{{$workout->type}}</td>
                <td>{{$workout->points[0]->time}}</td>
                <td>{{$workout->distance}} km</td>
                <td>{{$workout->duration}}</td>
                <td>
                    <div class="form-inline">
                        <a href="{{action('WorkoutsController@edit', [ 'id' => $workout->id ])}}" class="btn btn-warning mr-2 btn-sm">Edit</a>
                        <form method="POST"  action="{{action('WorkoutsController@destroy', [ 'id' => $workout->id ])}}">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="DELETE"/>
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
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