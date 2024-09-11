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
                            <router-link to="/process_registers">User Varaiables</router-link>
                        </li>
                        <li class="breadcrumb-item" aria-current="page" v-if="status">New User Variable</li>
                        <li class="breadcrumb-item active" aria-current="page" v-else>Update User Variable</li>
                    </ol>
                    <h4 class="main-title mb-0">User Variable</h4>
                </div>
                <router-link to="/process_registers" type="submit" class="btn btn-primary" style="float: right;"><i class="ri-list-check"></i> USER VARIABLES</router-link>
            </div>
            <div class="row">
                <div class="col-12">
                    <form @submit.prevent="submitForm">
                        <div class="card card-one">
                            <div class="card-header d-flex justify-content-between">
                                <h6 class="card-title" v-if="status">Add User Variable</h6>
                                <h6 class="card-title" v-else>Update User Variable</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-2 mb-5">
                                    <!-- <div class="col-md-4">
                                        <label class="form-label">Service Number</label><span class="text-danger"> *</span> 
                                        <input type="text" placeholder="Enter Service Number" class="form-control" :class="{'is-invalid':errors.service_no}" v-model="user_service.service_no" ref="service_no"/>
                                        <span v-if="errors.service_no" class="invalid-feedback">{{ errors.service_no[0] }}</span>
                                    </div> -->
                                    <div class="col-md-4">
                                        <label class="form-label">Job Date</label><span class="text-danger"> *</span>
                                        <input
                                            type="date"
                                            class="form-control"
                                            placeholder="Enter Job Date"
                                            :class="{'is-invalid': errors.job_date}"
                                            :value="convertDateFormat(user_variable.job_date)"
                                            v-model="user_variable.job_date"
                                            ref="job_date"
                                        />
                                        <span v-if="errors.job_date" class="invalid-feedback">{{ errors.job_date[0] }}</span>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Asset</label><span class="text-danger"> *</span>
                                        <search
                                            :class="{ 'is-invalid': errors.asset_id }"
                                            :customClass="{ 'is-invalid': errors.asset_id }"
                                            :initialize="user_variable.asset_id"
                                            id="asset_id"
                                            label="asset_name"
                                            label2="asset_code"
                                            placeholder="Select Asset"
                                            :data="assets"
                                            @input=" asset => user_variable.asset_id = asset"
                                        >
                                        </search>
                                        <span v-if="errors.asset_id" class="invalid-feedback">{{ errors.asset_id[0] }}</span>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Asset Zone</label>
                                        <search
                                            :class="{ 'is-invalid': errors.asset_zone_id }"
                                            :customClass="{ 'is-invalid': errors.asset_zone_id }"
                                            :initialize="user_variable.asset_zone_id"
                                            id="asset_zone_id"
                                            label="zone_name"
                                            placeholder="Select Asset Zone"
                                            :data="asset_zones"
                                            @input=" zone => user_variable.asset_zone_id = zone"
                                        >
                                        </search>
                                        <span v-if="errors.asset_zone_id" class="invalid-feedback">{{ errors.asset_zone_id[0] }}</span>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Note</label>
                                        <textarea type="text" placeholder="Enter Note" class="form-control" :class="{'is-invalid': errors.note}" v-model="user_variable.note"></textarea>
                                        <span v-if="errors.note" class="invalid-feedback">{{ errors.note[0] }}</span>
                                    </div>
                                </div>
    
                                <div class="row g-2">
                                    <div class="">
                                        <table class="table table-responsive table-responsive-sm table-sm text-nowrap table-bordered mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Variable <span class="text-danger"> *</span></th>
                                                    <th>Date Time <span class="text-danger"> *</span></th>
                                                    <th>Value <span class="text-danger"> *</span></th>
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <search
                                                            :class="{ 'is-invalid': errors.variable_id }"
                                                            :customClass="{ 'is-invalid': errors.variable_id }"
                                                            aria-describedby="basic-addon2"
                                                            aria-label="Select Variable"
                                                            :initialize="user_variable.variable_id"
                                                            id="variable_id"
                                                            label="variable_name"
                                                            label2="variable_code"
                                                            placeholder="Select Variable"
                                                            :data="variables"
                                                            @input=" variable1 => user_variable.variable_id = variable1"
                                                            @selectsearch="getValue(user_variable.variable_id)"
                                                        >
                                                        </search>
                                                        <span v-if="errors.variable_id" class="invalid-feedback">{{ errors.variable_id[0] }}</span>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control" placeholder="Enter Spare Cost" min="0" :class="{ 'is-invalid': errors.spare_cost }" v-model="user_spare.spare_cost" />
                                                        <span v-if="errors.spare_cost" class="invalid-feedback">{{ errors.spare_cost[0] }}</span>
                                                    </td>
                                                       <td>
                                                        <input type="number" class="form-control" placeholder="Enter Spare Cost" min="0" :class="{ 'is-invalid': errors.spare_cost }" v-model="user_spare.spare_cost" />
                                                        <span v-if="errors.spare_cost" class="invalid-feedback">{{ errors.spare_cost[0] }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <button v-if="user_spare.status" class="btn btn-outline-success mx-1" @click.prevent="addRow()"><i class="ri-add-line fs-18 lh-1"></i></button>
                                                        <button v-else class="btn btn-outline-success mx-1" @click.prevent="updateRow(user_spare)"><i class="ri-save-line fs-18 lh-1"></i></button>
                                                        <button class="btn btn-outline-danger mx-1" @click.prevent="discardNewRow()"><i class="ri-close-line fs-18 lh-1"></i></button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tbody>
                                                <tr v-for="spare, index in user_variable.user_spares" :key="index">
                                                    <td>{{ spare?.spare?.spare_name }}</td>
                                                    <td>{{ spare?.spare_cost }}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-outline-primary mx-2" @click.prevent="editSpare(spare,index)"><i class="ri-pencil-line fs-18 lh-1"></i></button>
                                                        <button type="button" class="btn btn-outline-danger" @click.prevent="deleteSpare(spare,index)"><i class="ri-delete-bin-line fs-18 lh-1"></i></button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <router-link type="button" to="/process_registers" class="btn btn-danger me-2"><i class="ri-arrow-left-line fs-18 lh-1"></i> Back</router-link>
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
                        variable_id:'',
                        job_no: "",
                        job_date: "",
                        asset_id: "",
                        asset_zone_id:"",
                        note: "",
                        status: true,
                    },
                    user_spare: {
                        user_spare_id: "",
                        spare_id: "",
                        spare: {
                            spare_name: "",
                        },
                        spare_cost: "",
                        status: true,
                    },
                    deleted_spares: [],
                    assets: [],
                    variables: [],
                    errors: [],
                    asset_zones:[],
                    status: true,
                };
            },
    
            watch:{
                'user_variable.asset_id': function(){
                    this.getAssetZones();
                },
                
                'user_variable.asset_zone_id': function(){
                   this.getServices();
                   this.getVariables();
                }
            },
            beforeRouteEnter(to, from, next) {
                next((vm) => {
                    vm.getAssets();
                    if (to.name == "CreateProcessRegister.Create") {
                        if(vm.$store.getters.asset_id){
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
                    let spare = vm.variables?.filter(function (ele) {
                        return ele.variable_id == value;
                    });
                    if (spare.length) {
                        vm.user_spare.spare.spare_name = spare[0].spare_name;
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
                getServices() {
                    let vm = this;
                    let loader = vm.$loading.show();
                    vm.$store
                        .dispatch("post", { uri: "getAssetsServices", data: vm.user_variable })
                        .then((response) => {
                            loader.hide();
                            vm.services = response.data;
                           
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
                            vm.variables = response.data
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
                            if(!vm.asset_zones.length){
                                vm.getServices()
                                vm.getVariables()
                            }
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
                        //     vm.$router.push("/user_services");
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
                        //     vm.$router.push("/user_services");
                        })
                        .catch(function (error) {
                            loader.hide();
                            vm.errors = error.response.data.errors;
                            vm.$store.dispatch("error", error.response.data.message);
                        });
                },
                addRow() {
                    let vm = this;
                    vm.errors = [];
                    if (vm.user_spare.spare_id == "" || vm.user_spare.spare_cost == "") {
                        if (vm.user_spare.spare_id == "") {
                            vm.errors.spare_id = ["Spare field cannot be empty"];
                        }
                        if (vm.user_spare.spare_cost == "") {
                            vm.errors.spare_cost = ["Spare Cost cannot be empty"];
                        }
                    } else {
                        vm.user_variable.user_spares.push({
                            user_spare_id: "",
                            spare_id: vm.user_spare.spare_id,
                            spare: {
                                spare_name: vm.user_spare.spare.spare_name,
                            },
                            spare_cost: vm.user_spare.spare_cost,
                        });
                        vm.discardNewRow();
                    }
                },
                discardNewRow() {
                    let vm = this;
                    vm.user_spare.user_spare_id = "";
                    vm.user_spare.spare_id = "";
                    vm.user_spare.spare_cost = "";
                    vm.user_spare.spare.spare_name = "";
                    vm.user_spare.status = true;
                    vm.errors = [];
                },
                editSpare(spare, key) {
                    let vm = this;
                    vm.user_spare.user_spare_id = spare.user_spare_id;
                    vm.user_spare.spare_id = spare.spare_id;
                    vm.user_spare.spare.spare_name = spare.spare.spare_name;
                    vm.user_spare.spare_cost = spare.spare_cost;
                    vm.user_spare.status = false;
                    vm.user_spare.key = key;
                    vm.errors = [];
                },
                updateRow(spare) {
                    let vm = this;
                    vm.errors = [];
                    if (vm.user_spare.spare_id == "" || vm.user_spare.spare_cost == "") {
                        if (vm.user_spare.spare_id == "") {
                            vm.errors.spare_id = ["Spare field cannot be empty"];
                        }
                        if (vm.user_spare.spare_cost == "") {
                            vm.errors.spare_cost = ["Spare Cost cannot be empty"];
                        }
                    } else {
                        let spare_data = vm.user_variable.user_spares.filter(function (element) {
                            return element.key == spare.key;
                        });
                        vm.user_variable.user_spares[spare_data.key] = spare_data;
                        vm.user_variable.user_spares.splice(vm.user_spare.key, 1);
                        vm.user_variable.user_spares.push({
                            user_spare_id: vm.user_spare.user_spare_id,
                            spare_id: vm.user_spare.spare_id,
                            spare: {
                                spare_name: vm.user_spare.spare.spare_name,
                            },
                            spare_cost: vm.user_spare.spare_cost,
                        });
                        vm.discardNewRow();
                    }
                },
                deleteSpare(spare, key) {
                    let vm = this;
                    // let sp = vm.user_variable.user_spares.filter(function (element) {
                    //     return element.spare_id == spare.user_spare_id;
                    // });
                    if (confirm("Are you sure you want to delete")) {
                        vm.deleted_spares.push(spare?.user_spare_id);
                        vm.user_variable.user_spares.splice(key, 1);
                        vm.discardNewRow();
                    }
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
    