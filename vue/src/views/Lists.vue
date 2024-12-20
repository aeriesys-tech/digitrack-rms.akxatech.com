<template>
    <div class="">
        <div>
            <ol class="breadcrumb fs-sm mb-1">
                <li class="breadcrumb-item">
                    <router-link to="/dashboard">Dashboard</router-link>
                </li>
                <li class="breadcrumb-item active" aria-current="page">List Parameters</li >
            </ol>
            <h4 class="main-title mb-2">List Parameters</h4>
        </div>
        <div class="row g-2">
            <div class="col-4">
                <form @submit.prevent="submitForm()">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">Add List Parameter</h6>
                            <h6 class="card-title" v-else>Update List Parameter</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-12">
                                    <label class="form-label">List Parameter Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="List Parameter Name" class="form-control" :class="{ 'is-invalid': errors.list_parameter_name }" v-model="list.list_parameter_name" ref="list_parameter_name"/>
                                    <span v-if="errors.list_parameter_name" class="invalid-feedback">{{ errors.list_parameter_name[0] }}</span>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Field Values</label><span class="text-danger"> *</span>
                                    <textarea rows="2" type="text" placeholder="Enter Field Values" class="form-control" :class="{ 'is-invalid': errors.field_values }" v-model="list.field_values"></textarea>
                                    <span v-if="errors.field_values" class="invalid-feedback">{{ errors.field_values[0] }}</span>
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
                        <h6 class="card-title">List Parameters</h6>
                    </div>
                    <div class="card-body">
                        <input class="form-control mb-2" type="text" placeholder="Type keyword and press enter key" v-model="meta.search" @keypress.enter="search()" />
                        <div class="table-responsive table-responsive-sm">
                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                <thead>
                                    <tr class="" style="background-color: #9b9b9b; color: white;">
                                        <th class="text-center">#</th>
                                        <th @click="sort('list_parameter_name')">List Parameter Name
                                            <span>
                                                <i v-if="meta.keyword=='list_parameter_name' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='list_parameter_name' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span></th>
                                        <th @click="sort('field_values')">Field Values

                                            <span>
                                                <i v-if="meta.keyword=='field_values' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='field_values' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span></th>

                                        <th class="text-center" >Status</th>
                                        <th class="text-center" >Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="lists.length==0">
                                        <td colspan="6" class="text-center">No records found</td>
                                    </tr>
                                    <tr v-for="list, key in lists" :key="key">
                                        <td class="text-center">{{ meta.from + key }}</td>
                                        <td>{{list.list_parameter_name}}</td>
                                        <td>{{ list.field_values }}</td>
                                        <td class="text-center" >
                                            <div class="form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" :id="'list' + list.list_parameter_id" :checked="list.status" :value="list.status" @change="deleteList(list)" />
                                                <label class="custom-control-label" :for="'list' + list.list_parameter_id"></label>
                                            </div>
                                        </td>
                                        <td class="text-center" >
                                            <a href="javascript:void(0)" v-if="list.status" class="text-success me-2" @click="editList(list)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
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
            column:'col-8',
            meta: {
                search: "",
                order_by: "desc",
                keyword: "list_parameter_id",
                per_page: 10,
                totalRows: 0,
                page: 1,
                lastPage: 1,
                from: 1,
                to: 1,
                maxPage: 1,
                trashed: false,
            },
            lists: [],
            list: {
                list_parameter_id: '',
                list_parameter_name: '',
                field_values: '',
                status: '',
            },
            status: true,
            errors: [],
        }
    },
    mounted() {
        this.index();
        this.$refs.list_parameter_name.focus();
    },

    methods: {
        submitForm() {
            let vm = this;
            if (vm.status) {
                vm.addList();
            } else {
                vm.updateList();
            }
        },
        index() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'paginateListParameters' , data:vm.meta })
                .then(response => {
                    loader.hide();
                    vm.lists = response.data.data;
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

        addList() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'addListParameter', data: vm.list })
                .then(response => {
                    loader.hide();
                    vm.$store.dispatch('success', response.data.message);
                    vm.discard();
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },

        deleteList(list) {
            let vm = this;
            list.status = list.status == 1 ? 0 : 1;
            vm.$store
                .dispatch("post", { uri: "deleteListParameter", data: list })
                .then(function (response) {
                    vm.$store.dispatch('success', response.data.message);
                    vm.discard();
                })
                .catch(function (error) {
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },

        editList(list) {

            this.list = list;
            this.status = false;
            this.$refs.list_parameter_name.focus();
        },

        updateList() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'updateListParameter', data: vm.list })
                .then(response => {
                    loader.hide();
                    vm.$store.dispatch('success', response.data.message);
                    vm.discard();
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
            vm.list.list_parameter_name = "";
            vm.list.field_values = "";
            vm.$refs.list_parameter_name.focus();
            vm.errors = [];
            vm.status = true;
            vm.index();
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
