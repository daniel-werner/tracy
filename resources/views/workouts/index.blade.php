@extends('layouts.app')

@section('title', 'Workouts')

@section('content')
<main role="main" class="container">
    <div class="mb-3">
        <form method="POST" action="/workouts" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="custom-file form-group">
                <input type="file" class="custom-file-input" name="workout_file" id="workout_file">
                <label class="custom-file-label" for="customFile">Choose file</label>
            </div>
            <div class="form-group"></div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <table class="table table-borderless table-striped">
        <thead>
            <tr>
                <th>Title</th>
                <th>Date</th>
                <th>Distance</th>
                <th>Duration</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($workouts as $workout)
            <tr>
                <td><a href="{{action('WorkoutsController@show', [ 'id' => $workout->id ])}}">{{$workout->title}}</a></td>
                <td>{{$workout->points[0]->time}}</td>
                <td>{{$workout->distance}} km</td>
                <td>{{$workout->duration}}</td>
            </tr>
            </div>
        @endforeach
        </tbody>
    </table>
</main>

@endsection

@section('scripts')
    <script type="application/javascript">
        document.addEventListener('DOMContentLoaded', function(){
            $("input[type=file]").change(function () {
                var fieldVal = $(this).val();

                // Change the node's value by removing the fake path (Chrome)
                fieldVal = fieldVal.replace("C:\\fakepath\\", "");

                if (fieldVal != undefined || fieldVal != "") {
                    $(this).next(".custom-file-label").attr('data-content', fieldVal);
                    $(this).next(".custom-file-label").text(fieldVal);
                }

            });

        });
    </script>

@endsection