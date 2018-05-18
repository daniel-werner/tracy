<div class="workout-details card mb-5 box-shadow bg-light">
    <div class="card-header">
        <h5>{{$workout->title}} on <span class="small">{{$workout->points[0]->time}}</span></h5>
    </div>
    <div class="row m-0">
        <div class="col-8 p-0">
            <div class="workout-map" id="workout-map-{{$workout->id}}"></div>
        </div>
        <div class="col-4">
            <div class="card-body pt-1 pl-0">
                <div class="row">
                    <div class="col-6">
                        <div>Distance: <h5>{{$workout->distance}} km</h5></div>
                        <div>Duration: <h5>{{$workout->duration}}</h5></div>
                        <div>Average speed: <h5>{{$workout->avgspeed}} km/h</h5></div>
                    </div>
                    <div class="col-6">
                        <div>Min elevation: <h5>{{$workout->minelevation}} m</h5></div>
                        <div>Max elevation: <h5>{{$workout->maxelevation}} m</h5></div>
                        <div>Average heart rate: <h5>{{$workout->avghr}} bpm</h5></div>
                        <div>Min heart rate: <h5>{{$workout->minhr}} bpm</h5></div>
                        <div>Max heart rate: <h5>{{$workout->maxhr}} bpm</h5></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="analysis-chart" id="analysis-chart-{{$workout->id}}"></div>
        </div>
    </div>
</div>

@section('scripts')
    <script type="application/javascript">
        $(document).ready(function(e){
            var data = {!!$workout->toJson()!!};
            var workouts = new Workouts([data]);
            workouts.init({
              mode: 'details'
            });
        });
    </script>

@endsection
