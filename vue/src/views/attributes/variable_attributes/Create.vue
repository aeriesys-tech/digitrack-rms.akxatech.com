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
                            <router-link to="/variable_attributes">Variable Attributes</router-link></li>
                        <li class="breadcrumb-item " aria-current="page" v-if="status">New Variable Attribute</li>
                        <li class="breadcrumb-item active" aria-current="page" v-else>Update Variable Attribute</li>
                    </ol>
                    <h4 class="main-title mb-0">Variable Attribute</h4>
                </div>
                <router-link to="/variable_attributes" type="submit" class="btn btn-primary" style="float: right;"><i
                        class="ri-list-check"></i> VARIABLE ATTRIBUTES</router-link>
            </div>
            <div class="row">
                <div class="col-12">
                    <form @submit.prevent="submitForm">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">New Variable Attribute</h6>
                            <h6 class="card-title" v-else>Update Variable Attribute</h6>
                        </div>
                        <div class="card-body ">
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <div class="form-label">
                                        <label class="form-label">Variable Type</label><span class="text-danger"> *</span>
                                        <!-- <div class="dropdown" @click="toggleVariableTypeStatus()">
                                            <div class="overselect"></div>
                                            <select class="form-control form-control" :class="{'is-invalid':errors.variable_types}">
                                                <option value="">Select Variable Type</option>
                                            </select>
                                            <span v-if="errors.variable_types" class="invalid-feedback">{{ errors.variable_types[0] }}</span>
                                        </div>
                                        <div class="multiselect" v-if="variable_type_status">
                                            <ul>
                                                <li class="" v-for="(variable_type, index) in variable_types" :key="index">
                                                    <input type="checkbox" :value="variable_type.variable_type_id" v-model="variable_attribute.variable_types" style="padding: 2px;" @click="updateActivityType($event, variable_attribute)"/>
                                                    <label style="margin-left: 5px;">{{ variable_type.variable_type_name }}</label>
                                                </li>
                                            </ul>
                                        </div> -->
                                        <MultiSelect v-model="variable_attribute.variable_types_obj"  filter optionLabel="variable_type_name"
                                            :options="variable_types"  placeholder="Select Data Source Type" :maxSelectedLabels="3"
                                            style="width: 100%;; height: 37px;" :style="errors?.variable_types ? error_style : ''"/>
                                            <span v-if="errors?.variable_types"><small class="text-danger">{{ errors?.variable_types[0] }}</small></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Field Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Field Name " class="form-control" :class="{'is-invalid':errors.field_name}" v-model="variable_attribute.field_name" />
                                    <span v-if="errors.field_name" class="invalid-feedback">{{ errors.field_name[0] }}</span>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Display Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Display Name " class="form-control" :class="{'is-invalid':errors.display_name}" v-model="variable_attribute.display_name"/>
                                    <span v-if="errors.display_name" class="invalid-feedback">{{ errors.display_name[0] }}</span>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Field Type</label><span class="text-danger"> *</span>
                                    <!-- <input type="text" placeholder="Field Type" class="form-control" :class="{'is-invalid':errors.field_type}" v-model="variable_parameter.field_type" /> -->
                                    <select class="form-control" v-model="variable_attribute.field_type" :class="{ 'is-invalid': errors.field_type }">
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
                                    <select class="form-control" v-model="variable_attribute.list_parameter_id" :class="{ 'is-invalid': errors.list_parameter_id }">
                                        <option value="">Select List</option>
                                        <option v-for="list_parameter, key in list_parameters" :key="key" :value="list_parameter.list_parameter_id">{{list_parameter.list_parameter_name}}</option>
                                    </select>
                                    <span v-if="errors.list_parameter_id" class="invalid-feedback">{{ errors.list_parameter_id[0] }}</span>
                                </div>
                                <div class="col-md-4" v-if="variable_attribute.field_type==='Dropdown'">
                                    <label class="form-label">Field Value</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Field Value" class="form-control" :class="{'is-invalid':errors.field_values}" v-model="variable_attribute.field_values" />
                                    <span v-if="errors.field_values" class="invalid-feedback">{{ errors.field_values[0] }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Field Length</label><span class="text-danger"> *</span>
                                    <input type="number" placeholder="Maximum Length" class="form-control" v-model="variable_attribute.field_length" :class="{'is-invalid':errors.field_length}" />
                                    <span v-if="errors.field_length" class="invalid-feedback">{{ errors.field_length[0] }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Is Required</label><span class="text-danger"> *</span>
                                    <select class="form-control" v-model="variable_attribute.is_required" :class="{ 'is-invalid': errors.is_required }">
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
    import MultiSelect from 'primevue/multiselect';
    export default {
        components: {
            MultiSelect
            },
        name: "VariableAttributes.Create",
        data() {
            return {
                variable_attribute: {
                    field_name: '',
                    display_name:'',
                    field_type: '',
                    field_values: '',
                    field_length: '',
                    is_required: "",
                    variable_type_id: '',
                    variable_types_obj:[],
                    variable_types:[],
                    list_parameter_id: '',
                    deleted_variable_attribute_types:[],
                },
                deleted_variable_attribute_types:[],
                variable_types: [],
                variable_attributes:[],
                list_parameters:[],
                // user_update: false,
                errors: [],
                status:true,
                variable_type_status:false,
                error_style: {
                    'border-color': '#dc3545',
                    'padding-right': 'calc(1.5em + 0.812rem)',
                    'background-image': `url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e")`,
                    'background-repeat': 'no-repeat',
                    'background-position': 'right calc(0.375em + 0.203rem) center',
                    'background-size': 'calc(0.75em + 0.406rem) calc(0.75em + 0.406rem)'
                }
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
                    vm.getVariableTypes();
                    if (to.name == "VariableAttributes.Create") {
                        // vm.$refs.field_name.focus();
                    } else {
                        vm.status = false;
                        let uri = { uri: "getVariableAttribute", data: { variable_attribute_id: to.params.variable_attribute_id } };
                        vm.$store
                            .dispatch("post", uri)
                            .then(function (response) {
                                vm.variable_attribute = response.data.data;
                                vm.variable_attribute.deleted_variable_attribute_types = []
                                vm.variable_attribute.variable_types_obj = []

                                vm.variable_attribute.variable_attribute_types.map(function(ele){
                                    vm.variable_attribute.variable_types_obj.push({variable_type_code: ele.variable_type.variable_type_code,
                                        variable_type_id: ele.variable_type.variable_type_id, status: ele.variable_type.status,
                                        variable_type_name: ele.variable_type.variable_type_name})
                                })

                            })
                            .catch(function (error) {
                                vm.errors = error.response.data.errors;
                                vm.$store.dispatch("error", error.response.data.message);
                            });
                    }
                });
            },
            watch:{
            'variable_attribute.field_type':function(){
                if(this.variable_attribute.field_type == 'List'){
                    this.getListParameters()
                }else{
                    this.list_parameters = [];
                    this.variable_attribute.list_parameter_id = "";
                }
            }
        },
        methods: {
             updateActivityType(event, activity_type) {
                let vm = this
                const isChecked = event.target.checked;
                let variable_attribute_type = activity_type?.variable_attribute_types?.filter(function (element) {
                    return element.variable_type_id == event.target.value
                })
                if (variable_attribute_type?.length) {
                    let variable_attribute_type_id = variable_attribute_type[0].variable_attribute_type_id
                    if (isChecked) {
                        if (vm.variable_attribute.deleted_variable_attribute_types.includes(variable_attribute_type_id)) {
                            let deleted_variable_attribute_types = this.variable_attribute.deleted_variable_attribute_types.filter(function (element) {
                                return element != variable_attribute_type_id
                            })
                            vm.variable_attribute.deleted_variable_attribute_types = deleted_variable_attribute_types
                        }
                    } else {
                        if (!vm.variable_attribute.deleted_variable_attribute_types.includes(variable_attribute_type_id)) {
                            vm.variable_attribute.deleted_variable_attribute_types.push(variable_attribute_type_id)
                        }
                    }
                }
            },

            toggleVariableTypeStatus(){
                this.variable_type_status = !this.variable_type_status
            },
                submitForm() {
                    let vm = this;
                    if (vm.status) {
                        vm.addVariableAttribute();
                    } else {
                        vm.updateVariableAttribute();
                    }
                },
            getVariableTypes() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store.dispatch('post', { uri: 'getVariableTypes' })
                    .then(response => {
                        loader.hide();
                        vm.variable_types = response.data.data;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            addVariableAttribute(){
                let vm = this;
                let loader = vm.$loading.show();

                vm.variable_attribute.variable_types_obj.map(function(ele){
                    vm.variable_attribute.variable_types.push(ele.variable_type_id)
                })

                vm.$store.dispatch('post', { uri: 'addVariableAttribute', data:vm.variable_attribute })
                    .then(response => {
                        loader.hide();
                        vm.$store.dispatch('success',"Variable Attribute Created Successfully");
                        vm.$router.push("/variable_attributes");
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            updateVariableAttribute(){
                let vm = this;
                let loader = vm.$loading.show();

                vm.variable_attribute.deleted_variable_attribute_types = vm.variable_attribute?.variable_attribute_types.filter(
                    item1 => !vm.variable_attribute.variable_types_obj.some(item2 => item1.variable_type_id === item2.variable_type_id));
                vm.variable_attribute.variable_types = vm.variable_attribute.variable_types_obj.map(item => item.variable_type_id);
                vm.variable_attribute.deleted_variable_attribute_types = vm.variable_attribute.deleted_variable_attribute_types.map(item => item.variable_attribute_type_id);

                vm.$store.dispatch('post', { uri: 'updateVariableAttribute', data:vm.variable_attribute })
                    .then(response => {
                        loader.hide();
                        vm.$store.dispatch('success',"Variable Attribute Updated Successfully");
                        vm.$router.push('/variable_attributes');
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            getVariableAttribute(){
                let vm = this;
                let loader = this.$loading.show();
                this.$store.dispatch('post', { uri: 'getVariableAttribute', data:this.variable_attribute })
                    .then(response => {
                        loader.hide();
                        this.variable_attributes = response.data.data;
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
            discard() {
                    let vm = this;
                    vm.variable_attribute.field_name = "";
                    vm.variable_attribute.field_type = "";
                    vm.variable_attribute.display_name = "";
                    vm.variable_attribute.field_values = "";
                    vm.variable_attribute.field_length = "";
                    vm.variable_attribute.is_required = "";
                    vm.variable_attribute.variable_type_id = "";
                    vm.variable_attribute.list_parameter_id = "";
                    // vm.$refs.field_name.focus();
                    vm.variable_attribute.variable_types = [],
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
