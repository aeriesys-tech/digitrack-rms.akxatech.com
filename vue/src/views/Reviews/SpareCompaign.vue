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
                    <li class="breadcrumb-item active" aria-current="page">Spare Campaign</li>
                </ol>
                <h4 class="main-title mb-0">Spare Campaign</h4>
            </div>
            <!-- <router-link to="/assets" type="submit" class="btn btn-primary" style="float: right;"><i class="ri-list-check"></i> ASSETS</router-link> -->
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-one">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="card-title">Spare Campaign</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <label class="form-label">Asset</label><span class="text-danger"> *</span>
                                <select class="form-control" :class="{ 'is-invalid': errors.asset_id }"
                                    v-model="spare.asset_id" @change="getScripts">
                                    <option value="">Select Asset</option>
                                    <option v-for="asset, key in assets" :key="key" :value="asset.asset_id">
                                        {{ asset.asset_name }}</option>
                                </select>
                                <span v-if="errors.asset_id" class="invalid-feedback">{{ errors.asset_id[0] }}</span>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Script</label><span class="text-danger"> *</span>
                                <select class="form-control" :class="{ 'is-invalid': errors.script }"
                                    v-model="spare.script" @change="getAnalysis">
                                    <option value="">Select Script</option>
                                    <option v-for="scr, key in scripts" :value="scr" :key="key">{{ scr }}</option>
                                </select>
                                <span v-if="errors.datasource" class="invalid-feedback">{{ errors.datasource[0]
                                    }}</span>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Analysis</label>
                                <select class="form-control" :class="{ 'is-invalid': errors.location }"
                                    v-model="spare.location">
                                    <option value="">Select Analysis</option>
                                    <option v-for="location, key in locations" :key="key" :value="location.location">
                                        {{ location.location }}</option>
                                </select>
                                <span v-if="errors.location" class="invalid-feedback">{{ errors.location[0]
                                    }}</span>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Data Source</label><span class="text-danger"> *</span>
                                <select class="form-control" :class="{ 'is-invalid': errors.datasource }"
                                    v-model="spare.datasource">
                                    <option value="">Select Data Source</option>
                                    <option value="File">File</option>
                                    <option value="Script">Script</option>
                                    <option value="Auto">Auto</option>
                                </select>
                                <span v-if="errors.datasource" class="invalid-feedback">{{ errors.datasource[0]
                                    }}</span>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">From Date</label><span class="text-danger"> *</span>
                                <input type="date" class="form-control" :class="{ 'is-invalid': errors.from_date }"
                                    v-model="spare.from_date" />
                                <span v-if="errors.from_date" class="invalid-feedback">{{ errors.from_date[0]
                                    }}</span>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">To Date</label><span class="text-danger"> *</span>
                                <input type="date" class="form-control" :class="{ 'is-invalid': errors.to_date }"
                                    v-model="spare.to_date" />
                                <span v-if="errors.to_date" class="invalid-feedback">{{ errors.to_date[0] }}</span>
                            </div>
                            <div class="col-md-12 mt-3 text-end">
                                <button class="btn btn-primary" @click="search">Search</button>
                            </div>
                        </div>


                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <tbody>
                                    <tr v-for="(item, index) in groupedResults" :key="index">

                                        <td class="text-center" v-if="item[0]">
                                            <h6>{{ dateFormat(item[0]?.campaign?.job_date_time) || '' }}</h6>
                                            <img class="mb-2" :src="item[0]?.file" height="180" />
                                            <h6 v-if="item[0]?.torpedo_values"><b>Top 10 Values:</b> {{
                                                item[0]?.torpedo_values }} </h6>
                                        </td>
                                        <td class="text-center" v-if="item[1]">
                                            <h6>{{ dateFormat(item[1]?.campaign?.job_date_time) || '' }}</h6>
                                            <img class="mb-2" :src="item[1]?.file" height="180" />
                                            <h6 v-if="item[1]?.torpedo_values"><b>Top 10 Values:</b> {{
                                                item[1]?.torpedo_values }}</h6>
                                        </td>
                                    </tr>
                                    <tr v-if="groupedResults.length == 0">
                                        <td colspan="3" class="text-center text-danger">No records found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from "moment";
export default {
    components: {},
    data() {
        return {
            spare: {
                asset_id: "",
                script: '',
                location: "",
                from_date: "",
                to_date: "",
                datasource: "",
            },

            assets: [],
            scripts: [],
            locations: [],
            campaign_results: [],


            wall: false,
            bottom: false,
            errors: [],
        };
    },

    mounted() {
        this.getAssets();
        // this.spare.from_date = moment().format("yyyy-MM-DD");
        // this.spare.to_date = moment().add(1, "day").format("yyyy-MM-DD");
        this.spare.from_date = moment().subtract(1, 'day').format("YYYY-MM-DD");
        this.spare.to_date = moment().format("yyyy-MM-DD");
    },
    computed: {
        groupedResults() {
            const results = this.campaign_results;
            const grouped = [];

            for (let i = 0; i < results.length; i += 2) {
                grouped.push(results.slice(i, i + 2));
            }

            return grouped;
        },
    },
    methods: {
        getAssets() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store
                .dispatch("post", { uri: "getAssets" })
                .then((response) => {
                    loader.hide();
                    vm.assets = response.data.data;

                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },
        getScripts() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store
                .dispatch("post", { uri: "getScripts", data: vm.spare })
                .then((response) => {
                    loader.hide();
                    vm.scripts = response.data;
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },
        getAnalysis() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store
                .dispatch("post", { uri: "getLocations", data: vm.spare })
                .then((response) => {
                    loader.hide();
                    vm.locations = response.data;
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
                .dispatch("post", { uri: "campaignResultImages", data: vm.spare })
                .then((response) => {
                    loader.hide();
                    vm.campaign_results = response.data.data;
                    vm.errors = [];
                })
                .catch(function (error) {
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