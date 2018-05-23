@extends('layouts.app')

@section('title', __('Create user'))

@section('content')
<main role="main" class="container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="POST" action="{{ action('UsersController@store') }}">
                    @csrf
                    @include('users.user_fields')
                </form>
        </div>
    </div>
</main>

@endsection

@section('scripts')
@endsection