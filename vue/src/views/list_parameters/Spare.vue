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
                <li class="breadcrumb-item active" aria-current="page">Spares</li>
            </ol>
            <h4 class="main-title mb-2">Spares</h4>
        </div> 
        <div class="row g-2">
            <div class="col-4" v-can="'spares.create'">
                <form @submit.prevent="submitForm()">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">Add Spare</h6>
                            <h6 class="card-title" v-else>Update Spare</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-12">
                                    <label class="form-label">Spare Types</label><span class="text-danger"> *</span>
                                    <search
                                        ref="spare_type_id"
                                        :class="{ 'is-invalid': errors.spare_type_id }"
                                        :customClass="{ 'is-invalid': errors.spare_type_id }"
                                        :initialize="spare.spare_type_id"
                                        id="spare_type_id"
                                        label="spare_type_name"
                                        placeholder="Select Spare Type"
                                        :data=" spare_types"
                                        @input=" spare_type => spare.spare_type_id = spare_type"
                                    >
                                    </search>
                                    <span v-if="errors.spare_type_id"><small class="text-danger">{{ errors.spare_type_id[0] }}</small></span>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Spare Code</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Spare Code" class="form-control" :class="{ 'is-invalid': errors.spare_code }" v-model="spare.spare_code"/>
                                    <span v-if="errors.spare_code" class="invalid-feedback">{{ errors.spare_code[0] }}</span>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Spare Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Spare Name" class="form-control" :class="{ 'is-invalid': errors.spare_name }" v-model="spare.spare_name"/>
                                    <span v-if="errors.spare_name" class="invalid-feedback">{{ errors.spare_name[0] }}</span>
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
                                                    <input type="checkbox" :value="asset_type.asset_type_id" v-model="spare.asset_types" style="padding: 2px;" />
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
                        <h6 class="card-title">Spares</h6>
                    </div>
                    <div class="card-body">
                        <input class="form-control mb-2" type="text" placeholder="Type keyword and press enter key" v-model="meta.search" @keypress.enter="search()" />
                        <div class="table-responsive table-responsive-sm">
                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                <thead>
                                    <tr class="" style="background-color: #9b9b9b; color: white;">
                                        <th class="text-center">#</th>
                                        <th @click="sort('spare_type_id')">Spare Type
                                            <span>
                                                <i v-if="meta.keyword=='spare_type_id' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='spare_type_id' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span></th>
                                        <th @click="sort('spare_code')">Spare Code
                                            <span>
                                                <i v-if="meta.keyword=='spare_code' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='spare_code' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span></th>
                                        <th @click="sort('spare_name')">Spare Name
                                        
                                            <span>
                                                <i v-if="meta.keyword=='spare_name' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='spare_name' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
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
                                       
                                        <th class="text-center" v-can="'spares.delete'">Status</th>
                                        <th class="text-center" v-can="'spares.update'">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="spares.length==0">
                                        <td colspan="6" class="text-center">No records found</td>
                                    </tr>
                                    <tr v-for="spare, key in spares" :key="key">
                                        <td class="text-center">{{ meta.from + key }}</td>
                                        <td>{{spare.spare_type?.spare_type_name}}</td>
                                        <td>{{spare.spare_code}}</td>
                                        <td>{{spare.spare_name}}</td>
                                        <td>
                                            <span v-for="asset_type, key in spare.spare_asset_types" :key="key">{{asset_type?.asset_types?.asset_type_name }}, </span>
                                        </td>
                        
                                        <td class="text-center" v-can="'spares.delete'">
                                            <div class="form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" :id="'spare' + spare.spare_id" :checked="spare.status" :value="spare.status" @change="deleteSpare(spare)" />
                                                <label class="custom-control-label" :for="'spare' + spare.spare_id"></label>
                                            </div>
                                        </td>
                                        <td class="text-center" v-can="'spares.update'">
                                            <a href="javascript:void(0)" v-if="spare.status" class="text-success me-2" @click="editSpare(spare)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
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
                keyword: "spare_id",
                per_page: 10,
                totalRows: 0,
                page: 1,
                lastPage: 1,
                from: 1,
                to: 1,
                maxPage: 1,
                trashed: false,
            },
            spares: [],
            spare: {
                spare_id: '',
                spare_type_id: '',
                spare_code: '',
                spare_name: '',
                status: '',
                asset_types: [],
            },
            status: true,
            errors: [],
            spare_types: [],
            asset_types: [],
            asset_type_status:false
        }
    },
    mounted() {
        this.create_spare = this.$store.getters.permissions.filter(function(element){
            return element.ability.ability.includes('spares.create')
        })
        if(this.create_spare.length){
            this.column = 'col-8'
        }else{
            this.column = 'col-12'
        }
        this.index();
        this.getSpareTypes();
        this.getAssetTypes();
    },

    methods: {
        submitForm() {
            let vm = this;
            if (vm.status) {
                vm.addSpare();
            } else {
                vm.updateSpare();
            }
        },
        toggleAssetTypeStatus(){
            this.asset_type_status = !this.asset_type_status
        },
        index() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'paginateSpares' , data:vm.meta })
                .then(response => {
                    loader.hide();
                    this.spares = response.data.data;
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

        addSpare() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'addSpare', data: vm.spare })
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

        deleteSpare(spare) {
            let vm = this;
            spare.status = spare.status == 1 ? 0 : 1;
            vm.$store
                .dispatch("post", { uri: "deleteSpare", data: spare })
                .then(function (response) {
                    vm.$store.dispatch('success', response.data.message);
                    vm.index();
                })
                .catch(function (error) {
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },

        editSpare(spare) {
            this.spare = spare;
            this.update = true;
            this.status = false;
        },

        updateSpare() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'updateSpare', data: this.spare })
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
        getSpareTypes() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'getSpareTypes' })
                .then(response => {
                    loader.hide();
                    vm.spare_types = response.data.data;
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
            vm.spare.spare_type_id="";
            vm.spare.spare_code = "";
            vm.spare.spare_name = "";
            vm.$refs.spare_type_id.focus();
            vm.spare.asset_types = [],
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

