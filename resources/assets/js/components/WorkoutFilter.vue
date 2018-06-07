<template>
    <div class="card mb-5 box-shadow">
        <div class="card-header">
            <h5><a data-toggle="collapse" href="#collapse1">Search for workout</a></h5>
        </div>
        <div id="collapse1" class="p-2 collapse show">
            <form action="#" @submit.prevent="handleSubmit">
                <div class="form-group row">
                    <label for="select-type" class="col-sm-2 col-form-label">Workout type:</label>
                    <div class="col-sm-4">
                        <select class="form-control col-9" id="select-type" v-model="type">
                            <option value="">All</option>
                            <option v-for="(type, index) in types" :value="index">{{type}}</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</template>

<script>
    import { eventBus } from '../app';

    export default {
        props: ['types'],
        data() {
            return {
                type: ''
            };
        },
        methods: {
            handleSubmit: function(e) {
                axios.get('/workouts/search', {
                            params: {
                                type: this.type
                            }
                        })
                        .then(({data}) => {
                            eventBus.$emit('workoutFiltered', data.data );
                });
            }
        }
    }
</script>
