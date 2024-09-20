<template>
    <div class="">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div>
                <ol class="breadcrumb fs-sm mb-1">
                    <li class="breadcrumb-item">
                        <router-link to="/dashboard">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0)">Masters</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><router-link to="/variables">Variables</router-link></li>
                    <li class="breadcrumb-item" aria-current="page" v-if="status">New Variable</li>
                    <li class="breadcrumb-item active" aria-current="page" v-else>Update Variable</li>
                </ol>
                <h4 class="main-title mb-0">Variables</h4>
            </div>
            <router-link to="/variables" type="submit" class="btn btn-primary" style="float: right;"><i class="ri-list-check"></i> VARIABLES</router-link>
        </div>
        <div class="row g-2">
            <div class="col-12">
                <form @submit.prevent="submitForm()">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">Add Variable</h6>
                            <h6 class="card-title" v-else>Update Variable</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="form-label">Variable Types</label><span class="text-danger"> *</span>
                                    <search
                                        :class="{ 'is-invalid': errors.variable_type_id }"
                                        :customClass="{ 'is-invalid': errors.variable_type_id }"
                                        :initialize="variable.variable_type_id"
                                        id="variable_type_id"
                                        label="variable_type_name"
                                        placeholder="Select Variable Type"
                                        :data=" variable_types"
                                        @input=" variable_type => variable.variable_type_id = variable_type"
                                        @selectsearch="getVariableTypeFields(variable.variable_type_id)"
                                    >
                                    </search>
                                    <span v-if="errors.variable_type_id"><small class="text-danger">{{ errors.variable_type_id[0] }}</small></span>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Variable Code</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Variable Code" class="form-control" :class="{ 'is-invalid': errors.variable_code }" v-model="variable.variable_code" />
                                    <span v-if="errors.variable_code" class="invalid-feedback">{{ errors.variable_code[0] }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Variable Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Variable Name" class="form-control" :class="{ 'is-invalid': errors.variable_name }" v-model="variable.variable_name" />
                                    <span v-if="errors.variable_name" class="invalid-feedback">{{ errors.variable_name[0] }}</span>
                                </div>
                                <div class="col-md-4" v-for="field, key in variable.variable_attributes" :key="key">
                                    <div v-if="field.field_type=='Text'">
                                        <label class="form-label">{{field.display_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <input type="text" class="form-control" :placeholder="'Enter '+ field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.variable_attribute_value.field_value" />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>

                                    <div v-if="field.field_type=='Number'">
                                        <label class="form-label">{{field.display_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <input
                                            type="number"
                                            class="form-control"
                                            min="0"
                                            oninput="validity.valid||(value='');"
                                            :placeholder="'Enter '+ field.display_name"
                                            :class="{'is-invalid': errors[field.display_name]}"
                                            v-model="field.variable_attribute_value.field_value"
                                        />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>

                                    <div v-if="field.field_type === 'Date'">
                                        <label class="form-label">
                                            {{ field.display_name }}
                                            <span v-if="field.is_required" class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control" :placeholder="'Enter ' + field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.variable_attribute_value.field_value" />
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
                                            type="datetime-local"
                                            class="form-control"
                                            :placeholder="'Enter ' + field.display_name"
                                            :class="{'is-invalid': errors[field.display_name]}"
                                            v-model="field.variable_attribute_value.field_value"
                                            step="1"
                                        />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">
                                            {{ errors[field.display_name][0] }}
                                        </span>
                                    </div>

                                    <div v-if="field.field_type=='Dropdown'">
                                        <label class="form-label">{{field.display_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <select class="form-control" :class="{'is-invalid': errors[field.display_name]}" v-model="field.variable_attribute_value.field_value">
                                            <option :value="field.variable_attribute_value.field_value" v-if="field.variable_attribute_value.field_value">{{field.variable_attribute_value.field_value}}</option>
                                            <option :value="field.variable_attribute_value.field_value" v-else>Select {{field.display_name}}</option>
                                            <option v-for="value, key in field.field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                        </select>
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>
                                    <div v-if="field.field_type=='Color'">
                                        <label class="form-label">{{ field.display_name }}<span v-if="field.is_required" class="text-danger">*</span></label>
                                        <input type="color" class="form-control" v-model="field.variable_attribute_value.field_value" style="height: 2.2rem;" />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>
                                    <div v-if="field.field_type=='List'">
                                        <label class="form-label">{{field.display_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <select class="form-control" :class="{'is-invalid': errors[field.display_name]}" v-model="field.variable_attribute_value.field_value">
                                            <option :value="field.variable_attribute_value.field_value" v-if="field.variable_attribute_value.field_value">{{field.variable_attribute_value.field_value}}</option>
                                            <option :value="field.variable_attribute_value.field_value" v-else>Select {{field.display_name}}</option>
                                            <option v-for="value, key in field.list_parameter?.field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                        </select>
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Assign To</label><span class="text-danger"> *</span>
                                        <div class="dropdown" @click="toggleAssetTypeStatus()">
                                            <div class="overselect"></div>
                                            <select class="form-control" :class="{ 'is-invalid': errors.asset_types }" :customClass="{ 'is-invalid': errors.asset_types }">
                                                <option value="">Select Assign To</option>
                                            </select>
                                            <span v-if="errors.asset_types"><small class="text-danger">{{ errors.asset_types[0] }}</small></span>
                                        </div>
                                        <div class="multiselect" v-if="asset_type_status">
                                            <ul>
                                                <li class="" v-for="(asset_type, index) in asset_types" :key="index">
                                                    <input type="checkbox" :value="asset_type.asset_type_id" v-model="variable.asset_types" style="padding: 2px;" @click="updateActivityType($event, variable)" />
                                                    <label style="margin-left: 5px;">{{ asset_type.asset_type_name }}</label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="button" class="btn btn-danger me-2" @click="discard()"><i class="ri-close-line fs-18 lh-1"></i> Discard</button>
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
    import Pagination from "@/components/Pagination.vue";
    import Search from "@/components/Search.vue";
    export default {
        components: {
            Pagination,
            Search,
        },
        data() {
            return {
                variables: [],
                variable: {
                    variable_id: "",
                    variable_type_id: "",
                    variable_code: "",
                    variable_name: "",
                    variable_attributes: [],
                    asset_types: [],
                    frequency_id: "",
                    deleted_variable_attribute_values: [],
                    deleted_variable_asset_types: [],
                },
                deleted_variable_asset_types: [],
                status: true,
                errors: [],
                deleted_variable_attribute_values: [],
                variable_types: [],
                asset_types: [],
                frequencies: [],
                show_variables: [],
                asset_type_status: false,
            };
        },

        beforeRouteEnter(to, from, next) {
            next((vm) => {
                vm.getAssetTypes();
                if (to.name == "Variables.Create") {
                    // vm.$refs.asset_name.focus();
                } else {
                    vm.status = false;
                    let uri = { uri: "getVariableData", data: { variable_id: to.params.variable_id } };
                    vm.$store
                        .dispatch("post", uri)
                        .then(function (response) {
                            vm.variable = response.data.data;
                            vm.variable.variable_attributes.map(function (element) {
                                vm.deleted_variable_attribute_values.push(element.variable_attribute_value.variable_attribute_value_id);
                            });
                            vm.variable.deleted_variable_attribute_values = [];
                            vm.variable.deleted_variable_asset_types = [];
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
                let variable_asset_type = activity_type?.variable_asset_types?.filter(function (element) {
                    return element.asset_type_id == event.target.value;
                });
                if (variable_asset_type?.length) {
                    let variable_asset_type_id = variable_asset_type[0].variable_asset_type_id;
                    if (isChecked) {
                        if (vm.variable.deleted_variable_asset_types.includes(variable_asset_type_id)) {
                            let deleted_variable_asset_types = this.variable.deleted_variable_asset_types.filter(function (element) {
                                return element != variable_asset_type_id;
                            });
                            vm.variable.deleted_variable_asset_types = deleted_variable_asset_types;
                        }
                    } else {
                        if (!vm.variable.deleted_variable_asset_types.includes(variable_asset_type_id)) {
                            vm.variable.deleted_variable_asset_types.push(variable_asset_type_id);
                        }
                    }
                }
            },
            toggleAssetTypeStatus() {
                this.asset_type_status = !this.asset_type_status;
            },
            submitForm() {
                let vm = this;
                if (vm.status) {
                    vm.addVariable();
                } else {
                    vm.updateVariable();
                }
            },

            validateFields() {
                let isValid = true;
                this.errors = {};

                if (!this.variable.variable_code) {
                    this.errors.variable_code = ["Variable Code is required"];
                    isValid = false;
                }
                if (!this.variable.variable_name) {
                    this.errors.variable_name = ["Variable Name is required"];
                    isValid = false;
                }
                if (!this.variable.variable_type_id) {
                    this.errors.variable_type_id = ["Variable Type is required"];
                    isValid = false;
                }
                if (this.variable.asset_types.length === 0) {
                    this.errors.asset_types = ["At least one Asset Type must be selected"];
                    isValid = false;
                }
                for (const field of Object.values(this.variable.variable_attributes)) {
                    if (field.is_required && !field.variable_attribute_value.field_value) {
                        this.errors[field.display_name] = [`${field.display_name} is required`];
                        isValid = false;
                    }
                }

                return isValid;
            },
            addVariable() {
                if (!this.validateFields()) {
                    return;
                }
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "addVariable", data: vm.variable })
                    .then((response) => {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.$router.push("/variables");
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
                        vm.getVariableTypes();
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
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            updateVariable() {
                if (!this.validateFields()) {
                    return;
                }
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "updateVariable", data: vm.variable })
                    .then((response) => {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.$router.push("/variables");
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getVariableTypes() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getVariableTypes" })
                    .then((response) => {
                        loader.hide();
                        vm.variable_types = response.data.data;
                        vm.getFrequencies();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getVariableTypeFields(variable_type_id) {
                let vm = this;
                let loader = vm.$loading.show();
                if (vm.deleted_variable_attribute_values.length) {
                    vm.variable.deleted_variable_attribute_values = vm.deleted_variable_attribute_values;
                }
                vm.$store
                    .dispatch("post", { uri: "getVariablesDropdown", data: { variable_type_id: variable_type_id } })
                    .then((response) => {
                        loader.hide();
                        vm.variable.variable_attributes = response.data.data;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            discard() {
                let vm = this;
                vm.variable.variable_type_id = "";
                vm.variable.variable_code = "";
                vm.variable.variable_name = "";
                vm.variable.asset_types = [];
                vm.variable.frequency_id = "";
                // vm.$refs.variable_type_id.focus();
                vm.show_variables = [];
                vm.variable.variable_attributes = [];
                vm.errors = [];
                vm.status = true;
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
