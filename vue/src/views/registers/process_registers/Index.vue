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
                    <li class="breadcrumb-item active" aria-current="page">Process Registers</li>
                </ol>
                <h4 class="main-title mb-0">Process Registers</h4>
            </div>
            <router-link to="/process_registers/create" class="btn btn-primary" style="float: right;"><i
                    class="ri-list-check"></i> Add Process Register</router-link>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-one">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="card-title">Process Registers</h6>
                    </div>
                    <div class="card-body">
                        <input class="form-control form-control-sm mb-2" type="text"
                            placeholder="Type keyword and press enter key" v-model="meta.search"
                            @keypress.enter="search()" />
                        <div class="table-responsive table-responsive-sm">
                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                <thead>
                                    <tr class="" style="background-color: #9b9b9b; color: white;">
                                        <th class="text-center">#</th>
                                        <th @click="sort('asset_id')">
                                            Asset
                                            <span>
                                                <i v-if="meta.keyword == 'asset_id' && meta.order_by == 'asc'"
                                                    class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'asset_id' && meta.order_by == 'desc'"
                                                    class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th @click="sort('reference_no')">
                                            Job No.
                                            <span>
                                                <i v-if="meta.keyword == 'reference_no' && meta.order_by == 'asc'"
                                                    class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'reference_no' && meta.order_by == 'desc'"
                                                    class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th @click="sort('reference_date')">
                                            Job Date.
                                            <span>
                                                <i v-if="meta.keyword == 'reference_date' && meta.order_by == 'asc'"
                                                    class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'reference_date' && meta.order_by == 'desc'"
                                                    class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th>
                                            Asset Zone.
                                            <span>
                                                <i v-if="meta.keyword == 'asset_zone_id' && meta.order_by == 'asc'"
                                                    class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'asset_zone_id' && meta.order_by == 'desc'"
                                                    class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th>
                                            Variable                                            
                                        </th>
                                        <th>
                                            Value                                            
                                        </th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="user_variable, key in user_variables" :key="key">
                                        <td class="text-center">{{ meta.from + key }}</td>
                                        <td>{{ user_variable.asset?.asset_code }}</td>
                                        <td>{{ user_variable.job_no }}</td>
                                        <td>{{ user_variable.job_date }}</td>
                                        <td>{{ user_variable?.asset_zone?.zone_name }}</td>
                                        <td>{{ user_variable?.asset_variables?.variable?.variable_name }}</td>
                                        <td>{{ user_variable?.value }}</td>
                                        <td class="text-center">
                                            <a title="Edit" v-can="'userChecks.update'" href="javascript:void(0)"
                                                class="text-success me-2" @click="editUserVariable(user_variable)">
                                                <i class="ri-pencil-line fs-18 lh-1"></i>
                                            </a>
                                            <!-- <a title="View" href="javascript:void(0)"
                                                @click="viewUserVariable(user_variable)" class="text-primary me-2"><i
                                                    class="ri-eye-fill fs-18 lh-1"></i></a> -->
                                            <a title="View" v-can="'userChecks.delete'" href="javascript:void(0)"
                                                class="text-danger me-2"
                                                @click.prevent="deleteUserVariable(user_variable)"><i
                                                    class="ri-delete-bin-6-line fs-18 lh-1"></i></a>
                                        </td>
                                    </tr>
                                    <tr v-if="user_variables.length == 0">
                                        <td colspan="8" class="text-center">No records found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <select class="form-select from-select-sm width-75" v-model="meta.per_page"
                                @change="onPerPageChange">
                                <option>10</option>
                                <option>15</option>
                                <option>20</option>
                                <option>25</option>
                                <option>30</option>
                            </select>
                            <span>Showing {{ meta.from }} to {{ meta.to }} of {{ meta.totalRows }} entries</span>
                            <Pagination :maxPage="meta.maxPage" :totalPages="meta.lastPage" :currentPage="meta.page"
                                @pagechanged="onPageChange" />
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
    name: "ProcessRegisters.Index",
    components: {
        Pagination,
    },
    data() {
        return {
            status: true,
            meta: {
                search: "",
                order_by: "desc",
                keyword: "user_variable_id",
                per_page: 10,
                totalRows: 0,
                page: 1,
                lastPage: 1,
                from: 1,
                to: 1,
                maxPage: 1,
                trashed: false,
            },
            user_variables: [],
            errors: [],
            status: true,
        }
    },
    beforeRouteEnter(to, from, next) {
        next((vm) => {
            if (from.name != 'UserChecks.Edit' && from.name != 'UserChecks.View') {
                vm.$store.commit("setCurrentPage", vm.meta.page)
            } else {
                vm.meta.page = vm.$store.getters.current_page
            }
        });
    },
    mounted() {
        // this.get_activity = this.$store.getters.permissions.filter(function (element) {
        //     return element.ability.ability.includes("userActivities.update") || element.ability.ability.includes("userActivities.delete");
        // });
        this.$store.commit("setAssetId", '');
        this.index();
    },
    methods: {
        index() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store
                .dispatch("post", { uri: "paginateUserVariables", data: vm.meta })
                .then((response) => {
                    loader.hide();
                    vm.user_variables = response.data.data;
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
        editUserVariable(user_variable) {
            this.$store.commit("setCurrentPage", this.meta.page)
            this.$router.push("/process_registers/" + user_variable.user_variable_id + "/edit");
        },
        viewUserVariable(user_variable) {
            this.$store.commit("setCurrentPage", this.meta.page)
            this.$router.push("/process_registers/" + user_variable.user_variable_id + "/view");
        },
        deleteUserVariable(user_variable) {
            let vm = this;
            alert('are you sure you want delete it!')
            let loader = vm.$loading.show();
            vm.$store
                .dispatch("post", { uri: "deleteUserVariable", data: user_variable, })
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
    }
}
</script>