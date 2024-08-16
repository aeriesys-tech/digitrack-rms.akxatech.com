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
                <li class="breadcrumb-item active" aria-current="page">Equipment Types</li>
            </ol>
            <h4 class="main-title mb-2">Equipment Types</h4>
        </div> 
        <div class="row g-2">
            <div class="col-4" v-can="'equipment_types.create'">
                <form @submit.prevent="submitForm()">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">Add Equipment Type</h6>
                            <h6 class="card-title" v-else>Update Equipment Type</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-12">
                                    <label class="form-label">Equipment Type Code</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Enter Equipment Type Code" class="form-control" :class="{ 'is-invalid': errors.equipment_type_code }" v-model="equipment_type.equipment_type_code" ref="equipment_type_code"/>
                                    <span v-if="errors.equipment_type_code" class="invalid-feedback">{{ errors.equipment_type_code[0] }}</span>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Equipment Type Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Enter Equipment Type name" class="form-control" :class="{ 'is-invalid': errors.equipment_type_name }" v-model="equipment_type.equipment_type_name"/>
                                    <span v-if="errors.equipment_type_name" class="invalid-feedback">{{ errors.equipment_type_name[0] }}</span>
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
                        <h6 class="card-title">Equipment Types</h6>
                    </div>
                    <div class="card-body">
                        <input class="form-control mb-2" type="text" placeholder="Type keyword and press enter key" v-model="meta.search" @keypress.enter="search()" />
                        <div class="table-responsive table-responsive-sm">
                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                <thead>
                                    <tr class="" style="background-color: #9b9b9b; color: white;">
                                        <th class="text-center">#</th>
                                        <th @click="sort('equipment_type_code')">Equipment Type Code
                                            <span>
                                                <i v-if="meta.keyword=='equipment_type_code' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='equipment_type_code' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span></th>
                                        <th @click="sort('equipment_type_name')">Equipment Type Name
                                        
                                            <span>
                                                <i v-if="meta.keyword=='equipment_type_name' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='equipment_type_name' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span></th>
                                       
                                        <th class="text-center" v-can="'equipment_types.delete'">Status</th>
                                        <th class="text-center" v-can="'equipment_types.update'">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="equpment_types.length==0">
                                        <td colspan="6" class="text-center">No records found</td>
                                    </tr>
                                    <tr v-for="equipment_type, key in equpment_types" :key="key">
                                        <td class="text-center">{{ meta.from + key }}</td>
                                        <td>{{equipment_type.equipment_type_code}}</td>
                                        <td>{{ equipment_type.equipment_type_name }}</td>
                                        <td class="text-center" v-can="'equipment_types.delete'">
                                            <div class="form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" :id="'equipment_type' + equipment_type.equipment_type_id" :checked="equipment_type.status" :value="equipment_type.status" @change="deleteEquipmentType(equipment_type)" />
                                                <label class="custom-control-label" :for="'equipment_type' + equipment_type.equipment_type_id"></label>
                                            </div>
                                        </td>
                                        <td class="text-center" v-can="'equipment_types.update'">
                                            <a href="javascript:void(0)" v-if="equipment_type.status" class="text-success me-2" @click="editEquipmentType(equipment_type)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
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
                search: "",
                order_by: "asc",
                keyword: "equipment_type_id",
                per_page: 10,
                totalRows: 0,
                page: 1,
                lastPage: 1,
                from: 1,
                to: 1,
                maxPage: 1,
                trashed: false,
            },
            equpment_types: [],
            equipment_type: {
                equipment_type_id: '',
                equipment_type_code: '',
                equipment_type_name: '',
                status: '',
            },
            status: true,
            errors: [],
            column:'col-8',
            update: false
        }
    },
    mounted() {
        this.create_equipment_types = this.$store.getters.permissions.filter(function(element){
            return element.ability.ability.includes('equipment_types.create')
        })
        if(this.create_equipment_types.length){
            this.column = 'col-8'
        }else{
            this.column = 'col-12'
        }
        this.index();
    },

    methods: {
        submitForm() {
            let vm = this;
            if (vm.status) {
                vm.addEquipmentType();
            } else {
                vm.updateEquipmentType();
            }
        },
        index() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'paginateEquipmentTypes' , data:vm.meta })
                .then(response => {
                    loader.hide();
                    this.equpment_types = response.data.data;
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

        addEquipmentType() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'addEquipmentType', data: vm.equipment_type })
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

        deleteEquipmentType(equipment_type) {
            let vm = this;
            equipment_type.status = equipment_type.status == 1 ? 0 : 1;
            vm.$store
                .dispatch("post", { uri: "deleteEquipmentType", data: equipment_type })
                .then(function (response) {
                    vm.$store.dispatch('success', response.data.message);
                    vm.index();
                })
                .catch(function (error) {
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },

        editEquipmentType(equipment_type) {
            this.equipment_type = equipment_type;
            this.update = true;
            this.status = false;
        },

        updateEquipmentType() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'updateEquipmentType', data: this.equipment_type })
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
            vm.equipment_type.equipment_type_code = "";
            vm.equipment_type.equipment_type_name = "";
            vm.$refs.equipment_type_code.focus();
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
