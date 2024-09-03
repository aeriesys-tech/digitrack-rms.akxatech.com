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
                                        <!-- <input type="text" disabled="true" class="form-control text-dark" :style="'background-color: ' + voltage.color + ';'" :value="getComponentCode" v-model="asset.asset_code" /> -->
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Longitude</label>
                                    <input type="text" placeholder="Enter Longitude" class="form-control" :class="{'is-invalid':errors.longitude}" v-model="asset.longitude" />
                                    <span v-if="errors.longitude" class="invalid-feedback">{{ errors.longitude[0] }}</span>
                                        <!-- <input type="text" disabled="true" class="form-control text-dark" :style="'background-color: ' + voltage.color + ';'" :value="getComponentCode" v-model="asset.asset_code" /> -->
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Radius</label>
                                    <input type="text" placeholder="Enter Radius" class="form-control" :class="{'is-invalid':errors.radius}" v-model="asset.radius" />
                                    <span v-if="errors.radius" class="invalid-feedback">{{ errors.radius[0] }}</span>
                                        <!-- <input type="text" disabled="true" class="form-control text-dark" :style="'background-color: ' + voltage.color + ';'" :value="getComponentCode" v-model="asset.asset_code" /> -->
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
                                    <label class="form-label">Functional</label>
                                    <select class="form-control" :class="{ 'is-invalid': errors.functional_id}" v-model="asset.functional_id">
                                        <option value="">Select Functional</option>
                                        <option v-for="functional, key in functionals" :key="key" :value="functional?.functional_id">{{ functional?.functional_name }}</option>
                                    </select> 
                                    <span v-if="errors.functional_id" class="invalid-feedback">{{ errors.functional_id[0] }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">No Of Zones </label><span class="text-danger"> *</span>
                                    <input type="number" placeholder="Enter No Of Zones " class="form-control" :class="{'is-invalid':errors.no_of_zones}" v-model="asset.no_of_zones"  />
                                    <span v-if="errors.no_of_zones" class="invalid-feedback">{{ errors.no_of_zones[0] }}</span>
                                </div>

                                <div v-for="(zone, index) in asset.zone_name" :key="index" class="col-md-4 ">
                                    <label class="form-label">Zone {{ index + 1 }}</label>
                                    <input type="text" v-model="zone.zone_name" class="form-control"/>
                                    <span style="color:red">{{ getZoneNameError(index) }}</span>
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
                                        :data="asset_attributes"
                                        @input="asset_type => asset.asset_type_id = asset_type"
                                        @selectsearch="getAssetType(asset.asset_type_id)"
                                    >
                                    </search>
                            
                                    <span v-if="errors.asset_type_id" class="invalid-feedback">{{ errors.asset_type_id[0] }}</span>
                                </div>
                                
                                <div class="col-md-4" v-for="field, key in show_assets" :key="key">
                                    <div v-if="field.field_type=='Text'">
                                        <label  class="form-label">{{field.field_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <input v-if="field.asset_attribute_value" type="text" class="form-control" :placeholder="'Enter '+ field.field_name" :class="{'is-invalid': errors[field.field_name]}" v-model="field.asset_attribute_value.field_value" @blur="updateAssetParameters(field)" />
                                        <input v-else type="text" class="form-control" :placeholder="'Enter '+ field.field_name" :class="{'is-invalid': errors[field.field_name]}" v-model="field.field_value" @blur="updateAssetParameters(field)" />
                                        <span v-if="errors[field.field_name]" class="invalid-feedback">{{ errors[field.field_name][0] }}</span>
                                    </div>
                                    
                                    <div v-if="field.field_type=='Number'">
                                        <label  class="form-label">{{field.field_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <input v-if="field.asset_attribute_value" type="text" class="form-control" min="0" oninput="validity.valid||(value='');" :placeholder="'Enter '+ field.field_name" :class="{'is-invalid': errors[field.field_name]}" v-model="field.asset_attribute_value.field_value" @blur="updateAssetParameters(field)" />
                                        <input v-else type="text" class="form-control" min="0" oninput="validity.valid||(value='');" :placeholder="'Enter '+ field.field_name" :class="{'is-invalid': errors[field.field_name]}" v-model="field.field_value" @blur="updateAssetParameters(field)" />
                                        <span v-if="errors[field.field_name]" class="invalid-feedback">{{ errors[field.field_name][0] }}</span>
                                    </div>

                                    <div v-if="field.field_type === 'Date'">
                                        <label class="form-label">
                                            {{ field.field_name }}
                                            <span v-if="field.is_required" class="text-danger">*</span>
                                        </label>
                                        <input v-if="field.asset_attribute_value"
                                            type="date" 
                                            class="form-control" 
                                            :placeholder="'Enter ' + field.field_name" 
                                            :class="{'is-invalid': errors[field.field_name]}" 
                                            v-model="field.asset_attribute_value.field_value" 
                                            @blur="updateAssetParameters(field)" 
                                        />
                                        <input  v-else
                                            type="date" 
                                            class="form-control" 
                                            :placeholder="'Enter ' + field.field_name" 
                                            :class="{'is-invalid': errors[field.field_name]}" 
                                            v-model="field.field_value" 
                                            @blur="updateAssetParameters(field)" 
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
                                        <input v-if="field.asset_attribute_value"
                                            type="datetime-local" 
                                            class="form-control" 
                                            :placeholder="'Enter ' + field.field_name" 
                                            :class="{'is-invalid': errors[field.field_name]}" 
                                            v-model="field.asset_attribute_value.field_value" 
                                            @blur="updateAssetParameters(field)" 
                                            step="1" 
                                        />
                                        <input v-else
                                            type="datetime-local" 
                                            class="form-control" 
                                            :placeholder="'Enter ' + field.field_name" 
                                            :class="{'is-invalid': errors[field.field_name]}" 
                                            v-model="field.field_value" 
                                            @blur="updateAssetParameters(field)" 
                                            step="1" 
                                        />
                                        <span v-if="errors[field.field_name]" class="invalid-feedback">
                                            {{ errors[field.field_name][0] }}
                                        </span>
                                    </div>

                                    <div v-if="field.field_type=='Dropdown'">
                                        <label  class="form-label">{{field.field_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <select v-if="field.asset_attribute_value" class="form-control" :class="{'is-invalid': errors[field.field_name]}" v-model="field.asset_attribute_value.field_value" @change="updateAssetParameters(field)">
                                            <option value="">Select {{field.field_name}}</option>
                                            <option v-for="value, key in field.field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                        </select>
                                        <select v-else class="form-control" :class="{'is-invalid': errors[field.field_name]}" v-model="field.field_value" @change="updateAssetParameters(field)">
                                            <option value="">Select {{field.field_name}}</option>
                                            <option v-for="value, key in field.field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                        </select>
                                        <span v-if="errors[field.field_name]" class="invalid-feedback">{{ errors[field.field_name][0] }}</span>
                                    </div>

                                    <!-- <div v-if="field.field_type=='Color'">
                                        <label class="form-label">{{ field.field_name }}</label>
                                        <span v-if="field.is_required" class="text-danger">*</span>
                                        <div class="input-group">
                                            <span class="input-group-text" :style="{ backgroundColor: selectedColor }" v-if="selectedColor"></span>
                                            <input 
                                                type="text" 
                                                class="form-control" 
                                                :value="selectedColorDisplay" 
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
                                    </div> -->

                                    <div v-if="field.field_type=='Color'">
                                        <label class="form-label">{{ field.display_name }}<span v-if="field.is_required" class="text-danger">*</span></label>
                                            <input v-if="field.asset_attribute_value" type="color" class="form-control" v-model="field.asset_attribute_value.field_value" @change="updateAssetParameters(field)" style="height: 2.2rem;"/>
                                            <input v-else type="color" class="form-control" v-model="field.field_value" @change="updateAssetParameters(field)" style="height: 2.2rem;"/>
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>


                                </div>
                        
                                <!-- <div class="col-md-4">
                                    <label class="form-label">Serial No.</label>
                                    <input type="text" placeholder="Enter Serial No." class="form-control" :class="{'is-invalid':errors.serial_no}" v-model="asset.serial_no" />
                                    <span v-if="errors.serial_no" class="invalid-feedback">{{ errors.serial_no[0] }}</span>
                                </div> -->
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <!-- <router-link type="button" to="/assets" class="btn btn-danger me-2"><i class="ri-arrow-left-line fs-18 lh-1"></i> Back</router-link> -->
                            <!-- <button type="button" class="btn btn-danger me-2" @click.prevent="discard()"><i class="ri-close-line fs-18 lh-1"></i> Discard</button> -->
                            <!-- <button type="submit" class="btn btn-primary">
                                <span ><i class="ri-save-line fs-18 lh-1"></i> Submit</span>
                            </button> -->
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
                // selectedColor: null,
                // selectedColorName: '',
                // dropdownVisible: false,
                // colors: [
                //     { name: 'Green', value: '#008000' },
                //     { name: 'Blue', value: '#0000FF' },
                //     { name: 'Red', value: '#FF0000' },
                //     { name: 'Orange', value: '#FFA500' },
                //     { name: 'Gray', value: '#808080' },
                // ],
                sample: "",
                asset: {
                    asset_id: "",
                    plant_id: "",
                    asset_code: "",
                    asset_name: "",
                    asset_type_id: "",
                    latitude:"",
                    longitude:"",
                    no_of_zones: null,
                    status: "",
                    asset_attributes:[],
                    department_id:'',
                    section_id:'',
                    functional_id:'',
                    radius:'',
                    zone_name:[]
                },
                voltage: {
                    color: "#ffffff",
                    voltage_code: "",
                },
                errors: {
                no_of_zones: []
            },
               
                device_code: "",
                // asset_attributes: [],
                departments:[],
                sections:[],
                functionals:[],
                plants: [],
                asset_types: [],
                show_assets:[],
                errors: [],
                status: true,
                zones: [],
            };
        },
        beforeRouteEnter(to, from, next) {
            next((vm) => {
                vm.asset.asset_attributes = []
                vm.getAssetsDropdown();
                if (to.name == "Assets.Create") {
                    vm.$refs.asset_name.focus();
                } else {
                    vm.status = false;
                    let uri = { uri: "getAsset", data: { asset_id: to.params.asset_id } };
                    vm.$store
                        .dispatch("post", uri)
                        .then(function (response) {
                            vm.asset = response.data.data;
                            console.log("t--",vm.asset)
                            vm.show_assets  = response.data.data?.asset_attributes
                            
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
        


        watch: {
        'asset.no_of_zones': function(newVal) {
            if(this.status){
                if (newVal < 0) {
                    this.asset.zone_name = [];
                } else {
                    this.asset.zone_name = Array.from({ length: newVal }, (_, i) => this.zones[i] || '');
                }
            }
            
        }
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
                let zone_names = Object.entries(this.errors)
                let err = zone_names.filter(function(element){
                    return element[0].includes('zone_name') && element[0].includes(index)
                })
                console.log(err)
                if(err.length){
                    return 'Zone name field is required'
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
            updateAssetParameters(field){
                console.log(field)
                let apid = this.asset.asset_attributes.filter(function(element){
                    return element.asset_attribute_id == field.asset_attribute_id
                })
                if(!apid.length){
                    this.asset.asset_attributes.push({
                        'asset_attribute_id':field.asset_attribute_id,
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
                vm.$store.dispatch('post', { uri: 'getFunctionals' })
                    .then(response => {
                        loader.hide();
                        vm.functionals = response.data.data;
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
                vm.asset = {};
                vm.$refs.asset_name.focus();
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
            },

            getAssetType(asset_type_id){
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                .dispatch("post", { uri: "getAssetsDropdown", data:{asset_type_id:asset_type_id} })
                .then((response) => {
                    loader.hide();
                    vm.show_assets = response.data.data;
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