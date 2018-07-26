<template>
    <div class="card mb-5 box-shadow">
        <div class="card-header p-2">
            <h5 class="m-1">
                <a class="card-link" href="#" v-on:click="toggleDetails(workout.id)">{{workout.title}}</a> on <span class="small">{{workout.time}}</span>
                <a href="#" v-on:click="toggleDetails(workout.id)" class="btn btn-sm btn-primary float-right">Toggle details</a>
            </h5>
        </div>
        <div class="row bg-light m-0" v-if="!detailsVisible">
            <div class="col-8 p-0">
                <div class="workout-map" :id="'workout-map-' + id"></div>
            </div>
            <div class="col-4">
                <div class="card-body pb-0 pt-2 pl-0">
                    <div class="row">
                        <div class="col-6">
                            <div>Distance: <h4>{{workout.distance}} km</h4></div>
                            <div>Duration: <h4>{{workout.duration}}</h4></div>
                            <div>Average speed: <h4>{{workout.avgspeed}} km/h</h4></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <workout-details v-if="detailsVisible" :workout="workout"></workout-details>
    </div>
</template>

<script>

    export default {
        props: ['id'],
        data() {
            return {
                detailsVisible: false,
                workout: {
                    points: [{}]
                }
            };
        },
        mounted () {
            axios.get('/workouts/' + this.id)
                    .then(({data}) => {
                    this.workout = data.data;
                    this.workouts = new Workouts([this.workout]);
                   });
        },
        updated(){

            if( this.id != this.workout.id ){
                axios.get('/workouts/' + this.id)
                        .then(({data}) => {
                    this.workout = data.data;
                    this.workouts = new Workouts([this.workout]);
                    this.workouts.init({
                        mode: this.detailsVisible ? 'details' : 'list'
                    });
             });
            }
            else{
                this.workouts.init({
                    mode: this.detailsVisible ? 'details' : 'list'
                });
            }
        },
        methods: {
            toggleDetails: function() {
                this.detailsVisible = !this.detailsVisible;
            }
        }
    }
</script>
