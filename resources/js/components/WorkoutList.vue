<template>
    <div>
        <div v-for="(workout, index) in workouts">
            <workout-list-item :id="workout.id" ></workout-list-item>
        </div>
    </div>
</template>

<script>
    import { eventBus } from '../app';

    export default {
        data() {
            return {
                workouts: []
            };
        },
        created() {
            // Using the service bus
            eventBus.$on('workoutFiltered', (workouts) => {
                var _this = this;
                //Hack to force vuejs to re-render the html to avoid map initialization errors.
                this.workouts = [];

                setTimeout(function(){
                    _this.workouts = workouts;
                },0);

        })},
        mounted () {
            axios.get('/workouts/search')
                    .then(({data}) => {
                        this.workouts = data.data;
                   });
        }
    }
</script>
