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
                    <li class="breadcrumb-item active" aria-current="page"><router-link to="/services">Services</router-link></li>
                    <li class="breadcrumb-item " aria-current="page" v-if="status">New Service</li>
                    <li class="breadcrumb-item active" aria-current="page" v-else>Update Service</li>
                </ol>
                <h4 class="main-title mb-0">Services</h4>
            </div> 
            <router-link to="/services" type="submit" class="btn btn-primary" style="float: right;"><i
                class="ri-list-check"></i> SERVICES</router-link>
        </div>
        <div class="row g-2">
            <div class="col-12" >
                <form @submit.prevent="submitForm()">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">Add Service</h6>
                            <h6 class="card-title" v-else>Update Service</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Asset Type</label><span class="text-danger"> *</span>
                                        <div class="dropdown" @click="toggleAssetTypeStatus()">
                                            <div class="overselect"></div>
                                            <select class="form-control" :class="{ 'is-invalid': errors.asset_types }" :customClass="{ 'is-invalid': errors.asset_types }" >
                                                <option value="">Select Asset Type</option>
                                            </select>
                                            <span v-if="errors.asset_types"><small class="text-danger">{{ errors.asset_types[0] }}</small></span>
                                        </div>
                                        <div class="multiselect" v-if="asset_type_status">
                                            <ul>
                                                <li class="" v-for="(asset_type, index) in asset_types" :key="index">
                                                    <input type="checkbox" :value="asset_type.asset_type_id" v-model="service.asset_types" style="padding: 2px;" />
                                                    <label style="margin-left: 5px;">{{ asset_type.asset_type_name }}</label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Service Types</label><span class="text-danger"> *</span>
                                    <search
                                        :class="{ 'is-invalid': errors.service_type_id }"
                                        :customClass="{ 'is-invalid': errors.service_type_id }"
                                        :initialize="service.service_type_id"
                                        id="service_type_id"
                                        label="service_type_name"
                                        placeholder="Select Service Type"
                                        :data=" service_types"
                                        @input=" service_type => service.service_type_id = service_type"
                                        @selectsearch="getServiceTypeFields(service.service_type_id)"
                                    >
                                    </search>
                                    <span v-if="errors.service_type_id"><small class="text-danger">{{ errors.service_type_id[0] }}</small></span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Service Code</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Service Code" class="form-control" :class="{ 'is-invalid': errors.service_code }" v-model="service.service_code"/>
                                    <span v-if="errors.service_code" class="invalid-feedback">{{ errors.service_code[0] }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Service Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Service Name" class="form-control" :class="{ 'is-invalid': errors.service_name }" v-model="service.service_name"/>
                                    <span v-if="errors.service_name" class="invalid-feedback">{{ errors.service_name[0] }}</span>
                                </div>

                                <!-- <div class="col-md-4">
                                    <label class="form-label">Frequency</label><span class="text-danger"> *</span>
                                    <select class="form-control" :class="{ 'is-invalid': errors.frequency_id}" v-model="service.frequency_id">
                                        <option value="">Select Frequency</option>
                                        <option v-for="frequency, key in frequencies" :key="key" :value="frequency?.frequency_id">{{ frequency?.frequency_name }}</option>
                                    </select> 
                                    <span v-if="errors.frequency_id" class="invalid-feedback">{{ errors.frequency_id[0] }}</span>
                                </div> -->

                                <div class="col-md-4" v-for="field, key in show_services" :key="key">
                                    <div v-if="field.field_type=='Text'">
                                        <label  class="form-label">{{field.display_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <input v-if="field.service_attribute_value" type="text" class="form-control" :placeholder="'Enter '+ field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.service_attribute_value.field_value" @blur="updateServiceParameters(field)" />
                                        <input v-else type="text" class="form-control" :placeholder="'Enter '+ field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.field_value" @blur="updateServiceParameters(field)" />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>
                                    
                                    <div v-if="field.field_type=='Number'">
                                        <label  class="form-label">{{field.display_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <input v-if="field.service_attribute_value" type="number" class="form-control" min="0" oninput="validity.valid||(value='');" :placeholder="'Enter '+ field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.service_attribute_value.field_value" @blur="updateServiceParameters(field)" />
                                        <input v-else type="number" class="form-control" min="0" oninput="validity.valid||(value='');" :placeholder="'Enter '+ field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.field_value" @blur="updateServiceParameters(field)" />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>

                                    <div v-if="field.field_type === 'Date'">
                                        <label class="form-label">
                                            {{ field.display_name }}
                                            <span v-if="field.is_required" class="text-danger">*</span>
                                        </label>
                                        <input v-if="field.service_attribute_value"  type="date" class="form-control" :placeholder="'Enter ' + field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.service_attribute_value.field_value" @blur="updateServiceParameters(field)" />
                                        <input v-else type="date" class="form-control" :placeholder="'Enter ' + field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.field_value" @blur="updateServiceParameters(field)" />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">
                                            {{ errors[field.display_name][0] }}
                                        </span>
                                    </div>

                                    <div v-if="field.field_type === 'Date&Time'">
                                        <label class="form-label">
                                            {{ field.display_name }}
                                            <span v-if="field.is_required" class="text-danger">*</span>
                                        </label>
                                        <input v-if="field.service_attribute_value"
                                            type="datetime-local" 
                                            class="form-control" 
                                            :placeholder="'Enter ' + field.display_name" 
                                            :class="{'is-invalid': errors[field.display_name]}" 
                                            v-model="field.service_attribute_value.field_value" 
                                            @blur="updateServiceParameters(field)" 
                                            step="1" 
                                        />
                                        <input v-else
                                            type="datetime-local" 
                                            class="form-control" 
                                            :placeholder="'Enter ' + field.display_name" 
                                            :class="{'is-invalid': errors[field.display_name]}" 
                                            v-model="field.field_value" 
                                            @blur="updateServiceParameters(field)" 
                                            step="1" 
                                        />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">
                                            {{ errors[field.display_name][0] }}
                                        </span>
                                    </div>

                                    <div v-if="field.field_type=='Dropdown'">
                                        <label  class="form-label">{{field.display_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <select v-if="field.service_attribute_value" class="form-control" :class="{'is-invalid': errors[field.display_name]}" v-model="field.service_attribute_value.field_value" @change="updateServiceParameters(field)">
                                            <option value="">Select {{field.display_name}}</option>
                                            <option v-for="value, key in field.field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                        </select>
                                        <select v-else class="form-control" :class="{'is-invalid': errors[field.display_name]}" v-model="field.field_value" @change="updateServiceParameters(field)">
                                            <option :value="field.field_value">Select {{field.display_name}}</option>
                                            <option v-for="value, key in field.field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                        </select>
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>
                                    <div v-if="field.field_type=='Color'">
                                        <label class="form-label">{{ field.display_name }}<span v-if="field.is_required" class="text-danger">*</span></label>
                                            <input v-if="field.service_attribute_value" type="color" class="form-control" v-model="field.service_attribute_value.field_value" @change="updateServiceParameters(field)" style="height: 2.2rem;"/>
                                            <input v-else type="color" class="form-control" v-model="field.field_value" @change="updateServiceParameters(field)" style="height: 2.2rem;"/>
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
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
        Pagination, Search
    },
    data() {
        return {
            services: [],
            service: {
                service_id: '',
                service_type_id: '',
                service_code: '',
                service_name: '',
                service_attributes:[],
                asset_types:[],
                frequency_id:'',
            },
            status: true,
            errors: [],
            service_types: [],
            asset_types:[],
            frequencies:[],
            show_services:[],
            asset_type_status:false,
        }
    },
    
    beforeRouteEnter(to, from, next) {
            next((vm) => {
                vm.getAssetTypes();
                if (to.name == "Services.Create") {
                    // vm.$refs.asset_name.focus();
                } else {
                    vm.status = false;
                    let uri = { uri: "getServiceData", data: { service_id: to.params.service_id } };
                    vm.$store
                        .dispatch("post", uri)
                        .then(function (response) {
                            vm.service = response.data.data;
                            vm.show_services  = response.data.data?.service_attributes

            
                        })
                        .catch(function (error) {
                            vm.errors = error.response.data.errors;
                            vm.$store.dispatch("error", error.response.data.message);
                        });
                }
            });
        },

    methods: {
        toggleAssetTypeStatus(){
            this.asset_type_status = !this.asset_type_status
        },
        submitForm() {
            let vm = this;
            if (vm.status) {
                vm.addService();
            } else {
                vm.updateService();
            }
        },
      

        addService() {
            let vm = this;
            let loader = this.$loading.show();
            vm.$store.dispatch('post', { uri: 'addService', data: vm.service })
                .then(response => {
                    loader.hide();
                    vm.$store.dispatch('success', response.data.message);
                    vm.$router.push("/services");
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
            vm.$store.dispatch('post', { uri: 'getAssetTypes' })
                .then(response => {
                    loader.hide();
                    vm.asset_types = response.data.data;
                    vm.getServiceTypes();
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
            vm.$store.dispatch('post', { uri: 'getFrequencies' })
                .then(response => {
                    loader.hide();
                    vm.frequencies = response.data.data;
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },


        updateService() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'updateService', data: vm.service })
                .then(response => {
                    loader.hide();
                    vm.$store.dispatch('success', response.data.message);
                    vm.$router.push("/services");
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },
        getServiceTypes() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'getServiceTypes' })
                .then(response => {
                    loader.hide();
                    vm.service_types = response.data.data;
                    vm.getFrequencies();
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },
        getServiceTypeFields(service_type_id){
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                .dispatch("post", { uri: "getServicesDropdown", data:{service_type_id:service_type_id} })
                .then((response) => {
                    loader.hide();
                    vm.show_services = response.data.data;
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
            },
            updateServiceParameters(field){
                console.log("field--",this.service)
                if(!this.service.service_attributes) {
                    this.service.service_attributes=[]
                }
                console.log(this.service)
                let apid = this.service.service_attributes?.filter(function(element){
                    console.log("ele",element)
                    return element.service_attribute_id == field.service_attribute_id
                })
                if(!apid.length){
                    this.service.service_attributes.push({
                        'service_attribute_id':field.service_attribute_id,
                        'field_value':field.field_value
                    })
                }else{
                    apid[0].service_attribute_id = field.service_attribute_id
                    apid[0].field_value = field.field_value
                }
            },

        discard() {
            let vm = this;
            vm.service.service_type_id="";
            vm.service.service_code = "";
            vm.service.service_name = "";
            vm.service.asset_types = [];
            vm.service.frequency_id = "";
            // vm.$refs.service_type_id.focus();
            vm.show_services=[];
            vm.service.service_attributes=[];
            vm.errors = [];
            vm.status = true;
        },

    }
}
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

