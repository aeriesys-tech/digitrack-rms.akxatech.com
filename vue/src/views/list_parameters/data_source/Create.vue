<template>
    <div class="">
        <div>
            <ol class="breadcrumb fs-sm mb-1">
                <li class="breadcrumb-item">
                    <router-link to="/dashboard">Dashboard</router-link>
                </li>
                <li class="breadcrumb-item">
                    <a href="javascript:void(0)">Masters</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Data Sources</li>
            </ol>
            <h4 class="main-title mb-2">Data Sources</h4>
        </div> 
        <div class="row g-2">
            <div class="col-12" >
                <form @submit.prevent="submitForm()">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">Add Data Source</h6>
                            <h6 class="card-title" v-else>Update Data Source</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Asset Type</label><span class="text-danger"> *</span>
                                        <div class="dropdown" @click="toggleAssetTypeStatus()">
                                            <div class="overselect"></div>
                                            <select class="form-control"  >
                                                <option value="">Select Asset Type</option>
                                            </select>
                                        </div>
                                        <div class="multiselect" v-if="asset_type_status">
                                            <ul>
                                                <li class="" v-for="(asset_type, index) in asset_types" :key="index">
                                                    <input type="checkbox" :value="asset_type.asset_type_id" v-model="data_source.asset_types" style="padding: 2px;" />
                                                    <label style="margin-left: 5px;">{{ asset_type.asset_type_name }}</label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Data Source Types</label><span class="text-danger"> *</span>
                                    <search
                                        :class="{ 'is-invalid': errors.data_source_type_id }"
                                        :customClass="{ 'is-invalid': errors.data_source_type_id }"
                                        :initialize="data_source.data_source_type_id"
                                        id="data_source_type_id"
                                        label="data_source_type_name"
                                        placeholder="Select Data Source Type"
                                        :data=" data_source_types"
                                        @input=" data_source_type => data_source.data_source_type_id = data_source_type"
                                        @selectsearch="getDataSourceTypeFields(data_source.data_source_type_id)"
                                    >
                                    </search>
                                    <span v-if="errors.data_source_type_id"><small class="text-danger">{{ errors.data_source_type_id[0] }}</small></span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Data Source Code</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Data Source Code" class="form-control" :class="{ 'is-invalid': errors.data_source_code }" v-model="data_source.data_source_code"/>
                                    <span v-if="errors.data_source_code" class="invalid-feedback">{{ errors.data_source_code[0] }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Data Source Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Data Source Name" class="form-control" :class="{ 'is-invalid': errors.data_source_name }" v-model="data_source.data_source_name"/>
                                    <span v-if="errors.data_source_name" class="invalid-feedback">{{ errors.data_source_name[0] }}</span>
                                </div>
                                <div class="col-md-4" v-for="field, key in show_data_sources" :key="key">
                                    <div v-if="field.field_type=='Text'">
                                        <label  class="form-label">{{field.display_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <input v-if="field.data_source_attribute_value" type="text" class="form-control" :placeholder="'Enter '+ field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.data_source_attribute_value.field_value" @blur="updateDataSourceParameters(field)" />
                                        <input v-else type="text" class="form-control" :placeholder="'Enter '+ field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.field_value" @blur="updateDataSourceParameters(field)" />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>
                                    
                                    <div v-if="field.field_type=='Number'">
                                        <label  class="form-label">{{field.display_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <input v-if="field.data_source_attribute_value" type="text" class="form-control" min="0" oninput="validity.valid||(value='');" :placeholder="'Enter '+ field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.data_source_attribute_value.field_value" @blur="updateDataSourceParameters(field)" />
                                        <input v-else type="text" class="form-control" min="0" oninput="validity.valid||(value='');" :placeholder="'Enter '+ field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.field_value" @blur="updateDataSourceParameters(field)" />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>

                                    <div v-if="field.field_type === 'Date'">
                                        <label class="form-label">
                                            {{ field.display_name }}
                                            <span v-if="field.is_required" class="text-danger">*</span>
                                        </label>
                                        <input v-if="field.data_source_attribute_value"  type="date" class="form-control" :placeholder="'Enter ' + field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.data_source_attribute_value.field_value" @blur="updateDataSourceParameters(field)" />
                                        <input v-else type="date" class="form-control" :placeholder="'Enter ' + field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.field_value" @blur="updateDataSourceParameters(field)" />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">
                                            {{ errors[field.display_name][0] }}
                                        </span>
                                    </div>

                                    <div v-if="field.field_type === 'Date&Time'">
                                        <label class="form-label">
                                            {{ field.display_name }}
                                            <span v-if="field.is_required" class="text-danger">*</span>
                                        </label>
                                        <input v-if="field.data_source_attribute_value"
                                            type="datetime-local" 
                                            class="form-control" 
                                            :placeholder="'Enter ' + field.display_name" 
                                            :class="{'is-invalid': errors[field.display_name]}" 
                                            v-model="field.data_source_attribute_value.field_value" 
                                            @blur="updateDataSourceParameters(field)" 
                                            step="1" 
                                        />
                                        <input v-else
                                            type="datetime-local" 
                                            class="form-control" 
                                            :placeholder="'Enter ' + field.display_name" 
                                            :class="{'is-invalid': errors[field.display_name]}" 
                                            v-model="field.field_value" 
                                            @blur="updateDataSourceParameters(field)" 
                                            step="1" 
                                        />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">
                                            {{ errors[field.display_name][0] }}
                                        </span>
                                    </div>

                                    <div v-if="field.field_type=='Dropdown'">
                                        <label  class="form-label">{{field.display_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <select v-if="field.data_source_attribute_value" class="form-control" :class="{'is-invalid': errors[field.display_name]}" v-model="field.data_source_attribute_value.field_value" @change="updateDataSourceParameters(field)">
                                            <option value="">Select {{field.display_name}}</option>
                                            <option v-for="value, key in field.field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                        </select>
                                        <select v-else class="form-control" :class="{'is-invalid': errors[field.display_name]}" v-model="field.field_value" @change="updateDataSourceParameters(field)">
                                            <option value="">Select {{field.display_name}}</option>
                                            <option v-for="value, key in field.field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                        </select>
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>
                                    <div v-if="field.field_type=='Color'">
                                        <label class="form-label">{{ field.display_name }}</label>
                                        <span v-if="field.is_required" class="text-danger">*</span>
                                        <div class="input-group">
                                            <span class="input-group-text" :style="{ backgroundColor: field?.data_source_attribute_value?.field_value }"></span>
                                            <input 
                                                type="text" 
                                                class="form-control" 
                                                :value="field?.data_source_attribute_value?.field_value" 
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
            selectedColor: null,
            selectedColorName: '',
            dropdownVisible: false,
            data_sources: [],
            data_source: {
                data_source_id: '',
                data_source_type_id: '',
                data_source_code: '',
                data_source_name: '',
                data_source_attributes:[],
                asset_types:[],
                frequency_id:'',
            },
            colors: [
                    { name: 'Green', value: '#008000' },
                    { name: 'Blue', value: '#0000FF' },
                    { name: 'Red', value: '#FF0000' },
                    { name: 'Orange', value: '#FFA500' },
                    { name: 'Gray', value: '#808080' },
                ],
            status: true,
            errors: [],
            data_source_types: [],
            asset_types:[],
            frequencies:[],
            show_data_sources:[],
            asset_type_status:false,
        }
    },
    
    beforeRouteEnter(to, from, next) {
            next((vm) => {
                vm.getAssetTypes();
                if (to.name == "DataSources.Create") {
                    // vm.$refs.asset_name.focus();
                } else {
                    vm.status = false;
                    let uri = { uri: "getDataSourceData", data: { data_source_id: to.params.data_source_id } };
                    vm.$store
                        .dispatch("post", uri)
                        .then(function (response) {
                            vm.data_source = response.data.data;
                            vm.show_data_sources  = response.data.data?.data_source_attributes

            
                        })
                        .catch(function (error) {
                            vm.errors = error.response.data.errors;
                            vm.$store.dispatch("error", error.response.data.message);
                        });
                }
            });
        },

    methods: {
        selectColor(colorValue, colorName, field) {
                this.selectedColor = colorValue;
                this.selectedColorName = colorName;
                this.dropdownVisible = false;
                // field.field_value = colorValue;
                if(field.data_source_attribute_value){
                    field.data_source_attribute_value.field_value = colorValue;
                }
                else{
                    field.data_source_attribute_value = {
                        field_value : colorValue
                    }
                    field.field_value = colorValue;
                }
                this.updateDataSourceParameters(field);
            },
        toggleAssetTypeStatus(){
            this.asset_type_status = !this.asset_type_status
        },
        toggleDropdown() {
                this.dropdownVisible = !this.dropdownVisible;
            },
        submitForm() {
            let vm = this;
            if (vm.status) {
                vm.addDataSource();
            } else {
                vm.updateDataSource();
            }
        },
      

        addDataSource() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'addDataSource', data: vm.data_source })
                .then(response => {
                    loader.hide();
                    vm.$store.dispatch('success', response.data.message);
                    vm.$router.push("/data_sources");
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
                    vm.getDataSourceTypes();
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


        updateDataSource() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'updateDataSource', data: vm.data_source })
                .then(response => {
                    loader.hide();
                    vm.$store.dispatch('success', response.data.message);
                    vm.$router.push("/data_sources");
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },
        getDataSourceTypes() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'getDataSourceTypes' })
                .then(response => {
                    loader.hide();
                    vm.data_source_types = response.data.data;
                    vm.getFrequencies();
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },
        getDataSourceTypeFields(data_source_type_id){
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                .dispatch("post", { uri: "getDataSourcesDropdown", data:{data_source_type_id:data_source_type_id} })
                .then((response) => {
                    loader.hide();
                    vm.show_data_sources = response.data.data;
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
            },
            updateDataSourceParameters(field){
                console.log("field--",this.data_source)
                if(!this.data_source.data_source_attributes) {
                    this.data_source.data_source_attributes=[]
                }
                console.log(this.data_source)
                let apid = this.data_source.data_source_attributes?.filter(function(element){
                    console.log("ele",element)
                    return element.data_source_attribute_id == field.data_source_attribute_id
                })
                if(!apid.length){
                    this.data_source.data_source_attributes.push({
                        'data_source_attribute_id':field.data_source_attribute_id,
                        'field_value':field.field_value
                    })
                }else{
                    apid[0].data_source_attribute_id = field.data_source_attribute_id
                    apid[0].field_value = field.field_value
                }
            },

        discard() {
            let vm = this;
            vm.data_source.data_source_type_id="";
            vm.data_source.data_source_code = "";
            vm.data_source.data_source_name = "";
            vm.data_source.asset_types = [];
            vm.data_source.frequency_id = "";
            // vm.$refs.data_source_type_id.focus();
            vm.show_data_sources=[];
            vm.data_source.data_source_attributes=[];
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

