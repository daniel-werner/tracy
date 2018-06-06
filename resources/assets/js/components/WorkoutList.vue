<template>
    <div>
        <div v-for="(workout, index) in workouts">
            <workout-list-item :id="workout.id" ></workout-list-item>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                workouts: []
            };
        },
        mounted () {
            axios.get('/workouts/search')
                    .then(({data}) => {
                    this.workouts = data.data;
                   });
        }
    }
</script>
