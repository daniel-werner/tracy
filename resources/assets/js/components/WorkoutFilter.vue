<template>
    <div class="card mb-5 box-shadow">
        <div class="card-header">
            <h5>Search for workout</h5>
        </div>
        <div class="p-2">
            <form action="#">
                <div class="form-group row">
                    <label for="exampleFormControlSelect1" class="col-sm-2 col-form-label">Workout type:</label>
                    <div class="col-sm-4">
                        <select class="form-control col-9" id="exampleFormControlSelect1">
                            <option v-for="(type, index) in types" :value="index">{{type}}</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['types'],
        data() {
            return {
                data: {}
            };
        }
    }
</script>
