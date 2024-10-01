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
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label">Asset Code</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Enter Asset Code" class="form-control" :class="{'is-invalid':errors.asset_code}" v-model="asset.asset_code" ref="asset_code" />
                                <span v-if="errors.asset_code" class="invalid-feedback">{{ errors.asset_code[0] }}</span>

                                    <!-- <input type="text" disabled="true" class="form-control text-dark" :style="'background-color: ' + voltage.color + ';'" :value="getComponentCode" v-model="asset.asset_code" /> -->
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Asset Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Enter Asset Name" class="form-control" :class="{'is-invalid':errors.asset_name}" v-model="asset.asset_name" ref="asset_name" />
                                    <span v-if="errors.asset_name" class="invalid-feedback">{{ errors.asset_name[0] }}</span>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Latitude</label>
                                    <input type="text" placeholder="Enter Latitude" class="form-control" :class="{'is-invalid':errors.latitude}" v-model="asset.latitude" />
                                    <span v-if="errors.latitude" class="invalid-feedback">{{ errors.latitude[0] }}</span>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Longitude</label>
                                    <input type="text" placeholder="Enter Longitude" class="form-control" :class="{'is-invalid':errors.longitude}" v-model="asset.longitude"/>
                                    <span v-if="errors.longitude" class="invalid-feedback">{{ errors.longitude[0] }}</span>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Radius</label>
                                    <input type="text" placeholder="Enter Radius" class="form-control" :class="{'is-invalid':errors.radius}" v-model="asset.radius"/>
                                    <span v-if="errors.radius" class="invalid-feedback">{{ errors.radius[0] }}</span>
                                </div>

                            <div class="col-md-4">
                                    <label class="form-label">Department</label>
                                    <select class="form-control" :class="{ 'is-invalid': errors.department_id}" v-model="asset.department_id">
                                            <option value="">Select Department</option>
                                            <option v-for="department, key in departments" :key="key" :value="department?.department_id">{{ department?.department_name }}</option>
                                    </select>
                                    <span v-if="errors.department_id" class="invalid-feedback">{{ errors.department_id[0] }}</span>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Section</label>
                                <select class="form-control" :class="{ 'is-invalid': errors.section_id}" v-model="asset.section_id">
                                    <option value="">Select Section</option>
                                    <option v-for="section, key in sections" :key="key" :value="section?.section_id">{{ section?.section_name }}</option>
                                </select>
                                <span v-if="errors.section_id" class="invalid-feedback">{{ errors.section_id[0] }}</span>
                            </div>

                                <div class="col-md-4">
                                    <label class="form-label">Asset Type</label><span class="text-danger"> *</span>
                                    <search
                                        :class="{ 'is-invalid': errors.asset_type_id }"
                                        :customClass="{ 'is-invalid': errors.asset_type_id }"
                                        :initialize="asset.asset_type_id"
                                        id="asset_type_id"
                                        label="asset_type_name"
                                        label2="asset_type_code"
                                        placeholder="Select Asset Type"
                                        :data="asset_parameters"
                                        @input="asset_type => asset.asset_type_id = asset_type"
                                        @selectsearch="getAssetType(asset.asset_type_id)"
                                    >
                                    </search>

                                    <span v-if="errors.asset_type_id" class="invalid-feedback">{{ errors.asset_type_id[0] }}</span>
                                </div>

                                <div class="col-md-4" v-for="field, key in asset?.asset_parameters" :key="key">
                                    <div v-if="field.field_type=='Text'">
                                        <label  class="form-label">{{field.field_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <input type="text" class="form-control" :placeholder="'Enter '+ field.field_name" :class="{'is-invalid': errors[field.field_name]}" v-model="field.asset_parameter_value.field_value" />
                                        <span v-if="errors[field.field_name]" class="invalid-feedback">{{ errors[field.field_name][0] }}</span>
                                    </div>

                                    <div v-if="field.field_type=='Number'">
                                        <label  class="form-label">{{field.field_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <input type="text" class="form-control" min="0" oninput="validity.valid||(value='');" :placeholder="'Enter '+ field.field_name" :class="{'is-invalid': errors[field.field_name]}" v-model="field.asset_parameter_value.field_value"/>
                                        <span v-if="errors[field.field_name]" class="invalid-feedback">{{ errors[field.field_name][0] }}</span>
                                    </div>

                                    <div v-if="field.field_type === 'Date'">
                                        <label class="form-label">
                                            {{ field.field_name }}
                                            <span v-if="field.is_required" class="text-danger">*</span>
                                        </label>

                                        <input
                                            type="date"
                                            class="form-control"
                                            :placeholder="'Enter ' + field.field_name"
                                            :class="{'is-invalid': errors[field.field_name]}"
                                            v-model="field.asset_parameter_value.field_value"
                                        />
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
                                            :class="{'is-invalid': errors[field.field_name]}"
                                            v-model="field.asset_parameter_value.field_value"
                                            step="1"
                                        />
                                        <span v-if="errors[field.field_name]" class="invalid-feedback">
                                            {{ errors[field.field_name][0] }}
                                        </span>
                                    </div>

                                    <div v-if="field.field_type=='Dropdown'">
                                        <label  class="form-label">{{field.field_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <select class="form-control" :class="{'is-invalid': errors[field.field_name]}" v-model="field.asset_parameter_value.field_value" >
                                            <option value="">Select {{field.field_name}}</option>
                                            <option v-for="value, key in field.field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                        </select>
                                        <span v-if="errors[field.field_name]" class="invalid-feedback">{{ errors[field.field_name][0] }}</span>
                                    </div>

                                    <div v-if="field.field_type=='Color'">
                                        <label class="form-label">{{ field.field_name }}</label>
                                        <span v-if="field.is_required" class="text-danger">*</span>
                                        <div class="input-group">
                                            <span class="input-group-text" :style="getColor(field)"></span>
                                            <input
                                                type="text"
                                                class="form-control"
                                                v-model="field.asset_parameter_value.field_value"
                                                readonly
                                                :style="{ color: selectedColor ? 'black' : 'gray', cursor: 'pointer' }"
                                                @click="toggleDropdown"
                                                placeholder="Select Color"
                                            />
                                            <div class="dropdown-menu" :class="{ show: dropdownVisible }">
                                                <ul class="list-unstyled mb-0">
                                                    <li
                                                        v-for="color in colors"
                                                        :key="color.value"
                                                        @click="selectColor(color.value, color.name, field)"
                                                        class="dropdown-item d-flex align-items-center"
                                                    >
                                                        <span
                                                            :style="{ backgroundColor: color.value }"
                                                            class="color-square me-2"
                                                        ></span>
                                                        <span>{{ color.name }}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <span v-if="errors[field.field_name]" class="invalid-feedback">{{ errors[field.field_name][0] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <router-link type="button" to="/assets" class="btn btn-danger me-2"><i class="ri-arrow-left-line fs-18 lh-1"></i> Back</router-link>
                            <!-- <button type="button" class="btn btn-danger me-2" @click.prevent="discard()"><i class="ri-close-line fs-18 lh-1"></i> Discard</button> -->
                            <button type="submit" class="btn btn-primary">
                                <!-- <span v-if="status"><i class="ri-save-line fs-18 lh-1"></i> Submit</span> -->
                                <span><i class="ri-save-line fs-18 lh-1"></i> Update</span>
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
                selectedColor: null,
                selectedColorName: '',
                dropdownVisible: false,
                colors: [
                    { name: 'Green', value: '#008000' },
                    { name: 'Blue', value: '#0000FF' },
                    { name: 'Red', value: '#FF0000' },
                    { name: 'Orange', value: '#FFA500' },
                    { name: 'Gray', value: '#808080' },
                ],
                sample: "",
                asset: {
                    asset_id: "",
                    plant_id: "",
                    asset_code: "",
                    asset_name: "",
                    asset_type_id: "",
                    section_id:"",
                    department_id:"",
                    latitude:"",
                    longitude:"",
                    longitude:"",
                    status: "",
                    asset_parameters:[],
                    radius:"",
                },
            //     voltage: {
            //         color: "#ffffff",
            //         voltage_code: "",
            //     },

            //     device_code: "",
                asset_parameters: [],
                plants: [],
                asset_types: [],
                sections:[],
                departments:[],
                errors: [],
                status: true,
            };
        },
        beforeRouteEnter(to, from, next) {
            next((vm) => {
                vm.asset.asset_parameters = []
                vm.getAssetsDropdown();
                if (to.name == "Assets.Create") {
                    vm.$refs.asset_name.focus();
                } else {
                    vm.status = false;
                    let uri = { uri: "getAssetdata", data: { asset_id: to.params.asset_id } };
                    vm.$store
                        .dispatch("post", uri)
                        .then(function (response) {
                            vm.asset = response.data.data;

                            // vm.voltage = response.data.data?.voltage;
                            // vm.watt_rating = response.data.data?.watt_rating?.watt_rating_code;
                            // vm.frame = response.data.data?.frame?.frame_code;
                            // vm.mounting = response.data.data?.mounting?.mounting_code;
                            // vm.section = response.data.data?.section?.section_code;
                            // vm.make = response.data.data?.make?.make_code;
                            // vm.speed = response.data.data?.speed?.speed_code;
                            // vm.getVoltage(vm.asset.voltage_id);
                        })
                        .catch(function (error) {
                            console.log(error)
                            // vm.errors = error.response.data.errors;
                            // vm.$store.dispatch("error", error.response.data.message);
                        });
                }
            });
        },
        computed: {
            selectedColorDisplay() {
                return this.selectedColorName ? `${this.selectedColorName} (${this.selectedColor})` : 'Select Color';
            },
            // getComponentCode() {
            //     // this.device_code = [this.voltage.voltage_code[0], this.watt_rating[0], this.frame.substring(0, 5), this.mounting[0], this.section[0], this.make[0], this.speed[0], this.asset.serial_no.substring(0, 5)]
            //     this.device_code = [this.voltage.voltage_code, this.watt_rating, this.frame, this.mounting, this.section, this.make, this.speed, this.asset.serial_no];
            //     return this.device_code.join("");
            //     // if(this.voltage.voltage_code[0]){
            //     //     return this.device_code.join('');
            //     // }else{
            //     //     return this.device_code.join('-');
            //     // }
            // },
        },
        methods: {
            getColor(asset_parameter){
                    let color = 'color:black'
                    if(asset_parameter.field_name = 'Color'){
                    color = 'background-color:'+asset_parameter.asset_parameter_value?.field_value +'; color:white; padding:5px'
                    }
                    return color

            },
            selectColor(colorValue, colorName, field) {
                    if(field.asset_parameter_value)
                        field.asset_parameter_value.field_value = colorValue;
                    field.field_name = colorName;
                this.dropdownVisible = false;
                field.field_value = colorValue;
                this.updateAssetParameters(field);
            },
            toggleDropdown() {
                this.dropdownVisible = !this.dropdownVisible;
            },
            updateAssetParameters(field){
                let apid = this.asset.asset_parameters.filter(function(element){
                    return element.asset_parameter_id == field.asset_parameter_id
                })
                if(!apid.length){
                    this.asset.asset_parameters.push({
                        'asset_parameter_id':field.asset_parameter_id,
                        'field_value':field.field_value
                    })
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

            getDepartments() {
                    let vm = this;
                    let loader = vm.$loading.show();
                    vm.$store.dispatch('post', { uri: 'getDepartments' })
                    .then(response => {
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
                    vm.$store.dispatch('post', { uri: 'getSections' })
                    .then(response => {
                            loader.hide();
                            vm.sections = response.data.data;
                    })
                    .catch(function (error) {
                            loader.hide();
                            vm.errors = error.response.data.errors;
                            vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getAssetsDropdown() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getAssetTypes" })
                    .then((response) => {
                        loader.hide();
                        vm.asset_parameters = response.data.data;
                        vm.getDepartments();
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
            //     vm.asset.asset_code = vm.device_code.join("");
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
                vm.asset = {};
                vm.$refs.asset_name.focus();
                vm.errors = [];
                vm.voltage.color = "#ffffff";
                vm.voltage.voltage_code = "";
                vm.asset.serial_no = "";
                vm.watt_rating = "";
                vm.frame = "";
            //     vm.device_code = "";
                vm.mounting = "";
                vm.section = "";
                vm.make = "";
                vm.speed = "";
            },

            getAssetType(asset_type_id){
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                .dispatch("post", { uri: "getAssetsDropdown", data:{asset_type_id:asset_type_id} })
                .then((response) => {
                    loader.hide();
                    vm.asset_types = response.data.data;
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
            }
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