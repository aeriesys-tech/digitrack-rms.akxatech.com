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
                <li class="breadcrumb-item active" aria-current="page">Spare Types</li>
            </ol>
            <h4 class="main-title mb-2">Spare Types</h4>
        </div>
        <div class="row g-2">
            <div class="col-4" v-can="'spare_types.create'">
                <form @submit.prevent="submitForm()">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">Add Spare Type </h6>
                            <h6 class="card-title" v-else>Update Spare Type </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-12">
                                    <label class="form-label">Spare Type Code </label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Spare Type Code" :class="{ 'is-invalid': errors.spare_type_code }"
                                        class="form-control" v-model="spare_type.spare_type_code" ref="spare_type_code"/>
                                    <span v-if="errors.spare_type_code"><small class="text-danger">{{ errors.spare_type_code[0] }}</small></span>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Spare Type Name </label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Spare Type Name " :class="{ 'is-invalid': errors.spare_type_name }"
                                        class="form-control" v-model="spare_type.spare_type_name" ref="spare_type_name"/>
                                    <span v-if="errors.spare_type_name"><small class="text-danger">{{ errors.spare_type_name[0] }}</small></span>
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
                        <h6 class="card-title">Spare Types</h6>
                    </div>
                    <div class="card-body">
                        <input class="form-control form-control-sm mb-2" type="text"
                            placeholder="Type keyword and press enter key" v-model="meta.search" @keypress.enter="search()"/>
                        <div class="table-responsive table-responsive-sm">
                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                <thead>
                                    <tr style="background-color: #9b9b9b; color: white;">
                                        <th class="text-center">#</th>
                                        <th @click="sort('spare_type_code')">Spare Type Code
                                            <span>
                                                <i v-if="meta.keyword=='spare_type_code' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='spare_type_code' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th @click="sort('spare_type_name')">Spare Type Name
                                            <span>
                                                <i v-if="meta.keyword=='spare_type_name' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='spare_type_name' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th class="text-center" v-can="'spare_types.delete'">Status</th>
                                        <th class="text-center" v-can="'spare_types.update'">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="spare_type, key in spare_types" :key="key">
                                        <td class="text-center">{{ meta.from + key }}</td>
                                        <td>{{ spare_type.spare_type_code }}</td>
                                        <td>{{ spare_type.spare_type_name }}</td>
                                        <td class="text-center" v-can="'spare_types.delete'">
                                            <div class="form-switch" >
                                                <input class="form-check-input"  type="checkbox" role="switch" :id="'spare_type' + spare_type.spare_type_id" :checked="spare_type.status" :value="spare_type.status" @change="deleteSpareType(spare_type)" />
                                                <label class="custom-control-label" :for="'spare_type' + spare_type.spare_type_id"></label>
                                            </div>
                                        </td>
                                        <td class="text-center" v-can="'spare_types.update'">
                                            <a href="javascript:void(0)" class="text-success" v-if="spare_type.status"
                                                @click="editSpareType(spare_type)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
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
                search: '',
                order_by: "desc",
                keyword: "spare_type_id",
                per_page: 10,
                totalRows: 0,
                page: 1,
                lastPage: 1,
                from: 1,
                to: 1,
                maxPage: 1,
                trashed: false,
            },
            spare_types: [],
            spare_type: {
                spare_type_id: '',
                spare_type_code: '',
                spare_type_name: '',
                status: ''
            },
            status: true,
            errors: [],
            update: false
        }
    },
    mounted() {
        this.create_spare_type = this.$store.getters.permissions.filter(function(element){
            return element.ability.ability.includes('spare_types.create')
        })
        if(this.create_spare_type.length){
            this.column = 'col-8'
        }else{
            this.column = 'col-12'
        }
        this.index();
        this.$refs.spare_type_code.focus();
    },

    methods: {
        submitForm() {
            let vm = this;
            if (vm.status) {
                vm.addSpareType();
            } else {
                vm.updateSpareType();
            }
        },
        index() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'paginateSpareTypes' , data:vm.meta })
                .then(response => {
                    loader.hide();
                    this.spare_types = response.data.data;
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

        addSpareType() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'addSpareType', data: vm.spare_type })
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

        deleteSpareType(spare_type) {
            let vm = this;
            let loader = this.$loading.show();
            spare_type.status = spare_type.status == 1 ? 0 : 1;
            vm.$store
                .dispatch("post", { uri: "deleteSpareType", data: spare_type })
                .then(function (response) {
                    loader.hide();
                    vm.$store.dispatch('success', response.data.message);
                    vm.discard();
                 })
                .catch(function (error) {
                    // console.error(error);
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },

        editSpareType(spare_type) {
            let vm = this;
            this.spare_type = spare_type;
            this.update = true;
            this.status = false;
            this.$refs.spare_type_code.focus();
        },

        updateSpareType() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'updateSpareType', data: this.spare_type })
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
            vm.spare_type.spare_type_code = "";
            vm.spare_type.spare_type_name= "";
            vm.$refs.spare_type_code.focus();
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