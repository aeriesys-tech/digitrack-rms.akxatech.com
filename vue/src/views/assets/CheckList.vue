<template>
    <div class="">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div>
                <h4 class="main-title mb-0">Check List</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body mb-3">
                        <div class="table-responsive table-responsive-sm">
                            <table class="table table-sm text-nowrap table-bordered mb-0">
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-center">#</th>
                                        <th>Asset Paramters</th>
                                        <th>Response</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="check_list, key in check_lists" :key="key">
                                        <td class="text-center">{{ key+1 }}</td>
                                        <td>{{check_list.parameter_name}}</td>
                                        <td class="text-center" style="width: 10%;">
                                            <input class="form-check-input me-1" type="checkbox" v-model="check_list.response" />
                                        </td>
                                        <td class="text-center" style="width: 10%;">
                                            {{ check_list.response?'Yes':'No' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-sm btn-primary mx-2" style="float: right;">Submit</button>
                        <button class="btn btn-sm btn-danger mx-2" style="float: right;">Discard</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default{
        data(){
            return{
                check_lists : [
                    {
                        check_list_id: 1, parameter_name: 'Motor is in running condition', response: false
                    },
                    {
                        check_list_id: 2, parameter_name: 'Running temperature is in normal range', response: false
                    },
                    {
                        check_list_id: 3, parameter_name: 'All parts are well lubricated', response: false
                    },
                    {
                        check_list_id: 4, parameter_name: 'Oil viscosity is normal', response: false
                    },
                    {
                        check_list_id: 5, parameter_name: 'Overload sensor is working', response: false
                    },
                    {
                        check_list_id: 6, parameter_name: 'High voltage/current protection sensor is working', response: false
                    },
                    {
                        check_list_id: 7, parameter_name: 'Sound/vibration is normal', response: false
                    },
                    {
                        check_list_id: 8, parameter_name: 'Motor IR sensor is working', response: false
                    },
                    {
                        check_list_id: 9, parameter_name: 'Bulk terminal is clean and intact', response: false
                    },
                    {
                        check_list_id: 10, parameter_name: 'Serial number/QR code is clean and visible', response: false
                    },
                ]
            }
        }
    }
</script>