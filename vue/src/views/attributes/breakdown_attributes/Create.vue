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
                            <router-link to="/break_down_attributes">Break Down Attributes</router-link></li>
                        <li class="breadcrumb-item " aria-current="page" v-if="status">New Break Down Attribute</li>
                        <li class="breadcrumb-item active" aria-current="page" v-else>Update Break Down Attribute</li>
                    </ol>
                    <h4 class="main-title mb-0"> Break Down Attribute</h4>
                </div>
                <router-link to="/break_down_attributes" type="submit" class="btn btn-primary" style="float: right;"><i
                        class="ri-list-check"></i> BREAK DOWN ATTRIBUTES</router-link>
            </div>
            <div class="row">
                <div class="col-12">
                    <form @submit.prevent="submitForm">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">New Break Down Attribute</h6>
                            <h6 class="card-title" v-else>Update Break Down Attribute</h6>
                        </div>
                        <div class="card-body ">
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <div class="form-label">
                                        <label class="form-label">Break Down Type</label><span class="text-danger"> *</span>
                                        <!-- <div class="dropdown" @click="toggleBreakDownTypeStatus()">
                                            <div class="overselect"></div>
                                            <select class="form-control form-control" :class="{'is-invalid':errors.break_down_types}">
                                                <option value="">Select Break Down Type</option>
                                            </select>
                                            <span v-if="errors.break_down_types" class="invalid-feedback">{{ errors.break_down_types[0] }}</span>
                                        </div>
                                        <div class="multiselect" v-if="break_down_type_status">
                                            <ul>
                                                <li class="" v-for="(break_down_type, index) in break_down_types" :key="index">
                                                    <input type="checkbox" :value="break_down_type.break_down_type_id" v-model="break_down_attribute.break_down_types" style="padding: 2px;" @click="updateActivityType($event, break_down_attribute)"/>
                                                    <label style="margin-left: 5px;">{{ break_down_type.break_down_type_name }}</label>
                                                </li>
                                            </ul>
                                        </div> -->
                                        <MultiSelect v-model="break_down_attribute.break_down_types_obj"  filter optionLabel="break_down_type_name"
                                            :options="break_down_types"  placeholder="Select Break Down Type" :maxSelectedLabels="3"
                                            style="width: 100%;; height: 37px;" :style="errors?.break_down_types ? error_style : ''"/>
                                        <span v-if="errors?.break_down_types"><small class="text-danger">{{ errors?.break_down_types[0] }}</small></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Field Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Field Name " class="form-control" :class="{'is-invalid':errors.field_name}" v-model="break_down_attribute.field_name" />
                                    <span v-if="errors.field_name" class="invalid-feedback">{{ errors.field_name[0] }}</span>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Display Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Display Name " class="form-control" :class="{'is-invalid':errors.display_name}" v-model="break_down_attribute.display_name"/>
                                    <span v-if="errors.display_name" class="invalid-feedback">{{ errors.display_name[0] }}</span>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Field Type</label><span class="text-danger"> *</span>
                                    <!-- <input type="text" placeholder="Field Type" class="form-control" :class="{'is-invalid':errors.field_type}" v-model="break_down_parameter.field_type" /> -->
                                    <select class="form-control" v-model="break_down_attribute.field_type" :class="{ 'is-invalid': errors.field_type }">
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
                                    <select class="form-control" v-model="break_down_attribute.list_parameter_id" :class="{ 'is-invalid': errors.list_parameter_id }">
                                        <option value="">Select List</option>
                                        <option v-for="list_parameter, key in list_parameters" :key="key" :value="list_parameter.list_parameter_id">{{list_parameter.list_parameter_name}}</option>
                                    </select>
                                    <span v-if="errors.list_parameter_id" class="invalid-feedback">{{ errors.list_parameter_id[0] }}</span>
                                </div>
                                <div class="col-md-4" v-if="break_down_attribute.field_type==='Dropdown'">
                                    <label class="form-label">Field Value</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Field Value" class="form-control" :class="{'is-invalid':errors.field_values}" v-model="break_down_attribute.field_values" />
                                    <span v-if="errors.field_values" class="invalid-feedback">{{ errors.field_values[0] }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Field Length</label><span class="text-danger"> *</span>
                                    <input type="number" placeholder="Maximum Length" class="form-control" v-model="break_down_attribute.field_length" :class="{'is-invalid':errors.field_length}" />
                                    <span v-if="errors.field_length" class="invalid-feedback">{{ errors.field_length[0] }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Is Required</label><span class="text-danger"> *</span>
                                    <select class="form-control" v-model="break_down_attribute.is_required" :class="{ 'is-invalid': errors.is_required }">
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
    import MultiSelect from 'primevue/multiselect';
    export default {
        components: {
            MultiSelect
            },
        name: "BreakDownAttributes.Create",
        data() {
            return {
                break_down_attribute: {
                    field_name: '',
                    display_name:'',
                    field_type: '',
                    field_values: '',
                    field_length: '',
                    is_required: "",
                    break_down_type_id: '',
                    break_down_types_obj:[],
                    break_down_types:[],
                    list_parameter_id: '',
                    deleted_break_down_attribute_types:[],
                },
                deleted_break_down_attribute_types:[],
                break_down_types: [],
                break_down_attributes:[],
                list_parameters:[],
                // user_update: false,
                errors: [],
                status:true,
                break_down_type_status:false,
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
                    vm.getBreakDownTypes();
                    if (to.name == "BreakDownAttributes.Create") {
                        // vm.$refs.field_name.focus();
                    } else {
                        vm.status = false;
                        let uri = { uri: "getBreakDownAttribute", data: { break_down_attribute_id: to.params.break_down_attribute_id } };
                        vm.$store
                            .dispatch("post", uri)
                            .then(function (response) {
                                vm.break_down_attribute = response.data.data;
                                vm.break_down_attribute.deleted_break_down_attribute_types = []
                                vm.break_down_attribute.break_down_types_obj = []

                                vm.break_down_attribute.break_down_attribute_types.map(function(ele){
                                    vm.break_down_attribute.break_down_types_obj.push({break_down_type_code: ele.break_down_type.break_down_type_code,
                                        break_down_type_id: ele.break_down_type.break_down_type_id, status: ele.break_down_type.status,
                                        break_down_type_name: ele.break_down_type.break_down_type_name})
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
            'break_down_attribute.field_type':function(){
                if(this.break_down_attribute.field_type == 'List'){
                    this.getListParameters()
                }else{
                    this.list_parameters = [];
                    this.break_down_attribute.list_parameter_id = "";
                }
            }
        },
        methods: {
              updateActivityType(event, activity_type) {
                let vm = this
                const isChecked = event.target.checked;
                let break_down_attribute_type = activity_type?.break_down_attribute_types?.filter(function (element) {
                    return element.break_down_type_id == event.target.value
                })
                if (break_down_attribute_type?.length) {
                    let break_down_attribute_type_id = break_down_attribute_type[0].break_down_attribute_type_id
                    if (isChecked) {
                        if (vm.break_down_attribute.deleted_break_down_attribute_types.includes(break_down_attribute_type_id)) {
                            let deleted_break_down_attribute_types = this.break_down_attribute.deleted_break_down_attribute_types.filter(function (element) {
                                return element != break_down_attribute_type_id
                            })
                            vm.break_down_attribute.deleted_break_down_attribute_types = deleted_break_down_attribute_types
                        }
                    } else {
                        if (!vm.break_down_attribute.deleted_break_down_attribute_types.includes(break_down_attribute_type_id)) {
                            vm.break_down_attribute.deleted_break_down_attribute_types.push(break_down_attribute_type_id)
                        }
                    }
                }
            },
            toggleBreakDownTypeStatus(){
                this.break_down_type_status = !this.break_down_type_status
            },
                submitForm() {
                    let vm = this;
                    if (vm.status) {
                        vm.addBreakDownAttribute();
                    } else {
                        vm.updateBreakDownAttribute();
                    }
                },
            getBreakDownTypes() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store.dispatch('post', { uri: 'getBreakDownTypes' })
                    .then(response => {
                        loader.hide();
                        vm.break_down_types = response.data.data;
                        console.log(vm.break_down_types)
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            addBreakDownAttribute(){
                let vm = this;
                let loader = vm.$loading.show();

                vm.break_down_attribute.break_down_types_obj.map(function(ele){
                    vm.break_down_attribute.break_down_types.push(ele.break_down_type_id)
                })

                vm.$store.dispatch('post', { uri: 'addBreakDownAttribute', data:vm.break_down_attribute })
                    .then(response => {
                        loader.hide();
                        vm.$store.dispatch('success',"Break Down Attribute Created Successfully");
                        vm.$router.push("/break_down_attributes");
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            updateBreakDownAttribute(){
                let vm = this;
                let loader = vm.$loading.show();
                vm.break_down_attribute.deleted_break_down_attribute_types = vm.break_down_attribute?.break_down_attribute_types.filter(
                item1 => !vm.break_down_attribute.break_down_types_obj.some(item2 => item1.break_down_type_id === item2.break_down_type_id));
                vm.break_down_attribute.break_down_types = vm.break_down_attribute.break_down_types_obj.map(item => item.break_down_type_id);
                vm.break_down_attribute.deleted_break_down_attribute_types = vm.break_down_attribute.deleted_break_down_attribute_types.map(item => item.break_down_attribute_type_id);
                vm.$store.dispatch('post', { uri: 'updateBreakDownAttribute', data:vm.break_down_attribute })
                    .then(response => {
                        loader.hide();
                        vm.$store.dispatch('success',"Break Down Attribute Updated Successfully");
                        vm.$router.push('/break_down_attributes');
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
            getBreakDownAttribute(){
                let vm = this;
                let loader = this.$loading.show();
                this.$store.dispatch('post', { uri: 'getBreakDownAttribute', data:this.break_down_attribute })
                    .then(response => {
                        loader.hide();
                        this.break_down_attributes = response.data.data;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            discard() {
                let vm = this;
                vm.break_down_attribute.field_name = "";
                vm.break_down_attribute.field_type = "";
                vm.break_down_attribute.display_name = "";
                vm.break_down_attribute.field_values = "";
                vm.break_down_attribute.field_length = "";
                vm.break_down_attribute.is_required = "";
                vm.break_down_attribute.break_down_type_id = "";
                vm.break_down_attribute.list_parameter_id = "";
                // vm.$refs.field_name.focus();
                vm.break_down_attribute.break_down_types = [],
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
