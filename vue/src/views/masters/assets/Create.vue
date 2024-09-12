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
                        <router-link to="/assets">Assets</router-link>
                    </li>
                    <li class="breadcrumb-item" aria-current="page" v-if="status">New Asset</li>
                    <li class="breadcrumb-item active" aria-current="page" v-else>Update Asset</li>
                </ol>
                <h4 class="main-title mb-0">Asset</h4>
            </div>
            <router-link to="/assets" type="submit" class="btn btn-primary" style="float: right;"><i class="ri-list-check"></i> ASSETS</router-link>
        </div>
        <div class="row">
            <div class="col-12">
                <form @submit.prevent="submitForm()">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">Add Asset</h6>
                            <h6 class="card-title" v-else>Update Asset</h6>
                        </div>
                        <div class="card-body">
                            <div class="card mb-3">
                                <div class="card-header d-flex justify-content-between">
                                    <h6 class="card-title">Asset Details</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <label class="form-label">Asset Type</label><span class="text-danger"> *</span>
                                            <search
                                                :class="{ 'is-invalid': errors.asset_type_id }"
                                                :customClass="{ 'is-invalid': errors?.asset_type_id }"
                                                :initialize="asset.asset_type_id"
                                                id="asset_type_id"
                                                label="asset_type_name"
                                                label2="asset_type_code"
                                                placeholder="Select Asset Type"
                                                :data="asset_attributes"
                                                @input="asset_type => asset.asset_type_id = asset_type"
                                                @selectsearch="getAssetType(asset.asset_type_id)"
                                            >
                                            </search>
                                            <span v-if="errors?.asset_type_id" class="invalid-feedback">{{ errors.asset_type_id[0] }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Asset Code</label><span class="text-danger"> *</span>
                                            <input type="text" placeholder="Enter Asset Code" class="form-control" :class="{ 'is-invalid': errors?.asset_code }" v-model="asset.asset_code"/>
                                            <span v-if="errors?.asset_code" class="invalid-feedback">{{ errors.asset_code[0] }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Asset Name</label><span class="text-danger"> *</span>
                                            <input type="text" placeholder="Enter Asset Name" class="form-control" :class="{ 'is-invalid': errors?.asset_name }" v-model="asset.asset_name" />
                                            <span v-if="errors?.asset_name" class="invalid-feedback">{{ errors.asset_name[0] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header d-flex justify-content-between">
                                    <h6 class="card-title">Lineage Parameters</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <label class="form-label">Department</label>
                                            <select class="form-control" :class="{ 'is-invalid': errors?.department_id }" v-model="asset.department_id">
                                                <option value="">Select Department</option>
                                                <option v-for="department, key in departments" :key="key" :value="department?.department_id">{{ department?.department_name }} </option>
                                            </select>
                                            <span v-if="errors?.department_id" class="invalid-feedback">{{ errors.department_id[0] }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Section</label>
                                            <select class="form-control" :class="{ 'is-invalid': errors?.section_id }" v-model="asset.section_id">
                                                <option value="">Select Section</option>
                                                <option v-for="section, key in sections" :key="key" :value="section?.section_id">{{ section?.section_name }}</option>
                                            </select>
                                            <span v-if="errors?.section_id" class="invalid-feedback">{{ errors.section_id[0] }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Functional</label>
                                            <select class="form-control" :class="{ 'is-invalid': errors?.functional_id }" v-model="asset.functional_id">
                                                <option value="">Select Functional</option>
                                                <option v-for="functional, key in functionals" :key="key" :value="functional?.functional_id">{{ functional?.functional_name }} </option>
                                            </select>
                                            <span v-if="errors?.functional_id" class="invalid-feedback">{{ errors.functional_id[0] }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Shop</label><span class="text-danger"> *</span>
                                            <select class="form-control" :class="{ 'is-invalid': errors?.plant_id }" v-model="asset.plant_id">
                                                <option value="">Select Shop</option>
                                                <option v-for="plant, key in plants" :key="key" :value="plant?.plant_id">{{ plant.plant_name }} </option>
                                            </select>
                                            <span v-if="errors?.plant_id" class="invalid-feedback">The shop field is required </span>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Area</label><span class="text-danger"> *</span>
                                            <select class="form-control" :class="{ 'is-invalid': errors?.area_id }" v-model="asset.area_id">
                                                <option value="">Select Area</option>
                                                <option v-for="area, key in areas" :key="key" :value="area?.area_id">{{ area?.area_name }} </option>
                                            </select>
                                            <span v-if="errors?.area_id" class="invalid-feedback">{{ errors.area_id[0] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-header d-flex justify-content-between">
                                    <h6 class="card-title">Settings</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <label class="form-label">No Of Zones </label><span class="text-danger"> *</span>
                                            <input type="number" placeholder="Enter No Of Zones " class="form-control" :class="{ 'is-invalid': errors?.no_of_zones }" v-model="asset.no_of_zones" />
                                            <span v-if="errors?.no_of_zones" class="invalid-feedback">{{ errors.no_of_zones[0] }}</span>
                                        </div>
                                        <div v-for="(zone, index) in asset.zone_name" :key="index" class="col-md-4">
                                            <label class="form-label">Zone {{ index + 1 }}</label>
                                            <input type="text" v-model="zone.zone_name" class="form-control" />
                                            <span style="color: red;">{{ getZoneNameError(index) }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Latitude</label>
                                            <input type="number" placeholder="Enter Latitude" class="form-control" :class="{ 'is-invalid': errors?.latitude }" v-model="asset.latitude" />
                                            <span v-if="errors?.latitude" class="invalid-feedback">{{ errors.latitude[0] }}</span>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Longitude</label>
                                            <input type="number" placeholder="Enter Longitude" class="form-control" :class="{ 'is-invalid': errors?.longitude }" v-model="asset.longitude" />
                                            <span v-if="errors?.longitude" class="invalid-feedback">{{ errors.longitude[0] }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Radius</label>
                                            <input type="number" placeholder="Enter Radius" class="form-control" :class="{ 'is-invalid': errors?.radius }" v-model="asset.radius" />
                                            <span v-if="errors?.radius" class="invalid-feedback">{{ errors.radius[0] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                  <div class="card-header d-flex justify-content-between">
                                    <h6 class="card-title">Attributes</h6>
                                </div>
                                 <div class="card-body">
                                     <div class="row g-2">
                                        <div class="col-md-4" v-for="field, key in asset.asset_attributes" :key="key">
                                            <div v-if="field.field_type == 'Text'">
                                                <label class="form-label">{{ field.field_name }}</label><span v-if="field.is_required" class="text-danger">*</span>
                                                <input type="text" class="form-control" :placeholder="'Enter ' + field.field_name" :class="{ 'is-invalid': errors[field.field_name] }" v-model="field.asset_attribute_value.field_value" />

                                                <span v-if="errors[field.field_name]" class="invalid-feedback">{{ errors[field.field_name][0] }}</span>
                                            </div>
                                            <div v-if="field.field_type == 'Number'">
                                                <label class="form-label">{{ field.field_name }}</label><span v-if="field.is_required" class="text-danger">*</span>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    min="0"
                                                    oninput="validity.valid||(value='');"
                                                    :placeholder="'Enter ' + field.field_name"
                                                    :class="{ 'is-invalid': errors[field.field_name] }"
                                                    v-model="field.asset_attribute_value.field_value"
                                                />
                                                <span v-if="errors[field.field_name]" class="invalid-feedback">{{ errors[field.field_name][0] }}</span>
                                            </div>
                                            <div v-if="field.field_type === 'Date'">
                                                <label class="form-label">
                                                    {{ field.field_name }}
                                                    <span v-if="field.is_required" class="text-danger">*</span>
                                                </label>
                                                <input type="date" class="form-control" :placeholder="'Enter ' + field.field_name" :class="{ 'is-invalid': errors[field.field_name] }" v-model="field.asset_attribute_value.field_value" />

                                                <span v-if="errors[field.field_name]" class="invalid-feedback">
                                                    {{ errors[field.field_name][0] }}
                                                </span>
                                            </div>
                                            <div v-if="field.field_type === 'Date&Time'">
                                                <label class="form-label">
                                                    {{ field.field_name }}
                                                    <span v-if="field.is_required" class="text-danger">*</span>
                                                </label>
                                                <input
                                                    type="datetime-local"
                                                    class="form-control"
                                                    :placeholder="'Enter ' + field.field_name"
                                                    :class="{ 'is-invalid': errors[field.field_name] }"
                                                    v-model="field.asset_attribute_value.field_value"
                                                    step="1"
                                                />
                                                <span v-if="errors[field.field_name]" class="invalid-feedback">
                                                    {{ errors[field.field_name][0] }}
                                                </span>
                                            </div>
                                            <div v-if="field.field_type == 'Dropdown'">
                                                <label class="form-label">{{ field.field_name }}</label><span v-if="field.is_required" class="text-danger">*</span>
                                                <select class="form-control" :class="{ 'is-invalid': errors[field.display_name] }" v-model="field.asset_attribute_value.field_value">
                                                    <option :value="field.asset_attribute_value.field_value" v-if="field.asset_attribute_value.field_value"> {{ field.asset_attribute_value.field_value }}</option>
                                                    <option :value="field.asset_attribute_value.field_value" v-else>Select {{ field.display_name }}</option>
                                                    <option v-for="value, key in field.field_values.split(',')" :key="key" :value="value">{{ value }}</option>
                                                </select>
                                                <span v-if="errors[field.field_name]" class="invalid-feedback">{{ errors[field.field_name][0] }}</span>
                                            </div>

                                            <div v-if="field.field_type == 'Color'">
                                                <label class="form-label">{{ field.display_name }}<span v-if="field.is_required" class="text-danger">*</span></label>
                                                <input type="color" class="form-control" v-model="field.asset_attribute_value.field_value" style="height: 2.2rem;" />

                                                <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                            </div>
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
    import Search from "@/components/Search.vue";
    export default {
        name: "Assets.Create",
        components: { Search },
        data() {
            return {
                sample: "",
                asset: {
                    asset_id: "",
                    plant_id: "",
                    asset_code: "",
                    asset_name: "",
                    asset_type_id: "",
                    latitude: "",
                    longitude: "",
                    no_of_zones: null,
                    status: "",
                    asset_attributes: [],
                    department_id: "",
                    section_id: "",
                    functional_id: "",
                    area_id:"",
                    radius: "",
                    zone_name: [],
                    deleted_asset_attribute_values: [],
                },
                voltage: {
                    color: "#ffffff",
                    voltage_code: "",
                },
                errors: {
                    no_of_zones: [],
                },

                device_code: "",
                deleted_asset_attribute_values: [],
                // asset_attributes: [],
                departments: [],
                sections: [],
                functionals: [],
                areas:[],
                plants: [],
                asset_types: [],
                show_assets: [],
                errors: [],
                status: true,
                zones: [],
                initial_zone_no: null,
                new_zone_names: [],
                prev_zone_names: [],
            };
        },
        beforeRouteEnter(to, from, next) {
            next((vm) => {
                vm.asset.asset_attributes = [];
                vm.getAssetsDropdown();
                if (to.name == "Assets.Create") {
                    // vm.$refs.asset_code.focus();
                } else {
                    vm.status = false;
                    let uri = { uri: "getAssetdata", data: { asset_id: to.params.asset_id } };
                    vm.$store
                        .dispatch("post", uri)
                        .then(function (response) {
                            vm.asset = response.data.data;
                            vm.initial_zone_no = vm.asset.no_of_zones;
                            vm.prev_zone_names = vm.asset.zone_name;
                            // console.log("t--",vm.asset)
                            vm.show_assets = response.data.data?.asset_attributes;

                            vm.asset.asset_attributes.map(function (element) {
                                vm.deleted_asset_attribute_values.push(element.asset_attribute_value.asset_attribute_value_id);
                            });
                            vm.asset.deleted_asset_attribute_values = [];
                        })
                        .catch(function (error) {
                            console.log(error);
                            // vm.errors = error.response.data.errors;
                            // vm.$store.dispatch("error", error.response.data.message);
                        });
                }
            });
        },

        watch: {
            "asset.no_of_zones": function (newVal) {
                let vm = this;
                vm.asset.zone_name = [];
                if (this.status) {
                    for (let i = 0; i < vm.asset.no_of_zones; i++) {
                        vm.asset.zone_name.push({
                            zone_name: null,
                        });
                    }
                } else {
                    vm.prev_zone_names.map(function (pre_element) {
                        vm.asset.zone_name.push(pre_element);
                    });
                    if (vm.asset.no_of_zones && vm.asset.no_of_zones < vm.prev_zone_names.length) {
                        vm.asset.no_of_zones = vm.prev_zone_names.length;
                    }
                    if (vm.asset.no_of_zones && vm.asset.no_of_zones > vm.prev_zone_names.length) {
                        let number = vm.asset.no_of_zones - vm.prev_zone_names.length;
                        console.log(number);
                        this.new_zone_names = [];
                        for (let i = 0; i < number; i++) {
                            this.new_zone_names.push({
                                zone_name: null,
                            });
                        }
                        vm.new_zone_names.map(function (element) {
                            vm.asset.zone_name.push(element);
                        });
                    }

                    // if(number < this.asset.zone_name.length && number > vm.initial_zone_no){
                    //     console.log('1')
                    //     for(let i = 0; i<number; i++){
                    //         this.asset.zone_name.pop()
                    //     }
                    // }else{
                    //     console.log('2')
                    //     console.log(number)
                    //     if(number > 0){
                    //         for(let i = 0; i<number; i++){
                    //             this.asset.zone_name.push({
                    //                 zone_name : ''
                    //             })
                    //         }
                    //     }else{
                    //         for(let i = number; i<0; i--){
                    //             this.asset.zone_name.pop()
                    //         }
                    //     }
                    // }
                }
            },
        },

        computed: {
            // selectedColorDisplay() {
            //     return this.selectedColorName ? `${this.selectedColorName} (${this.selectedColor})` : 'Select Color';
            // },
            getComponentCode() {
                // this.device_code = [this.voltage.voltage_code[0], this.watt_rating[0], this.frame.substring(0, 5), this.mounting[0], this.section[0], this.make[0], this.speed[0], this.asset.serial_no.substring(0, 5)]
                this.device_code = [this.voltage.voltage_code, this.watt_rating, this.frame, this.mounting, this.section, this.make, this.speed, this.asset.serial_no];
                return this.device_code.join("");
                // if(this.voltage.voltage_code[0]){
                //     return this.device_code.join('');
                // }else{
                //     return this.device_code.join('-');
                // }
            },
        },
        methods: {
            getZoneNameError(index) {
                let zone_names = Object.entries(this.errors);
                let err = zone_names.filter(function (element) {
                    return element[0].includes("zone_name") && element[0].includes(index);
                });
                console.log(err);
                if (err.length) {
                    return "Zone name field is required";
                }
            },
            // selectColor(colorValue, colorName, field) {
            //     this.selectedColor = colorValue;
            //     this.selectedColorName = colorName;
            //     this.dropdownVisible = false;
            //     field.field_value = colorValue;
            //     this.updateAssetParameters(field);
            // },
            // toggleDropdown() {
            //     this.dropdownVisible = !this.dropdownVisible;
            // },
            updateAssetParameters(field) {
                console.log(field);
                let apid = this.asset.asset_attributes.filter(function (element) {
                    return element.asset_attribute_id == field.asset_attribute_id;
                });
                if (!apid.length) {
                    this.asset.asset_attributes.push({
                        asset_attribute_id: field.asset_attribute_id,
                        field_value: field.field_value,
                    });
                }
            },
            submitForm() {
                let vm = this;
                if (vm.status) {
                    vm.addAsset();
                } else {
                    vm.updateAsset();
                }
            },
            getAssetsDropdown() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getAssetTypes" })
                    .then((response) => {
                        loader.hide();
                        vm.asset_attributes = response.data.data;
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
                        vm.getSections();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            getSections() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getSections" })
                    .then((response) => {
                        loader.hide();
                        vm.sections = response.data.data;
                        vm.getFunctionals();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getFunctionals() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getFunctionals" })
                    .then((response) => {
                        loader.hide();
                        vm.functionals = response.data.data;
                        vm.getShops();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
             getShops() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getPlants" })
                    .then((response) => {
                        loader.hide();
                        vm.plants = response.data.data;
                        vm.getAreas();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
             getAreas() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getAreas" })
                    .then((response) => {
                        loader.hide();
                        vm.areas = response.data.data;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            addAsset() {
                let vm = this;
                // vm.asset.asset_code = vm.device_code.join("");
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "addAsset", data: vm.asset })
                    .then((response) => {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.$router.push("/assets");
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            updateAsset() {
                let vm = this;
                // vm.asset.asset_code = vm.device_code.join("");
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "updateAsset", data: vm.asset })
                    .then((response) => {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.$router.push("/assets");
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            getAsset() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getAsset", data: vm.asset })
                    .then((response) => {
                        loader.hide();
                        vm.asset = response.data.data;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            discard() {
                let vm = this;
                // vm.asset = {};
                vm.asset.asset_code = "";
                vm.asset.asset_name = "";
                vm.asset.latitude = "";
                vm.asset.longitude = "";
                vm.asset.radius = "";
                vm.asset.department_id = "";
                vm.asset.section_id = "";
                vm.asset.functional_id = "";
                vm.errors = [];
                vm.voltage.color = "#ffffff";
                vm.voltage.voltage_code = "";
                vm.asset.serial_no = "";
                vm.watt_rating = "";
                vm.frame = "";
                vm.device_code = "";
                vm.mounting = "";
                vm.section = "";
                vm.make = "";
                vm.speed = "";
                vm.show_assets = [];
                vm.asset.asset_type_id = "";
                let discard_zone = vm.asset.zone_name.filter(function (element) {
                    console.log("dd--", element);
                    element.zone_name = "";
                });
                vm.$refs.asset_code.focus();
            },

            getAssetType(asset_type_id) {
                let vm = this;
                let loader = vm.$loading.show();
                if (vm.deleted_asset_attribute_values.length) {
                    vm.asset.deleted_asset_attribute_values = vm.deleted_asset_attribute_values;
                }
                vm.$store
                    .dispatch("post", { uri: "getAssetsDropdown", data: { asset_type_id: asset_type_id } })
                    .then((response) => {
                        loader.hide();
                        vm.show_assets = response.data.data;
                        vm.asset.asset_attributes = response.data.data;
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

<style>
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
