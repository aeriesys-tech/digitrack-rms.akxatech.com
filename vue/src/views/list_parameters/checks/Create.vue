<template>
    <div class="">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div>
                <ol class="breadcrumb fs-sm mb-1">
                    <li class="breadcrumb-item" aria-current="page">
                        <router-link to="/dashboard">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0)">Masters</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <router-link to="/checks">Checks</router-link>
                    </li>
                    <li class="breadcrumb-item" aria-current="page" v-if="status">New Check</li>
                    <li class="breadcrumb-item active" aria-current="page" v-else>Update Check</li>
                </ol>
                <h4 class="main-title mb-0">Check</h4>
            </div>
            <router-link to="/checks" type="submit" class="btn btn-primary" style="float: right;"><i class="ri-list-check"></i> CHECKS</router-link>
        </div>
        <div class="row">
            <div class="col-12" v-can="'checks.create'">
                <form @submit.prevent="submitForm">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">New Check</h6>
                            <h6 class="card-title" v-else>Update Check</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="form-label">Department</label><span class="text-danger"> *</span>
                                    <select class="form-control" :class="{ 'is-invalid': errors?.department_id }" v-model="check.department_id">
                                        <option value="">Select Department</option>
                                        <option v-for="department, key in departments" :key="key" :value="department?.department_id">{{ department?.department_name }} </option>
                                    </select>
                                    <span v-if="errors?.department_id" class="invalid-feedback">{{ errors.department_id[0] }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Field Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Field Name" class="form-control" :class="{ 'is-invalid': errors.field_name }" v-model="check.field_name" />
                                    <span v-if="errors.field_name" class="invalid-feedback">{{ errors.field_name[0] }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Field Type</label><span class="text-danger"> *</span>
                                    <select class="form-control" :class="{ 'is-invalid': errors.field_type}" v-model="check.field_type">
                                        <option value="">Select Field Type</option>
                                        <option value="Text">Short Text</option>
                                        <option value="Number">Number</option>
                                        <option value="Text Area">Long Text</option>
                                        <option value="Date">Date</option>
                                        <option value="Date & Time">Date & Time</option>
                                        <option value="Select">Dropdown</option>
                                        <!-- <option value="Color">Color</option> -->
                                        <!-- <option value="File">File</option> -->
                                    </select>
                                    <span v-if="errors.field_type" class="invalid-feedback">{{ errors.field_type[0] }}</span>
                                </div>
                                  <div class="col-md-4" v-if="check.field_type=='Select'">
                                    <label class="form-label">Field Values</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Field Values" class="form-control" :class="{ 'is-invalid': errors.field_values}" v-model="check.field_values" />
                                    <span v-if="errors.field_values" class="invalid-feedback">{{ errors.field_values[0] }}</span>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Is Required</label><span class="text-danger"> *</span>
                                    <select class="form-control" :class="{'is-invalid':errors.is_required}" v-model="check.is_required">
                                        <option value="">Select Is Required</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                    <span v-if="errors.is_required" class="invalid-feedback">{{ errors.is_required[0] }}</span>
                                </div>
                                <div class="col-md-4" v-if="check.field_type=='Number'">
                                    <label class="form-label">LCL</label><span v-if="check.field_type=='Number'" class="text-danger"> *</span>
                                    <input type="text" placeholder="LCL" class="form-control" :class="{ 'is-invalid': errors.lcl }" v-model="check.lcl" />
                                    <span v-if="errors.lcl" class="invalid-feedback">{{ errors.lcl[0] }}</span>
                                </div>
                                <div class="col-md-4" v-if="check.field_type=='Number'">
                                    <label class="form-label">UCL</label><span v-if="check.field_type=='Number'" class="text-danger"> *</span>
                                    <input type="text" placeholder="UCL" class="form-control" :class="{ 'is-invalid': errors.ucl }" v-model="check.ucl" />
                                    <span v-if="errors.ucl" class="invalid-feedback">{{ errors.ucl[0] }}</span>
                                </div>
                              <div class="col-md-4" v-if="['Select'].includes(check.field_type)">
                                    <label class="form-label">Default Value</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Default Value" class="form-control" :class="{ 'is-invalid': errors.default_value }" v-model="check.default_value" @input="validateDefaultValue()"/>
                                    <span v-if="errors.default_value" class="invalid-feedback">{{ errors.default_value[0] }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Order</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Order" class="form-control" :class="{ 'is-invalid': errors.order}" v-model="check.order" />
                                    <span v-if="errors.order" class="invalid-feedback">{{ errors.order[0] }}</span>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Assign To</label><span class="text-danger"> *</span>
                                        <!-- <div class="dropdown" @click="toggleAssetTypeStatus()">
                                            <div class="overselect"></div>
                                            <select class="form-control" :class="{ 'is-invalid': errors.asset_types }" :customClass="{ 'is-invalid': errors.asset_types }">
                                                <option value="">Select Asset Type</option>
                                            </select>
                                            <span v-if="errors.asset_types"><small class="text-danger">{{ errors.asset_types[0] }}</small></span>
                                        </div>
                                        <div class="multiselect" v-if="asset_type_status">
                                            <ul>
                                                <li class="" v-for="(asset_type, index) in asset_types" :key="index">
                                                    <input type="checkbox" :value="asset_type.asset_type_id" v-model="check.asset_types" style="padding: 2px;" @click="updateActivityType($event, check)" />
                                                    <label style="margin-left: 5px;">{{ asset_type.asset_type_name }}</label>
                                                </li>
                                            </ul>
                                        </div> -->

                                        <MultiSelect v-model="check.asset_types_obj"  filter optionLabel="asset_type_name"
                                            :options="asset_types"  placeholder="Select Assign To" :maxSelectedLabels="3"
                                            style="width: 100%;; height: 37px;" :style="errors?.asset_types ? error_style : ''"/>
                                        <span v-if="errors?.asset_types"><small class="text-danger">{{ errors?.asset_types[0] }}</small></span>
                                    </div>
                                </div>

                                <!-- <div class="col-md-4">
                                        <label class="form-label">Frequency</label>
                                        <select class="form-control" :class="{ 'is-invalid': errors.frequency_id}" v-model="check.frequency_id">
                                            <option value="">Select Frequency</option>
                                            <option v-for="frequency, key in frequencies" :key="key" :value="frequency?.frequency_id">{{ frequency?.frequency_name }}</option>
                                        </select>
                                        <span v-if="errors.frequency_id" class="invalid-feedback">{{ errors.frequency_id[0] }}</span>
                                    </div> -->
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="button" class="btn btn-danger me-2" @click.prevent="discard()"><i class="ri-close-line fs-18 lh-1"></i> Discard</button>
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
    import MultiSelect from 'primevue/multiselect';
    export default {
        name: "Checks.Create",
        components: {
            MultiSelect
        },
        data() {
            return {
                // check_update: false,

                check: {
                    check_id: "",
                    field_name: "",
                    field_type: "",
                    default_value: "",
                    is_required: "",
                    lcl: "",
                    ucl: "",
                    field_values: "",
                    order: "",
                    asset_types_obj:[],
                    asset_types: [],
                    frequency_id: "",
                    department_id: "",
                    deleted_check_asset_types: [],
                },
                deleted_check_asset_types: [],
                errors: [],
                asset_types: [],
                frequencies: [],
                departments: [],
                status: true,
                asset_type_status: false,
                error_style: {
                    'border-color': '#dc3545',
                    'padding-right': 'calc(1.5em + 0.812rem)',
                    'background-image': `url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e")`,
                    'background-repeat': 'no-repeat',
                    'background-position': 'right calc(0.375em + 0.203rem) center',
                    'background-size': 'calc(0.75em + 0.406rem) calc(0.75em + 0.406rem)'
                }
            };
        },
        beforeRouteEnter(to, from, next) {
            next((vm) => {
                vm.getAssetTypes();
                if (to.name == "Checks.Create") {
                    vm.$store.commit("setCurrentPage", 1)
                    // vm.$refs.field_name.focus();
                } else {
                    vm.status = false;
                    let uri = { uri: "getCheck", data: { check_id: to.params.check_id } };
                    vm.$store
                        .dispatch("post", uri)
                        .then(function (response) {
                            vm.check = response.data.data;
                            vm.check.deleted_check_asset_types = [];
                            vm.check.asset_types_obj = []

                            vm.check.check_asset_types.map(function(ele){
                                vm.check.asset_types_obj.push({asset_type_code: ele.asset_types.asset_type_code,
                                    asset_type_id: ele.asset_types.asset_type_id, status: ele.asset_types.status,
                                    asset_type_name: ele.asset_types.asset_type_name})
                            })
                        })
                        .catch(function (error) {
                            vm.errors = error.response.data.errors;
                            vm.$store.dispatch("error", error.response.data.message);
                        });
                }
            });
        },
        methods: {
            updateActivityType(event, activity_type) {
                let vm = this;
                const isChecked = event.target.checked;
                let check_asset_type = activity_type?.check_asset_types?.filter(function (element) {
                    return element.asset_type_id == event.target.value;
                });
                if (check_asset_type?.length) {
                    let check_asset_type_id = check_asset_type[0].check_asset_type_id;
                    if (isChecked) {
                        if (vm.check.deleted_check_asset_types.includes(check_asset_type_id)) {
                            let deleted_check_asset_types = vm.check.deleted_check_asset_types.filter(function (element) {
                                return element != check_asset_type_id;
                            });
                            vm.check.deleted_check_asset_types = deleted_check_asset_types;
                        }
                    } else {
                        if (!vm.check.deleted_check_asset_types.includes(check_asset_type_id)) {
                            vm.check.deleted_check_asset_types.push(check_asset_type_id);
                        }
                    }
                }
                console.log("Checked IDs:", vm.check.asset_types);
                console.log("Unchecked IDs:", vm.check.deleted_check_asset_types);
            },
            toggleAssetTypeStatus() {
                this.asset_type_status = !this.asset_type_status;
            },
            submitForm() {
                let vm = this;
                if (vm.status) {
                    vm.addCheck();
                } else {
                    vm.updateCheck();
                }
            },
            // getRoles() {
            //     let vm = this;
            //     let loader = this.$loading.show();
            //     this.$store.dispatch('post', { uri: 'getRoles' })
            //         .then(response => {
            //             loader.hide();
            //             this.roles = response.data.data;
            //         })
            //         .catch(function (error) {
            //             loader.hide();
            //             vm.errors = error.response.data.errors;
            //             vm.$store.dispatch("error", error.response.data.message);
            //         });
            // },
             validateDefaultValue() {
        // Ensure that default_value must be one of the values in field_values
        const fieldValuesArray = this.check.field_values.split(',').map(value => value.trim());
        if (!fieldValuesArray.includes(this.check.default_value)) {
            this.errors.default_value = ['Default value must be one of the Field Values: ' + fieldValuesArray.join(', ') + '.'];
        } else {
            this.errors.default_value = null; // Reset error if valid
        }
    },

            addCheck() {
                let vm = this;
                let loader = vm.$loading.show();

                vm.check.asset_types_obj.map(function(ele){
                    vm.check.asset_types.push(ele.asset_type_id)
                })

                vm.$store
                    .dispatch("post", { uri: "addCheck", data: vm.check })
                    .then((response) => {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.$router.push("/checks");
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            getAssetTypes() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getAssetTypes" })
                    .then((response) => {
                        loader.hide();
                        vm.asset_types = response.data.data;
                        vm.getFrequencies();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            getFrequencies() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getFrequencies" })
                    .then((response) => {
                        loader.hide();
                        vm.frequencies = response.data.data;
                        vm.getDepartments();
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
                    .dispatch("post", { uri: "getDepartments" })
                    .then((response) => {
                        loader.hide();
                        vm.departments = response.data.data;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            // editCheck(check) {
            //     this.check = check;
            //     this.update = true;
            //     this.status = false;
            // },

            updateCheck() {
                let vm = this;
                let loader = vm.$loading.show();

                vm.check.deleted_check_asset_types = vm.check?.check_asset_types.filter(
                    item1 => !vm.check.asset_types_obj.some(item2 => item1.asset_type_id === item2.asset_type_id));
                vm.check.asset_types = vm.check.asset_types_obj.map(item => item.asset_type_id);
                vm.check.deleted_check_asset_types = vm.check.deleted_check_asset_types.map(item => item.check_asset_type_id);


                vm.$store
                    .dispatch("post", { uri: "updateCheck", data: vm.check })
                    .then((response) => {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.$router.push("/checks");
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            discard() {
                let vm = this;
                vm.check.field_name = "";
                vm.check.field_type = "";
                vm.check.default_value = "";
                vm.check.is_required = "";
                vm.check.lcl = "";
                vm.check.ucl = "";
                vm.check.field_values = "";
                vm.check.order = "";
                vm.check.frequency_id = "";
                 vm.check.department_id = "";
                (vm.check.asset_types = []),
                    // vm.$refs.field_name.focus();
                    (vm.errors = []);
                vm.status = true;
                // vm.index();
            },

            getCheck() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "showCheck", data: vm.check })
                    .then((response) => {
                        loader.hide();
                        vm.user = response.data.data;
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
    .dropdown {
        position: relative;
        cursor: pointer;
    }
    .multiselect {
        position: relative;
    }
    .multiselect ul {
        border: 1px solid #ddd;
        border-top: 0;
        border-radius: 0 0 3px 3px;
        left: 0px;
        padding: 8px 8px;
        top: -0.1rem;
        width: 100%;
        list-style: none;
        max-height: 150px;
        overflow: auto;
        background: white;
    }
    .overselect {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
    }
</style>
