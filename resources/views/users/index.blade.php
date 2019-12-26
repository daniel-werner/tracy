@extends('layouts.app')

@section('title', 'Workouts')

@section('content')
<main role="main" class="container">
    @if(Session::has('status'))
        <div class="alert alert-success">{{ Session::get('status') }}</div>
    @endif

    <div class="m-3">
        <a href="{{action('UsersController@create')}}" class="btn btn-success">New user</a>
    </div>

    <table class="table table-borderless table-striped" >
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Created at</th>
                <th>Updated at</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
                <td>{{$user->created_at}}</td>
                <td>{{$user->updated_at}}</td>
                <td>
                    <div class="form-inline">
                        <a href="{{action('UsersController@edit', [ 'user' => $user ])}}" class="btn btn-warning mr-2 btn-sm">Edit</a>
                        <form method="POST"  action="{{action('UsersController@destroy', [ 'user' => $user ])}}">
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
</main>

@endsection

@section('scripts')
    <script type="application/javascript" >
        $(document).ready(function() {
            $('.table').DataTable();
        } );
    </script>
@endsection