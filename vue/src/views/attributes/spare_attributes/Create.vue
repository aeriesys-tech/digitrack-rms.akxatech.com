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
                            <router-link to="/spare_attributes">Spare Attributes</router-link></li>
                        <li class="breadcrumb-item " aria-current="page" v-if="status">New Spare Attribute</li>
                        <li class="breadcrumb-item active" aria-current="page" v-else>Update Spare Attribute</li>
                    </ol>
                    <h4 class="main-title mb-0">Spare Attribute</h4>
                </div>
                <router-link to="/spare_attributes" type="submit" class="btn btn-primary" style="float: right;"><i
                        class="ri-list-check"></i> SPARE ATTRIBUTES</router-link>
            </div>
            <div class="row">
                <div class="col-12">
                    <form @submit.prevent="submitForm">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">New Spare Attribute</h6>
                            <h6 class="card-title" v-else>Update Spare Attribute</h6>
                        </div>
                        <div class="card-body ">
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <div class="form-label">
                                        <label class="form-label">Spare Type</label><span class="text-danger"> *</span>
                                        <div class="dropdown" @click="toggleSpareTypeStatus()">
                                            <div class="overselect"></div>
                                            <select class="form-control form-control" :class="{'is-invalid':errors.spare_types}">
                                                <option value="">Select Spare Type</option>
                                            </select>
                                            <span v-if="errors.spare_types" class="invalid-feedback">{{ errors.spare_types[0] }}</span>
                                        </div>
                                        <div class="multiselect" v-if="spare_type_status">
                                            <ul>
                                                <li class="" v-for="(spare_type, index) in spare_types" :key="index">
                                                    <input type="checkbox" :value="spare_type.spare_type_id" v-model="spare_attribute.spare_types" style="padding: 2px;" @click="updateActivityType($event, spare_attribute)" />
                                                    <label style="margin-left: 5px;">{{ spare_type.spare_type_name }}</label>
                                                </li>
                                            </ul>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Field Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Field Name " class="form-control" :class="{'is-invalid':errors.field_name}" v-model="spare_attribute.field_name" />
                                    <span v-if="errors.field_name" class="invalid-feedback">{{ errors.field_name[0] }}</span>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Display Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Display Name " class="form-control" :class="{'is-invalid':errors.display_name}" v-model="spare_attribute.display_name"/>
                                    <span v-if="errors.display_name" class="invalid-feedback">{{ errors.display_name[0] }}</span>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Field Type</label><span class="text-danger"> *</span>
                                    <!-- <input type="text" placeholder="Field Type" class="form-control" :class="{'is-invalid':errors.field_type}" v-model="spare_parameter.field_type" /> -->
                                    <select class="form-control" v-model="spare_attribute.field_type" :class="{ 'is-invalid': errors.field_type }">
                                        <option value="">Select Field Type</option>
                                        <option value="Text">Text </option>
                                        <option value="Dropdown">Dropdown </option>
                                        <option value="Number">Number </option>
                                        <option value="Date">Date </option>
                                        <option value="Date&Time">Date&Time </option>
                                        <option value="Color">Color</option>
                                        <option value="List">List</option>
                                    </select>
                                    <span v-if="errors.field_type" class="invalid-feedback">{{ errors.field_type[0] }}</span>
                                </div>
                                <div class="col-md-4" v-if="list_parameters.length">
                                    <label class="form-label">List</label><span class="text-danger"> *</span>
                                    <select class="form-control" v-model="spare_attribute.list_parameter_id" :class="{ 'is-invalid': errors.list_parameter_id }">
                                        <option value="">Select List</option>
                                        <option v-for="list_parameter, key in list_parameters" :key="key" :value="list_parameter.list_parameter_id">{{list_parameter.list_parameter_name}}</option>
                                    </select>
                                    <span v-if="errors.list_parameter_id" class="invalid-feedback">{{ errors.list_parameter_id[0] }}</span>
                                </div>
                                <div class="col-md-4" v-if="spare_attribute.field_type==='Dropdown'">
                                    <label class="form-label">Field Value</label><span  class="text-danger"> *</span>
                                    <input type="text" placeholder="Field Value" class="form-control" :class="{'is-invalid':errors.field_values}" v-model="spare_attribute.field_values" />
                                    <span v-if="errors.field_values" class="invalid-feedback">{{ errors.field_values[0] }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Field Length</label><span class="text-danger"> *</span>
                                    <input type="number" placeholder="Field Length" class="form-control" v-model="spare_attribute.field_length" :class="{'is-invalid':errors.field_length}" />
                                    <span v-if="errors.field_length" class="invalid-feedback">{{ errors.field_length[0] }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Is Required</label><span class="text-danger"> *</span>
                                    <select class="form-control" v-model="spare_attribute.is_required" :class="{ 'is-invalid': errors.is_required }">
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
        name: "SpareAttributes.Create",
        data() {
            return {
                spare_attribute: {
                    field_name: '',
                    display_name:'',
                    field_type: '',
                    field_values: '',
                    field_length: '',
                    is_required: "",
                    spare_type_id: '',
                    spare_types:[],
                    list_parameter_id: "",
                    deleted_spare_attribute_types:[],
                },
                deleted_spare_attribute_types:[],
                spare_types: [],
                spare_attributes:[],
                list_parameters:[],
                // user_update: false,
                errors: [],
                status:true,
                spare_type_status:false,
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
                    vm.getSpareTypes();
                    if (to.name == "SpareAttributes.Create") {
                        // vm.$refs.field_name.focus();
                    } else {
                        vm.status = false;
                        let uri = { uri: "getSpareAttribute", data: { spare_attribute_id: to.params.spare_attribute_id } };
                        vm.$store
                            .dispatch("post", uri)
                            .then(function (response) {
                                vm.spare_attribute = response.data.data;
                                vm.spare_attribute.deleted_spare_attribute_types = []
                            })
                            .catch(function (error) {
                                vm.errors = error.response.data.errors;
                                vm.$store.dispatch("error", error.response.data.message);
                            });
                    }
                });
            },
            watch:{
            'spare_attribute.field_type':function(){
                if(this.spare_attribute.field_type == 'List'){
                    this.getListParameters()
                }else{
                    this.list_parameters = [];
                    this.spare_attribute.list_parameter_id = "";
                }
            }
        },
        methods: {
               updateActivityType(event, activity_type) {
                let vm = this
                const isChecked = event.target.checked;
                let spare_attribute_type = activity_type.spare_attribute_types.filter(function (element) {
                    return element.spare_type_id == event.target.value
                })
                if (spare_attribute_type.length) {
                    let spare_attribute_type_id = spare_attribute_type[0].spare_attribute_type_id
                    if (isChecked) {
                        if (vm.spare_attribute.deleted_spare_attribute_types.includes(spare_attribute_type_id)) {
                            let deleted_spare_attribute_types = this.spare_attribute.deleted_spare_attribute_types.filter(function (element) {
                                return element != spare_attribute_type_id
                            })
                            vm.spare_attribute.deleted_spare_attribute_types = deleted_spare_attribute_types
                        }
                    } else {
                        if (!vm.spare_attribute.deleted_spare_attribute_types.includes(spare_attribute_type_id)) {
                            vm.spare_attribute.deleted_spare_attribute_types.push(spare_attribute_type_id)
                        }
                    }
                }
                 console.log('Checked IDs:', this.spare_attribute.spare_types);
                console.log('Unchecked IDs:', vm.spare_attribute.deleted_spare_attribute_types);
            },
            toggleSpareTypeStatus(){
                this.spare_type_status = !this.spare_type_status
            },
                submitForm() {
                    let vm = this;
                    if (vm.status) {
                        vm.addSpareAttribute();
                    } else {
                        vm.updateSpareAttribute();
                    }
                },
            getSpareTypes() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store.dispatch('post', { uri: 'getSpareTypes' })
                    .then(response => {
                        loader.hide();
                        vm.spare_types = response.data.data;
                        console.log(vm.spare_types)
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
            addSpareAttribute(){
                let vm = this;
                let loader = this.$loading.show();
                vm.$store.dispatch('post', { uri: 'addSpareAttribute', data:vm.spare_attribute })
                    .then(response => {
                        loader.hide();
                        vm.$store.dispatch('success',"Spare Attribute created successfully");
                        vm.$router.push("/spare_attributes");
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            updateSpareAttribute(){
                let vm = this;
                let loader = this.$loading.show();
                vm.$store.dispatch('post', { uri: 'updateSpareAttribute', data:vm.spare_attribute })
                    .then(response => {
                        loader.hide();
                        vm.$store.dispatch('success',"Spare Attribute updated successfully");
                        vm.$router.push('/spare_attributes');
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            getSpareAttribute(){
                let vm = this;
                let loader = this.$loading.show();
                this.$store.dispatch('post', { uri: 'getSpareAttribute', data:this.spare_attribute })
                    .then(response => {
                        loader.hide();
                        this.spare_attributes = response.data.data;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            discard() {
                    let vm = this;
                    vm.spare_attribute.field_name = "";
                    vm.spare_attribute.field_type = "";
                    vm.spare_attribute.display_name = "";
                    vm.spare_attribute.field_values = "";
                    vm.spare_attribute.field_length = "";
                    vm.spare_attribute.is_required = "";
                    vm.spare_attribute.spare_type_id = "";
                    vm.spare_attribute.list_parameter_id = "";
                    // vm.$refs.field_name.focus();
                    vm.spare_attribute.spare_types = [],
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
