<template>
        <div class="">
            <div class="d-sm-flex align-items-center justify-content-between mb-2">
                <div>
                    <ol class="breadcrumb fs-sm mb-1">
                        <li class="breadcrumb-item" aria-current="page">
                            <router-link to="/dashboard">Dashboard</router-link></li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a href="javascript:void(0)">Deviation Reports</a></li>
                    </ol>
                    <h4 class="main-title mb-0">Deviation Reports</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title">Deviation Reports</h6>
                            <button v-can="'deviation_reports.download'" class="btn btn-success" @click="downloadExcel()"><i class="ri-file-excel-line"></i> DOWNLOAD EXCEL</button>
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
                                <!-- <div class="col-4">
                                    <select class="form-control form-control-sm mb-2" v-model="meta.department_id" @change="search()">
                                        <option value="">Select Department</option>
                                        <option v-for="department, key in departments" :key="key" :value="department.department_id">{{ department.department_name }}</option>
                                    </select>
                                </div> -->
                                 <div class="col-auto">
                                    <label class="form-label">Asset Type</label>
                                    <select class="form-control mb-3" v-model="meta.asset_type_id" @change="getAsset()">
                                        <option value="">Select Asset Type</option>
                                        <option v-for="asset_type, key in asset_types" :key="key" :value="asset_type.asset_type_id">{{ asset_type.asset_type_name }}</option>
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <label class="form-label">Asset</label>
                                    <select class="form-control mb-3" v-model="meta.asset_id" @change="getDepartments()">
                                        <option value="">Select Asset </option>
                                        <option v-for="asset, key in assets" :key="key" :value="asset.asset_id">{{ asset.asset_code}}::{{ asset.asset_name }}</option>
                                    </select>
                                </div>
                                 <div class="col-auto">
                                    <label class="form-label">Department</label>
                                    <select class="form-control mb-3" v-model="meta.department_id" >
                                        <option value="">Select Department</option>
                                        <option v-for="department, key in departments" :key="key" :value="department.department_id">{{ department.department_name }}</option>
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <label class="form-label">Remark Status</label>
                                    <select class="form-control mb-3" v-model="meta.remark_status">
                                        <option value="">Select Remark Status </option>
                                        <option value="All">All</option>
                                        <option value="Active">Active</option>
                                        <option value="Closed">Closed</option>
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <label class="form-label">From Date</label>
                                   <input class="form-control mb-3" type="date" v-model="meta.from_date">
                                </div>
                                <div class="col-auto">
                                    <label class="form-label">To Date</label>
                                   <input class="form-control mb-3" type="date" v-model="meta.to_date">
                                </div>
                                <div class="col-auto text-end mt-auto">
                                    <button class="btn mb-3 btn-primary me-2" @click="search">Search</button>
                                     <button class="btn mb-3 btn-danger" @click="reset">Reset</button>
                                </div>
                                <!-- <div class="col-12">
                                    <input class="form-control form-control-sm mb-2" type="text"
                                    placeholder="Type keyword and press enter key" v-model="meta.search" @keypress.enter="search()" />
                                </div> -->
                            </div>

                            <div class="table-responsive table-responsive-sm">
                                <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                    <thead>
                                        <tr style="background-color:#9b9b9b;color:white;">
                                            <th class="text-center">#</th>
                                            <th>
                                                Department
                                                <span>
                                                    <i v-if="meta.keyword == 'department_name' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword == 'department_name' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span>
                                            </th>
                                            <th>
                                                Asset
                                                <span>
                                                    <i v-if="meta.keyword == 'asset_name' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword == 'asset_name' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span>
                                            </th>
                                            <th>
                                                Asset Type
                                                <span>
                                                    <i v-if="meta.keyword == 'asset_type_name' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword == 'asset_type_name' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span>
                                            </th>
                                            <th>
                                                Check Date
                                                <span>
                                                    <i v-if="meta.keyword == 'reference_date' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword == 'reference_date' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span>
                                            </th>
                                            <th @click="sort('field_name')">
                                                Check
                                                <span>
                                                    <i v-if="meta.keyword == 'field_name' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword == 'field_name' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span>
                                            </th>
                                            <th @click="sort('lcl')">
                                                Lcl
                                                <span>
                                                    <i v-if="meta.keyword == 'lcl' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword == 'lcl' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span>
                                            </th>
                                            <th @click="sort('ucl')">
                                                Ucl
                                                <span>
                                                    <i v-if="meta.keyword == 'ucl' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword == 'ucl' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span>
                                            </th>
                                            <th @click="sort('default_value')">
                                                Default Value
                                                <span>
                                                    <i v-if="meta.keyword == 'default_value' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword == 'default_value' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span>
                                            </th>

                                            <th @click="sort('value')">
                                                 Value
                                                <span>
                                                    <i v-if="meta.keyword == 'value' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword == 'value' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span>
                                            </th>
                                            <th class="text-center" @click="sort('remark_status')">
                                                Remark Status
                                                <span>
                                                    <i v-if="meta.keyword == 'remark_status' && meta.order_by == 'asc'"
                                                        class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword == 'remark_status' && meta.order_by == 'desc'"
                                                        class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span>
                                            </th>
                                            <!-- <th class="text-center" v-can="'users.delete'">Status</th>
                                            <th class="text-center" v-can="'users.update'">Actions</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="user_assets.length==0">
                                            <td colspan="10" class="text-center">No records found</td>
                                        </tr>
                                        <tr v-for="deviation, key in user_assets" :key="key">
                                            <td class="text-center">{{ meta.from + key }}</td>
                                            <!-- <td>{{ deviation.department?.department_name }}</td> -->
                                            <td>
                                                {{ deviation?.asset?.asset_department_ids.map(department => department.department?.department_name).join(', ') }}
                                            </td>
                                            <td>{{deviation?.asset?.asset_name}}</td>
                                            <td>{{deviation.asset_type?.asset_type_name}}</td>
                                            <td>{{ deviation.user_check?.reference_date}}</td>
                                            <td>{{ deviation.check?.field_name}}</td>
                                            <td>{{ deviation?.lcl }}</td>
                                            <td>{{ deviation?.ucl }}</td>
                                            <td>{{ deviation.default_value }}</td>
                                            <td>{{ deviation.value }}</td>
                                            <td class="text-center"
                                                :class="deviation.remark_status ? 'text-success' : 'text-danger'">
                                                {{ deviation.remark_status ? 'Closed' : 'Active' }}
                                            </td>
                                            <!-- <td class="text-center" v-can="'users.delete'">
                                                <div class="form-switch" >
                                                    <input class="form-check-input"  type="checkbox" role="switch" :id="'user' + user.user_id" :checked="user.status" :value="user.status" @change="deleteUser(user)" />
                                                    <label class="custom-control-label" :for="'user' + user.user_id"></label>
                                                </div>
                                            </td>
                                            <td class="text-center" v-can="'users.update'">
                                                <a href="javascript:void(0)" class="text-success" v-if="user.status"
                                                    @click="editUser(user)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
                                            </td> -->
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
                    search: '',
                    order_by: "desc",
                    keyword: "user_check_id",
                    per_page: 10,
                    totalRows: 0,
                    page: 1,
                    lastPage: 1,
                    from: 1,
                    to: 1,
                    maxPage: 1,
                    trashed: false,
                    department_id: '',
                    asset_type_id: '',
                    asset_id: '',
                    from_date: '',
                    to_date: '',
                    download:"excel",
                    remark_status: 'All',
                },
                user_assets: [],
                errors: [],
                asset_types: [],
                assets:[],
                departments:[],
                status: true,
                downloaded_excel:[],
            }
        },
        // beforeRouteEnter(to, from, next) {
        //     next((vm) => {
        //         if(from.name != 'Users.Edit'){
        //             vm.$store.commit("setCurrentPage", vm.meta.page)
        //         }else{
        //             vm.meta.page = vm.$store.getters.current_page
        //         }
        //     });
        // },
        mounted() {
            this.index();
            this.getAssetTypes();
        },

        methods: {
            index() {
                let vm = this;
                let loader = this.$loading.show();
                this.$store.dispatch('post', { uri: 'paginateDeviations', data: vm.meta })
                    .then(response => {
                        loader.hide();
                        vm.user_assets = response.data.data;
                        vm.meta.totalRows = response.data.meta.total;
                        vm.meta.from = response.data.meta.from;
                        const end = vm.meta.from + Number(vm.meta.per_page) - 1;
                        vm.meta.to = end > vm.meta.totalRows ? vm.meta.totalRows : end;
                        vm.meta.lastPage = response.data.meta.last_page;
                        vm.meta.maxPage = vm.meta.lastPage >= 3 ? 3 : vm.meta.lastPage;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            reset() {
                let vm = this;
                vm.meta.asset_type_id = "";
                vm.meta.asset_id = "";
                vm.meta.department_id = "";
                vm.meta.remark_status = "";
                vm.meta.from_date = "";
                vm.meta.to_date = "";
                vm.errors = [];
                vm.index();
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
            getAsset() {
                let vm = this;
                let loader = vm.$loading.show();
                 vm.$store.dispatch('post', { uri: 'getAssetTypeAssets',data:vm.meta})
                .then(response => {
                    loader.hide();
                    console.log("dd--",response.data)
                    vm.assets = response.data;
                    console.log(" vm.assets--", vm.assets)
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
            vm.$store.dispatch('post', { uri: 'getDepartments',data:vm.meta })
                .then(response => {
                    loader.hide();
                    console.log("response.data.data",response.data)
                    vm.departments = response.data.data;
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
            },
         downloadExcel() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store.dispatch('post', { uri: 'downloadDeviations',data:vm.meta})
                .then(response => {
                    loader.hide();
                    vm.$store.dispatch("success", response.data.message);
                    vm.downloaded_excel = response.data;
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
            onPerPageChange() {
                let vm = this;
                vm.meta.page = 1;
                vm.index();
            },
            sort(field) {
                this.meta.keyword = field;
                this.meta.order_by = this.meta.order_by == "asc" ? "desc" : "asc";
                this.index();
            },

        }
    }
    </script>
