<template>
<div class="">
    <!-- <div class="d-sm-flex align-items-center justify-content-between mb-2">
        <div>
            <ol class="breadcrumb fs-sm mb-1">
                <li class="breadcrumb-item">
                    <router-link to="/checks">List Parameters</router-link>
                </li>
                <li class="breadcrumb-item">
                    Checks
                </li>
            </ol>
            <h4 class="main-title mb-0">checks</h4>
        </div>
        <router-link to="/checks/create" type="submit" class="btn btn-sm btn-primary" style="float: right;"><i class="ri-list-check"></i> ADD checks</router-link>
    </div> -->

    <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div>
                <ol class="breadcrumb fs-sm mb-1">
                    <li class="breadcrumb-item" aria-current="page">
                        <router-link to="/dashboard">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0)">List Parameters</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Checks</li>
                </ol>
                <h4 class="main-title mb-0">Checks</h4>
            </div>
            <router-link v-can="'checks.create'" to="/checks/create" class="btn btn-primary" style="float: right;"><i
                    class="ri-list-check"></i> ADD CHECKS</router-link>
        </div>
    <div class="row">

        <div class="col-12">
            <div class="card card-one">
                <div class="card-header d-flex justify-content-between">
                    <h6 class="card-title">Checks</h6>
                </div>
                <div class="card-body">
                    <input class="form-control mb-2" type="text" placeholder="Type keyword and press enter key" v-model="meta.search" @keypress.enter="search()" />
                    <div class="table-responsive table-responsive-sm">
                        <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                            <thead>
                                <tr class="" style="background-color: #9b9b9b; color: white;">
                                    <th class="text-center">#</th>
                                    <th @click="sort('field_name')">Field Name
                                        <span>
                                            <i v-if="meta.keyword=='field_name' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                            <i v-else-if="meta.keyword=='field_name' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                            <i v-else class="fas fa-sort"></i>
                                        </span></th>
                                    <th @click="sort('field_type')">Field Type
                                        <span>
                                            <i v-if="meta.keyword=='field_type' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                            <i v-else-if="meta.keyword=='field_type' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                            <i v-else class="fas fa-sort"></i>
                                        </span></th>
                                    <th @click="sort('default_value')">Default Value
                                        <span>
                                            <i v-if="meta.keyword=='default_value' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                            <i v-else-if="meta.keyword=='default_value' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                            <i v-else class="fas fa-sort"></i>
                                        </span></th>
                                    <th @click="sort('is_required')">Is Required
                                        <span>
                                            <i v-if="meta.keyword=='is_required' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                            <i v-else-if="meta.keyword=='is_required' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                            <i v-else class="fas fa-sort"></i>
                                        </span></th>
                                    <th @click="sort('lcl')">LCL
                                        <span>
                                            <i v-if="meta.keyword=='lcl' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                            <i v-else-if="meta.keyword=='lcl' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                            <i v-else class="fas fa-sort"></i>
                                        </span></th>
                                    <th @click="sort('ucl')">UCL
                                        <span>
                                            <i v-if="meta.keyword=='ucl' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                            <i v-else-if="meta.keyword=='ucl' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                            <i v-else class="fas fa-sort"></i>
                                        </span></th>
                                    <th @click="sort('field_values')">Field Values
                                        <span>
                                            <i v-if="meta.keyword=='field_values' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                            <i v-else-if="meta.keyword=='field_values' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                            <i v-else class="fas fa-sort"></i>
                                        </span></th>
                                    <th @click="sort('order')">Order
                                        <span>
                                            <i v-if="meta.keyword=='order' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                            <i v-else-if="meta.keyword=='order' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
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
                                    <th @click="sort('asset_type_id')">
                                        Asset Type.
                                        <span>
                                            <i v-if="meta.keyword == 'asset_type_id' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                            <i v-else-if="meta.keyword == 'asset_type_id' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                            <i v-else class="fas fa-sort"></i>
                                        </span>
                                    </th>

                                    <th class="text-center" v-can="'checks.delete'">Status</th>
                                    <th class="text-center" v-can="'checks.update'">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="checks.length==0">
                                    <td colspan="6" class="text-center">No records found</td>
                                </tr>
                                <tr v-for="check, key in checks" :key="key">
                                    <td class="text-center">{{ meta.from + key }}</td>
                                    <td>{{check.field_name}}</td>
                                    <td>{{ check.field_type }}</td>
                                    <td>{{ check.default_value }}</td>
                                    <td>{{ check.is_required==1 ? 'Yes' : 'No'}}</td>
                                    <td>{{ check.lcl }}</td>
                                    <td>{{ check.ucl }}</td>
                                    <td>{{ check.field_values }}</td>
                                    <td>{{ check.order }}</td>
                                    <td>{{ check?.frequency?.frequency_name }}</td>
                                    <td>
                                        <span v-for="asset_type, key in check.check_asset_types" :key="key">{{asset_type?.asset_types?.asset_type_name }}, </span>
                                    </td>

                                    <td class="text-center" v-can="'checks.delete'">
                                        <div class="form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" :id="'check' + check.check_id" :checked="check.status" :value="check.status" @change="deleteCheck(check)" />
                                            <label class="custom-control-label" :for="'check' + check.check_id"></label>
                                        </div>
                                    </td>
                                    <td class="text-center" v-can="'checks.update'">
                                        <a href="javascript:void(0)" v-if="check.status" class="text-success me-2" @click="editCheck(check)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
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
        Pagination
    },
    data() {
        return {
            meta: {
                search: '',
                order_by: "asc",
                keyword: "check_id",
                per_page: 10,
                totalRows: 0,
                page: 1,
                lastPage: 1,
                from: 1,
                maxPage: 1,
            },
            check: {
                check_id: '',
            },
            checks: [],
            errors: [],
            update: false
        }
    },
    beforeRouteEnter(to, from, next) {
        next((vm) => {
            if(from.name != 'Checks.Edit'){
                vm.$store.commit("setCurrentPage", vm.meta.page)
            }else{
                vm.meta.page = vm.$store.getters.current_page
            }
        });
    },
    mounted() {
        this.index();

        //this.demo();
    },

    methods: {
        index() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', {
                    uri: 'paginateChecks',data:vm.meta
                })
                .then(response => {
                    loader.hide();
                    this.checks = response.data.data;
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
        
        editCheck(check) {
            this.$store.commit("setCurrentPage", this.meta.page)
            this.$router.push("/checks/"+check.check_id+"/edit");
        },

        deleteCheck(check) {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store
                .dispatch("post", {uri: "deleteCheck", data: check})
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
    }
}
</script>
