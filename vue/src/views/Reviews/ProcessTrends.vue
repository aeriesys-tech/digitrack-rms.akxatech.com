<template>
    <div class="">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div>
                <ol class="breadcrumb fs-sm mb-1">
                    <li class="breadcrumb-item" aria-current="page">
                        <router-link to="/dashboard">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0)">Review</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Process Trends</li>
                </ol>
                <h4 class="main-title mb-0">Process Trends</h4>
            </div>
            <!-- <router-link to="/assets" type="submit" class="btn btn-primary" style="float: right;"><i class="ri-list-check"></i> ASSETS</router-link> -->
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-one">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="card-title">Process Trends</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <label class="form-label">Asset</label><span class="text-danger"> *</span>
                                <select class="form-control" :class="{ 'is-invalid': errors.asset_id }"
                                    v-model="process_trend.asset_id" @change="getAssetZones">
                                    <option value="">Select Asset</option>
                                    <option v-for="asset, key in assets" :key="key" :value="asset.asset_id">
                                        {{ asset.asset_name }}</option>
                                </select>
                                <span v-if="errors.asset_id" class="invalid-feedback">{{ errors.asset_id[0] }}</span>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Asset Zones</label><span class="text-danger"> *</span>
                                <select class="form-control" :class="{ 'is-invalid': errors.asset_zone_id }"
                                    v-model="process_trend.asset_zone_id" @change="getVariables">
                                    <option value="">Select Asset Zones</option>
                                    <option v-for="asset_zone, key in asset_zones" :value="asset_zone.asset_zone_id" :key="key">{{ asset_zone.zone_name }}</option>
                                </select>
                                <span v-if="errors.asset_zone_id" class="invalid-feedback">{{ errors.asset_zone_id[0]
                                    }}</span>
                            </div>
                              <div class="col-md-3">
                                <label class="form-label">Variables</label><span class="text-danger"> *</span>
                                <select class="form-control" :class="{ 'is-invalid': errors.variable_id }"
                                    v-model="process_trend.variable_id" >
                                    <option value="">Select Variables</option>
                                    <option v-for="variable, key in variables" :value="variable?.variable_id" :key="key">{{ variable.variable_name }}</option>
                                </select>
                                <span v-if="errors.variable_id" class="invalid-feedback">{{ errors.variable_id[0]
                                    }}</span>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">From Date</label><span class="text-danger"> *</span>
                                <input type="date" class="form-control" :class="{ 'is-invalid': errors.from_date }"
                                    v-model="process_trend.from_date" />
                                <span v-if="errors.from_date" class="invalid-feedback">{{ errors.from_date[0]
                                    }}</span>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">To Date</label><span class="text-danger"> *</span>
                                <input type="date" class="form-control" :class="{ 'is-invalid': errors.to_date }"
                                    v-model="process_trend.to_date" />
                                <span v-if="errors.to_date" class="invalid-feedback">{{ errors.to_date[0] }}</span>
                            </div>
                            <div class="col-md-12 mt-3 text-end">
                                <button class="btn btn-primary" @click="search">Search</button>
                            </div>
                        </div>
                        <div class="row mb-3" v-if="process_trend_values.length">
                            <div class="col-md-12">
                                <!-- <LineChart
                                    :title="Line"
                                    :series="series"
                                    :xAxis="xAxis"

                                /> -->
                                <highcharts :options="chartOptions" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LineChart from "@/components/charts/LineChart.vue";
import Highcharts from 'highcharts';
import moment from "moment";
export default {
    components: {LineChart},
    data() {
        return {
            process_trend: {
                asset_id: "",
                asset_zone_id: '',
                variable_id:'',
                from_date: "",
                to_date: "",
            },
    //         series: [{
    //             type: 'line',
    //             data: []
    //         }],
    //         xAxis: {
    //     type: 'category', // Assuming you're using a category axis
    //     data: []
    //   },

        chartOptions: {
            chart: {
                type: 'line'
            },
            title: {
                text: 'Job Date vs Value'
            },
            xAxis: {
                type: 'datetime', // Specify the x-axis as datetime
                title: {
                    text: 'Job Date and Time'
                },
                dateTimeLabelFormats: { // Custom formatting for date and time labels
                    minute: '%H:%M', // Display hours and minutes
                    hour: '%H:%M',   // Display hours and minutes
                    day: '%Y-%m-%d'  // Display full date
                },
            },
            yAxis: {
                title: {
                    text: 'Value'
                }
            },
            series: [{
                name: 'Values',
                data: this.formatData() // Format the data for Highcharts
            }]
        },
            assets: [],
            asset_zones: [],
            variables:[],
            process_trend_values: [],
            errors: [],
            job_date_time: [],
            job_values:[],
        };
    },

    mounted() {
        this.getAssets();
        this.process_trend.from_date = moment().subtract(1, 'day').format("YYYY-MM-DD");
        this.process_trend.to_date = moment().format("yyyy-MM-DD");
        //  this.prepareChartData();
    },
    computed: {
        groupedResults() {
            const results = this.process_trend_values;
            const grouped = [];

            for (let i = 0; i < results.length; i += 2) {
                grouped.push(results.slice(i, i + 2));
            }

            return grouped;
        },
    },
    methods: {
        formatData() {
            // Your input data
            // const job_date = [
            //     "2024-10-18 10:50:00",
            //     "2024-10-18 10:55:00",
            //     "2024-10-18 11:00:00",
            //     "2024-10-18 11:05:00",
            //     "2024-10-18 11:10:00"
            // ];
            const job_date = this.job_date_time ? this.job_date_time : [];

            // const value = [10, 20, 5, 25, 2];
            const value = this.job_values ? this.job_values : [];


            console.log('value:----', value)
            console.log('job_date:----', job_date)
            // Convert job_date strings to timestamp format and pair with corresponding values
            // return job_date.map((date, index) => [new Date(date).getTime(), value[index]]);
            return job_date.map((date, index) => [
        new Date(date + 'Z').getTime(), // Append 'Z' to indicate UTC
        value[index]
      ]);
        },
        getAssets() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store
                .dispatch("post", { uri: "getAssets" })
                .then((response) => {
                    loader.hide();
                    vm.assets = response.data.data;

                    console.log("assets",vm.assets)

                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },
        getAssetZones() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store
                .dispatch("post", { uri: "getAssetZones", data: vm.process_trend })
                .then((response) => {
                    loader.hide();
                    vm.asset_zones = response.data.data;
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },
         getVariables() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store
                .dispatch("post", { uri: "getZoneUserVariables", data: vm.process_trend })
                .then((response) => {
                    loader.hide();
                    vm.variables = response.data;
                    console.log("Vvv---",vm.variables)
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },

        search() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store
                .dispatch("post", { uri: "getProcessTrendValues", data: vm.process_trend })
                .then((response) => {
                    loader.hide();
                    vm.process_trend_values = response.data.data;
                    console.log("valuss-----", vm.process_trend_values)
                    vm.job_values = vm.process_trend_values.map(item => Number(item.value));
                    vm.job_date_time = vm.process_trend_values.map(item => item.job_date);
                    vm.chartOptions.series[0].data = vm.formatData();
                    // console.log("valuesArray-----", valuesArray)
                    //  console.log("JobDateArray-----", JobDateArray)
                    // vm.series[0].data = valuesArray;
                    vm.errors = [];
                })
                .catch(function (error) {
                     console.log("error-----", error)
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },

        dateFormat(value) {
            return moment(value).format("DD-MM-yyyy HH:mm");
        },


    },
};
</script>