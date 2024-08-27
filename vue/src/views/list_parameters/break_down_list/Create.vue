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
                <li class="breadcrumb-item active" aria-current="page">Break Down Lists</li>
            </ol>
            <h4 class="main-title mb-2">Break Down Lists</h4>
        </div> 
        <div class="row g-2">
            <div class="col-12" >
                <form @submit.prevent="submitForm()">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">Add Break Down List</h6>
                            <h6 class="card-title" v-else>Update Break Down List</h6>
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
                                                    <input type="checkbox" :value="asset_type.asset_type_id" v-model="break_down.asset_types" style="padding: 2px;" />
                                                    <label style="margin-left: 5px;">{{ asset_type.asset_type_name }}</label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Break Down Types</label><span class="text-danger"> *</span>
                                    <search
                                        :class="{ 'is-invalid': errors.break_down_type_id }"
                                        :customClass="{ 'is-invalid': errors.break_down_type_id }"
                                        :initialize="break_down.break_down_type_id"
                                        id="break_down_type_id"
                                        label="break_down_type_name"
                                        placeholder="Select Break Down Type"
                                        :data=" break_down_types"
                                        @input=" break_down_type => break_down.break_down_type_id = break_down_type"
                                        @selectsearch="getBreakDownTypeFields(break_down.break_down_type_id)"
                                    >
                                    </search>
                                    <span v-if="errors.break_down_type_id"><small class="text-danger">{{ errors.break_down_type_id[0] }}</small></span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Break Down List Code</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Break Down List Code" class="form-control" :class="{ 'is-invalid': errors.break_down_list_code }" v-model="break_down.break_down_list_code"/>
                                    <span v-if="errors.break_down_list_code" class="invalid-feedback">{{ errors.break_down_list_code[0] }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Break Down List Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Break Down List Name" class="form-control" :class="{ 'is-invalid': errors.break_down_list_name }" v-model="break_down.break_down_list_name"/>
                                    <span v-if="errors.break_down_list_name" class="invalid-feedback">{{ errors.break_down_list_name[0] }}</span>
                                </div>
                                <div class="col-md-4" v-for="field, key in show_break_downs" :key="key">
                                    <div v-if="field.field_type=='Text'">
                                        <label  class="form-label">{{field.display_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <input v-if="field.break_down_attribute_value" type="text" class="form-control" :placeholder="'Enter '+ field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.break_down_attribute_value.field_value" @blur="updateBreakDownParameters(field)" />
                                        <input v-else type="text" class="form-control" :placeholder="'Enter '+ field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.field_value" @blur="updateBreakDownParameters(field)" />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>
                                    
                                    <div v-if="field.field_type=='Number'">
                                        <label  class="form-label">{{field.display_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <input v-if="field.break_down_attribute_value" type="text" class="form-control" min="0" oninput="validity.valid||(value='');" :placeholder="'Enter '+ field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.break_down_attribute_value.field_value" @blur="updateBreakDownParameters(field)" />
                                        <input v-else type="text" class="form-control" min="0" oninput="validity.valid||(value='');" :placeholder="'Enter '+ field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.field_value" @blur="updateBreakDownParameters(field)" />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>

                                    <div v-if="field.field_type === 'Date'">
                                        <label class="form-label">
                                            {{ field.display_name }}
                                            <span v-if="field.is_required" class="text-danger">*</span>
                                        </label>
                                        <input v-if="field.break_down_attribute_value"  type="date" class="form-control" :placeholder="'Enter ' + field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.break_down_attribute_value.field_value" @blur="updateBreakDownParameters(field)" />
                                        <input v-else type="date" class="form-control" :placeholder="'Enter ' + field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.field_value" @blur="updateBreakDownParameters(field)" />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">
                                            {{ errors[field.display_name][0] }}
                                        </span>
                                    </div>

                                    <div v-if="field.field_type === 'Date&Time'">
                                        <label class="form-label">
                                            {{ field.display_name }}
                                            <span v-if="field.is_required" class="text-danger">*</span>
                                        </label>
                                        <input v-if="field.break_down_attribute_value"
                                            type="datetime-local" 
                                            class="form-control" 
                                            :placeholder="'Enter ' + field.display_name" 
                                            :class="{'is-invalid': errors[field.display_name]}" 
                                            v-model="field.break_down_attribute_value.field_value" 
                                            @blur="updateBreakDownParameters(field)" 
                                            step="1" 
                                        />
                                        <input v-else
                                            type="datetime-local" 
                                            class="form-control" 
                                            :placeholder="'Enter ' + field.display_name" 
                                            :class="{'is-invalid': errors[field.display_name]}" 
                                            v-model="field.field_value" 
                                            @blur="updateBreakDownParameters(field)" 
                                            step="1" 
                                        />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">
                                            {{ errors[field.display_name][0] }}
                                        </span>
                                    </div>

                                    <div v-if="field.field_type=='Dropdown'">
                                        <label  class="form-label">{{field.display_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <select v-if="field.break_down_attribute_value" class="form-control" :class="{'is-invalid': errors[field.display_name]}" v-model="field.break_down_attribute_value.field_value" @change="updateBreakDownParameters(field)">
                                            <option value="">Select {{field.display_name}}</option>
                                            <option v-for="value, key in field.field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                        </select>
                                        <select v-else class="form-control" :class="{'is-invalid': errors[field.display_name]}" v-model="field.field_value" @change="updateBreakDownParameters(field)">
                                            <option value="">Select {{field.display_name}}</option>
                                            <option v-for="value, key in field.field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                        </select>
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>
                                    <div v-if="field.field_type=='Color'">
                                        <label class="form-label">{{ field.display_name }}</label>
                                        <span v-if="field.is_required" class="text-danger">*</span>
                                        <div class="input-group">
                                            <span class="input-group-text" :style="{ backgroundColor: field?.break_down_attribute_value?.field_value }"></span>
                                            <input 
                                                type="text" 
                                                class="form-control" 
                                                :value="field?.break_down_attribute_value?.field_value" 
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
            break_downs: [],
            break_down: {
                break_down_list_id: '',
                break_down_type_id: '',
                break_down_list_code: '',
                break_down_list_name: '',
                break_down_attributes:[],
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
            break_down_types: [],
            asset_types:[],
            frequencies:[],
            show_break_downs:[],
            asset_type_status:false,
        }
    },
    
    beforeRouteEnter(to, from, next) {
            next((vm) => {
                vm.getAssetTypes();
                if (to.name == "BreakDownLists.Create") {
                    // vm.$refs.asset_name.focus();
                } else {
                    vm.status = false;
                    let uri = { uri: "getBreakDownData", data: { break_down_list_id: to.params.break_down_list_id } };
                    vm.$store
                        .dispatch("post", uri)
                        .then(function (response) {
                            vm.break_down = response.data.data;
                            vm.show_break_downs  = response.data.data?.break_down_attributes

            
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
                if(field.break_down_attribute_value){
                    field.break_down_attribute_value.field_value = colorValue;
                }
                else{
                    field.break_down_attribute_value = {
                        field_value : colorValue
                    }
                    field.field_value = colorValue;
                }
                this.updateBreakDownParameters(field);
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
                vm.addBreakDown();
            } else {
                vm.updateBreakDown();
            }
        },
      

        addBreakDown() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'addBreakDownList', data: vm.break_down })
                .then(response => {
                    loader.hide();
                    vm.$store.dispatch('success', response.data.message);
                    vm.$router.push("/break_down_lists");
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
                    vm.getBreakDownTypes();
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


        updateBreakDown() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'updateBreakDownList', data: vm.break_down })
                .then(response => {
                    loader.hide();
                    vm.$store.dispatch('success', response.data.message);
                    vm.$router.push("/break_down_lists");
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },
        getBreakDownTypes() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'getBreakDownTypes' })
                .then(response => {
                    loader.hide();
                    vm.break_down_types = response.data.data;
                    vm.getFrequencies();
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },
        getBreakDownTypeFields(break_down_type_id){
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                .dispatch("post", { uri: "getBreakDownsDropdown", data:{break_down_type_id:break_down_type_id} })
                .then((response) => {
                    loader.hide();
                    vm.show_break_downs = response.data.data;
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
            },
            updateBreakDownParameters(field){
                console.log("field--",this.break_down)
                if(!this.break_down.break_down_attributes) {
                    this.break_down.break_down_attributes=[]
                }
                console.log(this.break_down)
                let apid = this.break_down.break_down_attributes?.filter(function(element){
                    console.log("ele",element)
                    return element.break_down_attribute_id == field.break_down_attribute_id
                })
                if(!apid.length){
                    this.break_down.break_down_attributes.push({
                        'break_down_attribute_id':field.break_down_attribute_id,
                        'field_value':field.field_value
                    })
                }else{
                    apid[0].break_down_attribute_id = field.break_down_attribute_id
                    apid[0].field_value = field.field_value
                }
            },

        discard() {
            let vm = this;
            vm.break_down.break_down_type_id="";
            vm.break_down.break_down_list_code = "";
            vm.break_down.break_down_list_name = "";
            vm.break_down.asset_types = [];
            vm.break_down.frequency_id = "";
            // vm.$refs.break_down_type_id.focus();
            vm.show_break_downs=[];
            vm.break_down.break_down_attributes=[];
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

