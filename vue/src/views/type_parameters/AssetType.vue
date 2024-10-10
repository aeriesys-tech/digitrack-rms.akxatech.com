<template>
    <div class="">
        <div>
            <ol class="breadcrumb fs-sm mb-1">
                <li class="breadcrumb-item">
                    <router-link to="/dashboard">Dashboard</router-link>
                </li>
                <li class="breadcrumb-item">
                    <a href="javascript:void(0)">Type Parameters</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Asset Types</li>
            </ol>
            <h4 class="main-title mb-2">Asset Types</h4>
        </div>
        <div class="row g-2">
            <div class="col-4" v-can="'asset_types.create'">
                <form @submit.prevent="submitForm()">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">Add Asset Type</h6>
                            <h6 class="card-title" v-else>Update Asset Type</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-12">
                                    <label class="form-label">Asset Type Code</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Enter Asset Type Code" class="form-control" :class="{ 'is-invalid': errors.asset_type_code }" v-model="asset_type.asset_type_code" ref="asset_type_code"/>
                                    <span v-if="errors.asset_type_code" class="invalid-feedback">{{ errors.asset_type_code[0] }}</span>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Asset Type Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Enter Asset Type name" class="form-control" :class="{ 'is-invalid': errors.asset_type_name }" v-model="asset_type.asset_type_name"/>
                                    <span v-if="errors.asset_type_name" class="invalid-feedback">{{ errors.asset_type_name[0] }}</span>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Geometry Type</label>
                                    <select class="form-control" :class="{ 'is-invalid': errors?.geometry_type }" v-model="asset_type.geometry_type">
                                        <option value="">Select Geometry Type</option>
                                        <option value="Cylindrical">Cylindrical </option>
                                        <option value="Cubical">Cubical</option>
                                        <option value="Conical">Conical</option>
                                        <option value="Trapezoidal">Trapezoidal</option>
                                        <option value="Others">Others</option>
                                    </select>
                                    <span v-if="errors?.radius" class="invalid-feedback">{{ errors.geometry_type[0] }}</span>
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
                        <h6 class="card-title">Asset Types</h6>
                    </div>
                    <div class="card-body">
                        <input class="form-control mb-2" type="text" placeholder="Type keyword and press enter key" v-model="meta.search" @keypress.enter="search()" />
                        <div class="table-responsive table-responsive-sm">
                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                <thead>
                                    <tr class="" style="background-color: #9b9b9b; color: white;">
                                        <th class="text-center">#</th>
                                        <th @click="sort('asset_type_code')">Asset Type Code
                                            <span>
                                                <i v-if="meta.keyword=='asset_type_code' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='asset_type_code' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span></th>
                                        <th @click="sort('asset_type_name')">Asset Type Name

                                            <span>
                                                <i v-if="meta.keyword=='asset_type_name' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='asset_type_name' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span></th>

                                            <th @click="sort('geometry_type')">Geometry Type
                                            <span>
                                                <i v-if="meta.keyword=='geometry_type' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='geometry_type' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span></th>

                                        <th class="text-center" v-can="'asset_types.delete'">Status</th>
                                        <th class="text-center" v-can="'asset_types.update'">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="asset_types.length==0">
                                        <td colspan="6" class="text-center">No records found</td>
                                    </tr>
                                    <tr v-for="asset_type, key in asset_types" :key="key">
                                        <td class="text-center">{{ meta.from + key }}</td>
                                        <td>{{asset_type.asset_type_code}}</td>
                                        <td>{{ asset_type.asset_type_name }}</td>
                                        <td>{{ asset_type?.geometry_type }}</td>
                                        <td class="text-center" v-can="'asset_types.delete'">
                                            <div class="form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" :id="'asset_type' + asset_type.asset_type_id" :checked="asset_type.status" :value="asset_type.status" @change="deleteAssetType(asset_type)" />
                                                <label class="custom-control-label" :for="'asset_type' + asset_type.asset_type_id"></label>
                                            </div>
                                        </td>
                                        <td class="text-center" v-can="'asset_types.update'">
                                            <a href="javascript:void(0)" v-if="asset_type.status" class="text-success me-2" @click="editAssetType(asset_type)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
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
export default {
    components: {
        Pagination
    },
    data() {
        return {
            meta: {
                search: "",
                order_by: "desc",
                keyword: "asset_type_id",
                per_page: 10,
                totalRows: 0,
                page: 1,
                lastPage: 1,
                from: 1,
                to: 1,
                maxPage: 1,
                trashed: false,
            },
            asset_types: [],
            asset_type: {
                asset_type_id: '',
                asset_type_code: '',
                asset_type_name: '',
                geometry_type:'',
                status: '',
            },
            status: true,
            column:'col-8',
            errors: [],
        }
    },
    mounted() {
        this.create_asset_type = this.$store.getters.permissions.filter(function(element){
            return element.ability.ability.includes('asset_types.create')
        })
        if(this.create_asset_type.length){
            this.column = 'col-8'
        }else{
            this.column = 'col-12'
        }
        this.index();
        this.$refs.asset_type_code.focus();
    },

    methods: {
        submitForm() {
            let vm = this;
            if (vm.status) {
                vm.addAssetType();
            } else {
                vm.updateAssetType();
            }
        },
        index() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'paginateAssetTypes' , data:vm.meta })
                .then(response => {
                    loader.hide();
                    vm.asset_types = response.data.data;
                    vm.meta.totalRows = response.data.meta.total;
                    vm.meta.from = response.data.meta.from;
                    vm.meta.lastPage = response.data.meta.last_page;
                    vm.meta.maxPage = vm.meta.lastPage >= 3 ? 3 : vm.meta.lastPage;
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },

        addAssetType() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'addAssetType', data: vm.asset_type })
                .then(response => {
                    loader.hide();
                    vm.$store.dispatch('success', response.data.message);
                    vm.discard();
                    vm.index();
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },

        deleteAssetType(asset_type) {
            let vm = this;
            asset_type.status = asset_type.status == 1 ? 0 : 1;
            vm.$store
                .dispatch("post", { uri: "deleteAssetType", data: asset_type })
                .then(function (response) {
                    vm.$store.dispatch('success', response.data.message);
                    vm.index();
                })
                .catch(function (error) {
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },

        editAssetType(asset_type) {
            console.log(asset_type)
            this.asset_type = asset_type;
            this.update = true;
            this.status = false;
            this.$refs.asset_type_code.focus();
        },

        updateAssetType() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'updateAssetType', data: vm.asset_type })
                .then(response => {
                    loader.hide();
                    vm.$store.dispatch('success', response.data.message);
                    vm.discard();
                    vm.index();
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
            vm.asset_type.asset_type_code = "";
            vm.asset_type.asset_type_name = "";
            vm.asset_type.geometry_type = "";
            vm.$refs.asset_type_code.focus();
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
