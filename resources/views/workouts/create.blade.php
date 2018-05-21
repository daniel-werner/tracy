@extends('layouts.app')

@section('title', 'Workouts')

@section('content')
<main role="main" class="container">
    @if(Session::has('status'))
        <div class="alert alert-success">{{ Session::get('status') }}</div>
    @endif

    <div class="mb-3">
        <form method="POST" action="{{action('WorkoutsController@create')}}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <select class="custom-select mt-2 mb-2" name="type">
                        <option value="">Select workout type</option>
                    @foreach ( $workout->types as $type => $label )
                        <option value="{{$type}}">{{$label}}</option>
                    @endforeach
            </select>
            <div class="custom-file form-group">
                <input type="file" class="custom-file-input" name="workout_file" id="workout_file">
                <label class="custom-file-label" for="customFile">Choose file</label>
            </div>
            <div class="form-group"></div>
            <button type="submit" class="btn btn-primary">Import</button>
        </form>
    </div>
</main>

@endsection

@section('scripts')
    <script type="application/javascript">
        $(document).ready(function(e){
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