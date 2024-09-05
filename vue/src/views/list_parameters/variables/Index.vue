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
                    <li class="breadcrumb-item active" aria-current="page">Variables</li>
                </ol>
                <h4 class="main-title mb-0">Variables</h4>
            </div>
            <router-link to="/variables/create" class="btn btn-primary" style="float: right;"><i class="ri-list-check"></i> ADD VARIABLE</router-link>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-one">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="card-title">Variables</h6>
                    </div>
                    <div class="card-body">
                        <input class="form-control mb-2" type="text" placeholder="Type keyword and press enter key" v-model="meta.search" @keypress.enter="search()" />
                        <div class="table-responsive table-responsive-sm">
                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                <thead>
                                    <tr class="" style="background-color: #9b9b9b; color: white;">
                                        <th class="text-center">#</th>
                                        <th @click="sort('variable_type_id')">
                                            Variable Type
                                            <span>
                                                <i v-if="meta.keyword=='variable_type_id' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='variable_type_id' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th @click="sort('variable_code')">
                                            Variable Code
                                            <span>
                                                <i v-if="meta.keyword=='variable_code' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='variable_code' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th @click="sort('variable_name')">
                                            Variable Name

                                            <span>
                                                <i v-if="meta.keyword=='variable_name' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='variable_name' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th >
                                            Asset Type.
                                            <span>
                                                <i v-if="meta.keyword == 'asset_type_id' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'asset_type_id' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="variables.length==0">
                                        <td colspan="7" class="text-center">No records found</td>
                                    </tr>
                                    <tr v-for="variable, key in variables" :key="key">
                                        <td class="text-center">{{ meta.from + key }}</td>
                                        <td>{{variable.variable_type?.variable_type_name}}</td>
                                        <td>{{variable.variable_code}}</td>
                                        <td>{{variable.variable_name}}</td>
                                        <td>
                                            <span v-for="asset_type, key in variable.variable_asset_types" :key="key">{{asset_type?.asset_types?.asset_type_name }}, </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" :id="'variable' + variable.variable_id" :checked="variable.status" :value="variable.status" @change="deleteVariable(variable)" />
                                                <label class="custom-control-label" :for="'variable' + variable.variable_id"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" v-if="variable.status" class="text-success me-2" @click="editVariable(variable)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
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
            Pagination,
        },
        data() {
            return {
                meta: {
                    search: "",
                    order_by: "asc",
                    keyword: "variable_id",
                    per_page: 10,
                    totalRows: 0,
                    page: 1,
                    lastPage: 1,
                    from: 1,
                    maxPage: 1,
                },
                variable: {
                    variable_id: "",
                },
                variables: [],
                errors: [],
                update: false,
            };
        },
        beforeRouteEnter(to, from, next) {
            next((vm) => {
                if (from.name != "Variables.Edit") {
                    vm.$store.commit("setCurrentPage", vm.meta.page);
                } else {
                    vm.meta.page = vm.$store.getters.current_page;
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
                this.$store
                    .dispatch("post", {
                        uri: "paginateVariables",
                        data: vm.meta,
                    })
                    .then((response) => {
                        loader.hide();
                        this.variables = response.data.data;
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

            editVariable(variable) {
                this.$store.commit("setCurrentPage", this.meta.page);
                this.$router.push("/variables/" + variable.variable_id + "/edit");
            },

            deleteVariable(variable) {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "deleteVariable", data: variable })
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
        },
    };
</script>
