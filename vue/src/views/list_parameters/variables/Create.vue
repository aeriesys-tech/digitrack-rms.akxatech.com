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
                    <li class="breadcrumb-item active" aria-current="page">Variables</li>
                </ol>
                <h4 class="main-title mb-0">Variables</h4>
            </div> 
            <router-link to="/variables" type="submit" class="btn btn-primary" style="float: right;"><i
                class="ri-list-check"></i> VARIABLES</router-link>
    </div>
        <div class="row g-2">
            <div class="col-12" >
                <form @submit.prevent="submitForm()">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">Add Variable</h6>
                            <h6 class="card-title" v-else>Update Variable</h6>
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
                                                    <input type="checkbox" :value="asset_type.asset_type_id" v-model="variable.asset_types" style="padding: 2px;" />
                                                    <label style="margin-left: 5px;">{{ asset_type.asset_type_name }}</label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
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
                                    <input type="text" placeholder="Variable Code" class="form-control" :class="{ 'is-invalid': errors.variable_code }" v-model="variable.variable_code"/>
                                    <span v-if="errors.variable_code" class="invalid-feedback">{{ errors.variable_code[0] }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Variable Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Variable Name" class="form-control" :class="{ 'is-invalid': errors.variable_name }" v-model="variable.variable_name"/>
                                    <span v-if="errors.variable_name" class="invalid-feedback">{{ errors.variable_name[0] }}</span>
                                </div>
                                <div class="col-md-4" v-for="field, key in show_variables" :key="key">
                                    <div v-if="field.field_type=='Text'">
                                        <label  class="form-label">{{field.display_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <input v-if="field.variable_attribute_value" type="text" class="form-control" :placeholder="'Enter '+ field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.variable_attribute_value.field_value" @blur="updateVariableParameters(field)" />
                                        <input v-else type="text" class="form-control" :placeholder="'Enter '+ field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.field_value" @blur="updateVariableParameters(field)" />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>
                                    
                                    <div v-if="field.field_type=='Number'">
                                        <label  class="form-label">{{field.display_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <input v-if="field.variable_attribute_value" type="text" class="form-control" min="0" oninput="validity.valid||(value='');" :placeholder="'Enter '+ field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.variable_attribute_value.field_value" @blur="updateVariableParameters(field)" />
                                        <input v-else type="text" class="form-control" min="0" oninput="validity.valid||(value='');" :placeholder="'Enter '+ field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.field_value" @blur="updateVariableParameters(field)" />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>

                                    <div v-if="field.field_type === 'Date'">
                                        <label class="form-label">
                                            {{ field.display_name }}
                                            <span v-if="field.is_required" class="text-danger">*</span>
                                        </label>
                                        <input v-if="field.variable_attribute_value"  type="date" class="form-control" :placeholder="'Enter ' + field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.variable_attribute_value.field_value" @blur="updateVariableParameters(field)" />
                                        <input v-else type="date" class="form-control" :placeholder="'Enter ' + field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.field_value" @blur="updateVariableParameters(field)" />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">
                                            {{ errors[field.display_name][0] }}
                                        </span>
                                    </div>

                                    <div v-if="field.field_type === 'Date&Time'">
                                        <label class="form-label">
                                            {{ field.display_name }}
                                            <span v-if="field.is_required" class="text-danger">*</span>
                                        </label>
                                        <input v-if="field.variable_attribute_value"
                                            type="datetime-local" 
                                            class="form-control" 
                                            :placeholder="'Enter ' + field.display_name" 
                                            :class="{'is-invalid': errors[field.display_name]}" 
                                            v-model="field.variable_attribute_value.field_value" 
                                            @blur="updateVariableParameters(field)" 
                                            step="1" 
                                        />
                                        <input v-else
                                            type="datetime-local" 
                                            class="form-control" 
                                            :placeholder="'Enter ' + field.display_name" 
                                            :class="{'is-invalid': errors[field.display_name]}" 
                                            v-model="field.field_value" 
                                            @blur="updateVariableParameters(field)" 
                                            step="1" 
                                        />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">
                                            {{ errors[field.display_name][0] }}
                                        </span>
                                    </div>

                                    <div v-if="field.field_type=='Dropdown'">
                                        <label  class="form-label">{{field.display_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <select v-if="field.variable_attribute_value" class="form-control" :class="{'is-invalid': errors[field.display_name]}" v-model="field.variable_attribute_value.field_value" @change="updateVariableParameters(field)">
                                            <option value="">Select {{field.display_name}}</option>
                                            <option v-for="value, key in field.field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                        </select>
                                        <select v-else class="form-control" :class="{'is-invalid': errors[field.display_name]}" v-model="field.field_value" @change="updateVariableParameters(field)">
                                            <option value="">Select {{field.display_name}}</option>
                                            <option v-for="value, key in field.field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                        </select>
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>
                                    <div v-if="field.field_type=='Color'">
                                        <label class="form-label">{{ field.display_name }}<span v-if="field.is_required" class="text-danger">*</span></label>
                                            <input v-if="field.variable_attribute_value" type="color" class="form-control" v-model="field.variable_attribute_value.field_value" @change="updateVariableParameters(field)" style="height: 2.2rem;"/>
                                            <input v-else type="color" class="form-control" v-model="field.field_value" @change="updateVariableParameters(field)" style="height: 2.2rem;"/>
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
            variables: [],
            variable: {
                variable_id: '',
                variable_type_id: '',
                variable_code: '',
                variable_name: '',
                variable_attributes:[],
                asset_types:[],
                frequency_id:'',
            },
            status: true,
            errors: [],
            variable_types: [],
            asset_types:[],
            frequencies:[],
            show_variables:[],
            asset_type_status:false,
        }
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
                            vm.show_variables  = response.data.data?.variable_attributes
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
                vm.addVariable();
            } else {
                vm.updateVariable();
            }
        },
      

        addVariable() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'addVariable', data: vm.variable })
                .then(response => {
                    loader.hide();
                    vm.$store.dispatch('success', response.data.message);
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
            vm.$store.dispatch('post', { uri: 'getAssetTypes' })
                .then(response => {
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


        updateVariable() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'updateVariable', data: vm.variable })
                .then(response => {
                    loader.hide();
                    vm.$store.dispatch('success', response.data.message);
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
            vm.$store.dispatch('post', { uri: 'getVariableTypes' })
                .then(response => {
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
        getVariableTypeFields(variable_type_id){
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                .dispatch("post", { uri: "getVariablesDropdown", data:{variable_type_id:variable_type_id} })
                .then((response) => {
                    loader.hide();
                    vm.show_variables = response.data.data;
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
            },
            updateVariableParameters(field){
                console.log("field--",this.variable)
                if(!this.variable.variable_attributes) {
                    this.variable.variable_attributes=[]
                }
                console.log(this.variable)
                let apid = this.variable.variable_attributes?.filter(function(element){
                    console.log("ele",element)
                    return element.variable_attribute_id == field.variable_attribute_id
                })
                if(!apid.length){
                    this.variable.variable_attributes.push({
                        'variable_attribute_id':field.variable_attribute_id,
                        'field_value':field.field_value
                    })
                }else{
                    apid[0].variable_attribute_id = field.variable_attribute_id
                    apid[0].field_value = field.field_value
                }
            },

        discard() {
            let vm = this;
            vm.variable.variable_type_id="";
            vm.variable.variable_code = "";
            vm.variable.variable_name = "";
            vm.variable.asset_types = [];
            vm.variable.frequency_id = "";
            // vm.$refs.variable_type_id.focus();
            vm.show_variables=[];
            vm.variable.variable_attributes=[];
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

