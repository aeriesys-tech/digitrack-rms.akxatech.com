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
                            <router-link to="/service_attributes">Service Attributes</router-link></li>
                        <li class="breadcrumb-item " aria-current="page" v-if="status">New Service Attribute</li>
                        <li class="breadcrumb-item active" aria-current="page" v-else>Update Service Attribute</li>
                    </ol>
                    <h4 class="main-title mb-0">Service Attribute</h4>
                </div>
                <router-link to="/service_attributes" type="submit" class="btn btn-primary" style="float: right;"><i
                        class="ri-list-check"></i> SERVICE ATTRIBUTES</router-link>
            </div>
            <div class="row">
                <div class="col-12">
                    <form @submit.prevent="submitForm">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">New Service Attribute</h6>
                            <h6 class="card-title" v-else>Update Service Attribute</h6>
                        </div>
                        <div class="card-body ">
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <div class="form-label">
                                        <label class="form-label">Service Type</label><span class="text-danger"> *</span>
                                        <div class="dropdown" @click="toggleServiceTypeStatus()">
                                            <div class="overselect"></div>
                                            <select class="form-control form-control" :class="{'is-invalid':errors?.service_types}">
                                                <option value="">Select Service Type</option>
                                            </select>
                                            <span v-if="errors?.service_types" class="invalid-feedback">{{ errors?.service_types[0] }}</span>
                                        </div>
                                        <div class="multiselect" v-if="service_type_status">
                                            <ul>
                                                <li class="" v-for="(service_type, index) in service_types" :key="index">
                                                    <input type="checkbox" :value="service_type.service_type_id" v-model="service_attribute.service_types" style="padding: 2px;" @click="updateActivityType($event, service_attribute)" />
                                                    <label style="margin-left: 5px;">{{ service_type.service_type_name }}</label>
                                                </li>
                                            </ul>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Field Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Field Name " class="form-control" :class="{'is-invalid':errors?.field_name}" v-model="service_attribute.field_name" />
                                    <span v-if="errors?.field_name" class="invalid-feedback">{{ errors?.field_name[0] }}</span>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Display Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Display Name " class="form-control" :class="{'is-invalid':errors?.display_name}" v-model="service_attribute.display_name"/>
                                    <span v-if="errors?.display_name" class="invalid-feedback">{{ errors?.display_name[0] }}</span>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Field Type</label><span class="text-danger"> *</span>
                                    <!-- <input type="text" placeholder="Field Type" class="form-control" :class="{'is-invalid':errors.field_type}" v-model="service_parameter.field_type" /> -->
                                    <select class="form-control" v-model="service_attribute.field_type" :class="{ 'is-invalid': errors?.field_type }">
                                        <option value="">Select Field Type</option>
                                        <option value="Text">Text </option>
                                        <option value="Dropdown">Dropdown </option>
                                        <option value="Number">Number </option>
                                        <option value="Date">Date </option>
                                        <option value="Date&Time">Date&Time </option>
                                        <option value="Color">Color</option>
                                        <option value="List">List</option>
                                    </select>
                                    <span v-if="errors?.field_type" class="invalid-feedback">{{ errors?.field_type[0] }}</span>
                                </div>
                                <div class="col-md-4" v-if="list_parameters.length">
                                    <label class="form-label">List</label><span class="text-danger"> *</span>
                                    <select class="form-control" v-model="service_attribute.list_parameter_id" :class="{ 'is-invalid': errors?.list_parameter_id }">
                                        <option value="">Select List</option>
                                        <option v-for="list_parameter, key in list_parameters" :key="key" :value="list_parameter.list_parameter_id">{{list_parameter.list_parameter_name}}</option>
                                    </select>
                                    <span v-if="errors?.list_parameter_id" class="invalid-feedback">{{ errors?.list_parameter_id[0] }}</span>
                                </div>
                                <div class="col-md-4" v-if="service_attribute.field_type==='Dropdown'">
                                    <label class="form-label">Field Value</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Field Value" class="form-control" :class="{'is-invalid':errors?.field_values}" v-model="service_attribute.field_values" />
                                    <span v-if="errors?.field_values" class="invalid-feedback">{{ errors?.field_values[0] }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Field Length</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Maximum Characters" class="form-control" v-model="service_attribute.field_length" :class="{'is-invalid':errors?.field_length}" />
                                    <span v-if="errors?.field_length" class="invalid-feedback">{{ errors?.field_length[0] }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Is Required</label><span class="text-danger"> *</span>
                                    <select class="form-control" v-model="service_attribute.is_required" :class="{ 'is-invalid': errors?.is_required }">
                                        <option value="">Select Is Required</option>
                                        <option value="1">Yes </option>
                                        <option value="0">No </option>
                                    </select>
                                    <span v-if="errors?.is_required" class="invalid-feedback">{{ errors?.is_required[0] }}</span>
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
        name: "ServiceAttributes.Create",
        data() {
            return {
                service_attribute: {
                    field_name: '',
                    display_name:'',
                    field_type: '',
                    field_values: '',
                    field_length: '',
                    is_required: "",
                    service_type_id: '',
                    service_types:[],
                    list_parameter_id: '',
                    deleted_service_attribute_types:[],
                },
                deleted_service_attribute_types:[],
                service_types: [],
                service_attributes:[],
                list_parameters:[],
                // user_update: false,
                errors: [],
                status:true,
                service_type_status:false,
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
                    vm.getServiceTypes();
                    if (to.name == "ServiceAttributes.Create") {
                        // vm.$refs.field_name.focus();
                    } else {
                        vm.status = false;
                        let uri = { uri: "getServiceAttribute", data: { service_attribute_id: to.params.service_attribute_id } };
                        vm.$store
                            .dispatch("post", uri)
                            .then(function (response) {
                                vm.service_attribute = response.data.data;
                                vm.service_attribute.deleted_service_attribute_types = []
                            })
                            .catch(function (error) {
                                vm.errors = error.response.data.errors;
                                vm.$store.dispatch("error", error.response.data.message);
                            });
                    }
                });
            },
            watch:{
            'service_attribute.field_type':function(){
                if(this.service_attribute.field_type == 'List'){
                    this.getListParameters()
                }else{
                    this.list_parameters = [];
                    this.service_attribute.list_parameter_id = "";
                }
            }
        },
        methods: {
             updateActivityType(event, activity_type) {
                let vm = this
                const isChecked = event.target.checked;
                let service_attribute_type = activity_type?.service_attribute_types?.filter(function (element) {
                    return element.service_type_id == event.target.value
                })
                if (service_attribute_type?.length) {
                    let service_attribute_type_id = service_attribute_type[0].service_attribute_type_id
                    if (isChecked) {
                        if (vm.service_attribute.deleted_service_attribute_types.includes(service_attribute_type_id)) {
                            let deleted_service_attribute_types = this.service_attribute.deleted_service_attribute_types.filter(function (element) {
                                return element != service_attribute_type_id
                            })
                            vm.service_attribute.deleted_service_attribute_types = deleted_service_attribute_types
                        }
                    } else {
                        if (!vm.service_attribute.deleted_service_attribute_types.includes(service_attribute_type_id)) {
                            vm.service_attribute.deleted_service_attribute_types.push(service_attribute_type_id)
                        }
                    }
                }
            },
            toggleServiceTypeStatus(){
                this.service_type_status = !this.service_type_status
            },
                submitForm() {
                    let vm = this;
                    if (vm.status) {
                        vm.addServiceAttribute();
                    } else {
                        vm.updateServiceAttribute();
                    }
                },
            getServiceTypes() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store.dispatch('post', { uri: 'getServiceTypes' })
                    .then(response => {
                        loader.hide();
                        vm.service_types = response.data.data;
                        console.log(vm.service_types)
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            addServiceAttribute(){
                let vm = this;
                let loader = this.$loading.show();
                this.$store.dispatch('post', { uri: 'addServiceAttribute', data:this.service_attribute })
                    .then(response => {
                        loader.hide();
                        this.$store.dispatch('success',"Service Attribute created successfully");
                        vm.$router.push("/service_attributes");
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            updateServiceAttribute(){
                let vm = this;
                let loader = this.$loading.show();
                this.$store.dispatch('post', { uri: 'updateServiceAttribute', data:this.service_attribute })
                    .then(response => {
                        loader.hide();
                        this.$store.dispatch('success',"Service Attribute updated successfully");
                        this.$router.push('/service_attributes');
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            getListParameters(){
                let vm = this;
                let loader = this.$loading.show();
                vm.$store.dispatch('post', { uri: 'getListParameters' })
                    .then(response => {
                        loader.hide();
                        vm.list_parameters = response.data.data;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });

            },
            getServiceAttribute(){
                let vm = this;
                let loader = this.$loading.show();
                this.$store.dispatch('post', { uri: 'getServiceAttribute', data:this.service_attribute })
                    .then(response => {
                        loader.hide();
                        this.service_attributes = response.data.data;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            discard() {
                    let vm = this;
                    vm.service_attribute.field_name = "";
                    vm.service_attribute.field_type = "";
                    vm.service_attribute.display_name = "";
                    vm.service_attribute.field_values = "";
                    vm.service_attribute.field_length = "";
                    vm.service_attribute.is_required = "";
                    vm.service_attribute.service_type_id = "";
                    vm.service_attribute.list_parameter_id = "";
                    // vm.$refs.field_name.focus();
                    vm.service_attribute.service_types = [],
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
