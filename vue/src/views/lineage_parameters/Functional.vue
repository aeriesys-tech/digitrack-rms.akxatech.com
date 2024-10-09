<template>
    <div class="">
        <div>
            <ol class="breadcrumb fs-sm mb-1">
                <li class="breadcrumb-item">
                    <router-link to="/dashboard">Dashboard</router-link>
                </li>
                <li class="breadcrumb-item">
                    <a href="javascript:void(0)">Lineage Parameters</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Functional</li>
            </ol>
            <h4 class="main-title mb-2">Functional</h4>
        </div>
        <div class="row g-2">
            <div class="col-4">
                <form @submit.prevent="submitForm()">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">Add Functional</h6>
                            <h6 class="card-title" v-else>Update Functional</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-12">
                                    <label class="form-label">Functional Code</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Functional Code" class="form-control" :class="{ 'is-invalid': errors.functional_code }" v-model="functional.functional_code" ref="functional_code"/>
                                    <span v-if="errors.functional_code" class="invalid-feedback">{{ errors.functional_code[0] }}</span>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Functional Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Enter Functional name" class="form-control" :class="{ 'is-invalid': errors.functional_name }" v-model="functional.functional_name"/>
                                    <span v-if="errors.functional_name" class="invalid-feedback">{{ errors.functional_name[0] }}</span>
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
                        <h6 class="card-title">Functionals</h6>
                    </div>
                    <div class="card-body">
                        <input class="form-control mb-2" type="text" placeholder="Type keyword and press enter key" v-model="meta.search" @keypress.enter="search()" />
                        <div class="table-responsive table-responsive-sm">
                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                <thead>
                                    <tr class="" style="background-color: #9b9b9b; color: white;">
                                        <th class="text-center">#</th>
                                        <th @click="sort('functional_code')">Functional Code
                                            <span>
                                                <i v-if="meta.keyword=='functional_code' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='functional_code' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span></th>
                                        <th @click="sort('functional_name')">Area Name

                                            <span>
                                                <i v-if="meta.keyword=='functional_name' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='functional_name' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span></th>

                                        <th class="text-center" >Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="functionals.length==0">
                                        <td colspan="6" class="text-center">No records found</td>
                                    </tr>
                                    <tr v-for="functional, key in functionals" :key="key">
                                        <td class="text-center">{{ meta.from + key }}</td>
                                        <td>{{functional.functional_code}}</td>
                                        <td>{{ functional.functional_name }}</td>
                                        <td class="text-center" >
                                            <div class="form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" :id="'functional' + functional.functional_id" :checked="functional.status" :value="functional.status" @change="deleteFunctional(functional)" />
                                                <label class="custom-control-label" :for="'functional' + functional.functional_id"></label>
                                            </div>
                                        </td>
                                        <td class="text-center" >
                                            <a href="javascript:void(0)" v-if="functional.status" class="text-success me-2" @click="editFunctional(functional)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
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
                keyword: "functional_id",
                per_page: 10,
                totalRows: 0,
                page: 1,
                lastPage: 1,
                from: 1,
                to: 1,
                maxPage: 1,
                trashed: false,
            },
            functionals: [],
            functional: {
                functional_id: '',
                functional_code: '',
                functional_name: '',
                status: '',
            },
            status: true,
            errors: [],
        }
    },
    mounted() {
        // this.create_functional = this.$store.getters.permissions.filter(function(element){
        //         return element.ability.ability.includes('functionals.create')
        //     })
        //     if(this.create_functional.length){
        //         this.column = 'col-8'
        //     }else{
        //         this.column = 'col-12'
        //     }
        this.index();
        this.$refs.functional_code.focus();
    },

    methods: {
        submitForm() {
            let vm = this;
            if (vm.status) {
                vm.addFunctional();
            } else {
                vm.updateFunctional();
            }
        },
        index() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'paginateFunctionals' , data:vm.meta })
                .then(response => {
                    loader.hide();
                    vm.functionals = response.data.data;
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

        addFunctional() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'addFunctional', data: vm.functional })
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

        deleteFunctional(functional) {
            let vm = this;
            functional.status = functional.status == 1 ? 0 : 1;
            vm.$store
                .dispatch("post", { uri: "deleteFunctional", data: functional })
                .then(function (response) {
                    vm.$store.dispatch('success', response.data.message);
                    vm.discard();
                })
                .catch(function (error) {
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },

        editFunctional(functional) {
            this.functional = functional;
            this.status = false;
            this.$refs.functional_code.focus();
        },

        updateFunctional() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'updateFunctional', data: vm.functional })
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
            vm.functional.functional_code = "";
            vm.functional.functional_name = "";
            vm.$refs.functional_code.focus();
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
