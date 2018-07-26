@extends('layouts.app')

@section('title', 'Workouts')

@section('content')
<main role="main" class="container">
    @if(Session::has('status'))
        <div class="alert alert-success">{{ Session::get('status') }}</div>
    @endif

    <div class="m-3">
        <a href="{{action('WorkoutsController@create')}}" class="btn btn-success">Import workout</a>
    </div>

    <table class="table table-borderless table-striped" >
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
                <td class="p-2"><a href="{{action('WorkoutsController@show', [ 'id' => $workout->id ])}}">{{$workout->title}}</a></td>
                <td class="p-2">{{$workout->type}}</td>
                <td class="p-2">{{$workout->time}}</td>
                <td class="p-2">{{$workout->distance}} km</td>
                <td class="p-2">{{$workout->duration}}</td>
                <td class="p-2">
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
{{--    {{ $workouts->links() }}--}}
</main>

@endsection

@section('scripts')
    <script type="application/javascript" >
        $(document).ready(function() {
            $('.table').DataTable();
        } );
    </script>
@endsection