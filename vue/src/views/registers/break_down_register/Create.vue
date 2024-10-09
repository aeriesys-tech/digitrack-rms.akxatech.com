<template>
    <div class="">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div>
                <ol class="breadcrumb fs-sm mb-1">
                    <li class="breadcrumb-item">
                        <router-link to="/dashboard">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0)">Registers</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><router-link to="/break_down_lists">Break Down Registers</router-link></li>
                    <li class="breadcrumb-item " aria-current="page" v-if="status">New Break Down List</li>
                    <li class="breadcrumb-item active" aria-current="page" v-else>Update Break Down List</li>
                </ol>
                <h4 class="main-title mb-0">Break Down Registers</h4>
            </div>
            <router-link to="/break_down_registers" type="submit" class="btn btn-primary" style="float: right;"><i
                class="ri-list-check"></i> BREAK DOWN REGISTERS</router-link>
        </div>
        <div class="row g-2">
            <div class="col-12" >
                <form @submit.prevent="submitForm()">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">Add Break Down Register</h6>
                            <h6 class="card-title" v-else>Update Break Down Register</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <!-- <div class="form-group">
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
                                                    <input type="checkbox" :value="asset_type.asset_type_id" v-model="break_down.asset_types" style="padding: 2px;" />
                                                    <label style="margin-left: 5px;">{{ asset_type.asset_type_name }}</label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div> -->
                                     <label class="form-label">Asset</label><span class="text-danger"> *</span>
                                    <search
                                        :disabled="!status"
                                        :class="{ 'is-invalid': errors.asset_id }"
                                        :customClass="{ 'is-invalid': errors.asset_id }"
                                        :initialize="break_down.asset_id"
                                        id="asset_id"
                                        label="asset_name"
                                        label2="asset_code"
                                        placeholder="Select Asset"
                                        :data="assets"
                                        @input=" asset => break_down.asset_id = asset"
                                    >
                                    </search>
                                    <span v-if="errors.asset_id" class="invalid-feedback">{{ errors.asset_id[0] }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Break Down Types</label><span class="text-danger"> *</span>
                                    <search
                                     :disabled="!status"
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
                                    <label class="form-label">Job Date & Time</label><span class="text-danger"> *</span>
                                    <input type="datetime-local" step="any" placeholder="Break Down List Code" class="form-control" :class="{ 'is-invalid': errors.job_date }" v-model="break_down.job_date" />
                                    <span v-if="errors.job_date" class="invalid-feedback">{{ errors.job_date[0] }}</span>
                                </div>

                                <div class="col-md-4" v-for="field, key in break_down.break_down_attributes" :key="key">
                                    <div v-if="field.field_type=='Text'">
                                        <label class="form-label">{{field.display_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <input type="text" class="form-control" :placeholder="'Enter '+ field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.break_down_attribute_value.field_value"  />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>

                                    <div v-if="field.field_type=='Number'">
                                        <label class="form-label">{{field.display_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <input type="number" class="form-control" min="0" oninput="validity.valid||(value='');" :placeholder="'Enter '+ field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.break_down_attribute_value.field_value" />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>

                                    <div v-if="field.field_type === 'Date'">
                                        <label class="form-label">
                                            {{ field.display_name }}
                                            <span v-if="field.is_required" class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control" :placeholder="'Enter ' + field.display_name" :class="{'is-invalid': errors[field.display_name]}" v-model="field.break_down_attribute_value.field_value" />
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
                                            v-model="field.break_down_attribute_value.field_value"
                                            step="any"
                                        />
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">
                                            {{ errors[field.display_name][0] }}
                                        </span>
                                    </div>

                                    <div v-if="field.field_type=='Dropdown'">
                                        <label  class="form-label">{{field.display_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <select class="form-control" :class="{'is-invalid': errors[field.display_name]}" v-model="field.break_down_attribute_value.field_value">
                                            <option :value="field.break_down_attribute_value.field_value" v-if="field.break_down_attribute_value.field_value">{{field.break_down_attribute_value.field_value}}</option>
                                            <option :value="field.break_down_attribute_value.field_value" v-else>Select {{field.display_name}}</option>
                                            <option v-for="value, key in field.field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                        </select>
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>
                                    <div v-if="field.field_type=='Color'">
                                        <label class="form-label">{{ field.display_name }}<span v-if="field.is_required" class="text-danger">*</span></label>
                                            <input type="color" class="form-control" v-model="field.break_down_attribute_value.field_value" style="height: 2.2rem;"/>
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>
                                    <div v-if="field.field_type=='List'">
                                        <label  class="form-label">{{field.display_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                        <select class="form-control" :class="{'is-invalid': errors[field.display_name]}" v-model="field.break_down_attribute_value.field_value">
                                            <option :value="field.break_down_attribute_value.field_value" v-if="field.break_down_attribute_value.field_value">{{field.break_down_attribute_value.field_value}}</option>
                                            <option :value="field.break_down_attribute_value.field_value" v-else>Select {{field.display_name}}</option>
                                            <option v-for="value, key in field.list_parameter?.field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                        </select>
                                        <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Note</label>
                                    <textarea type="text" rows="2" placeholder="Note" class="form-control" :class="{ 'is-invalid': errors.note }" v-model="break_down.note"></textarea>
                                    <span v-if="errors.note" class="invalid-feedback">{{ errors.note[0] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button v-if="status" type="button" class="btn btn-danger me-2" @click="discard()"><i class="ri-close-line fs-18 lh-1"></i> Discard</button>
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
import moment from "moment";
export default {
    components: {
        Pagination, Search
    },
    data() {
        return {
            break_downs: [],
            break_down: {
                break_down_list_id: '',
                break_down_type_id: '',
                job_date: '',
                note: '',
                break_down_attributes:[],
                asset_types: [],
                assets:[],
                deleted_break_down_attribute_values: [],
            },
            status: true,
            errors: [],
            deleted_break_down_attribute_values: [],
            break_down_types: [],
            asset_types: [],
            assets:[],
            show_break_downs:[],
            asset_type_status:false,
        }
    },

    beforeRouteEnter(to, from, next) {
            next((vm) => {
                vm.getAssets();
                if (to.name == "BreakDownRegister.Create") {
                    // vm.$refs.asset_id.focus();
                } else {
                    vm.status = false;

                    let uri = { uri: "getBreakDownData", data: { break_down_list_id: to.params.break_down_list_id } };
                    vm.$store
                        .dispatch("post", uri)
                        .then(function (response) {
                            vm.break_down = response.data.data;
                            vm.break_down.job_date = moment(vm.break_down.job_date).format("yyyy-MM-DDTHH:mm");
                            vm.break_down.break_down_attributes.map(function (element) {
                                vm.deleted_break_down_attribute_values.push(element.break_down_attribute_value.break_down_attribute_value_id);
                            });
                            vm.break_down.deleted_break_down_attribute_values = [];


                        })
                        .catch(function (error) {
                            vm.errors = error.response.data.errors;
                            vm.$store.dispatch("error", error.response.data.message);
                        });
                }
            });
    },
        mounted() {
        this.break_down.job_date = moment().format("yyyy-MM-DDTHH:mm");
    },

    methods: {
        toggleAssetTypeStatus(){
            this.asset_type_status = !this.asset_type_status
        },
        submitForm() {
            let vm = this;
            if (vm.status) {
                vm.addBreakDown();
            } else {
                vm.updateBreakDown();
            }
        },
        validateFields() {
                let isValid = true;
                this.errors = {};

                if (!this.break_down.asset_id) {
                    this.errors.asset_id = ["Asset is required"];
                    isValid = false;
                }
                if (!this.break_down.break_down_type_id) {
                    this.errors.break_down_type_id = ["Break Down Type is required"];
                    isValid = false;
                }
                if (!this.break_down.job_date) {
                    this.errors.job_date = ["Job Type is required"];
                    isValid = false;
            }

                for (const field of Object.values(this.break_down.break_down_attributes)) {
                    if (field.is_required && !field.break_down_attribute_value.field_value) {
                        this.errors[field.display_name] = [`${field.display_name} is required`];
                        isValid = false;
                    }
                }
                console.log("errors",this.errors)
                return isValid;
            },

        addBreakDown() {
            if (!this.validateFields()) {
                    return;
                }
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'addBreakDownList', data: vm.break_down })
                .then(response => {
                    loader.hide();
                    vm.$store.dispatch('success', response.data.message);
                    vm.$router.push("/break_down_registers");
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },
        getAssets() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'getAssets' })
                .then(response => {
                    loader.hide();
                    vm.assets = response.data.data;
                    vm.getBreakDownTypes();
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },
        updateBreakDown() {
              if (!this.validateFields()) {
                    return;
                }
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'updateBreakDownList', data: vm.break_down })
                .then(response => {
                    loader.hide();
                    vm.$store.dispatch('success', response.data.message);
                    vm.$router.push("/break_down_registers");
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
                if (vm.deleted_break_down_attribute_values.length) {
                    vm.break_down.deleted_break_down_attribute_values = vm.deleted_break_down_attribute_values;
                }
                vm.$store
                .dispatch("post", { uri: "getBreakDownsDropdown", data:{break_down_type_id:break_down_type_id} })
                .then((response) => {
                    loader.hide();
                    vm.break_down.break_down_attributes = response.data.data;
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
            },

        discard() {
            let vm = this;
            vm.break_down.break_down_type_id = "";
            vm.break_down.asset_id = "";
            vm.break_down.job_date = "";
            vm.break_down.note = "";
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
