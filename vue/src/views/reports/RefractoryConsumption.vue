<template>
    <div class="">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div>
                <ol class="breadcrumb fs-sm mb-1">
                    <li class="breadcrumb-item" aria-current="page">
                        <router-link to="/dashboard">Dashboard</router-link></li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a href="javascript:void(0)">Refractory Consumption Reports</a></li>
                    <!-- <li class="breadcrumb-item active" aria-current="page">Pendings/Overdues</li> -->
                </ol>
                <h4 class="main-title mb-0">Refractory Consumption Reports</h4>
            </div>
            <!-- <router-link v-can="'users.create'" to="/user/create" class="btn btn-primary" style="float: right;"><i
                    class="ri-list-check"></i> ADD USER</router-link> -->
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-one">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="card-title">Refractory Consumption Reports</h6>
                        <button v-can="'refractory_consumption_reports.download'" class="btn btn-success" @click="downloadExcel()" style="float: right;"><i class="ri-file-excel-line"></i> DOWNLOAD EXCEL</button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-2">
                                <label class="form-label">Asset Type</label>
                                <select class="form-control mb-3" v-model="meta.asset_type_id" @change="getAsset()">
                                    <option value="">Select Asset Type</option>
                                    <option v-for="asset_type, key in asset_types" :key="key" :value="asset_type.asset_type_id">{{ asset_type.asset_type_name }}</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <label class="form-label">Asset</label>
                                <select class="form-control mb-3" v-model="meta.asset_id" @change="getAssetZones()">
                                    <option value="">Select Asset </option>
                                    <option v-for="asset, key in assets" :key="key" :value="asset.asset_id">{{ asset.asset_code}}::{{ asset.asset_name }}</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <label class="form-label">Spare Types</label>
                                <select class="form-control mb-3" v-model="meta.spare_type_id" @change="getSpares()">
                                    <option value="">Select Spare Types</option>
                                    <option v-for="spare_type, key in spare_types" :key="key" :value="spare_type.spare_type_id">{{ spare_type.spare_type_name }}</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <label class="form-label">Spares</label>
                                <select class="form-control mb-3" v-model="meta.spare_id" >
                                    <option value="">Select Spares</option>
                                    <option v-for="spare, key in spares" :key="key" :value="spare.spare_id">{{ spare.spare_name }}</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <label class="form-label">From Date</label>
                               <input class="form-control mb-3" type="date" v-model="meta.from_date">
                            </div>
                            <div class="col-2">
                                <label class="form-label">To Date</label>
                               <input class="form-control mb-3" type="date" v-model="meta.to_date">
                            </div>
                            <div class="col-2 mt-auto">
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
                                            Asset
                                            <span>
                                                <i v-if="meta.keyword == 'asset_id' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'asset_id' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th>
                                            Asset Type
                                            <span>
                                                <i v-if="meta.keyword == 'asset_type_id' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'asset_type_id' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th>
                                           Spare
                                            <span>
                                                <i v-if="meta.keyword == 'spare_id' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'spare_id' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th>
                                           Spare Type
                                            <span>
                                                <i v-if="meta.keyword == 'spare_type_id' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'spare_type_id' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th>
                                            Total Quantity
                                            <span>
                                                <i v-if="meta.keyword == 'quantity' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'quantity' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="user_services.length==0">
                                        <td colspan="10" class="text-center">No records found</td>
                                    </tr>
                                    <tr v-for="service, key in user_services" :key="key">
                                        <td class="text-center">{{ meta.from + key }}</td>
                                        <td>{{ service?.asset_name }}</td>
                                        <td>{{ service?.asset_type_name }}</td>
                                        <td>{{service?.spare?.spare_name}}</td>
                                        <td>{{service?.spare?.spare_type?.spare_type_name}}</td>
                                        <td>{{ service?.total_quantity}}</td>
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
                keyword: "user_spare_id",
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
                spare_id:'',
                spare_type_id:'',
                asset_zone_id:''
            },
            user_services: [],
            errors: [],
            asset_types: [],
            assets:[],
            spares:[],
            spare_types: [],
            zones:[],
            downloaded_excel:[],
            status: true,
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
        this.getSpareTypes();
    },

    methods: {
        index() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'getTotalQuantitySpares', data: vm.meta })
                .then(response => {
                    loader.hide();
                    vm.user_services = response.data.data;
                    vm.meta.totalRows = response.data.meta.total;
                    vm.meta.from = response.data.meta.from;
                    vm.meta.lastPage = response.data.meta.last_page;
                    const end = vm.meta.from + Number(vm.meta.per_page) - 1;
                    vm.meta.to = end > vm.meta.totalRows ? vm.meta.totalRows : end;
                    vm.meta.maxPage = vm.meta.lastPage >= 3 ? 3 : vm.meta.lastPage;
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
        getSpares() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'getTypesSpares' ,data:vm.meta})
                .then(response => {
                    loader.hide();
                    vm.spares = response.data.data;
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
            },
        getAssetZones() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'getAssetZones' ,data:vm.meta})
                .then(response => {
                    loader.hide();
                    vm.zones = response.data.data;
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
            vm.$store.dispatch('post', { uri: 'downloadRefractoryConsumptions',data:vm.meta})
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
        reset() {
            let vm = this;
            vm.meta.asset_type_id = "";
            vm.meta.asset_id = "";
            vm.meta.department_id = "";
            vm.meta.from_date = "";
            vm.meta.to_date = "";
            vm.errors = [];
            vm.index();
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
