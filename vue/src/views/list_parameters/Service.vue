<template>
    <div class="">
        <div>
            <ol class="breadcrumb fs-sm mb-1">
                <li class="breadcrumb-item">
                    <router-link to="/dashboard">Dashboard</router-link>
                </li>
                <li class="breadcrumb-item">
                    <a href="javascript:void(0)">List Parameters</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Services</li>
            </ol>
            <h4 class="main-title mb-2">Services</h4>
        </div> 
        <div class="row g-2">
            <div class="col-4" v-can="'services.create'">
                <form @submit.prevent="submitForm()">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">Add Service</h6>
                            <h6 class="card-title" v-else>Update Service</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-12">
                                    <label class="form-label">Service Types</label><span class="text-danger"> *</span>
                                    <search
                                        ref="service_type_id"
                                        :class="{ 'is-invalid': errors.service_type_id }"
                                        :customClass="{ 'is-invalid': errors.service_type_id }"
                                        :initialize="service.service_type_id"
                                        id="service_type_id"
                                        label="service_type_name"
                                        placeholder="Select Service Type"
                                        :data=" service_types"
                                        @input=" service_type => service.service_type_id = service_type"
                                    >
                                    </search>
                                    <span v-if="errors.service_type_id"><small class="text-danger">{{ errors.service_type_id[0] }}</small></span>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Service Code</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Service Code" class="form-control" :class="{ 'is-invalid': errors.service_code }" v-model="service.service_code"/>
                                    <span v-if="errors.service_code" class="invalid-feedback">{{ errors.service_code[0] }}</span>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Service Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Service Name" class="form-control" :class="{ 'is-invalid': errors.service_name }" v-model="service.service_name"/>
                                    <span v-if="errors.service_name" class="invalid-feedback">{{ errors.service_name[0] }}</span>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Frequency</label><span class="text-danger"> *</span>
                                    <select class="form-control" :class="{ 'is-invalid': errors.frequency_id}" v-model="service.frequency_id">
                                        <option value="">Select Frequency</option>
                                        <option v-for="frequency, key in frequencies" :key="key" :value="frequency?.frequency_id">{{ frequency?.frequency_name }}</option>
                                    </select> 
                                    <span v-if="errors.frequency_id" class="invalid-feedback">{{ errors.frequency_id[0] }}</span>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Asset Type</label><span class="text-danger"> *</span>
                                        <div class="dropdown" @click="toggleAssetTypeStatus()">
                                            <div class="overselect"></div>
                                            <select class="form-control">
                                                <option value="">Select Asset Type</option>
                                            </select>
                                        </div>
                                        <div class="multiselect" v-if="asset_type_status">
                                            <ul>
                                                <li class="" v-for="(asset_type, index) in asset_types" :key="index">
                                                    <input type="checkbox" :value="asset_type.asset_type_id" v-model="service.asset_types" style="padding: 2px;" />
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
                        <h6 class="card-title">Services</h6>
                    </div>
                    <div class="card-body">
                        <input class="form-control mb-2" type="text" placeholder="Type keyword and press enter key" v-model="meta.search" @keypress.enter="search()" />
                        <div class="table-responsive table-responsive-sm">
                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                <thead>
                                    <tr class="" style="background-color: #9b9b9b; color: white;">
                                        <th class="text-center">#</th>
                                        <th @click="sort('service_type_id')">Service Type
                                            <span>
                                                <i v-if="meta.keyword=='service_type_id' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='service_type_id' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span></th>
                                        <th @click="sort('service_code')">Service Code
                                            <span>
                                                <i v-if="meta.keyword=='service_code' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='service_code' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span></th>
                                        <th @click="sort('service_name')">Service Name
                                        
                                            <span>
                                                <i v-if="meta.keyword=='service_name' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='service_name' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
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

                                        <th @click="sort('frequency')">Frequency
                                        <span>
                                            <i v-if="meta.keyword=='order' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                            <i v-else-if="meta.keyword=='order' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                            <i v-else class="fas fa-sort"></i>
                                        </span>
                                    </th>
                                       
                                       
                                        <th class="text-center" v-can="'services.delete'">Status</th>
                                        <th class="text-center" v-can="'services.update'">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="services.length==0">
                                        <td colspan="6" class="text-center">No records found</td>
                                    </tr>
                                    <tr v-for="service, key in services" :key="key">
                                        <td class="text-center">{{ meta.from + key }}</td>
                                        <td>{{service.service_type?.service_type_name}}</td>
                                        <td>{{service.service_code}}</td>
                                        <td>{{service.service_name}}</td>
                                        <td>
                                            <span v-for="asset_type, key in service.service_asset_types" :key="key">{{asset_type?.asset_types?.asset_type_name }}, </span>
                                        </td>
                                        <td>{{service?.frequency?.frequency_name}}</td>
                                        <td class="text-center" v-can="'services.delete'">
                                            <div class="form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" :id="'service' + service.service_id" :checked="service.status" :value="service.status" @change="deleteService(service)" />
                                                <label class="custom-control-label" :for="'service' + service.service_id"></label>
                                            </div>
                                        </td>
                                        <td class="text-center" v-can="'services.update'">
                                            <a href="javascript:void(0)" v-if="service.status" class="text-success me-2" @click="editService(service)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
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
                keyword: "service_id",
                per_page: 10,
                totalRows: 0,
                page: 1,
                lastPage: 1,
                from: 1,
                to: 1,
                maxPage: 1,
                trashed: false,
            },
            services: [],
            service: {
                service_id: '',
                service_type_id: '',
                service_code: '',
                service_name: '',
                status: '',
                asset_types:[],
                frequency_id:'',
            },
            status: true,
            errors: [],
            service_types: [],
            asset_types:[],
            frequencies:[],
            asset_type_status:false,
        }
    },
    mounted() {
        this.create_service = this.$store.getters.permissions.filter(function(element){
            return element.ability.ability.includes('services.create')
        })
        if(this.create_service.length){
            this.column = 'col-8'
        }else{
            this.column = 'col-12'
        }
        this.index();
        this.getServiceTypes();
        this.getAssetTypes();
        this.getFrequencies();
    },

    methods: {
        toggleAssetTypeStatus(){
            this.asset_type_status = !this.asset_type_status
        },
        submitForm() {
            let vm = this;
            if (vm.status) {
                vm.addService();
            } else {
                vm.updateService();
            }
        },
        index() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'paginateServices' , data:vm.meta })
                .then(response => {
                    loader.hide();
                    this.services = response.data.data;
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

        addService() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'addService', data: vm.service })
                .then(response => {
                    loader.hide();
                    this.$store.dispatch('success', response.data.message);
                    this.discard();
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

        getFrequencies() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'getFrequencies' })
                .then(response => {
                    loader.hide();
                    vm.frequencies = response.data.data;
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },

        deleteService(service) {
            let vm = this;
            service.status = service.status == 1 ? 0 : 1;
            vm.$store
                .dispatch("post", { uri: "deleteService", data: service })
                .then(function (response) {
                    vm.$store.dispatch('success', response.data.message);
                    vm.index();
                })
                .catch(function (error) {
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },

        editService(service) {
            this.service = service;
            this.update = true;
            this.status = false;
        },

        updateService() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'updateService', data: this.service })
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
        getServiceTypes() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'getServiceTypes' })
                .then(response => {
                    loader.hide();
                    vm.service_types = response.data.data;
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
            vm.service.service_type_id="";
            vm.service.service_code = "";
            vm.service.service_name = "";
            vm.service.asset_types = [];
            vm.service.frequency_id = "";
            vm.$refs.service_type_id.focus();
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

