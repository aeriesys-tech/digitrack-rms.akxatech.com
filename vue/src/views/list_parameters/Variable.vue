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
                <li class="breadcrumb-item active" aria-current="page">Variables</li>
            </ol>
            <h4 class="main-title mb-2">Variables</h4>
        </div> 
        <div class="row g-2">
            <div class="col-4" >
                <form @submit.prevent="submitForm()">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">Add Variable</h6>
                            <h6 class="card-title" v-else>Update Variable</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-12">
                                    <label class="form-label">Variable Types</label><span class="text-danger"> *</span>
                                    <search
                                        ref="variable_type_id"
                                        :class="{ 'is-invalid': errors.variable_type_id }"
                                        :customClass="{ 'is-invalid': errors.variable_type_id }"
                                        :initialize="variable.variable_type_id"
                                        id="variable_type_id"
                                        label="variable_type_name"
                                        placeholder="Select Variable Type"
                                        :data=" variable_types"
                                        @input=" variable_type => variable.variable_type_id = variable_type"
                                    >
                                    </search>
                                    <span v-if="errors?.variable_type_id"><small class="text-danger">{{ errors.variable_type_id[0] }}</small></span>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Variable Code</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Variable Code" class="form-control" :class="{ 'is-invalid': errors.variable_code }" v-model="variable.variable_code"/>
                                    <span v-if="errors.variable_code" class="invalid-feedback">{{ errors.variable_code[0] }}</span>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Variable Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Variable Name" class="form-control" :class="{ 'is-invalid': errors.variable_name }" v-model="variable.variable_name"/>
                                    <span v-if="errors.variable_name" class="invalid-feedback">{{ errors.variable_name[0] }}</span>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Asset Type</label><span class="text-danger"> *</span>
                                        <div class="dropdown" @click="toggleAssetTypeStatus()">
                                            <div class="overselect"></div>
                                            <select class="form-control"  >
                                                <option value="">Select Asset Type</option>
                                            </select>
                                        </div>
                                        <div class="multiselect" v-if="asset_type_status" >
                                            <ul>
                                                <li class="" v-for="(asset_type, index) in asset_types" :key="index">
                                                    <input type="checkbox" :value="asset_type.asset_type_id" v-model="variable.asset_types" style="padding: 2px;" />
                                                    <label style="margin-left: 5px;">{{ asset_type.asset_type_name }}</label>
                                                </li>
                                            </ul>
                                        </div>
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
            <div :class="column">
                <div class="card card-one">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="card-title">Variables</h6>
                    </div>
                    <div class="card-body">
                        <input class="form-control mb-2" type="text" placeholder="Type keyword and press enter key" v-model="meta.search" @keypress.enter="search()" />
                        <div class="table-responsive table-responsive-sm">
                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                <thead>
                                    <tr class="" style="background-color: #9b9b9b; color: white;">
                                        <th class="text-center">#</th>
                                        <th @click="sort('variable_type_id')">Variable Type
                                            <span>
                                                <i v-if="meta.keyword=='variable_type_id' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='variable_type_id' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span></th>
                                        <th @click="sort('variable_code')">Variable Code
                                            <span>
                                                <i v-if="meta.keyword=='variable_code' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='variable_code' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span></th>
                                        <th @click="sort('variable_name')">Variable Name
                                        
                                            <span>
                                                <i v-if="meta.keyword=='variable_name' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='variable_name' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th @click="sort('asset_type_id')">
                                            Asset Type.
                                            <span>
                                                <i v-if="meta.keyword == 'asset_type_id' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'asset_type_id' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                       
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="variables.length==0">
                                        <td colspan="7" class="text-center">No records found</td>
                                    </tr>
                                    <tr v-for="variable, key in variables" :key="key">
                                        <td class="text-center">{{ meta.from + key }}</td>
                                        <td>{{variable.variable_type?.variable_type_name}}</td>
                                        <td>{{variable.variable_code}}</td>
                                        <td>{{variable.variable_name}}</td>
                                        <td>
                                            <span v-for="asset_type, key in variable.variable_asset_types" :key="key">{{asset_type?.asset_types?.asset_type_name }}, </span>
                                        </td>
                        
                                        <td class="text-center">
                                            <div class="form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" :id="'variable' + variable.variable_id" :checked="variable.status" :value="variable.status" @change="deleteVariable(variable)" />
                                                <label class="custom-control-label" :for="'variable' + variable.variable_id"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" v-if="variable.status" class="text-success me-2" @click="editVariable(variable)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <select class="form-select from-select-sm width-75" v-model="meta.per_page" @change="onPerPageChange">
                                <option>10</option>
                                <option>15</option>
                                <option>20</option>
                                <option>25</option>
                                <option>30</option>
                            </select>
                            <span>Showing {{ meta.from }} to {{ meta.to }} of {{ meta.totalRows }} entries</span>
                            <Pagination :maxPage="meta.maxPage" :totalPages="meta.lastPage" :currentPage="meta.page" @pagechanged="onPageChange" />
                        </div>
                    </div>
                </div>
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
            column:'col-8',
            meta: {
                search: "",
                order_by: "asc",
                keyword: "variable_id",
                per_page: 10,
                totalRows: 0,
                page: 1,
                lastPage: 1,
                from: 1,
                to: 1,
                maxPage: 1,
                trashed: false,
            },
            variables: [],
            variable: {
                variable_id: '',
                variable_type_id: '',
                variable_code: '',
                variable_name: '',
                status: '',
                asset_types: [],
            },
            status: true,
            errors: [],
            variable_types: [],
            asset_types: [],
            asset_type_status:false
        }
    },
    mounted() {
        // this.create_spare = this.$store.getters.permissions.filter(function(element){
        //     return element.ability.ability.includes('spares.create')
        // })
        // if(this.create_spare.length){
        //     this.column = 'col-8'
        // }else{
        //     this.column = 'col-12'
        // }
        this.index();
        this.getVariableTypes();
        this.getAssetTypes();
    },

    methods: {
        submitForm() {
            let vm = this;
            if (vm.status) {
                vm.addVariable();
            } else {
                vm.updateVariable();
            }
        },
        toggleAssetTypeStatus(){
            this.asset_type_status = !this.asset_type_status
        },
        index() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'paginateVariables' , data:vm.meta })
                .then(response => {
                    loader.hide();
                    this.variables = response.data.data;
                    this.meta.totalRows = response.data.meta.total;
                    this.meta.from = response.data.meta.from;
                    this.meta.lastPage = response.data.meta.last_page;
                    this.meta.maxPage = vm.meta.lastPage >= 3 ? 3 : vm.meta.lastPage;
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },

        addVariable() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'addVariable', data: vm.variable })
                .then(response => {
                    loader.hide();
                    this.$store.dispatch('success', response.data.message);
                    this.discard();
                    // this.index();
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
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

        deleteVariable(variable) {
            let vm = this;
            variable.status = variable.status == 1 ? 0 : 1;
            vm.$store
                .dispatch("post", { uri: "deleteVariable", data: variable })
                .then(function (response) {
                    vm.$store.dispatch('success', response.data.message);
                    vm.index();
                })
                .catch(function (error) {
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },

        editVariable(variable) {
            this.variable = variable;
            this.update = true;
            this.status = false;
        },

        updateVariable() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'updateVariable', data: this.variable })
                .then(response => {
                    loader.hide();
                    this.$store.dispatch('success', response.data.message);
                    this.discard();
                    this.index();
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
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },

        onPageChange(page) {
            this.meta.page = page;
            this.index();
        },
        sort(field) {
            this.meta.keyword = field;
            this.meta.order_by = this.meta.order_by == "asc" ? "desc" : "asc";
            this.index();
        },
        discard() {
            let vm = this;
            vm.variable.variable_type_id="";
            vm.variable.variable_code = "";
            vm.variable.variable_name = "";
            vm.$refs.variable_type_id.focus();
            vm.variable.asset_types = [],
            vm.errors = [];
            vm.status = true;
            vm.index();
        },

        search() {
            let vm = this;
            vm.meta.page = 1;
            vm.index();
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

