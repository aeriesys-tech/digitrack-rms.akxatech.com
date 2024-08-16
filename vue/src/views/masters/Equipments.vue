<template>
    <div class="">
        <div>
            <ol class="breadcrumb fs-sm mb-1">
                <li class="breadcrumb-item">
                    <router-link to="/dashboard">Dashboard</router-link>
                </li>
                <li class="breadcrumb-item">
                    <a href="javascript:void(0)">Masters</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Equipments</li>
            </ol>
            <h4 class="main-title mb-2">Equipments</h4>
        </div> 
        <div class="row g-2">
            <div class="col-4" v-can="'equipment.create'">
                <form @submit.prevent="submitForm()">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">Add Equipment</h6>
                            <h6 class="card-title" v-else>Update Equipment</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-12">
                                    <label class="form-label">Equipment Type</label><span class="text-danger"> *</span>
                                    
                                    <search
                                        ref="equipment_type_id"
                                        :class="{ 'is-invalid': errors.equipment_type_id }"
                                        :customClass="{ 'is-invalid': errors.equipment_type_id }"
                                        :initialize="equipment.equipment_type_id"
                                        id="equipment_type_id"
                                        label="equipment_type_name"
                                        placeholder="Select Equipment_type"
                                        :data=" equipment_types"
                                        @input=" equipment_type => equipment.equipment_type_id = equipment_type"
                                    >
                                    </search>
                                    <span v-if="errors.equipment_type_id" class="invalid-feedback">{{ errors.equipment_type_id[0] }}</span>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Equipment Code</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Enter Equipment Code" class="form-control" :class="{ 'is-invalid': errors.equipment_code }"
                                        v-model="equipment.equipment_code" />
                                        <span v-if="errors.equipment_code" class="invalid-feedback">{{ errors.equipment_code[0] }}</span>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Equipment Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Enter equipment name" class="form-control" :class="{ 'is-invalid': errors.equipment_name }"
                                        v-model="equipment.equipment_name" />
                                        <span v-if="errors.equipment_name" class="invalid-feedback">{{ errors.equipment_name[0] }}</span>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Description</label>
                                    <textarea placeholder="Enter description" class="form-control" :class="{ 'is-invalid': errors.description }"
                                        v-model="equipment.description"></textarea>
                                    <span v-if="errors.description" class="invalid-feedback">{{ errors.description[0] }}</span>
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
                        <h6 class="card-title">Equipments</h6>
                    </div>
                    <div class="card-body">
                        <input class="form-control mb-2" type="text" placeholder="Type keyword and press enter key" v-model="meta.search" @keypress.enter="search()" />
                        <div class="table-responsive table-responsive-sm">
                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                <thead>
                                    <tr class="" style="background-color: #9b9b9b; color: white;">
                                        <th class="text-center">#</th>
                                        <th @click="sort('equipment_type_id')">Equipment Type
                                            <span>
                                                <i v-if="meta.keyword=='equipment_type_id' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='equipment_type_id' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span></th>
                                            <th @click="sort('equipment_code')">Equipment Code
                                                <span>
                                                    <i v-if="meta.keyword=='equipment_code' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword=='equipment_code' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span></th>
                                        <th @click="sort('equipment_name')">Equipment Name
                                        
                                            <span>
                                                <i v-if="meta.keyword=='equipment_name' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='equipment_name' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span></th>

                                            <th @click="sort('description')">Description
                                            <span>
                                                <i v-if="meta.keyword=='description' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='description' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span></th>

                                        <th class="text-center" v-can="'equipment.delete'">Status</th>
                                        <th class="text-center" v-can="'equipment.update'">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="equipments.length==0">
                                        <td colspan="6" class="text-center">No records found</td>
                                    </tr>
                                    <tr v-for="equipment, key in equipments" :key="key">
                                        <td class="text-center">{{ meta.from + key }}</td>
                                        <td>{{ equipment.equipment_type?.equipment_type_name }}</td>
                                        <td>{{equipment.equipment_code}}</td>
                                        <td>{{ equipment.equipment_name }}</td>
                                        <td>{{ equipment.description }}</td>
                                        
                                        <td class="text-center" v-can="'equipment.delete'">
                                            <div class="form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" :id="'equipment' + equipment.equipment_id" :checked="equipment.status" :value="equipment.status" @change="deleteEquipment(equipment)" />
                                                <label class="custom-control-label" :for="'equipment' + equipment.equipment_id"></label>
                                            </div>
                                        </td>
                                        <td class="text-center" v-can="'equipment.update'">
                                            <a href="javascript:void(0)" v-if="equipment.status" class="text-success me-2" @click="editEquipment(equipment)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
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
            column:'col-8',
            meta: {
                search: '',
                order_by: "asc",
                keyword: "equipment_id",
                per_page: 10,
                totalRows: 0,
                page: 1,
                lastPage: 1,
                from: 1,
                maxPage: 1,
            },
            equipments: [],
            equipment: {
                equipment_id: '',
                equipment_type_id: '',
                equipment_code: '',
                equipment_name: '',
                description: '',
                status: ''
            },
            errors: [],
            status: true,
            equipment_types: []
        }
    },
    mounted() {
        this.create_equipment = this.$store.getters.permissions.filter(function(element){
            return element.ability.ability.includes('equipment.create')
        })
        if(this.create_equipment.length){
            this.column = 'col-8'
        }else{
            this.column = 'col-12'
        }
        this.index();
        this.getEquipmentTypes();
    },
    methods: {
        submitForm() {
            let vm = this;
            if (vm.status) {
                vm.addEquipment();
            } else {
                vm.updateEquipment();
            }
        },
        index() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'paginateEquipment', data:vm.meta })
                .then(response => {
                    loader.hide();
                    this.equipments = response.data.data;
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

        addEquipment() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'addEquipment', data: vm.equipment })
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

        deleteEquipment(equipment) {
            let vm = this;
            equipment.status = equipment.status == 1 ? 0 : 1;
            vm.$store
                .dispatch("post", { uri: "deleteEquipment", data: equipment })
                .then(function (response) {
                    vm.$store.dispatch('success', response.data.message);
                    vm.discard();
                })
                .catch(function (error) {
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },

        editEquipment(equipment) {
            this.equipment = equipment;
            this.status = false;
            this.errors=[];
        },

        updateEquipment() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'updateEquipment', data: this.equipment })
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

        getEquipmentTypes() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'getEquipmentTypes' })
                .then(response => {
                    loader.hide();
                    vm.equipment_types = response.data.data;
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
            let vm=this;
            vm.equipment = {};
            vm.equipment.equipment_type_id='';
            // vm.$refs.equipment_type_id.focus();
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

        generateBarCode(value) {
            this.equipment.equipment_code = value;
            var url = 'https://api.qrserver.com/v1/create-qr-code/?data=' + value + '&amp;size=50x50';
            $('#barcode').attr('src', url);
        }
    }
}
</script>
