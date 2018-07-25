@extends('layouts.app')

@section('title', __('Edit profile'))

@section('content')
<main role="main" class="container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="POST" action="{{ action('UsersController@profile') }}">
                    @csrf
                    <input type="hidden" name="_method" value="PUT"/>
                    @include('users.user_fields')
                </form>
        </div>
    </div>
</main>

@endsection

@section('scripts')
@endsection