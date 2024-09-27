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
            <div class="col-4">
                <form @submit.prevent="submitForm()">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">Add Data Source</h6>
                            <h6 class="card-title" v-else>Update Data Source</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-12">
                                    <label class="form-label">Data Source Types</label><span class="text-danger"> *</span>
                                    <search
                                        ref="data_source_type_id"
                                        :class="{ 'is-invalid': errors.data_source_type_id }"
                                        :customClass="{ 'is-invalid': errors.data_source_type_id }"
                                        :initialize="data_source.data_source_type_id"
                                        id="data_source_type_id"
                                        label="data_source_type_name"
                                        placeholder="Select Data Source Type"
                                        :data=" data_source_types"
                                        @input=" data_source_type => data_source.data_source_type_id = data_source_type"
                                    >
                                    </search>
                                    <span v-if="errors?.data_source_type_id"><small class="text-danger">{{ errors.data_source_type_id[0] }}</small></span>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Data Source Code</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Data Source Code" class="form-control" :class="{ 'is-invalid': errors.data_source_code }" v-model="data_source.data_source_code"/>
                                    <span v-if="errors.data_source_code" class="invalid-feedback">{{ errors.data_source_code[0] }}</span>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Data Source Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Data Source Name" class="form-control" :class="{ 'is-invalid': errors.data_source_name }" v-model="data_source.data_source_name"/>
                                    <span v-if="errors.data_source_name" class="invalid-feedback">{{ errors.data_source_name[0] }}</span>
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
                                                    <input type="checkbox" :value="asset_type.asset_type_id" v-model="data_source.asset_types" style="padding: 2px;" />
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
                        <h6 class="card-title">Data Sources</h6>
                    </div>
                    <div class="card-body">
                        <input class="form-control mb-2" type="text" placeholder="Type keyword and press enter key" v-model="meta.search" @keypress.enter="search()" />
                        <div class="table-responsive table-responsive-sm">
                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                <thead>
                                    <tr class="" style="background-color: #9b9b9b; color: white;">
                                        <th class="text-center">#</th>
                                        <th @click="sort('data_source_type_id')">Data Source Type
                                            <span>
                                                <i v-if="meta.keyword=='data_source_type_id' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='data_source_type_id' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span></th>
                                        <th @click="sort('data_source_code')">Data Source Code
                                            <span>
                                                <i v-if="meta.keyword=='data_source_code' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='data_source_code' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span></th>
                                        <th @click="sort('data_source_name')">Data Source Name

                                            <span>
                                                <i v-if="meta.keyword=='data_source_name' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='data_source_name' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th @click="sort('asset_type_id')">
                                            Asset Type
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
                                    <tr v-if="data_sources.length==0">
                                        <td colspan="6" class="text-center">No records found</td>
                                    </tr>
                                    <tr v-for="data_source, key in data_sources" :key="key">
                                        <td class="text-center">{{ meta.from + key }}</td>
                                        <td>{{data_source.data_source_type?.data_source_type_name}}</td>
                                        <td>{{data_source.data_source_code}}</td>
                                        <td>{{data_source.data_source_name}}</td>
                                        <td>
                                            <span v-for="asset_type, key in data_source.data_source_asset_types" :key="key">{{asset_type?.asset_types?.asset_type_name }}, </span>
                                        </td>

                                        <td class="text-center">
                                            <div class="form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" :id="'data_source' + data_source.data_source_id" :checked="data_source.status" :value="data_source.status" @change="deleteDataSource(data_source)" />
                                                <label class="custom-control-label" :for="'data_source' + data_source.data_source_id"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" v-if="data_source.status" class="text-success me-2" @click="editDataSource(data_source)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
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
                keyword: "data_source_id",
                per_page: 10,
                totalRows: 0,
                page: 1,
                lastPage: 1,
                from: 1,
                to: 1,
                maxPage: 1,
                trashed: false,
            },
            data_sources: [],
            data_source: {
                data_source_id: '',
                data_source_type_id: '',
                data_source_code: '',
                data_source_name: '',
                status: '',
                asset_types: [],
            },
            status: true,
            errors: [],
            data_source_types: [],
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
        this.getDataSourceTypes();
        this.getAssetTypes();
    },

    methods: {
        submitForm() {
            let vm = this;
            if (vm.status) {
                vm.addDataSource();
            } else {
                vm.updateDataSource();
            }
        },
        toggleAssetTypeStatus(){
            this.asset_type_status = !this.asset_type_status
        },
        index() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'paginateDataSources' , data:vm.meta })
                .then(response => {
                    loader.hide();
                    this.data_sources = response.data.data;
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

        addDataSource() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'addDataSource', data: vm.data_source })
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

        deleteDataSource(data_source) {
            let vm = this;
            data_source.status = data_source.status == 1 ? 0 : 1;
            vm.$store
                .dispatch("post", { uri: "deleteDataSource", data: data_source })
                .then(function (response) {
                    vm.$store.dispatch('success', response.data.message);
                    vm.index();
                })
                .catch(function (error) {
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },

        editDataSource(data_source) {
            this.data_source = data_source;
            this.update = true;
            this.status = false;
        },

        updateDataSource() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'updateDataSource', data: this.data_source })
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
        getDataSourceTypes() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'getDataSourceTypes' })
                .then(response => {
                    loader.hide();
                    vm.data_source_types = response.data.data;
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
            vm.data_source.data_source_type_id="";
            vm.data_source.data_source_code = "";
            vm.data_source.data_source_name = "";
            vm.$refs.data_source_type_id.focus();
            vm.data_source.asset_types = [],
            vm.errors = [];
            vm.status = true;
            vm.index();
        },

        search() {
            let vm = this;
            vm.meta.page = 1;
            vm.index();
        },
        onPerPageChange() {
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
