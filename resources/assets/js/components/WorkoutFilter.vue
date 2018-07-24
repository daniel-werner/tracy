<template>
    <div class="card mb-4 box-shadow">
        <div class="card-header p-2">
            <h5 class="m-1"><a data-toggle="collapse" href="#collapse1">Search workouts</a></h5>
        </div>
        <div id="collapse1" class="pt-3 pl-3 m-0 collapse show">
            <form action="#" @submit.prevent="handleSubmit">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <div>
                            <select @change="handleSubmit" class="form-control select-workout-type" id="select-type" v-model="type">
                                <option value="">Select workout type</option>
                                <option v-for="(type, index) in types" :value="index">{{type}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <div>
                            <date-picker @change="handleSubmit" v-model="from" :first-day-of-week="1" :lang="lang" class="form-control no-border p-0 col-12"></date-picker>
                        </div>
                    </div>
                    <div class="col-md-0 pt-2 pl-2 pr-2">to</div>
                    <div class="form-group col-md-4">
                        <div>
                            <date-picker @change="handleSubmit" v-model="to" :first-day-of-week="1" :lang="lang" class="p-0 col-12"></date-picker>
                        </div>
                    </div>
                    <!--<button type="submit" class="btn btn-primary">Submit</button>-->
                    </div>
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
