<template>
    <div class="">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
                <div>
                    <ol class="breadcrumb fs-sm mb-1">
                        <li class="breadcrumb-item" aria-current="page">
                            <router-link to="/dashboard">Dashboard</router-link>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0)">Masters</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Services</li>
                    </ol>
                    <h4 class="main-title mb-0">Services</h4>
                </div>
                <router-link to="/services/create" class="btn btn-primary" style="float: right;"><i
                        class="ri-list-check"></i> ADD SERVICE</router-link>
            </div>
        <div class="row">

            <div class="col-12">
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
                                        <th >
                                            Asset Type
                                            <span>
                                                <i v-if="meta.keyword == 'asset_type_id' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'asset_type_id' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>

                                        <!-- <th @click="sort('frequency_id')">Frequency
                                        <span>
                                            <i v-if="meta.keyword=='frequency_id' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                            <i v-else-if="meta.keyword=='frequency_id' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                            <i v-else class="fas fa-sort"></i>
                                        </span>
                                    </th> -->


                                        <th class="text-center">Status</th>
                                        <th class="text-center" >Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="services.length==0">
                                        <td colspan="7" class="text-center">No records found</td>
                                    </tr>
                                    <tr v-for="service, key in services" :key="key">
                                        <td class="text-center">{{ meta.from + key }}</td>
                                        <td>{{service.service_type?.service_type_name}}</td>
                                        <td>{{service.service_code}}</td>
                                        <td>{{service.service_name}}</td>
                                        <td>
                                            <span v-for="asset_type, key in service.service_asset_types" :key="key">{{asset_type?.asset_types?.asset_type_name }}, </span>
                                        </td>
                                        <!-- <td>{{service?.frequency?.frequency_name}}</td> -->
                                        <td class="text-center" >
                                            <div class="form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" :id="'service' + service.service_id" :checked="service.status" :value="service.status" @change="deleteService(service)" />
                                                <label class="custom-control-label" :for="'service' + service.service_id"></label>
                                            </div>
                                        </td>
                                        <td class="text-center" >
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
                                <Pagination :maxPage="meta.maxPage" :totalPages="meta.lastPage" :currentPage="parseInt(meta.page)" @pagechanged="onPageChange" />
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
                    search: '',
                    order_by: "desc",
                    keyword: "service_id",
                    per_page: 10,
                    totalRows: 0,
                    page: 1,
                    lastPage: 1,
                    from: 1,
                    maxPage: 1,
                },
                service: {
                    service_id: '',
                },
                services: [],
                errors: [],
                update: false
            }
        },
        beforeRouteEnter(to, from, next) {
            next((vm) => {
                if(from.name != 'Services.Edit'){
                    vm.$store.commit("setCurrentPage", vm.meta.page)
                }else{
                    vm.meta.page = vm.$store.getters.current_page
                }
            });
        },
        mounted() {
            this.index();
        },

        methods: {
            index() {
                let vm = this;
                let loader = this.$loading.show();
                this.$store.dispatch('post', {
                        uri: 'paginateServices',data:vm.meta
                    })
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

            editService(service) {
                this.$store.commit("setCurrentPage", parseInt(this.meta.page))
                this.$router.push("/services/"+service.service_id+"/edit");
            },

            deleteService(service) {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", {uri: "deleteService", data: service})
                    .then((response) => {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
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
