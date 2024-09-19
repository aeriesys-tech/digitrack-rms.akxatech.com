<template>
    <div class="">
        <div>
            <ol class="breadcrumb fs-sm mb-1">
                <li class="breadcrumb-item">
                    <router-link to="/dashboard">Dashboard</router-link>
                </li>
                <li class="breadcrumb-item">
                    <a href="javascript:void(0)">Type Parameters</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Activity Types</li>
            </ol>
            <h4 class="main-title mb-2">Activity Types</h4>
        </div> 
        <div class="row g-2">
            <div class="col-4" v-can="'reasons.create'">
                <form @submit.prevent="submitForm()">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">Add Activity Type</h6>
                            <h6 class="card-title" v-else>Update Activity Type</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-12">
                                    <label class="form-label">Activity Type Code</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Enter Activity Type Code" class="form-control" :class="{ 'is-invalid': errors.reason_code }" v-model="reason.reason_code" ref="reason_code"/>
                                    <span v-if="errors.reason_code" class="invalid-feedback">{{ errors.reason_code[0] }}</span>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Activity Type Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Enter Activity Type Name" class="form-control" :class="{ 'is-invalid': errors.reason_name }" v-model="reason.reason_name"/>
                                    <span v-if="errors.reason_name" class="invalid-feedback">{{ errors.reason_name[0] }}</span>
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
                        <h6 class="card-title">Activity types</h6>
                    </div>
                    <div class="card-body">
                        <input class="form-control mb-2" type="text" placeholder="Type keyword and press enter key" v-model="meta.search" @keypress.enter="search()" />
                        <div class="table-responsive table-responsive-sm">
                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                <thead>
                                    <tr class="" style="background-color: #9b9b9b; color: white;">
                                        <th class="text-center">#</th>
                                        <th @click="sort('reason_code')">Activity Type Code
                                            <span>
                                                <i v-if="meta.keyword=='reason_code' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='reason_code' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span></th>
                                        <th @click="sort('reason_name')">Activity Type Name
                                        
                                            <span>
                                                <i v-if="meta.keyword=='reason_name' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='reason_name' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span></th>
                                       
                                        <th class="text-center" v-can="'reasons.delete'">Status</th>
                                        <th class="text-center" v-can="'reasons.update'">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="reasons.length==0">
                                        <td colspan="6" class="text-center">No records found</td>
                                    </tr>
                                    <tr v-for="reason, key in reasons" :key="key">
                                        <td class="text-center">{{ meta.from + key }}</td>
                                        <td>{{reason.reason_code}}</td>
                                        <td>{{ reason.reason_name }}</td>
                                        <td class="text-center" v-can="'reasons.delete'">
                                            <div class="form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" :id="'reason' + reason.reason_id" :checked="reason.status" :value="reason.status" @change="deleteReason(reason)" />
                                                <label class="custom-control-label" :for="'reason' + reason.reason_id"></label>
                                            </div>
                                        </td>
                                        <td class="text-center" v-can="'reasons.update'">
                                            <a href="javascript:void(0)" v-if="reason.status" class="text-success me-2" @click="editReason(reason)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
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
                order_by: "asc",
                keyword: "reason_id",
                per_page: 10,
                totalRows: 0,
                page: 1,
                lastPage: 1,
                from: 1,
                to: 1,
                maxPage: 1,
                trashed: false,
            },
            reasons: [],
            reason: {
                reason_id: '',
                reason_code: '',
                reason_name: '',
                status: '',
            },
            status: true,
            errors: [],
            column:'col-8'
        }
    },
    mounted() {
        this.create_reason = this.$store.getters.permissions.filter(function(element){
            return element.ability.ability.includes('reasons.create')
        })
        if(this.create_reason.length){
            this.column = 'col-8'
        }else{
            this.column = 'col-12'
        }
        this.index();
        this.$refs.reason_code.focus();
    },

    methods: {
        submitForm() {
            let vm = this;
            if (vm.status) {
                vm.addReason();
            } else {
                vm.updateReason();
            }
        },
        index() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'paginateReasons' , data:vm.meta })
                .then(response => {
                    loader.hide();
                    this.reasons = response.data.data;
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

        addReason() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'addReason', data: vm.reason })
                .then(response => {
                    loader.hide();
                    this.$store.dispatch('success', response.data.message);
                    this.discard();
                    this.index();
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },

        deleteReason(reason) {
            let vm = this;
            reason.status = reason.status == 1 ? 0 : 1;
            vm.$store
                .dispatch("post", { uri: "deleteReason", data: reason })
                .then(function (response) {
                    vm.$store.dispatch('success', response.data.message);
                    vm.index();
                })
                .catch(function (error) {
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },

        editReason(reason) {
            this.reason = reason;
            this.status = false;
            this.$refs.reason_code.focus();
        },

        updateReason() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'updateReason', data: this.reason })
                .then(response => {
                    loader.hide();
                    this.$store.dispatch('success', response.data.message);
                    this.discard();
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
            vm.reason.reason_code = "";
            vm.reason.reason_name = "";
            vm.$refs.reason_code.focus();
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
