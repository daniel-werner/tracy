@extends('layouts.app')

@section('title', 'Edit Workout')

@section( 'content' )
<main role="main" class="container">
    <div class="mb-3">
        <form method="POST"  action="{{action('WorkoutsController@update', [ 'id' => $workout->id ])}}">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT"/>
            <input type="hidden" name="status" value="{{\App\Workout::STATUS_ACTIVE}}"/>
            <div class="form-group">
                <label for="title">Workout title</label>
                <input type="text" id="title" name="title" class="form-control" value="{{$workout->title}}"/>
            </div>
            <select class="custom-select mt-2 mb-2" name="type">
                <option value="">Select workout type</option>
                @foreach ( $workout->types as $type => $label )
                    <option value="{{$type}}" {{ $workout->type == $label ? 'selected' : '' }}>{{$label}}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary pull-right m-2 pl-4 pr-4">Save</button>
        </form>

        <form method="POST"  action="{{action('WorkoutsController@destroy', [ 'id' => $workout->id ])}}">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="DELETE" />
            <button type="submit" class="btn btn-danger pull-right m-2">Discard</button>
        </form>
        <div class="clearfix"></div>
    </div>


    @includeWhen($workout->id, 'workouts.workout_details')

</main>

@endsection