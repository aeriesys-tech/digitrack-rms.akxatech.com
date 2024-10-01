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
                    <li class="breadcrumb-item active" aria-current="page">
                        <router-link to="/user_checks">Check Registers</router-link>
                    </li>
                    <li class="breadcrumb-item" aria-current="page" v-if="status">Add Check</li>
                    <li class="breadcrumb-item active" aria-current="page" v-else>Update Check</li>
                </ol>
                <h4 class="main-title mb-0">Check Register</h4>
            </div>
            <div>
                <router-link to="/user_checks" type="button" class="btn btn-danger me-2"><i class="ri-arrow-left-line fs-18 lh-1"></i> Back</router-link>
                <button type="button" v-if="status" class="btn btn-primary me-2" @click="addUserCheck()"><i class="ri-save-line fs-18 lh-1"></i> Submit</button>
                <button type="button" v-else class="btn btn-primary me-2" @click="updateUserCheck()"><i class="ri-save-line fs-18 lh-1"></i> Update</button>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mb-3">
                <form>
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">Add Check Register</h6>
                            <h6 class="card-title" v-else>Update Check Register</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                 <div class="col-md-3">
                                    <label class="form-label">Asset</label><span class="text-danger"> *</span>
                                    <search
                                        :disabled="!status"
                                        :class="{ 'is-invalid': errors?.asset_id }"
                                        :customClass="{ 'is-invalid': errors?.asset_id }"
                                        :initialize="user_check.asset_id"
                                        id="asset_id"
                                        label="asset_name"
                                        label2="asset_code"
                                        placeholder="Select Asset"
                                        :data="assets"
                                        @input=" asset => user_check.asset_id = asset"
                                        @selectsearch="checkAssets()"
                                    >
                                    </search>
                                    <span v-if="errors?.asset_id" class="invalid-feedback">{{ errors?.asset_id[0] }}</span>
                                </div>
                                 <div class="col-md-3">
                                    <label class="form-label">Department</label><span class="text-danger"> *</span>
                                    <search
                                        :disabled="!status"
                                        :class="{ 'is-invalid': errors.department_id }"
                                        :customClass="{ 'is-invalid': errors.department_id }"
                                        :initialize="user_check.department_id"
                                        id="department_id"
                                        label="department_name"
                                        label2="department_code"
                                        placeholder="Select Department"
                                        :data="departments"
                                        @input=" department => user_check.department_id = department"
                                    >
                                    </search>
                                    <span v-if="errors.asset_id" class="invalid-feedback">{{ errors.asset_id[0] }}</span>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Asset Zone</label>
                                    <search
                                        :disabled="!status"
                                        :class="{ 'is-invalid': errors.asset_zone_id }"
                                        :customClass="{ 'is-invalid': errors.asset_zone_id }"
                                        :initialize="user_check.asset_zone_id"
                                        id="asset_zone_id"
                                        label="zone_name"
                                        placeholder="Select Asset Zone"
                                        :data="asset_zones"
                                        @input=" zone => user_check.asset_zone_id = zone"
                                        @selectsearch="getAssetChecks(user_check.asset_zone_id)"
                                    >
                                    </search>
                                    <span v-if="errors.asset_zone_id" class="invalid-feedback">{{ errors.asset_zone_id[0] }}</span>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Reference Date & Time</label><span class="text-danger"> *</span>
                                    <input
                                        type="datetime-local"
                                        class="form-control"
                                        placeholder="Enter Reference Date"
                                        :class="{'is-invalid': errors.reference_date}"
                                        :value="convertDateFormat(user_check.reference_date)"
                                        v-model="user_check.reference_date"
                                        ref="reference_date"
                                    />
                                    <span v-if="errors.reference_date" class="invalid-feedback">{{ errors.reference_date[0] }}</span>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Note</label>
                                    <textarea type="text" placeholder="Enter Note" class="form-control" :class="{'is-invalid': errors.note}" v-model="user_check.note"></textarea>
                                    <span v-if="errors.note" class="invalid-feedback">{{ errors.note[0] }}</span>
                                </div>
                            </div>
                        </div>


                    </div>
                </form>
            </div>
            <div class="col-12">
                <div class="card card-one">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="card-title">Asset Checks</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="">
                                <table class="table table-responsive table-striped table-responsive-sm table-sm text-nowrap table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Field name</th>
                                            <th>Field Type</th>
                                            <th>Default Value</th>
                                            <th>Is Required</th>
                                            <th>LCL</th>
                                            <th>UCL</th>
                                            <th>Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="check, key in user_check.asset_checks" :key="key">
                                            <td class="text-center">{{ key + 1 }}</td>
                                            <td>{{ check.field_name}}</td>
                                            <td>{{ check.field_type}}</td>
                                            <td>{{ check.default_value}}</td>
                                            <td>{{ check.is_required==1 ? 'Yes' : 'No'}}</td>
                                            <td>{{ check.lcl}}</td>
                                            <td>{{ check.ucl}}</td>
                                            <td v-if="check.field_type == 'Text'">
                                                <input type="text" placeholder="Enter Value" class="form-control"  v-model="check.value" />
                                            </td>
                                            <td v-if="check.field_type == 'Number'">
                                                <input type="number" placeholder="Enter Value" class="form-control"  v-model="check.value" />
                                            </td>
                                            <td v-if="check.field_type == 'Text Area'">
                                                <textarea type="text" placeholder="Enter Value" class="form-control" v-model="check.value"></textarea>
                                            </td>
                                            <td v-if="check.field_type == 'Date'">
                                                <input type="date" placeholder="Enter Value" class="form-control"  v-model="check.value" />
                                            </td>
                                            <td v-if="check.field_type == 'Date & Time'">
                                                <input type="datetime-local" placeholder="Enter Value" class="form-control"  v-model="check.value" />
                                            </td>
                                            <td v-if="check.field_type == 'Select'">
                                                <select class="form-control" v-model="check.value">
                                                    <option value="">Select</option>
                                                    <option v-for="value, key in check?.field_values?.split(',')" :key="key" :value="value">{{ value }}</option>
                                                </select>
                                            </td>
                                            <!-- <td></td> -->
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Search from "@/components/Search.vue";
import moment from "moment";
export default {
    name: "UserChecks.Create",
    components: { Search },
    data(){
        return {
            status:true,
            user_check:{
                user_check_id:'',
                asset_id:'',
                reference_no:'',
                reference_date:'',
                note:'',
                asset_zone_id: "",
                department_id:"",
                status:'',
                asset_checks:[],
            },
            assets: [],
            status:true,
            asset_checks:[],
            asset_zones: [],
            departments:[],
            errors:[]
        }
    },
    beforeRouteEnter(to, from, next) {
        next((vm) => {
            vm.getAssets();
            if (to.name == "UserChecks.Create") {
                if(vm.$store.getters.asset_id){
                    vm.user_check.asset_id = vm.$store.getters.asset_id
                    // vm.getAssetChecks()
                }
                vm.$refs.reference_date.focus();
            } else {
                vm.status = false;
                let uri = { uri: "getUserCheck", data: { user_check_id: to.params.user_check_id } };
                vm.$store
                    .dispatch("post", uri)
                    .then(function (response) {
                        vm.user_check = response.data.data;
                    })
                    .catch(function (error) {
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            }
        });
    },
     watch:{
            'user_check.asset_id': function(){
                this.getAssetZones();
                this.getDepartments();
            },

         'user_check.department_id': function () {
                if (this.status) {
                    this.getAssetChecks();
                }
            }
        },
    mounted() {
        this.user_check.reference_date = moment().format("yyyy-MM-DDTHH:mm");
    },
    methods: {
        checkAssets() {
            this.user_check.asset_zone_id = '';
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
        getDepartments() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store
            .dispatch("post", { uri: "getAssetRegisterDepartments", data:vm.user_check  })
            .then((response) => {
                loader.hide();
                vm.departments = response.data;
            })
            .catch(function (error) {
                loader.hide();
                vm.errors = error.response.data.errors;
                vm.$store.dispatch("error", error.response.data.message);
            });
        },
        getAssetChecks(){
            let vm = this;
            vm.asset_checks = [];
            vm.user_check.asset_checks = []
            let loader = vm.$loading.show();
            vm.$store
            .dispatch("post", { uri: "getAssetRegisterChecks", data:vm.user_check })
            .then((response) => {
                loader.hide();
                // vm.assets = response.data.data;
                if (response.data.data?.length) {
                    vm.user_check.asset_checks = []
                    let asset_check = response.data.data.filter(function(ele){
                        // vm.asset_checks.push(ele.check)
                        // ele.check.value=ele.check.default_value
                        vm.user_check.asset_checks.push({
                            'check_id': ele.check.check_id,
                            'default_value': ele.check.default_value,
                            'field_name': ele.check.field_name,
                            'field_type': ele.check.field_type,
                            'field_value': ele.check.field_value,
                            'is_required': ele.check.is_required,
                            'field_values': ele.check.field_values,
                            'lcl':ele.check.lcl,
                            'order': ele.check.order,
                            'status': ele.check.status,
                            'ucl':ele.check.ucl,
                            'value': "",
                            'asset_check_id': ele.asset_check_id,

                        })
                    })
                    console.log("user", vm.user_check.asset_checks)
                }
            })
            .catch(function (error) {
                loader.hide();
                vm.errors = error.response.data.errors;
                vm.$store.dispatch("error", error.response.data.message);
            });
        },
        addUserCheck(){

            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'addUserCheck', data:vm.user_check })
                .then(response => {
                    loader.hide();
                    vm.$store.dispatch('success',response.data.message);
                    vm.$router.push("/user_checks");
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },
        updateUserCheck(){
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'updateUserCheck', data:vm.user_check })
                .then(response => {
                    loader.hide();
                    vm.$store.dispatch('success',response.data.message);
                    vm.$router.push("/user_checks");
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
                .dispatch("post", { uri: "getAssetZones", data: vm.user_check })
                .then((response) => {
                    loader.hide();
                    vm.asset_zones = response.data.data;
                    // if(!vm.asset_zones.length){
                    //     vm.getAssetChecks()
                    // }
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },
        convertDateFormat(date) {
                let vm = this;
                return moment(date).format('yyyy-MM-DDTHH:mm')

            },


    }
}
</script>