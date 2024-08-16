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
                    <li class="breadcrumb-item active" aria-current="page">Frequencies</li>
                </ol>
                <h4 class="main-title mb-2">Frequencies</h4>
            </div> 
            <div class="row g-2">
                <div class="col-4">
                    <form @submit.prevent="submitForm()">
                        <div class="card card-one">
                            <div class="card-header d-flex justify-content-between">
                                <h6 class="card-title" v-if="status">Add Frequency</h6>
                                <h6 class="card-title" v-else>Update Frequency</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-12">
                                        <label class="form-label">Frequency Code</label><span class="text-danger"> *</span>
                                        <input type="text" placeholder="Enter Frequency Code" class="form-control" ref="frequency_code" :class="{ 'is-invalid': errors.frequency_code }"
                                            v-model="frequency.frequency_code" />
                                        <span v-if="errors.frequency_code" class="invalid-feedback">{{ errors.frequency_code[0] }}</span>
                                    </div>
    
                                    <div class="col-md-12">
                                        <label class="form-label">Frequency Name</label><span class="text-danger"> *</span>
                                        <input type="text" placeholder="Enter Frequency name" class="form-control" :class="{ 'is-invalid': errors.frequency_name }"
                                            v-model="frequency.frequency_name" />
                                        <span v-if="errors.frequency_name" class="invalid-feedback">{{ errors.frequency_name[0] }}</span>
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
                            <h6 class="card-title">Frequencies</h6>
                        </div>
                        <div class="card-body">
                            <input class="form-control mb-2" type="text" placeholder="Type keyword and press enter key" v-model="meta.search" @keypress.enter="search()" />
                            <div class="table-responsive table-responsive-sm">
                                <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                    <thead>
                                        <tr class="text-center" style="background-color: #9b9b9b; color: white;">
                                            <th>#</th>
                                            <th @click="sort('frequency_code')">Frequency Code
                                                <span>
                                                    <i v-if="meta.keyword=='frequency_code' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword=='frequency_code' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span></th>
                                            <th @click="sort('frequency_name')">Frequency Name
                                            
                                                <span>
                                                    <i v-if="meta.keyword=='frequency_name' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword=='frequency_name' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span></th>
                                           
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="frequencies.length==0">
                                            <td colspan="6" class="text-center">No records found</td>
                                        </tr>
                                        <tr v-for="frequency, key in frequencies" :key="key">
                                            <td class="text-center">{{ meta.from + key }}</td>
                                            <td>{{frequency.frequency_code}}</td>
                                            <td>{{frequency.frequency_name }}</td>
                                            <td class="text-center" >
                                                <div class="form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" :id="'frequency' + frequency.frequency_id" :checked="frequency.status" :value="frequency.status" @change="deleteFrequency(frequency)" />
                                                    <label class="custom-control-label" :for="'frequency' + frequency.frequency_id"></label>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <a href="javascript:void(0)" v-if="frequency.status" class="text-success me-2" @click="editFrequency(frequency)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
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
                    keyword: "frequency_id",
                    per_page: 10,
                    totalRows: 0,
                    page: 1,
                    lastPage: 1,
                    from: 1,
                    maxPage: 1,
                },
                frequencies: [],
                frequency: {
                    frequency_id: '',
                    frequency_code: '',
                    frequency_name: '',
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
            this.getFrequencies();
        },
    
        methods: {
            submitForm() {
                let vm = this;
                if (vm.status) {
                    vm.addFrequency();
                } else {
                    vm.updateFrequency();
                }
            },
            index() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store.dispatch('post', { uri: 'paginateFrequencies', data:vm.meta })
                    .then(response => {
                        loader.hide();
                        vm.frequencies = response.data.data;
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
    
            addFrequency() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store.dispatch('post', { uri: 'addFrequency', data: vm.frequency })
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
    
            deleteFrequency(frequency) {
                let vm = this;
                frequency.status = frequency.status == 1 ? 0 : 1;
                vm.$store
                    .dispatch("post", { uri: "deleteFrequency", data: frequency })
                    .then(function (response) {
                        vm.$store.dispatch('success', response.data.message);
                        vm.discard();
                    })
                    .catch(function (error) {
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
    
            editFrequency(frequency) {
                this.frequency = frequency;
                this.status = false;
            },
    
            updateFrequency() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store.dispatch('post', { uri: 'updateFrequency', data: vm.frequency })
                    .then(response => {
                        loader.hide();
                        vm.$store.dispatch('success', response.data.message);
                        vm.discard();
                    })
                    .catch(function (error) {
                        loader.hide();
                        // console.log.error
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
    
            getFrequencies() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store.dispatch('post', { uri: 'getFrequencies' })
                    .then(response => {
                        loader.hide();
                        vm.frequencies = response.data.data;
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
                vm.frequency.frequency_code = "";
                vm.frequency.frequency_name = "";
                vm.$refs.frequency_code.focus();
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
    