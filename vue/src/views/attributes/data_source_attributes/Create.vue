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
                            <router-link to="/data_source_attributes">Data Source Attributes</router-link></li>
                        <li class="breadcrumb-item " aria-current="page" v-if="status">New Data Source Attribute</li>
                        <li class="breadcrumb-item active" aria-current="page" v-else>Update Data Source Attribute</li>
                    </ol>
                    <h4 class="main-title mb-0">Data Source Attribute</h4>
                </div>
                <router-link to="/data_source_attributes" type="submit" class="btn btn-primary" style="float: right;"><i
                        class="ri-list-check"></i> DATA SOURCE ATTRIBUTES</router-link>
            </div>
            <div class="row">
                <div class="col-12">
                    <form @submit.prevent="submitForm">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">New Data Source Attribute</h6>
                            <h6 class="card-title" v-else>Update Data Source Attribute</h6>
                        </div>
                        <div class="card-body ">
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <div class="form-label">
                                        <label class="form-label">Data Source Type</label><span class="text-danger"> *</span>
                                        <div class="dropdown" @click="toggleDataSourceTypeStatus()">
                                            <div class="overselect"></div>
                                            <select class="form-control form-control" :class="{'is-invalid':errors.data_source_types}">
                                                <option value="">Select Data Source Type</option>
                                            </select>
                                            <span v-if="errors.data_source_types" class="invalid-feedback">{{ errors.data_source_types[0] }}</span>
                                        </div>
                                        <div class="multiselect" v-if="data_source_type_status">
                                            <ul>
                                                <li class="" v-for="(data_source_type, index) in data_source_types" :key="index">
                                                    <input type="checkbox" :value="data_source_type.data_source_type_id" v-model="data_source_attribute.data_source_types" style="padding: 2px;" />
                                                    <label style="margin-left: 5px;">{{ data_source_type.data_source_type_name }}</label>
                                                </li>
                                            </ul>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Field Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Field Name " class="form-control" :class="{'is-invalid':errors.field_name}" v-model="data_source_attribute.field_name" />
                                    <span v-if="errors.field_name" class="invalid-feedback">{{ errors.field_name[0] }}</span>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Display Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Display Name " class="form-control" :class="{'is-invalid':errors.display_name}" v-model="data_source_attribute.display_name"/>
                                    <span v-if="errors.display_name" class="invalid-feedback">{{ errors.display_name[0] }}</span>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Field Type</label><span class="text-danger"> *</span>
                                    <!-- <input type="text" placeholder="Field Type" class="form-control" :class="{'is-invalid':errors.field_type}" v-model="data_source_parameter.field_type" /> -->
                                    <select class="form-control" v-model="data_source_attribute.field_type" :class="{ 'is-invalid': errors.field_type }">
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
                                    <label class="form-label">Field Value</label><span v-if="data_source_attribute.field_type==='Dropdown'" class="text-danger"> *</span>
                                    <input type="text" placeholder="Field Value" class="form-control" :class="{'is-invalid':errors.field_values}" v-model="data_source_attribute.field_values" />
                                    <span v-if="errors.field_values" class="invalid-feedback">{{ errors.field_values[0] }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Field Length</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Field Length" class="form-control" v-model="data_source_attribute.field_length" :class="{'is-invalid':errors.field_length}" />
                                    <span v-if="errors.field_length" class="invalid-feedback">{{ errors.field_length[0] }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Is Required</label><span class="text-danger"> *</span>
                                    <select class="form-control" v-model="data_source_attribute.is_required" :class="{ 'is-invalid': errors.is_required }">
                                        <option value="">Select Is Required</option>
                                        <option value="1">Yes </option>
                                        <option value="0">No </option>
                                    </select>
                                    <span v-if="errors.is_required" class="invalid-feedback">{{ errors.is_required[0] }}</span>
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
        components: {
            },
        name: "DataSourceAttributes.Create",
        data() {
            return {
                data_source_attribute: {
                    field_name: '',
                    display_name:'',
                    field_type: '',
                    field_values: '',
                    field_length: '',
                    is_required: "",
                    data_source_type_id: '',
                    data_source_types:[],
                },
                data_source_types: [],
                data_source_attributes:[],
                // user_update: false,
                errors: [],
                status:true,
                data_source_type_status:false,
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
                    vm.getDataSourceTypes();
                    if (to.name == "DataSourceAttributes.Create") {
                        // vm.$refs.field_name.focus();
                    } else {
                        vm.status = false;
                        let uri = { uri: "getDataSourceAttribute", data: { data_source_attribute_id: to.params.data_source_attribute_id } };
                        vm.$store
                            .dispatch("post", uri)
                            .then(function (response) {
                                vm.data_source_attribute = response.data.data;
                            })
                            .catch(function (error) {
                                vm.errors = error.response.data.errors;
                                vm.$store.dispatch("error", error.response.data.message);
                            });
                    }
                });
            },
        methods: {
            toggleDataSourceTypeStatus(){
                this.data_source_type_status = !this.data_source_type_status
            },
                submitForm() {
                    let vm = this;
                    if (vm.status) {
                        vm.addDataSourceAttribute();
                    } else {
                        vm.updateDataSourceAttribute();
                    }
                },
            getDataSourceTypes() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store.dispatch('post', { uri: 'getDataSourceTypes' })
                    .then(response => {
                        loader.hide();
                        vm.data_source_types = response.data.data;
                        console.log(vm.data_source_types)
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
    
            addDataSourceAttribute(){
                let vm = this;
                let loader = this.$loading.show();
                this.$store.dispatch('post', { uri: 'addDataSourceAttribute', data:this.data_source_attribute })
                    .then(response => {
                        loader.hide();
                        this.$store.dispatch('success',"Data Source Attribute created successfully");
                        vm.$router.push("/data_source_attributes");
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
    
            updateDataSourceAttribute(){
                let vm = this;
                let loader = this.$loading.show();
                this.$store.dispatch('post', { uri: 'updateDataSourceAttribute', data:this.data_source_attribute })
                    .then(response => {
                        loader.hide();
                        this.$store.dispatch('success',"Data Source Attribute updated successfully");
                        this.$router.push('/data_source_attributes');
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
    
            getDataSourceAttribute(){
                let vm = this;
                let loader = this.$loading.show();
                this.$store.dispatch('post', { uri: 'getDataSourceAttribute', data:this.data_source_attribute })
                    .then(response => {
                        loader.hide();
                        this.data_source_attributes = response.data.data;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            discard() {
                    let vm = this;
                    vm.data_source_attribute.field_name = "";
                    vm.data_source_attribute.field_type = "";
                    vm.data_source_attribute.display_name = "";
                    vm.data_source_attribute.field_values = "";
                    vm.data_source_attribute.field_length = "";
                    vm.data_source_attribute.is_required = "";
                    vm.data_source_attribute.data_source_type_id = "";
                    // vm.$refs.field_name.focus();
                    vm.data_source_attribute.data_source_types = [],
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
    