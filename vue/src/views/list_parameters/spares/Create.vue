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
                    <li class="breadcrumb-item active" aria-current="page"><router-link to="/spares">Spares</router-link></li>
                    <li class="breadcrumb-item" aria-current="page" v-if="status">New Spare</li>
                    <li class="breadcrumb-item active" aria-current="page" v-else>Update Spare</li>
                </ol>
                <h4 class="main-title mb-0">Spares</h4>
            </div>
            <router-link to="/spares" type="submit" class="btn btn-primary" style="float: right;"><i class="ri-list-check"></i> SPARES</router-link>
        </div>
        <div class="row g-2">
            <div class="col-12">
                <form @submit.prevent="submitForm()">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">Add Spare</h6>
                            <h6 class="card-title" v-else>Update Spare</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <div class="d-flex justify-content-between">
                                    <div><label class="form-label">Spare Types</label><span class="text-danger"> *</span></div>
                                     <a type="button" class="text-danger me-2" @click="reset()"><i class="ri-close-line fs-20 lh-1"></i></a>
                                    </div>
                                    <search
                                        :class="{ 'is-invalid': errors?.spare_type_id }"
                                        :customClass="{ 'is-invalid': errors?.spare_type_id }"
                                        :initialize="spare.spare_type_id"
                                        id="spare_type_id"
                                        label="spare_type_name"
                                        placeholder="Select Spare Type"
                                        :data=" spare_types"
                                        @input=" spare_type => spare.spare_type_id = spare_type"
                                        @selectsearch="getSpareTypeFields(spare)"
                                    >
                                    </search>
                                    <span v-if="errors?.spare_type_id"><small class="text-danger">{{ errors?.spare_type_id[0] }}</small></span>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Spare Code</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Spare Code" class="form-control" :class="{ 'is-invalid': errors?.spare_code }" v-model="spare.spare_code" />
                                    <span v-if="errors?.spare_code" class="invalid-feedback">{{ errors?.spare_code[0] }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Spare Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Spare Name" class="form-control" :class="{ 'is-invalid': errors?.spare_name }" v-model="spare.spare_name" />
                                    <span v-if="errors?.spare_name" class="invalid-feedback">{{ errors?.spare_name[0] }}</span>
                                </div>
                                <div class="col-md-4" v-for="field, key in spare.spare_attributes" :key="key">
                                    <div v-if="field.field_type=='Text'">
                                        <label class="form-label">{{field.display_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <input type="text" class="form-control" :placeholder="'Enter '+ field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.spare_attribute_value.field_value" />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>

                                    <div v-if="field.field_type=='Number'">
                                        <label class="form-label">{{field.display_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <input
                                            type="number"
                                            class="form-control"
                                            min="0"
                                            step="any"
                                            oninput="validity.valid||(value='');"
                                            :placeholder="'Enter '+ field.display_name"
                                            :class="{'is-invalid': errors[field.display_name]}"
                                            v-model="field.spare_attribute_value.field_value"
                                        />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>

                                    <div v-if="field.field_type === 'Date'">
                                        <label class="form-label">
                                            {{ field.display_name }}
                                            <span v-if="field.is_required" class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control" :placeholder="'Enter ' + field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.spare_attribute_value.field_value" />
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
                                            v-model="field.spare_attribute_value.field_value"
                                            step="1"
                                        />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">
                                            {{ errors[field.display_name][0] }}
                                        </span>
                                    </div>
                                    <div v-if="field.field_type=='Dropdown'">
                                        <label class="form-label">{{field.display_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <select class="form-control" :class="{'is-invalid': errors[field.display_name]}" v-model="field.spare_attribute_value.field_value">
                                            <option :value="field.spare_attribute_value.field_value" v-if="field.spare_attribute_value.field_value">{{field.spare_attribute_value.field_value}}</option>
                                            <option :value="field.spare_attribute_value.field_value" v-else>Select {{field.display_name}}</option>
                                            <option v-for="value, key in field.field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                        </select>
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>

                                    <div v-if="field.field_type=='Color'">
                                        <label class="form-label">{{ field.display_name }}<span v-if="field.is_required" class="text-danger">*</span></label>
                                        <input type="color" class="form-control" v-model="field.spare_attribute_value.field_value" style="height: 2.2rem;" />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>

                                    <div v-if="field.field_type=='List'">
                                        <label class="form-label">{{field.display_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <select class="form-control" :class="{'is-invalid': errors[field.display_name]}" v-model="field.spare_attribute_value.field_value">
                                            <option :value="field.spare_attribute_value.field_value" v-if="field.spare_attribute_value.field_value">{{field.spare_attribute_value.field_value}}</option>
                                            <option :value="field.spare_attribute_value.field_value" v-else>Select {{field.display_name}}</option>
                                            <option v-for="value, key in field.list_parameter?.field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                        </select>
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Assign To</label><span class="text-danger"> *</span>
                                        <!-- <div class="dropdown" @click="toggleAssetTypeStatus()">
                                            <div class="overselect"></div>
                                            <select class="form-control" :class="{ 'is-invalid': errors?.asset_types }" :customClass="{ 'is-invalid': errors?.asset_types }">
                                                <option value="">Select Assign To</option>
                                            </select>
                                            <span v-if="errors?.asset_types"><small class="text-danger">{{ errors?.asset_types[0] }}</small></span>
                                        </div>
                                        <div class="multiselect" v-if="asset_type_status">
                                            <ul>
                                                <li class="" v-for="(asset_type, index) in asset_types" :key="index">
                                                    <input type="checkbox" :value="asset_type.asset_type_id" v-model="spare.asset_types" style="padding: 2px;" @click="updateActivityType($event, spare)" />
                                                    <label style="margin-left: 5px;">{{ asset_type.asset_type_name }}</label>
                                                </li>
                                            </ul>
                                        </div> -->
                                        <MultiSelect v-model="spare.asset_types_obj"  filter optionLabel="asset_type_name" 
                                            :options="asset_types"  placeholder="Select Assign To" :maxSelectedLabels="3"  
                                            style="width: 100%;; height: 37px;" :style="errors?.asset_types ? error_style : ''"/>
                                        <span v-if="errors?.asset_types"><small class="text-danger">{{ errors?.asset_types[0] }}</small></span>
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
    import MultiSelect from 'primevue/multiselect';
    export default {
        components: {
            Pagination,
            Search,
            MultiSelect
        },
        data() {
            return {
                spares: [],
                spare: {
                    spare_id: "",
                    spare_type_id: "",
                    spare_code: "",
                    spare_name: "",
                    spare_attributes: [],
                    asset_types_obj:[],
                    asset_types: [],
                    frequency_id: "",
                    deleted_spare_attribute_values: [],
                    deleted_spare_asset_types: [],
                },
                deleted_spare_asset_types: [],
                deleted_spare_attribute_values: [],
                status: true,
                errors: [],
                spare_types: [],
                asset_types: [],
                frequencies: [],
                show_spares: [],
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
                if (to.name == "Spares.Create") {
                    // vm.$refs.asset_name.focus();
                } else {
                    vm.status = false;
                    let uri = { uri: "getSpareData", data: { spare_id: to.params.spare_id } };
                    vm.$store
                        .dispatch("post", uri)
                        .then(function (response) {
                            vm.spare = response.data.data;
                            vm.spare.spare_attributes.map(function (element) {
                                vm.deleted_spare_attribute_values.push(element.spare_attribute_value.spare_attribute_value_id);
                            });
                            vm.spare.deleted_spare_attribute_values = [];
                            vm.spare.deleted_spare_asset_types = [];

                            vm.spare.asset_types_obj = []

                            vm.spare.spare_asset_types.map(function(ele){
                                vm.spare.asset_types_obj.push({asset_type_code: ele.asset_types.asset_type_code, 
                                    asset_type_id: ele.asset_types.asset_type_id, status: ele.asset_types.status,
                                    asset_type_name: ele.asset_types.asset_type_name})
                            })
                        })
                        .catch(function (error) {
                            console.log("error", error);
                            vm.errors = error.response?.data?.errors;
                            vm.$store.dispatch("error", error.response?.data?.message);
                        });
                }
            });
        },

        methods: {
            updateActivityType(event, activity_type) {
                let vm = this;
                const isChecked = event.target.checked;
                let spare_asset_type = activity_type?.spare_asset_types?.filter(function (element) {
                    return element.asset_type_id == event.target.value;
                });
                if (spare_asset_type?.length) {
                    let spare_asset_type_id = spare_asset_type[0].spare_asset_type_id;
                    if (isChecked) {
                        if (vm.spare.deleted_spare_asset_types.includes(spare_asset_type_id)) {
                            let deleted_spare_asset_types = vm.spare.deleted_spare_asset_types.filter(function (element) {
                                return element != spare_asset_type_id;
                            });
                            vm.spare.deleted_spare_asset_types = deleted_spare_asset_types;
                        }
                    } else {
                        if (!vm.spare.deleted_spare_asset_types.includes(spare_asset_type_id)) {
                            vm.spare.deleted_spare_asset_types.push(spare_asset_type_id);
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
                    vm.addSpare();
                } else {
                    vm.updateSpare();
                }
            },

            validateFields() {
                let isValid = true;
                this.errors = {};

                if (!this.spare.spare_code) {
                    this.errors.spare_code = ["Spare Code is required"];
                    isValid = false;
                }
                if (!this.spare.spare_name) {
                    this.errors.spare_name = ["Spare Name is required"];
                    isValid = false;
                }
                if (!this.spare.spare_type_id) {
                    this.errors.spare_type_id = ["Spare Type is required"];
                    isValid = false;
                }
                if (this.spare.asset_types.length === 0) {
                    this.errors.asset_types = ["At least one Asset Type must be selected"];
                    isValid = false;
                }
                for (const field of Object.values(this.spare.spare_attributes)) {
                    if (field.is_required && !field.spare_attribute_value.field_value) {
                        if (field.field_type === "Color") {
                            // Set default color if not provided
                            field.spare_attribute_value.field_value = "#000000"; // Default to black
                        } else {
                            this.errors[field.display_name] = [`${field.display_name} is required`];
                            isValid = false;
                        }
                    }
                }
                console.log("Error",this.errors)
                return isValid;
            },
            addSpare() {
                let vm = this;
                vm.spare.asset_types_obj.map(function(ele){
                    vm.spare.asset_types.push(ele.asset_type_id)
                })
                if (!vm.validateFields()) {
                    return;
                }                
                let loader = vm.$loading.show();
                vm.$store.dispatch("post", { uri: "addSpare", data: vm.spare })
                .then((response) => {
                    loader.hide();
                    vm.$store.dispatch("success", response.data.message);
                    vm.$router.push("/spares");
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
                        vm.getSpareTypes();
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

            updateSpare() {
                let vm = this;
                vm.spare.deleted_spare_asset_types = vm.spare?.spare_asset_types.filter(
                    item1 => !vm.spare.asset_types_obj.some(item2 => item1.asset_type_id === item2.asset_type_id));
                vm.spare.asset_types = vm.spare.asset_types_obj.map(item => item.asset_type_id);
                vm.spare.deleted_spare_asset_types = vm.spare.deleted_spare_asset_types.map(item => item.spare_asset_type_id);

                if (!this.validateFields()) {
                    return; // Stop the submit if validation fails
                }
               
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "updateSpare", data: vm.spare })
                    .then((response) => {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.$router.push("/spares");
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getSpareTypes() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getSpareTypes" })
                    .then((response) => {
                        loader.hide();
                        vm.spare_types = response.data.data;
                        vm.getFrequencies();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getSpareTypeFields(spare) {
                let vm = this;
                let loader = vm.$loading.show();
                if (vm.deleted_spare_attribute_values.length) {
                    vm.spare.deleted_spare_attribute_values = vm.deleted_spare_attribute_values;
                }
                vm.$store
                    .dispatch("post", { uri: "getSparesDropdown", data: spare })
                    .then((response) => {
                        loader.hide();
                        vm.spare.spare_attributes = response.data.data;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            discard() {
                let vm = this;
                vm.spare.spare_type_id = "";
                vm.spare.spare_code = "";
                vm.spare.spare_name = "";
                vm.spare.asset_types = [];
                vm.spare.frequency_id = "";
                // vm.$refs.spare_type_id.focus();
                vm.show_spares = [];
                vm.spare.spare_attributes = [];
                vm.errors = [];
                vm.status = true;
            },
            reset() {
                 let vm = this;
                vm.spare.spare_type_id = "";
                vm.spare.asset_types = [];
                vm.spare.frequency_id = "";
                // vm.$refs.spare_type_id.focus();
                vm.show_spares = [];
                vm.spare.spare_attributes = [];
                vm.errors = [];
            }
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
    .color-square {
        width: 16px;
        height: 16px;
        display: inline-block;
        border: 1px solid #000;
    }
    .input-group {
        position: relative;
    }
    .dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1000;
        display: none;
        background-color: #fff;
        border: 1px solid rgba(0, 0, 0, 0.15);
    }
    .dropdown-menu.show {
        display: block;
    }
</style>
