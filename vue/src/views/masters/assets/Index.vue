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
                    <li class="breadcrumb-item active" aria-current="page">Assets</li>
                </ol>
                <h4 class="main-title mb-0">Assets</h4>
            </div>
            <router-link v-can="'assets.create'" to="/asset/create" class="btn btn-primary" style="float: right;"><i class="ri-list-check"></i> ADD Asset</router-link>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-one">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="card-title">Assets</h6>
                    </div>
                    <div class="card-body">
                        <input class="form-control form-control-sm mb-2" type="text" placeholder="Type keyword and press enter key" v-model="meta.search" @keypress.enter="search()" />
                        <div class="table-responsive table-responsive-sm">
                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                <thead>
                                    <tr class="" style="background-color: #9b9b9b; color: white;">
                                        <th class="text-center">#</th>
                                        <th @click="sort('asset_type_id')">
                                            Asset Type
                                            <span>
                                                <i v-if="meta.keyword == 'asset_type_name' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'asset_type_name' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th @click="sort('asset_code')">
                                            Asset Code
                                            <span>
                                                <i v-if="meta.keyword == 'asset_code' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'asset_code' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th @click="sort('asset_name')">
                                            Asset Name
                                            <span>
                                                <i v-if="meta.keyword == 'asset_name' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'asset_name' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>

                                        <th @click="sort('latitude')">
                                            Latitude
                                            <span>
                                                <i v-if="meta.keyword == 'latitude' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'latitude' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>

                                        <th @click="sort('longitude')">
                                            Longitude
                                            <span>
                                                <i v-if="meta.keyword == 'longitude' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'longitude' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>

                                        <th @click="sort('radius')">
                                            Radius
                                            <span>
                                                <i v-if="meta.keyword == 'radius' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'radius' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <!-- <th @click="sort('serial_no')">
                                            Serial No.
                                            <span>
                                                <i v-if="meta.keyword == 'serial_no' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'serial_no' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th> -->
                                        <th class="text-center" v-can="'assets.delete'">Status</th>
                                        <th class="text-center" v-if="get_assetviews.length || get_asset.length">Actions</th>
                                        <th class="text-center">GoTo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="asset, key in assets" :key="key">
                                        <td class="text-center">{{ meta.from + key }}</td>
                                        <td>{{ asset.asset_type.asset_type_name }}</td>
                                        <td>{{asset.asset_code}}</td>
                                        <td>{{asset.asset_name}}</td>
                                        <td>{{asset.latitude}}</td>
                                        <td>{{asset.longitude}}</td>
                                        <td>{{asset.radius}}</td>
                                        <!-- <td>{{asset.serial_no}}</td> -->
                                        <td class="text-center" v-can="'assets.delete'">
                                            <div class="form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" :id="'asset' + asset.asset_id" :checked="asset.status" :value="asset.status" @change="deleteAsset(asset)" />
                                                <label class="custom-control-label" :for="'asset' + asset.asset_id"></label>
                                            </div>
                                        </td>
                                        <td class="text-center" v-if="get_assetviews.length || get_asset.length">
                                            <a v-can="'assets.update'" title="Edit" href="javascript:void(0)" class="text-success me-2" v-if="asset.status" @click="editAsset(asset)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
                                            <a v-can="'assetviews.view'" title="View" href="javascript:void(0)" class="text-primary me-2" @click.prevent="viewAsset(asset)"><i class="ri-eye-fill fs-18 lh-1"></i></a>
                                            <!-- <a href="javascript:void(0)" title="QR code" class="text-dark me-2" @click.prevent="getQRCode(asset)"><i class="ri-qr-code-line fs-18 lh-1"></i></a> -->
                                        </td>
                                        <td class="text-center">
                                            <a title="Activity Register" href="javascript:void(0)" class="text-primary me-2" @click.prevent="viewRegister(asset, '/activity/create')"><i class="ri-stack-fill fs-18 lh-1"></i></a>
                                            <a title="Service Register" href="javascript:void(0)" class="text-primary me-2" @click.prevent="viewRegister(asset, 'user_service/create')"><i class="ri-tools-fill fs-18 lh-1"></i></a>
                                            <a title="Check Register" href="javascript:void(0)" class="text-primary me-2" @click.prevent="viewRegister(asset, 'user_check/create')"><i class="ri-calendar-check-fill fs-18 lh-1"></i></a>
                                        </td>
                                    </tr>
                                    <tr v-if="assets.length==0">
                                        <td colspan="10" class="text-center">No records found</td>

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
            Pagination,
        },
        data() {
            return {
                meta: {
                    search: "",
                    order_by: "asc",
                    keyword: "asset_id",
                    per_page: 10,
                    totalRows: 0,
                    page: 1,
                    lastPage: 1,
                    from: 1,
                    to: 1,
                    maxPage: 1,
                    trashed: false,
                },
                get_assetviews: [],
                get_asset: [],
                assets: [],
                errors: [],
                status: true,
            };
        },
        beforeRouteEnter(to, from, next) {
            next((vm) => {
                if(from.name != 'Assets.Edit' && from.name != 'Assets.View'){
                    vm.$store.commit("setCurrentPage", vm.meta.page)
                }else{
                    vm.meta.page = vm.$store.getters.current_page
                }
            });
        },
        mounted() {
            this.assetviews = this.$store.getters.permissions.filter(function (element) {
                return element.ability.ability.includes("assetviews.view");
            });

            this.get_asset = this.$store.getters.permissions.filter(function (element) {
                return element.ability.ability.includes("assets.update");
            });
            this.$store.commit("setAssetId", '');
            this.index();
        },

        methods: {
            index() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "paginateAssets", data: vm.meta })
                    .then((response) => {
                        loader.hide();
                        vm.assets = response.data.data;
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
            editAsset(asset) {
                this.$store.commit("setCurrentPage", this.meta.page)
                this.$router.push("/asset/" + asset.asset_id + "/edit");
            },
            viewAsset(asset) {
                this.$store.commit("setCurrentPage", this.meta.page)
                this.$router.push("/asset/" + asset.asset_id + "/view");
            },
            viewRegister(asset, path){
                this.$store.commit("setCurrentPage", this.meta.page)
                this.$store.commit("setAssetId", asset.asset_id);
                this.$router.push(path);
            },
            getQRCode(asset) {
                this.$router.push("/QR_code/" + asset.asset_id + "/get");
            },
            deleteAsset(asset) {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", {
                        uri: "deleteAsset",
                        data: asset,
                    })
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
        },
    };
</script>
