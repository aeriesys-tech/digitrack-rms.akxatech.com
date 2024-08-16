<template>
    <div class="">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div>
                <ol class="breadcrumb fs-sm mb-1">
                    <li class="breadcrumb-item" aria-current="page">
                        <router-link to="/dashboard">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0)">Registers</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Activities</li>
                </ol>
                <h4 class="main-title mb-0">Activities</h4>
            </div>
            <router-link v-can="'userActivities.create'" to="/activity/create" class="btn btn-primary" style="float: right;"><i class="ri-list-check"></i> Add Activity</router-link>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-one">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="card-title">Activities</h6>
                    </div>
                    <div class="card-body">
                        <input class="form-control form-control-sm mb-2" type="text" placeholder="Type keyword and press enter key" v-model="meta.search" @keypress.enter="search()" />
                        <div class="table-responsive table-responsive-sm">
                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                <thead>
                                    <tr class="" style="background-color: #9b9b9b; color: white;">
                                        <th class="text-center">#</th>
                                        <th @click="sort('activity_no')">
                                            Activity No.
                                            <span>
                                                <i v-if="meta.keyword == 'activity_no' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'activity_no' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th>Activity Date & Time</th>
                                        <th @click="sort('asset_id')">
                                            Asset Code
                                            <span>
                                                <i v-if="meta.keyword == 'asset_id' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'asset_id' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th @click="sort('equipment_id')">
                                            Equipment
                                            <span>
                                                <i v-if="meta.keyword == 'equipment_id' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'equipment_id' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th @click="sort('reason_id')">
                                            Reason
                                            <span>
                                                <i v-if="meta.keyword == 'reason_id' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'reason_id' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th @click="sort('cost')">
                                            Cost
                                            <span>
                                                <i v-if="meta.keyword == 'cost' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'cost' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center" v-if="get_activity.length">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="activity, key in activities" :key="key">
                                        <td class="text-center">{{ meta.from + key }}</td>
                                        <td>{{activity.activity_no}}</td>
                                        <td>{{activity.activity_date}}</td>
                                        <td>{{activity.asset?.asset_code}}</td>
                                        <td>{{activity.equipment?.equipment_name}}</td>
                                        <td>{{activity.reason?.reason_code}}</td>
                                        <td>{{activity.cost}}</td>
                                        <td>{{activity.activity_status}}</td>
                                        <td class="text-center" v-if="get_activity.length">
                                            <a title="Edit" v-can="'userActivities.update'" href="javascript:void(0)" class="text-success me-2" v-if="activity.status" @click="editActivity(activity)">
                                                <i class="ri-pencil-line fs-18 lh-1"></i>
                                            </a>
                                            <a title="View" v-can="'userActivities.delete'" href="javascript:void(0)" class="text-danger me-2" @click.prevent="deleteActivity(activity)"><i class="ri-delete-bin-6-line fs-18 lh-1"></i></a>
                                        </td>
                                    </tr>
                                    <tr v-if="activities.length==0">
                                        <td colspan="8" class="text-center">No records found</td>
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
                    keyword: "user_activity_id",
                    per_page: 10,
                    totalRows: 0,
                    page: 1,
                    lastPage: 1,
                    from: 1,
                    to: 1,
                    maxPage: 1,
                    trashed: false,
                },
                get_activity: [],
                get_asset: [],
                activities: [],
                errors: [],
                status: true,
            };
        },
        beforeRouteEnter(to, from, next) {
            next((vm) => {
                if(from.name != 'Activity.Edit'){
                    vm.$store.commit("setCurrentPage", vm.meta.page)
                }else{
                    vm.meta.page = vm.$store.getters.current_page
                }
            });
        },
        mounted() {
            this.get_activity = this.$store.getters.permissions.filter(function (element) {
                return element.ability.ability.includes("userActivities.update") || element.ability.ability.includes("userActivities.delete");
            });
            this.$store.commit("setAssetId", '');
            this.index();
        },

        methods: {
            index() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "paginateUserActivities", data: vm.meta })
                    .then((response) => {
                        loader.hide();
                        vm.activities = response.data.data;
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
            editActivity(activity) {
                this.$store.commit("setCurrentPage", this.meta.page)
                this.$router.push("/activity/" + activity.user_activity_id + "/edit");
            },
            deleteActivity(activity) {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", {
                        uri: "deleteUserActivity",
                        data: activity,
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
