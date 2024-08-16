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
                    <li class="breadcrumb-item active" aria-current="page">Departments</li>
                </ol>
                <h4 class="main-title mb-2">Departments</h4>
            </div> 
            <div class="row g-2">
                <div class="col-4">
                    <form @submit.prevent="submitForm()">
                        <div class="card card-one">
                            <div class="card-header d-flex justify-content-between">
                                <h6 class="card-title" v-if="status">Add Department</h6>
                                <h6 class="card-title" v-else>Update Departments</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-12">
                                        <label class="form-label">Department Code</label><span class="text-danger"> *</span>
                                        <input type="text" placeholder="Enter Department Code" class="form-control" ref="department_code" :class="{ 'is-invalid': errors.department_code }"
                                            v-model="department.department_code" />
                                        <span v-if="errors.department_code" class="invalid-feedback">{{ errors.department_code[0] }}</span>
                                    </div>
    
                                    <div class="col-md-12">
                                        <label class="form-label">Department Name</label><span class="text-danger"> *</span>
                                        <input type="text" placeholder="Enter Department name" class="form-control" :class="{ 'is-invalid': errors.department_name }"
                                            v-model="department.department_name" />
                                        <span v-if="errors.department_name" class="invalid-feedback">{{ errors.department_name[0] }}</span>
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
                            <h6 class="card-title">Departments</h6>
                        </div>
                        <div class="card-body">
                            <input class="form-control mb-2" type="text" placeholder="Type keyword and press enter key" v-model="meta.search" @keypress.enter="search()" />
                            <div class="table-responsive table-responsive-sm">
                                <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                    <thead>
                                        <tr class="text-center" style="background-color: #9b9b9b; color: white;">
                                            <th>#</th>
                                            <th @click="sort('department_code')">Department Code
                                                <span>
                                                    <i v-if="meta.keyword=='department_code' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword=='department_code' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span></th>
                                            <th @click="sort('department_name')">Department Name
                                            
                                                <span>
                                                    <i v-if="meta.keyword=='department_name' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword=='department_name' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span></th>
                                           
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="departments.length==0">
                                            <td colspan="6" class="text-center">No records found</td>
                                        </tr>
                                        <tr v-for="department, key in departments" :key="key">
                                            <td class="text-center">{{ meta.from + key }}</td>
                                            <td>{{department.department_code}}</td>
                                            <td>{{department.department_name }}</td>
                                            <td class="text-center" >
                                                <div class="form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" :id="'department' + department.department_id" :checked="department.status" :value="department.status" @change="deleteDepartment(department)" />
                                                    <label class="custom-control-label" :for="'department' + department.department_id"></label>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <a href="javascript:void(0)" v-if="department.status" class="text-success me-2" @click="editDepartment(department)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
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
    import Search from "@/components/Search.vue";
    export default {
        components: {
            Pagination, Search
        },
        data() {
            return {
                meta: {
                    search: '',
                    order_by: "asc",
                    keyword: "department_id",
                    per_page: 10,
                    totalRows: 0,
                    page: 1,
                    lastPage: 1,
                    from: 1,
                    maxPage: 1,
                },
                departments: [],
                department: {
                    department_id: '',
                    department_code: '',
                    department_name: '',
                    status: '',
                },
                errors: [],
                status: true,
                column:'col-8',
            }
        },
        mounted() {
        //     this.create_plant = this.$store.getters.permissions.filter(function(element){
        //         return element.ability.ability.includes('plants.create')
        //     })
        //     if(this.create_plant.length){
        //         this.column = 'col-8'
        //     }else{
        //         this.column = 'col-12'
        //     }
            this.index();
            this.getDepartments();
        },
    
        methods: {
            submitForm() {
                let vm = this;
                if (vm.status) {
                    vm.addDepartment();
                } else {
                    vm.updateDepartment();
                }
            },
            index() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store.dispatch('post', { uri: 'paginateDepartments', data:vm.meta })
                    .then(response => {
                        loader.hide();
                        vm.departments = response.data.data;
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
    
            addDepartment() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store.dispatch('post', { uri: 'addDepartment', data: vm.department })
                    .then(response => {
                        loader.hide();
                        vm.$store.dispatch('success', response.data.message);
                        vm.discard();
                        // this.index();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
    
            deleteDepartment(department) {
                let vm = this;
                department.status = department.status == 1 ? 0 : 1;
                vm.$store
                    .dispatch("post", { uri: "deleteDepartment", data: department })
                    .then(function (response) {
                        vm.$store.dispatch('success', response.data.message);
                        vm.discard();
                    })
                    .catch(function (error) {
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
    
            editDepartment(department) {
                this.department = department;
                this.status = false;
            },
    
            updateDepartment() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store.dispatch('post', { uri: 'updateDepartment', data: vm.department })
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
    
            getDepartments() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store.dispatch('post', { uri: 'getDepartments' })
                    .then(response => {
                        loader.hide();
                        vm.departments = response.data.data;
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
                vm.department.department_code = "";
                vm.department.department_name = "";
                vm.$refs.department_code.focus();
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
    