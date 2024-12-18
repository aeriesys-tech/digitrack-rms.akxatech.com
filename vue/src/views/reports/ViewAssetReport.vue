<template>
    <!-- activity -->
    <div class="" v-if="show_activity">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div>
                <ol class="breadcrumb fs-sm mb-1">
                    <li class="breadcrumb-item" aria-current="page">
                        <router-link to="/dashboard">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0)">Reports</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="javascript:void(0)">Asset Reports</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">View Activity</li>
                </ol>
                <h4 class="main-title mb-0">Activity</h4>
            </div>
            <router-link to="/asset_reports" type="submit" class="btn btn-primary"><i class="ri-arrow-left-line"></i> BACK</router-link>
        </div>
        <div class="row">
            <div class="col-12">
                <form @submit.prevent="submitForm">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title">Activity</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="form-label">Asset</label><span class="text-danger"> *</span>
                                    <input type="text" disabled class="form-control" :value="activity.asset?.asset_code + ' :: ' + activity.asset?.asset_name" />
                                    <span v-if="errors.asset_id" class="invalid-feedback">{{ errors.asset_id[0] }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Activity Type</label><span class="text-danger"> *</span>
                                    <input type="text" disabled class="form-control" :value="activity.reason?.reason_code + ' :: ' + activity.reason?.reason_name" />
                                    <span v-if="errors.reason_id" class="invalid-feedback">Activity type field is required</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Activity Date & Time</label><span class="text-danger"> *</span>
                                    <input
                                        type="datetime-local"
                                        disabled
                                        class="form-control"
                                        placeholder="Enter Activity Date"
                                        :class="{'is-invalid': errors.activity_date}"
                                        ref="activity_date"
                                        step="any"
                                        v-model="activity.activity_date"
                                    />
                                    <span v-if="errors.activity_date" class="invalid-feedback">{{ errors.activity_date[0] }}</span>
                                </div>

                                <div class="col-md-4" v-for="field, key in activity.activity_attributes" :key="key">
                                    <div v-if="field.field_type=='Text'">
                                        <label class="form-label">{{field.display_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <input disabled type="text" class="form-control" :placeholder="'Enter '+ field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.activity_attribute_value.field_value" />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>

                                    <div v-if="field.field_type=='Number'">
                                        <label class="form-label">{{field.display_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <input
                                            type="number"
                                            disabled
                                            class="form-control"
                                            min="0"
                                            oninput="validity.valid||(value='');"
                                            :placeholder="'Enter '+ field.display_name"
                                            :class="{'is-invalid': errors[field.display_name]}"
                                            v-model="field.activity_attribute_value.field_value"
                                        />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>

                                    <div v-if="field.field_type === 'Date'">
                                        <label class="form-label">
                                            {{ field.display_name }}
                                            <span v-if="field.is_required" class="text-danger">*</span>
                                        </label>
                                        <input
                                            disabled
                                            type="date"
                                            class="form-control"
                                            :placeholder="'Enter ' + field.display_name"
                                            :class="{'is-invalid': errors[field.display_name]}"
                                            v-model="field.activity_attribute_value.field_value"
                                        />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">
                                            {{ errors[field.display_name][0] }}
                                        </span>
                                    </div>

                                    <div v-if="field.field_type === 'Date&Time'">
                                        <label class="form-label">
                                            {{ field.display_name }}
                                            <span v-if="field.is_required" class="text-danger">*</span>
                                        </label>
                                        <input
                                            disabled
                                            type="datetime-local"
                                            class="form-control"
                                            :placeholder="'Enter ' + field.display_name"
                                            :class="{'is-invalid': errors[field.display_name]}"
                                            v-model="field.activity_attribute_value.field_value"
                                            step="1"
                                        />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">
                                            {{ errors[field.display_name][0] }}
                                        </span>
                                    </div>
                                    <div v-if="field.field_type=='Dropdown'">
                                        <label class="form-label">{{field.display_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <select disabled class="form-control" :class="{'is-invalid': errors[field.display_name]}" v-model="field.activity_attribute_value.field_value">
                                            <option :value="field.activity_attribute_value.field_value" v-if="field.activity_attribute_value.field_value">{{field.activity_attribute_value.field_value}}</option>
                                            <option :value="field.activity_attribute_value.field_value" v-else>Select {{field.display_name}}</option>
                                            <option v-for="value, key in field.field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                        </select>
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>

                                    <div v-if="field.field_type=='Color'">
                                        <label class="form-label">{{ field.display_name }}<span v-if="field.is_required" class="text-danger">*</span></label>
                                        <input disabled type="color" class="form-control" v-model="field.activity_attribute_value.field_value" style="height: 2.2rem;" />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>

                                    <div v-if="field.field_type=='List'">
                                        <label class="form-label">{{field.display_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <select disabled class="form-control" :class="{'is-invalid': errors[field.display_name]}" v-model="field.activity_attribute_value.field_value">
                                            <option :value="field.activity_attribute_value.field_value" v-if="field.activity_attribute_value.field_value">{{field.activity_attribute_value.field_value}}</option>
                                            <option :value="field.activity_attribute_value.field_value" v-else>Select {{field.display_name}}</option>
                                            <option v-for="value, key in field.list_parameter?.field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                        </select>
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Cost</label>
                                    <input disabled type="number" placeholder="Enter Cost" class="form-control" :class="{'is-invalid':errors.cost}" v-model="activity.cost" />
                                    <span v-if="errors.cost" class="invalid-feedback">{{ errors.cost[0] }}</span>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Note</label>
                                    <textarea disabled type="text" placeholder="Enter Note" class="form-control" :class="{'is-invalid': errors.note}" v-model="activity.note"></textarea>
                                    <span v-if="errors.note" class="invalid-feedback">{{ errors.note[0] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- service -->
    <div class="" v-if="show_service">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div>
                <ol class="breadcrumb fs-sm mb-1">
                    <li class="breadcrumb-item" aria-current="page">
                        <router-link to="/dashboard">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0)">Reports</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="javascript:void(0)">Asset Reports</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">View Service Register</li>
                </ol>
                <h4 class="main-title mb-0">Service Register</h4>
            </div>
            <router-link to="/asset_reports" type="submit" class="btn btn-primary"><i class="ri-arrow-left-line"></i> BACK</router-link>
        </div>
        <div class="row">
            <div class="col-12">
                <form @submit.prevent="submitForm">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title">Service Register</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2 mb-5">
                                <div class="col-md-4">
                                    <label class="form-label">Asset</label><span class="text-danger"> *</span>
                                    <input type="text" disabled class="form-control" :value="user_service.asset?.asset_code + ' :: ' + user_service.asset?.asset_name" />
                                    <span v-if="errors.asset_id" class="invalid-feedback">{{ errors.asset_id }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Service Date & Time</label><span class="text-danger"> *</span>
                                    <input
                                        disabled
                                        type="datetime-local"
                                        class="form-control"
                                        :class="{'is-invalid': errors.service_date}"
                                        :value="convertDateFormat(user_service.service_date)"
                                        v-model="user_service.service_date"
                                        ref="service_date"
                                    />
                                    <span v-if="errors.service_date" class="invalid-feedback">{{ errors.service_date[0] }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Next Service Date & Time</label><span class="text-danger"> *</span>
                                    <input
                                        disabled
                                        type="datetime-local"
                                        class="form-control"
                                        placeholder="Enter Next Service Date"
                                        :class="{'is-invalid': errors.next_service_date}"
                                        :value="convertDateFormat(user_service.next_service_date)"
                                        v-model="user_service.next_service_date"
                                    />
                                    <span v-if="errors.next_service_date" class="invalid-feedback">{{ errors.next_service_date[0] }}</span>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Note</label>
                                    <textarea disabled type="text" placeholder="Enter Note" class="form-control" :class="{'is-invalid': errors.note}" v-model="user_service.note"></textarea>
                                    <span v-if="errors.note" class="invalid-feedback">{{ errors.note[0] }}</span>
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="table-responsive table-responsive-sm">
                                    <table class="table table-sm text-nowrap table-bordered mb-0">
                                        <thead>
                                            <tr style="background-color: rgb(155, 155, 155); color: white;">
                                                <th>Asset Zone</th>
                                                <th>Service</th>
                                                <th>Service Cost</th>
                                                <th>Spare</th>
                                                <th>Quantity</th>
                                                <th>Spare Cost</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="spare, index in user_service.user_spares" :key="index">
                                                <td>{{ spare?.asset_zone?.zone_name}}</td>
                                                <td>{{ spare?.service?.service_name }}</td>
                                                <td>{{ spare?.service_cost }}</td>
                                                <td>{{ spare?.spare?.spare_name }}</td>
                                                <td>{{ spare?.quantity }}</td>
                                                <td>{{ spare?.spare_cost }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- check -->
    <div class="" v-if="show_check">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div>
                <ol class="breadcrumb fs-sm mb-1">
                    <li class="breadcrumb-item" aria-current="page">
                        <router-link to="/dashboard">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0)">Reports</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="javascript:void(0)">Asset Reports</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">View Check Register</li>
                </ol>
                <h4 class="main-title mb-0">Check Register</h4>
            </div>
            <router-link to="/asset_reports" type="submit" class="btn btn-primary"><i class="ri-arrow-left-line"></i> BACK</router-link>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-one mb-3">
                    <div class="card-header">
                        <h5 class="card-title">Check Register</h5>
                        <br />
                    </div>
                    <div class="card-body p-0">
                        <div class="setting-item">
                            <div class="row g-2 mb-4">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Reference Number</label>
                                    <input type="text" disabled class="form-control" :value="user_check.reference_no" />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Asset Code</label>
                                    <input type="text" disabled class="form-control" :value="user_check?.asset?.asset_code" />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Asset Zone</label>
                                    <input type="text" disabled class="form-control" :value="user_check?.asset_zone?.zone_name" />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Reference Date</label>
                                    <input type="text" disabled class="form-control" :value="convertDateFormat(user_check.reference_date)" />
                                </div>
                                <div class="col-md-12 mt-1">
                                    <h6 class="mb-2">Asset Checks :</h6>
                                    <div class="table-responsive table-responsive-sm">
                                        <table class="table table-sm table-striped text-nowrap table-bordered mb-0">
                                            <thead>
                                                <tr style="background-color: rgb(155, 155, 155); color: white;">
                                                    <th class="text-center">#</th>
                                                    <th>Field name</th>
                                                    <th>Field Type</th>
                                                    <th>Default Value</th>
                                                    <th>Is Required</th>
                                                    <th>Field Values</th>
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
                                                    <td>{{ check.field_values}}</td>
                                                    <td>{{ check.lcl}}</td>
                                                    <td>{{ check.ucl}}</td>
                                                    <td>{{ check.value}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-2">
                                <h6>Attachments :</h6>
                                <div class="col-2 mb-2 imageContainer" v-for="img, key in user_check.user_attachments" :key="key">
                                    <a :href="$store.state.apiUrl+'downloadCheckAttachment?file_name='+img.file_name">
                                        <img :src="img.attachments" class="img-fluid1" alt="" :href="img.attachments" style="width: 120px; height: 120px;" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- process -->
    <div class="" v-if="show_process">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div>
                <ol class="breadcrumb fs-sm mb-1">
                    <li class="breadcrumb-item" aria-current="page">
                        <router-link to="/dashboard">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0)">Reports</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="javascript:void(0)">Asset Reports</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">View Process Register</li>
                </ol>
                <h4 class="main-title mb-0">Process Register</h4>
            </div>
            <router-link to="/asset_reports" type="submit" class="btn btn-primary"><i class="ri-arrow-left-line"></i> BACK</router-link>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-one mb-3">
                    <div class="card-header">
                        <h5 class="card-title">Process Register</h5>
                        <br />
                    </div>
                    <div class="card-body p-0">
                        <div class="setting-item">
                            <div class="row g-2 mb-4">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Job Number</label>
                                    <input type="text" disabled class="form-control" :value="user_variable.job_no" />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Asset Code</label>
                                    <input type="text" disabled class="form-control" :value="user_variable?.asset?.asset_code" />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Job Date</label>
                                    <input type="text" disabled class="form-control" :value="convertDateFormat(user_variable.reference_date)" />
                                </div>
                                <div class="col-md-12 mt-1">
                                    <h6 class="mb-2">Asset Variables :</h6>
                                    <div class="table-responsive table-responsive-sm row">
                                        <div class="col-md-4" v-for="asset_zone,key in asset_zones" :key="key">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h6 class="mb-0">{{ asset_zone.zone_name }}</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table text-nowrap table-bordered mb-0">
                                                            <thead>
                                                                <tr style="background-color: rgb(155, 155, 155); color: white;">
                                                                    <th>Variable</th>
                                                                    <th>Value</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr v-for="user_variable_data, key1 in user_variable.user_asset_variables[key]" :key="key1">
                                                                    <td>{{ user_variable_data?.variable?.variable_name}}</td>
                                                                    <td>
                                                                        {{user_variable_data.value}}
                                                                    </td>
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
        components: { Search },
        data() {
            return {
                activity: {},
                user_service: {},
                user_check: {},
                user_variable: {},
                asset_zones:[],
                errors: [],
                show_activity: false,
                show_service: false,
                show_check: false,
                show_process: false,
            };
        },
        beforeRouteEnter(to, from, next) {
            next((vm) => {
                if (to.params.type == "activity") {
                    vm.show_activity = true;
                    let uri = { uri: "getUserActivity", data: { user_activity_id: to.params.id } };
                    vm.$store
                        .dispatch("post", uri)
                        .then(function (response) {
                            vm.activity = response.data.data;
                        })
                        .catch(function (error) {
                            vm.errors = error.response.data.errors;
                            vm.$store.dispatch("error", error.response.data.message);
                        });
                }
                if (to.params.type == "service") {
                    vm.show_service = true;
                    let uri = { uri: "getUserService", data: { user_service_id: to.params.id } };
                    vm.$store
                        .dispatch("post", uri)
                        .then(function (response) {
                            vm.user_service = response.data.data;
                        })
                        .catch(function (error) {
                            vm.errors = error.response.data.errors;
                            vm.$store.dispatch("error", error.response.data.message);
                        });
                }
                if (to.params.type == "user_check") {
                    vm.show_check = true;
                    let uri = { uri: "getUserCheck", data: { user_check_id: to.params.id } };
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
                if (to.params.type == "process") {
                    vm.show_process = true;
                    let uri = { uri: "getUserVariable", data: { user_variable_id: to.params.id } };
                    vm.$store
                        .dispatch("post", uri)
                        .then(function (response) {
                            vm.user_variable = response.data.data;
                            vm.getAssetZones();
                        })
                        .catch(function (error) {
                            console.log("error", error);
                            vm.errors = error.response.data.errors;
                            vm.$store.dispatch("error", error.response.data.message);
                        });
                }
            });
        },
        methods: {
            convertDateFormat(date) {
                let vm = this;
                return moment(date).format("YYYY-MM-DDTHH:mm");
            },
             getAssetZones() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getAssetZones", data: vm.user_variable})
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
    },

    };
</script>
<style scoped>
    .setting-item .table th:not(:first-child),
    .setting-item .table td:not(:first-child) {
        text-align: left;
    }
    .setting-item .table th {
    padding: 4px;
    font-size: .8125rem;
}
</style>
