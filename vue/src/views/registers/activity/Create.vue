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
                        <router-link to="/activities">Activities</router-link>
                    </li>
                    <li class="breadcrumb-item" aria-current="page" v-if="status">New Activity</li>
                    <li class="breadcrumb-item active" aria-current="page" v-else>Update Activity</li>
                </ol>
                <h4 class="main-title mb-0">Activity</h4>
            </div>
            <router-link to="/activities" type="submit" class="btn btn-primary" style="float: right;"><i class="ri-list-check"></i> ACTIVITIES</router-link>
        </div>
        <div class="row">
            <div class="col-12">
                <form @submit.prevent="submitForm">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">Add Activity</h6>
                            <h6 class="card-title" v-else>Update Activity</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="form-label">Activity Date & Time</label><span class="text-danger"> *</span>
                                    <input type="datetime-local" class="form-control" placeholder="Enter Activity Date"  :class="{'is-invalid': errors.activity_date}" ref="activity_date" :value="convertDateFormat(activity.activity_date)" v-model="activity.activity_date" />
                                    <span v-if="errors.activity_date" class="invalid-feedback">{{ errors.activity_date[0] }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Activity Status</label><span class="text-danger"> *</span>
                                    <select class="form-control" v-model="activity.activity_status" :class="{'is-invalid': errors.status}">
                                        <option value="">Select Status</option>
                                        <option value="Added">Added</option>
                                        <option value="Removed">Removed</option>
                                        <option value="Scraped">Scraped</option>
                                    </select>
                                    <span v-if="errors.activity_status" class="invalid-feedback">{{ errors.activity_status[0] }}</span>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Asset</label><span class="text-danger"> *</span>
                                    <search
                                        :class="{ 'is-invalid': errors.asset_id }"
                                        :customClass="{ 'is-invalid': errors.asset_id }"
                                        :initialize="activity.asset_id"
                                        id="asset_id"
                                        label="asset_name"
                                        label2="asset_code"
                                        placeholder="Select Asset"
                                        :data="assets"
                                        @input="asset => activity.asset_id = asset"
                                    >
                                    </search>
                                    <span v-if="errors.asset_id" class="invalid-feedback">{{ errors.asset_id[0] }}</span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Activity Type</label><span class="text-danger" v-if="activity.activity_status == 'Removed' || activity.activity_status == 'Scraped'"> *</span>
                                    <search
                                        :class="{ 'is-invalid': errors.reason_id }"
                                        :customClass="{ 'is-invalid': errors.reason_id }"
                                        :initialize="activity.reason_id"
                                        id="reason_id"
                                        label="reason_name"
                                        label2="reason_code"
                                        placeholder="Select Activity Type"
                                        :data="reasons"
                                        @input=" reason => activity.reason_id = reason"
                                    >
                                    </search>
                                    <span v-if="errors.reason_id" class="invalid-feedback">activity type field is required when activity status is Removed</span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Cost</label>
                                    <input type="number" placeholder="Enter Cost" class="form-control" :class="{'is-invalid':errors.cost}" v-model="activity.cost" />
                                    <span v-if="errors.cost" class="invalid-feedback">{{ errors.cost[0] }}</span>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Note</label>
                                    <textarea type="text" placeholder="Enter Note" class="form-control" :class="{'is-invalid': errors.note}" v-model="activity.note"></textarea>
                                    <span v-if="errors.note" class="invalid-feedback">{{ errors.note[0] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <router-link type="button" to="/activities" class="btn btn-danger me-2"><i class="ri-arrow-left-line fs-18 lh-1"></i> Back</router-link>
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
                sample: "",
                activity: {
                    user_activity_id: "",
                    activity_no: "",
                    activity_date: "",
                    asset_id: "",
                    reason_id: "",
                    cost: "",
                    note: "",
                    status: true,
                    activity_status: "",
                },
                assets: [],
                reasons: [],
                errors: [],
                status: true,
            };
        },
        beforeRouteEnter(to, from, next) {
            next((vm) => {
                vm.getAssets();
                if (to.name == "Activity.Create") {
                    if(vm.$store.getters.asset_id){
                        vm.activity.asset_id = vm.$store.getters.asset_id
                    }
                    vm.$refs.activity_date.focus();
                } else {
                    vm.status = false;
                    let uri = { uri: "getUserActivity", data: { user_activity_id: to.params.user_activity_id } };
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
            });
        },
        mounted() {
            this.activity.activity_date = moment().format("yyyy-MM-DD HH:MM");
        },
        methods: {
            submitForm() {
                let vm = this;
                if (vm.status) {
                    vm.addUserActivity();
                } else {
                    vm.updateUserActivity();
                }
            },
            convertDateFormat(date) {
                let vm = this;
                return moment(date).format("yyyy-MM-DD HH:MM");
            },
            getAssets() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getAssets" })
                    .then((response) => {
                        loader.hide();
                        vm.assets = response.data.data;
                        vm.getReasons();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getReasons() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getReasons" })
                    .then((response) => {
                        loader.hide();
                        vm.reasons = response.data.data;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            addUserActivity() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "addUserActivity", data: vm.activity })
                    .then((response) => {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.$router.push("/activities");
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            updateUserActivity() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "updateUserActivity", data: vm.activity })
                    .then((response) => {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.$router.push("/activities");
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            discard() {
                let vm = this;
                vm.activity = {};
                vm.$refs.activity_date.focus();
                vm.errors = [];
                vm.status = true;
            },
        },
    };
</script>
