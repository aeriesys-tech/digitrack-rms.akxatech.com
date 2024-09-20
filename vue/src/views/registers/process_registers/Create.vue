<template>
    <div class="">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div>
                <ol class="breadcrumb fs-sm mb-1">
                    <li class="breadcrumb-item" aria-current="page">
                        <router-link to="/dashboard">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0)">Registers</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <router-link to="/process_registers">Process Registers</router-link>
                    </li>
                    <li class="breadcrumb-item" aria-current="page" v-if="status">New Process Register</li>
                    <li class="breadcrumb-item active" aria-current="page" v-else>Update Process Register</li>
                </ol>
                <h4 class="main-title mb-0">Process Register</h4>
            </div>
            <router-link to="/process_registers" type="submit" class="btn btn-primary" style="float: right;"><i
                    class="ri-list-check"></i> PROCESS REGISTERS</router-link>
        </div>
        <div class="row">
            <div class="col-12">
                <form @submit.prevent="submitForm">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">Add Process Register</h6>
                            <h6 class="card-title" v-else>Update Process Register</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2 mb-5">
                                <!-- <div class="col-md-4">
                                        <label class="form-label">Service Number</label><span class="text-danger"> *</span>
                                        <input type="text" placeholder="Enter Service Number" class="form-control" :class="{'is-invalid':errors.service_no}" v-model="user_service.service_no" ref="service_no"/>
                                        <span v-if="errors.service_no" class="invalid-feedback">{{ errors.service_no[0] }}</span>
                                    </div> -->
                                <div class="col-md-6">
                                    <label class="form-label">Asset</label><span class="text-danger"> *</span>
                                    <search :class="{ 'is-invalid': errors.asset_id }"
                                        :customClass="{ 'is-invalid': errors.asset_id }"
                                        :initialize="user_variable.asset_id" id="asset_id" label="asset_name"
                                        label2="asset_code" placeholder="Select Asset" :data="assets"
                                        @input="asset => user_variable.asset_id = asset">
                                    </search>
                                    <span v-if="errors.asset_id" class="invalid-feedback">{{ errors.asset_id[0]
                                        }}</span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Job Date Time</label><span class="text-danger"> *</span>
                                    <input type="datetime-local" class="form-control" placeholder="Enter Job Date"
                                        :class="{ 'is-invalid': errors.job_date }"
                                        :value="convertDateFormat(user_variable.job_date)"
                                        v-model="user_variable.job_date" ref="job_date" />
                                    <span v-if="errors.job_date" class="invalid-feedback">{{ errors.job_date[0]
                                        }}</span>
                                </div>

                                <!-- <div class="col-md-4">
                                    <label class="form-label">Asset Zone</label>
                                    <search :class="{ 'is-invalid': errors.asset_zone_id }"
                                        :customClass="{ 'is-invalid': errors.asset_zone_id }"
                                        :initialize="user_variable.asset_zone_id" id="asset_zone_id" label="zone_name"
                                        placeholder="Select Asset Zone" :data="asset_zones"
                                        @input="zone => user_variable.asset_zone_id = zone">
                                    </search>
                                    <span v-if="errors.asset_zone_id" class="invalid-feedback">{{
                                        errors.asset_zone_id[0] }}</span>
                                </div> -->
                                <div class="col-md-12">
                                    <label class="form-label">Note</label>
                                    <textarea type="text" placeholder="Enter Note" class="form-control"
                                        :class="{ 'is-invalid': errors.note }" v-model="user_variable.note"></textarea>
                                    <span v-if="errors.note" class="invalid-feedback">{{ errors.note[0] }}</span>
                                </div>
                            </div>

                            <!-- try start -->
                              <div class="row g-2 mb-3">

                                <div class="col-md-4" v-for="asset_zone,key in asset_zones" :key="key">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">{{ asset_zone.zone_name }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <table
                                        class="table table-responsive table-responsive-sm table-sm text-nowrap table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Variable</th>
                                                <th>Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="user_variable_data, key in user_variable.asset_variables" :key="key">
                                               <td>{{ user_variable_data.variable_name}}</td>
                                               <!-- <td>{{ user_variable.variable_name }}</td> -->
                                                <td>
                                                    <input type="number" class="form-control" placeholder="Enter Value"
                                                        min="0" :class="{ 'is-invalid': errors.value }"
                                                        v-model="user_variable_data.value" />
                                                    <span v-if="errors.value" class="invalid-feedback">{{
                                                        errors.value[0] }}</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                        </div>
                                    </div>
                                </div>
                              </div>


                            <!-- try ends -->
                        </div>
                        <div class="card-footer text-end">
                            <router-link type="button" to="/process_registers" class="btn btn-danger me-2"><i
                                    class="ri-arrow-left-line fs-18 lh-1"></i> Back</router-link>
                            <button type="submit" class="btn btn-primary">
                                <span v-if="status"><i class="ri-save-line fs-18 lh-1"></i> Submit</span>
                                <span v-else><i class="ri-save-line fs-18 lh-1"></i> Update</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import Search from "@/components/Search.vue";
import moment from "moment";
export default {
    name: "Assets.Create",
    components: { Search },
    data() {
        return {
            user_variable: {
                user_variable_id: "",
                variable_id: '',
                job_no: "",
                job_date: "",
                asset_id: "",
                asset_zone_id: "",
                note: "",
                status: true,
                asset_variables: [],
            },
            user_variable_data: {
                user_variable_data_id: "",
                variable_id: "",
                variable: {
                    variable_name: "",
                },
                date_time: "",
                value: "",
                status: true,
            },
            deleted_spares: [],
            assets: [],
            variables: [],
            errors: [],
            asset_zones: [],
            status: true,
        };
    },

    watch: {
        'user_variable.asset_id': function () {
            this.getAssetZones();
            this.getVariables();
        },

        // 'user_variable.asset_zone_id': function () {
        //     this.getServices();
        //     this.getVariables();
        // }
    },
    beforeRouteEnter(to, from, next) {
        next((vm) => {
            vm.getAssets();
            if (to.name == "CreateProcessRegister.Create") {
                if (vm.$store.getters.asset_id) {
                    vm.user_variable.asset_id = vm.$store.getters.asset_id
                }
                vm.$refs.job_date.focus();
            } else {
                vm.status = false;
                let uri = { uri: "getUserVariable", data: { user_variable_id: to.params.user_variable_id } };
                vm.$store
                    .dispatch("post", uri)
                    .then(function (response) {
                        vm.user_variable = response.data.data;
                    })
                    .catch(function (error) {
                        console.log("error", error)
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            }
        });
    },
    mounted() {
        this.user_variable.job_date = moment().format("yyyy-MM-DD");
    },
    methods: {
        convertDateFormat(date) {
            let vm = this;
            return moment(date).format("yyyy-MM-DD");
        },
        submitForm() {
            let vm = this;
            if (vm.status) {
                vm.addUserVariable();
            } else {
                vm.updateUserVariable();
            }
        },
        getValue(value) {
            let vm = this;
            let variable = vm.variables?.filter(function (ele) {
                return ele.variable_id == value;
            });
            if (variable.length) {
                vm.user_variable_data.variable.variable_name = variable[0].variable_name;
            }
        },
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

        getVariables() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store
                .dispatch("post", { uri: "getAssetRegisterVariables", data: vm.user_variable })
                .then((response) => {
                    loader.hide();
                    // vm.variables = response.data
                     vm.user_variable.asset_variables=response.data
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
                .dispatch("post", { uri: "getAssetZones", data: vm.user_variable })
                .then((response) => {
                    loader.hide();
                    vm.asset_zones = response.data.data;


                    // if (!vm.asset_zones.length) {
                    //     vm.getVariables()
                    // }
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },
        addUserVariable() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store
                .dispatch("post", { uri: "addUserVariable", data: vm.user_variable })
                .then((response) => {
                    loader.hide();
                    vm.$store.dispatch("success", response.data.message);
                    vm.$router.push("/process_registers");
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },

        updateUserVariable() {
            let vm = this;
            vm.user_variable.deleted_user_spares = vm.deleted_spares;
            let loader = vm.$loading.show();
            vm.$store
                .dispatch("post", { uri: "updateUserVariable", data: vm.user_variable })
                .then((response) => {
                    loader.hide();
                    vm.$store.dispatch("success", response.data.message);
                    vm.$router.push("/process_registers");
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },

    },
};
</script>
<style scoped>
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
</style>