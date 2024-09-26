<template>
        <div class="">
            <div class="d-sm-flex align-items-center justify-content-between mb-2">
                <div>
                    <ol class="breadcrumb fs-sm mb-1">
                        <li class="breadcrumb-item" aria-current="page">
                            <router-link to="/dashboard">Dashboard</router-link></li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a href="javascript:void(0)">Upcoming Jobs</a></li>
                        <!-- <li class="breadcrumb-item active" aria-current="page">Upcoming Jobs</li> -->
                    </ol>
                    <h4 class="main-title mb-0">Upcoming Jobs</h4>
                </div>
                <!-- <router-link v-can="'users.create'" to="/user/create" class="btn btn-primary" style="float: right;"><i
                        class="ri-list-check"></i> ADD USER</router-link> -->
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title">Upcoming Jobs</h6>
                        </div>
                        <div class="card-body">
                            <!-- <td>Department
                                <span>
                                    <i v-if="meta.keyword=='department_id' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                    <i v-else-if="meta.keyword=='department_id' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                    <i v-else class="fas fa-sort"></i>
                                </span>
                            </td> -->
                            <div class="row">
                                <div class="col-3">
                                    <select class="form-control form-control-sm mb-2" v-model="meta.asset_id" @change="search()">
                                        <option value="">Select Asset</option>
                                        <option v-for="asset, key in assets" :key="key" :value="asset.asset_id">{{ asset.asset_name }}</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <select class="form-control form-control-sm mb-2" v-model="meta.department_id" @change="search()">
                                        <option value="">Select Department</option>
                                        <option v-for="department, key in departments" :key="key" :value="department.department_id">{{ department.department_name }}</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <input class="form-control form-control-sm mb-2" type="text"
                                    placeholder="Type keyword and press enter key" v-model="meta.search" @keypress.enter="search()" />
                                </div>
                            </div>

                            <div class="table-responsive table-responsive-sm">
                                <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                    <thead>
                                        <tr style="background-color:#9b9b9b;color:white;">
                                            <th class="text-center">#</th>
                                            <th>
                                                Department
                                                <span>
                                                    <i v-if="meta.keyword == 'department_id' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword == 'department_id' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span>
                                            </th>
                                            <th @click="sort('service_no')">
                                                Service No.
                                                <span>
                                                    <i v-if="meta.keyword == 'service_no' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword == 'service_no' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span>
                                            </th>
                                            <th @click="sort('service_date')">
                                                Service Date
                                                <span>
                                                    <i v-if="meta.keyword == 'service_date' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword == 'service_date' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span>
                                            </th>
                                            <!-- <th @click="sort('service_cost')">
                                                Service Cost
                                                <span>
                                                    <i v-if="meta.keyword == 'service_cost' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword == 'service_cost' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span>
                                            </th> -->
                                            <th @click="sort('next_service_date')">
                                                Next Service Date
                                                <span>
                                                    <i v-if="meta.keyword == 'next_service_date' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword == 'next_service_date' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span>
                                            </th>
                                            <!-- <th @click="sort('service_id')">
                                                Service Name
                                                <span>
                                                    <i v-if="meta.keyword == 'service_id' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword == 'service_id' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span>
                                            </th> -->

                                            <th @click="sort('asset_id')">
                                                Asset Code
                                                <span>
                                                    <i v-if="meta.keyword == 'asset_id' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword == 'asset_id' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                         <tr v-if="user_services.length==0">
                                              <td colspan="6" class="text-center">No records found</td>
                                        </tr>
                                        <tr v-for="service, key in user_services" :key="key">
                                            <td class="text-center">{{ meta.from + key }}</td>
                                            <td>
                                                {{ service?.asset?.asset_department_ids.map(department => department.department?.department_name).join(', ') }}
                                            </td>
                                            <td>{{service?.service_no}}</td>
                                            <td>{{service?.service_date}}</td>
                                            <!-- <td>{{ service?.service_cost}}</td> -->
                                            <td>{{ service?.next_service_date}}</td>
                                            <!-- <td>{{ service?.service?.service_name }}</td> -->
                                            <td>{{ service.asset?.asset_code }}</td>
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
                    order_by: "asc",
                    keyword: "user_service_id",
                    per_page: 10,
                    totalRows: 0,
                    page: 1,
                    lastPage: 1,
                    from: 1,
                    to: 1,
                    maxPage: 1,
                    trashed: false,
                    department_id:'',
                    asset_id:'',
                },
                user_services: [],
                errors: [],
                departments:[],
                assets:[],
                status: true,
            }
        },
        mounted() {
            this.index();
            this.getDepartments();
            this.getAssets();
        },

        methods: {
            index() {
                let vm = this;
                let loader = this.$loading.show();
                this.$store.dispatch('post', { uri: 'getUpcomingServices', data: vm.meta })
                    .then(response => {
                        loader.hide();
                        this.user_services = response.data.data;
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

            getDepartments() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store.dispatch('post', { uri: 'getDepartments' })
                    .then(response => {
                        loader.hide();
                        vm.departments = response.data.data;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getAssets() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store.dispatch('post', { uri: 'getAssets' })
                    .then(response => {
                        loader.hide();
                        vm.assets = response.data.data;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            search() {
                let vm = this;
                vm.meta.page = 1;
                vm.index();
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
            onPerPageChange() {
            let vm = this;
            vm.meta.page = 1;
            vm.index();
        },

        }
    }
    </script>
