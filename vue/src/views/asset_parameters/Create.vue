<template>
        <div class="">
            <div class="d-sm-flex align-items-center justify-content-between mb-2">
                <div>
                    <ol class="breadcrumb fs-sm mb-1">
                        <li class="breadcrumb-item" aria-current="page">
                            <router-link to="/dashboard">Dashboard</router-link></li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a href="javascript:void(0)">Attributes</a></li>
                            <li class="breadcrumb-item" aria-current="page">
                            <router-link to="/asset_attributes">Asset Attributes</router-link></li>
                        <li class="breadcrumb-item " aria-current="page" v-if="status">New Asset Attribute</li>
                        <li class="breadcrumb-item active" aria-current="page" v-else>Update Asset Attribute</li>
                    </ol>
                    <h4 class="main-title mb-0">Asset Attribute</h4>
                </div>
                <router-link to="/asset_attributes"  v-can="'assetParameters.create'" type="submit" class="btn btn-primary" style="float: right;"><i
                        class="ri-list-check"></i> ASSET ATTRIBUTES</router-link>
            </div>
            <div class="row">
                <div class="col-12">
                    <form @submit.prevent="submitForm">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">New Asset Attribute</h6>
                            <h6 class="card-title" v-else>Update Asset Attribute</h6>
                        </div>
                        <div class="card-body ">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label">Field Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Field Name " class="form-control" :class="{'is-invalid':errors.field_name}" v-model="asset_parameter.field_name" ref="field_name"/>
                                    <span v-if="errors.field_name" class="invalid-feedback">{{ errors.field_name[0] }}</span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Display Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Display Name " class="form-control" :class="{'is-invalid':errors.display_name}" v-model="asset_parameter.display_name"/>
                                    <span v-if="errors.display_name" class="invalid-feedback">{{ errors.display_name[0] }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Field Type</label><span class="text-danger"> *</span>
                                    <!-- <input type="text" placeholder="Field Type" class="form-control" :class="{'is-invalid':errors.field_type}" v-model="asset_parameter.field_type" /> -->
                                    <select class="form-control" v-model="asset_parameter.field_type" :class="{ 'is-invalid': errors.field_type }">
                                        <option value="">Select Field Type</option>
                                        <option value="Text">Text </option>
                                        <option value="Dropdown">Dropdown </option>
                                        <option value="Number">Number </option>
                                        <option value="Date">Date </option>
                                        <option value="Date&Time">Date&Time </option>
                                        <option value="Color">Color</option>
                                    </select>
                                    <span v-if="errors.field_type" class="invalid-feedback">{{ errors.field_type[0] }}</span>
                                </div> 
                                <div class="col-md-4">
                                    <label class="form-label">Field Value</label>
                                    <input type="text" placeholder="Field Value" class="form-control" :class="{'is-invalid':errors.field_values}" v-model="asset_parameter.field_values" />
                                    <span v-if="errors.field_values" class="invalid-feedback">{{ errors.field_values[0] }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Field Length</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Field Length" class="form-control" v-model="asset_parameter.field_length" :class="{'is-invalid':errors.field_length}" />
                                    <span v-if="errors.field_length" class="invalid-feedback">{{ errors.field_length[0] }}</span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Is Required</label><span class="text-danger"> *</span>
                                    <select class="form-control" v-model="asset_parameter.is_required" :class="{ 'is-invalid': errors.is_required }">
                                        <option value="">Select Is Required</option>
                                        <option value="1">Yes </option>
                                        <option value="0">No </option>
                                    </select>
                                    <span v-if="errors.is_required" class="invalid-feedback">{{ errors.is_required[0] }}</span>
                                </div>
                                <!-- <div class="col-md-6">
                                    <label class="form-label">Asset Type</label><span class="text-danger"> *</span>
                                    <select class="form-control" v-model="asset_parameter.asset_type_id" :class="{ 'is-invalid': errors.asset_type_id }">
                                        <option value="">Select Asset Type</option>
                                        <option v-for="asset_type, key in asset_types" :key="key" :value="asset_type.asset_type_id">{{ asset_type.asset_type_name }}</option>
                                    </select>
                                    <span v-if="errors.asset_type_id" class="invalid-feedback">{{ errors.asset_type_id[0] }}</span>
                                </div> -->

                                <div class="col-md-6">
                                    <div class="form-label">
                                        <!-- <PlantSearch
                                            :class="{ 'is-invalid': errors.plant_id }"
                                            :customClass="{ 'is-invalid': errors.plant_id }"
                                            :initialize="user.plant_id"
                                            id="plant_id"
                                            label="plant_name"
                                            placeholder="Select Plant"
                                            :data="plants"
                                            @input="selectPlant($event)"
                                        /> -->
                                        <label class="form-label">Asset Type</label><span class="text-danger"> *</span>
                                        <div class="dropdown" @click="toggleAssetTypeStatus()">
                                            <div class="overselect"></div>
                                            <select class="form-control form-control">
                                                <option value="">Select Asset Type</option>
                                            </select>
                                        </div>
                                        <div class="multiselect" v-if="asset_type_status">
                                            <ul>
                                                <li class="" v-for="(asset_type, index) in asset_types" :key="index">
                                                    <input type="checkbox" :value="asset_type.asset_type_id" v-model="asset_parameter.asset_types" style="padding: 2px;" />
                                                    <label style="margin-left: 5px;">{{ asset_type.asset_type_name }}</label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                                <button type="button" class="btn btn-danger me-2" @click.prevent="discard()"><i class="ri-close-line fs-18 lh-1"></i> Discard</button>
                                <button type="submit" class="btn  btn-primary" >
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
//      import Search from "@/components/Search.vue";
    export default {
        // components: {
        //         Search,
        //     },
        name: "AssetParameters.Create",
        data() {
            return {
              
                asset_parameter: {
                    field_name: '',
                    display_name:'',
                    field_type: '',
                    field_value: '',
                    field_length: '',
                    is_required: "",
                    asset_type_id: '',
                    asset_types:[],
                },
                asset_types: [],
                asset_parameters:[],
                // user_update: false,
                errors: [],
                status:true,
                asset_type_status:false,
            }
        },
        // beforeRouteEnter(to, from, next) {
        //     next(vm => {
        //         vm.getRoles();
        //         if(to.name == 'UserUpdate'){
        //             vm.user_update = true;
        //             vm.user.user_id = to.params.user_id;
        //             vm.getUser();
        //         }
        //     });
        // },
        beforeRouteEnter(to, from, next) {
                next((vm) => {
                    vm.getAssetTypes();
                    if (to.name == "AssetParameters.Create") {
                        vm.$refs.field_name.focus();
                    } else {
                        vm.status = false;
                        let uri = { uri: "getAssetParameter", data: { asset_parameter_id: to.params.asset_parameter_id } };
                        vm.$store
                            .dispatch("post", uri)
                            .then(function (response) {
                                vm.asset_parameter = response.data.data;
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
                        vm.addAssetParameter();
                    } else {
                        vm.updateAssetParameter();
                    }
                },
            getAssetTypes() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store.dispatch('post', { uri: 'getAssetTypes' })
                    .then(response => {
                        loader.hide();
                        vm.asset_types = response.data.data;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
    
            addAssetParameter(){
                let vm = this;
                let loader = this.$loading.show();
                this.$store.dispatch('post', { uri: 'addAssetParameter', data:this.asset_parameter })
                    .then(response => {
                        loader.hide();
                        this.$store.dispatch('success',response.data.message);
                        vm.$router.push("/asset_parameters");
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
    
            updateAssetParameter(){
                let vm = this;
                let loader = this.$loading.show();
                this.$store.dispatch('post', { uri: 'updateAssetParameter', data:this.asset_parameter })
                    .then(response => {
                        loader.hide();
                        this.$store.dispatch('success',response.data.message);
                        this.$router.push('/asset_parameters');
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
    
            getAssetParameter(){
                let vm = this;
                let loader = this.$loading.show();
                this.$store.dispatch('post', { uri: 'getAssetParameter', data:this.asset_parameter })
                    .then(response => {
                        loader.hide();
                        this.asset_parameters = response.data.data;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            discard() {
                    let vm = this;
                    vm.asset_parameter.field_name = "";
                    vm.asset_parameter.field_type = "";
                    vm.asset_parameter.display_name = "";
                    vm.asset_parameter.field_values = "";
                    vm.asset_parameter.field_length = "";
                    vm.asset_parameter.is_required = "";
                    vm.asset_parameter.asset_type_id = "";
                    vm.$refs.field_name.focus();
                    vm.asset_parameter.asset_types = [],
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
    