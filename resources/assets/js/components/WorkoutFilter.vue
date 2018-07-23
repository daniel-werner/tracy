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
                        <select @change="handleSubmit" class="form-control col-9" id="select-type" v-model="type">
                            <option value="">All</option>
                            <option v-for="(type, index) in types" :value="index">{{type}}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <span class="col-sm-2 col-form-label">From:</span>
                    <div class="col-sm-4">
                        <date-picker @change="handleSubmit" v-model="from" :first-day-of-week="1" :lang="lang"></date-picker>
                    </div>
                </div>
                <div class="form-group row">
                    <span class="col-sm-2 col-form-label">From:</span>
                    <div class="col-sm-4">
                        <date-picker @change="handleSubmit" v-model="to" :first-day-of-week="1" :lang="lang"></date-picker>
                    </div>
                </div>
                <!--<button type="submit" class="btn btn-primary">Submit</button>-->
            </form>
        </div>
    </div>
</template>

<script>
    import { eventBus } from '../app';
    import DatePicker from 'vue2-datepicker'

    export default {
        components: { DatePicker },
        props: ['types'],
        data() {
            return {
                lang: 'en',
                type: '',
                from: '',
                to: ''
            };
        },
        methods: {
            handleSubmit: function(e) {
                axios.get('/workouts/search', {
                            params: {
                                type: this.type,
                                from: this.from,
                                to: this.to
                            }
                        })
                        .then(({data}) => {
                            eventBus.$emit('workoutFiltered', data.data );
                });
            }
        }
    }
</script>
