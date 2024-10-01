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
                        <router-link to="/user_services">Service Registers</router-link>
                    </li>
                    <li class="breadcrumb-item" aria-current="page" v-if="status">New Service Register</li>
                    <li class="breadcrumb-item active" aria-current="page" v-else>Update Service Register</li>
                </ol>
                <h4 class="main-title mb-0">Service Register</h4>
            </div>
            <router-link to="/user_services" type="submit" class="btn btn-primary" style="float: right;"><i class="ri-list-check"></i> SERVICE REGISTERS</router-link>
        </div>
        <div class="row">
            <div class="col-12">
                <form @submit.prevent="submitForm">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">Add Service Register</h6>
                            <h6 class="card-title" v-else>Update Service Register</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2 mb-5">
                                <!-- <div class="col-md-4">
                                    <label class="form-label">Service Number</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Enter Service Number" class="form-control" :class="{'is-invalid':errors.service_no}" v-model="user_service.service_no" ref="service_no"/>
                                    <span v-if="errors.service_no" class="invalid-feedback">{{ errors.service_no[0] }}</span>
                                </div> -->
                                <div class="col-md-4">
                                    <label class="form-label">Asset</label><span class="text-danger"> *</span>
                                    <search
                                    :disabled="!status"
                                        :class="{ 'is-invalid': errors.asset_id }"
                                        :customClass="{ 'is-invalid': errors.asset_id }"
                                        :initialize="user_service.asset_id"
                                        id="asset_id"
                                        label="asset_name"
                                        label2="asset_code"
                                        placeholder="Select Asset"
                                        :data="assets"
                                        @input=" asset => user_service.asset_id = asset"
                                    >
                                    </search>
                                    <span v-if="errors.asset_id" class="invalid-feedback">{{ errors.asset_id }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Service Date & Time</label><span class="text-danger"> *</span>
                                    <input type="datetime-local" class="form-control" :class="{'is-invalid': errors.service_date}" :value="convertDateFormat(user_service.service_date)" v-model="user_service.service_date" ref="service_date"/>
                                    <span v-if="errors.service_date" class="invalid-feedback">{{ errors.service_date[0] }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Next Service Date & Time</label><span class="text-danger"> *</span>
                                    <input
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
                                    <textarea type="text" placeholder="Enter Note" class="form-control" :class="{'is-invalid': errors.note}" v-model="user_service.note"></textarea>
                                    <span v-if="errors.note" class="invalid-feedback">{{ errors.note[0] }}</span>
                                </div>
                            </div>

                            <div class="row g-2">
                                <div class="">
                                    <table class="table table-responsive table-responsive-sm table-sm text-nowrap table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Asset Zone <span class="text-danger"> *</span></th>
                                                <th>Service <span class="text-danger"> *</span></th>
                                                <th>Service Cost <span class="text-danger"> *</span></th>
                                                <th>Spare</th>
                                                <th>Quantity</th>
                                                <th>Spare Cost</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                           <td>
                                                <search
                                                    :class="{ 'is-invalid': errors.asset_zone_id }"
                                                    :customClass="{ 'is-invalid': errors.asset_zone_id }"
                                                    :initialize="user_spare.asset_zone_id"
                                                    id="asset_zone_id"
                                                    label="zone_name"
                                                    placeholder="Select Asset Zone"
                                                    :data="asset_zones"
                                                    @input=" zone1 => user_spare.asset_zone_id = zone1"
                                                    @selectsearch="getAssetZoneValue(user_spare.asset_zone_id)"
                                                >
                                                </search>
                                                <span v-if="errors.asset_zone_id" class="invalid-feedback">{{ errors.asset_zone_id[0] }}</span>
                                            </td>
                                            <td>
                                                <search
                                                    :class="{ 'is-invalid': errors.service_id }"
                                                    :customClass="{ 'is-invalid': errors.service_id }"
                                                    :initialize="user_spare.service_id"
                                                    id="service_id"
                                                    label="service_name"
                                                    label2="service_code"
                                                    placeholder="Select Service"
                                                    :data="services"
                                                    @input=" service1 => user_spare.service_id = service1"
                                                    @selectsearch="getServiceValue(user_spare.service_id)"
                                                >
                                                </search>
                                                <span v-if="errors.service_id" class="invalid-feedback">{{ errors.service_id[0] }}</span>
                                            </td>
                                            <td>
                                                <input type="number" placeholder="Enter Service Cost" min="0" oninput="validity.valid||(value='');" class="form-control" :class="{'is-invalid':errors.service_cost}" v-model="user_spare.service_cost" />
                                                <span v-if="errors.service_cost" class="invalid-feedback">{{ errors.service_cost[0] }}</span>
                                            </td>
                                            <td>
                                                <search
                                                    :class="{ 'is-invalid': errors.spare_id }"
                                                    :customClass="{ 'is-invalid': errors.spare_id }"
                                                    aria-describedby="basic-addon2"
                                                    aria-label="Select Spare"
                                                    :initialize="user_spare.spare_id"
                                                    id="spare_id"
                                                    label="spare_name"
                                                    label2="spare_code"
                                                    placeholder="Select Spare"
                                                    :data="spares"
                                                    @input=" spare1 => user_spare.spare_id = spare1"
                                                    @selectsearch="getValue(user_spare)"
                                                >
                                                </search>
                                                <span v-if="errors.spare_id" class="invalid-feedback">{{ errors.spare_id[0] }}</span>
                                            </td>
                                            <td>
                                                <!-- oninput="validity.valid||(value='');" -->
                                                <input type="number" placeholder="Enter Quantity" min="0" class="form-control"
                                                    :class="{'is-invalid':errors.quantity}" v-model="user_spare.quantity"
                                                    :max="user_spare.max_quantity" @input="enforceMinMax($event, user_spare)"/>
                                                <span v-if="errors.quantity" class="invalid-feedback">{{ errors.quantity[0] }}</span>
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
                                             <tr>
                                                <td colspan="6" class="text-danger text-center">{{ errors.user_spares }}</td>
                                            </tr>
                                        </tbody>
                                        <tbody>
                                            <tr v-for="spare, index in user_service.user_spares" :key="index">
                                                <td>{{ spare?.asset_zone?.zone_name}}</td>
                                                <td>{{ spare?.service?.service_name }}</td>
                                                <td>{{ spare?.service_cost }}</td>
                                                <td>{{ spare?.spare?.spare_name }}</td>
                                                <td>{{ spare?.quantity }}</td>
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
                            <router-link type="button" to="/user_services" class="btn btn-danger me-2"><i class="ri-arrow-left-line fs-18 lh-1"></i> Back</router-link>
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
                user_service: {
                    user_service_id: "",
                    service_no: "",
                    service_date: "",
                    asset_id: "",
                    // service_cost: "",
                    next_service_date: "",
                    // service_id: "",
                    // asset_zone_id:"",
                    note: "",
                    user_spares: [],
                    deleted_user_spares: [],
                    status: true,
                    asset_zone_id:""
                },
                user_spare: {
                    user_spare_id: "",
                    spare_id: "",
                     spare: {
                        spare_name: "",
                    },
                    asset_zone_id: "",
                    asset_zone: {
                        zone_name: "",
                    },
                    service_id: "",
                     service: {
                        service_name:"",
                    },
                    service_cost: "",
                    quantity:'',
                    max_quantity:'',
                    spare_cost: "",
                    status: true,
                },
                deleted_spares: [],
                assets: [],
                services: [],
                spares: [],
                errors: [],
                asset_zones:[],
                status: true,
            };
        },

        watch:{
            'user_service.asset_id': function(){
                this.getAssetZones();
            },

            'user_spare.asset_zone_id': function(){
               this.getServices();
               this.getSpares();
            }
        },
        beforeRouteEnter(to, from, next) {
            next((vm) => {
                vm.getAssets();
                if (to.name == "UserServices.Create") {
                    if(vm.$store.getters.asset_id){
                        vm.user_service.asset_id = vm.$store.getters.asset_id
                    }
                    vm.$refs.service_date.focus();
                } else {
                    vm.status = false;
                    let uri = { uri: "getUserService", data: { user_service_id: to.params.user_service_id } };
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
            });
        },
        mounted() {
            this.user_service.service_date = moment().format("yyyy-MM-DD HH:mm");
            this.user_service.next_service_date = moment().add(1, 'days').format("yyyy-MM-DD HH:mm");
        },
        methods: {
            convertDateFormat(date) {
                let vm = this;
                return moment(date).format("yyyy-MM-DD HH:mm");
            },
            submitForm() {
                let vm = this;
                if (vm.status) {
                    vm.addUserService();
                } else {
                    vm.updateUserService();
                }
            },
            enforceMinMax(event, user_spare) {
                const input = event.target;
                let value = parseInt(input.value, 10);

                // Enforce the min and max manually
                if (value < parseInt(input.min, 10)) {
                    input.value = input.min;
                }
                if (value > input.max) {
                    input.value = input.max;  // Use dynamic max
                }
                user_spare.quantity = input.value
            },
             getAssetZoneValue(value) {
                let vm = this;
                let asset_zone = vm.asset_zones?.filter(function (ele) {
                    return ele.asset_zone_id == value;
                });
                 if (asset_zone.length) {
                    vm.user_service.asset_zone_id = asset_zone[0].asset_zone_id
                    vm.user_spare.asset_zone.zone_name = asset_zone[0].zone_name;
                }
            },
            getValue(value) {
                let vm = this;
                let spare = vm.spares?.filter(function (ele) {
                    return ele.spare_id == value.spare_id;
                });
                // if (spare.length) {
                //     vm.user_spare.spare.spare_name = spare[0].spare_name;
                //     let spr = spare[0]?.asset_spare?.filter(function (ele) {
                //         return ele.asset_zone_id == value.asset_zone_id;
                //     });
                // }
                // value.quantity = spare[0].asset_spare[0].quantity;
                // value.max_quantity = spare[0].asset_spare[0].quantity;

                vm.user_spare.spare.spare_name = spare[0].spare_name;
                let spr = spare[0]?.asset_spare?.filter(function (ele) {
                    return ele.asset_zone_id == value.asset_zone_id;
                });
                value.quantity = spr[0].quantity;
                value.max_quantity = spr[0].quantity;

            },
            getServiceValue(value) {
                let vm = this;
                let service = vm.services?.filter(function (ele) {
                    return ele.service_id == value;
                });
                if (service.length) {
                    vm.user_spare.service.service_name = service[0].service_name;
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
                    .dispatch("post", { uri: "getAssetsServices", data: vm.user_service })
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
            getSpares() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getAssetServiceSpares", data: vm.user_service })
                    .then((response) => {
                        loader.hide();
                        vm.spares = response.data;
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
                    .dispatch("post", { uri: "getAssetZones", data: vm.user_service })
                    .then((response) => {
                        loader.hide();
                        vm.asset_zones = response.data.data;
                        if(!vm.asset_zones.length){
                            vm.getServices()
                            vm.getSpares()
                        }
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            addUserService() {
                let vm = this;
                let loader = vm.$loading.show();
                 vm.errors = {};

                if (!vm.user_service.asset_id) {
                    loader.hide();
                    vm.errors.asset_id = "Asset is required";
                    vm.$store.dispatch("error", "Asset is required.");
                    return;
                }
                // Check if user_spares is empty
                if (vm.user_service.user_spares.length === 0) {
                    loader.hide();
                    // Set an error indicating that at least one entry is required
                    vm.errors.user_spares = "At least one entry is required";

                    // Display the error message to the user
                    vm.$store.dispatch("error", "At least one entry is required.");
                    return; // Prevent further execution
                }
                vm.$store
                    .dispatch("post", { uri: "addUserService", data: vm.user_service })
                    .then((response) => {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.$router.push("/user_services");
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            updateUserService() {
                let vm = this;
                vm.user_service.deleted_user_spares = vm.deleted_spares;
                let loader = vm.$loading.show();
                 if (vm.user_service.user_spares.length === 0) {
                    loader.hide();
                    // Set an error indicating that at least one entry is required
                    vm.errors.user_spares = "At least one entry is required";

                    // Display the error message to the user
                    vm.$store.dispatch("error", "At least one entry is required.");
                    return; // Prevent further execution
                }
                vm.$store
                    .dispatch("post", { uri: "updateUserService", data: vm.user_service })
                    .then((response) => {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.$router.push("/user_services");
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
                if (vm.user_spare.asset_zone_id == "" || vm.user_spare.service_id == "" || vm.user_spare.service_cost == "") {
                    if (vm.user_spare.asset_zone_id == "") {
                        vm.errors.asset_zone_id = ["Asset zone cannot be empty"];
                    }
                    if (vm.user_spare.service_id == "") {
                        vm.errors.service_id = ["Service cannot be empty"];
                    }
                    if (vm.user_spare.service_cost == "") {
                        vm.errors.service_cost = ["Service Cost cannot be empty"];
                    }
                } else {
                    vm.user_service.user_spares.push({
                        user_spare_id: "",
                        asset_zone_id: vm.user_spare.asset_zone_id,
                        asset_zone: {
                            zone_name: vm.user_spare.asset_zone.zone_name,
                        },
                        service_id: vm.user_spare.service_id,
                         service: {
                            service_name: vm.user_spare.service.service_name,
                        },
                        service_cost: vm.user_spare.service_cost,
                        spare_id: vm.user_spare.spare_id,
                        spare: {
                            spare_name: vm.user_spare?.spare?.spare_name,
                        },
                        spare_cost: vm.user_spare.spare_cost,
                        quantity: vm.user_spare.quantity,
                    });
                    vm.discardNewRow();
                }
            },
            discardNewRow() {
                let vm = this;
                vm.user_spare.asset_zone_id = "";
                vm.user_spare.service_id = "";
                vm.user_spare.service_cost = "";
                vm.user_spare.user_spare_id = "";
                vm.user_spare.spare_id = "";
                vm.user_spare.spare_cost = "";
                vm.user_spare.spare.spare_name = "";
                vm.user_spare.status = true;
                vm.user_spare.quantity="";
                vm.errors = [];
            },
            editSpare(spare, key) {
                let vm = this;
                vm.user_spare.asset_zone_id = spare.asset_zone_id;
                vm.user_spare.asset_zone.zone_name = spare.asset_zone.zone_name;
                vm.user_spare.service_id = spare.service_id;
                vm.user_spare.service.service_name = spare.service.service_name;
                vm.user_spare.service_cost = spare.service_cost;
                vm.user_spare.user_spare_id = spare.user_spare_id;
                vm.user_spare.spare_id = spare.spare_id;
                vm.user_spare.spare.spare_name = spare?.spare?.spare_name;
                vm.user_spare.spare_cost = spare?.spare_cost;
                vm.user_spare.quantity = spare?.quantity;
                vm.user_spare.status = false;
                vm.user_spare.key = key;
                vm.errors = [];
            },
            updateRow(spare) {
                let vm = this;
                vm.errors = [];
                if (vm.user_spare.asset_zone_id == "" || vm.user_spare.service_id == "" || vm.user_spare.service_cost == "") {
                    if (vm.user_spare.asset_zone_id == "") {
                        vm.errors.asset_zone_id = ["Asset zone cannot be empty"];
                    }
                    if (vm.user_spare.service_id == "") {
                        vm.errors.service_id = ["Service cannot be empty"];
                    }
                    if (vm.user_spare.service_cost == "") {
                        vm.errors.service_cost = ["Service Cost cannot be empty"];
                    }
                } else {
                    let spare_data = vm.user_service.user_spares.filter(function (element) {
                        return element.key == spare.key;
                    });
                    vm.user_service.user_spares[spare_data.key] = spare_data;
                    vm.user_service.user_spares.splice(vm.user_spare.key, 1);
                    vm.user_service.user_spares.push({
                        asset_zone_id: vm.user_spare.asset_zone_id,
                         asset_zone: {
                            zone_name: vm.user_spare.asset_zone.zone_name,
                        },
                        service_id: vm.user_spare.service_id,
                        service: {
                            service_name: vm.user_spare.service.service_name,
                        },
                        service_cost: vm.user_spare.service_cost,
                        user_spare_id: vm.user_spare.user_spare_id,
                        spare_id: vm.user_spare.spare_id,
                        spare: {
                            spare_name: vm.user_spare?.spare?.spare_name,
                        },
                        spare_cost: vm.user_spare.spare_cost,
                        quantity: vm.user_spare.quantity,
                    });
                    vm.discardNewRow();
                }
            },
            deleteSpare(spare, key) {
                let vm = this;
                // let sp = vm.user_service.user_spares.filter(function (element) {
                //     return element.spare_id == spare.user_spare_id;
                // });
                if (confirm("Are you sure you want to delete")) {
                    vm.deleted_spares.push(spare?.user_spare_id);
                    vm.user_service.user_spares.splice(key, 1);
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
