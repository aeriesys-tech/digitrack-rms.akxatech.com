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
                <li class="breadcrumb-item active" aria-current="page">Shops</li>
            </ol>
            <h4 class="main-title mb-2">Shops</h4>
        </div>
        <div class="row g-2">
            <div class="col-4" v-can="'plants.create'">
                <form @submit.prevent="submitForm()">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">Add Shop</h6>
                            <h6 class="card-title" v-else>Update Shop</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-12">
                                    <label class="form-label">Area</label><span class="text-danger"> *</span>
                                    <search
                                        :class="{ 'is-invalid': errors.area_id }"
                                        :customClass="{ 'is-invalid': errors.area_id }"
                                        :initialize="plant.area_id"
                                        id="area_id"
                                        label="area_name"
                                        placeholder="Select Area"
                                        :data=" areas"
                                        @input=" area => plant.area_id = area"
                                    >
                                    </search>
                                    <span v-if="errors.area_id" class="invalid-feedback">{{ errors.area_id[0] }}</span>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Shop Code</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Enter Shop Code" class="form-control" :class="{ 'is-invalid': errors.plant_code }"
                                        v-model="plant.plant_code" />
                                    <span v-if="errors.plant_code" class="invalid-feedback">{{ errors.plant_code[0] }}</span>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Shop Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Enter Shop Name" class="form-control" :class="{ 'is-invalid': errors.plant_name }"
                                        v-model="plant.plant_name" />
                                    <span v-if="errors.plant_name" class="invalid-feedback">{{ errors.plant_name[0] }}</span>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Latitude</label>
                                    <input type="text" placeholder="Enter Latitude" class="form-control" :class="{ 'is-invalid': errors.latitude }"
                                        v-model="plant.latitude" />
                                    <span v-if="errors.latitude" class="invalid-feedback">{{ errors.latitude[0] }}</span>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Longitude</label>
                                    <input type="text" placeholder="Enter Longitude" class="form-control" :class="{ 'is-invalid': errors.longitude }"
                                        v-model="plant.longitude" />
                                    <span v-if="errors.longitude" class="invalid-feedback">{{ errors.longitude[0] }}</span>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Radius</label>
                                    <input type="text" placeholder="Enter Radius" class="form-control" :class="{ 'is-invalid': errors.radius }"
                                        v-model="plant.radius" />
                                    <span v-if="errors.radius" class="invalid-feedback">{{ errors.radius[0] }}</span>
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
                        <h6 class="card-title">Shops</h6>
                    </div>
                    <div class="card-body">
                        <input class="form-control mb-2" type="text" placeholder="Type keyword and press enter key" v-model="meta.search" @keypress.enter="search()" />
                        <div class="table-responsive table-responsive-sm">
                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                <thead>
                                    <tr style="background-color: #9b9b9b; color: white;">
                                        <th>#</th>
                                        <th @click="sort('area_name')">
                                            Area
                                            <span>
                                                <i v-if="meta.keyword=='area_name' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='area_name' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th @click="sort('plant_code')">Shop Code
                                            <span>
                                                <i v-if="meta.keyword=='plant_code' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='plant_code' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span></th>
                                        <th @click="sort('plant_name')">Shop Name

                                            <span>
                                                <i v-if="meta.keyword=='plant_name' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='plant_name' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>

                                        <th @click="sort('plant_name')">Latitude

                                            <span>
                                                <i v-if="meta.keyword=='latitude' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='latitude' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>

                                        <th @click="sort('plant_name')">Longitude

                                            <span>
                                                <i v-if="meta.keyword=='longitude' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='longitude' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>

                                        <th @click="sort('plant_name')">Radius

                                        <span>
                                            <i v-if="meta.keyword=='radius' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                            <i v-else-if="meta.keyword=='radius' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                            <i v-else class="fas fa-sort"></i>
                                        </span>
                                    </th>

                                        <th class="text-center" v-can="'plants.delete'">Status</th>
                                        <th class="text-center" v-can="'plants.update'">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="plants.length==0">
                                        <td colspan="9" class="text-center">No records found</td>
                                    </tr>
                                    <tr v-for="plant, key in plants" :key="key">
                                        <td class="text-center">{{ meta.from + key }}</td>
                                        <td>{{plant?.Area?.area_name}}</td>
                                        <td>{{plant.plant_code}}</td>
                                        <td>{{plant.plant_name }}</td>
                                        <td>{{plant.latitude }}</td>
                                        <td>{{plant.longitude }}</td>
                                        <td>{{plant.radius }}</td>
                                        <td class="text-center" v-can="'plants.delete'">
                                            <div class="form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" :id="'plant' + plant.plant_id" :checked="plant.status" :value="plant.status" @change="deletePlant(plant)" />
                                                <label class="custom-control-label" :for="'plant' + plant.plant_id"></label>
                                            </div>
                                        </td>
                                        <td class="text-center" v-can="'plants.update'">
                                            <a href="javascript:void(0)" v-if="plant.status" class="text-success me-2" @click="editPlant(plant)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
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
                order_by: "desc",
                keyword: "plant_id",
                per_page: 10,
                totalRows: 0,
                page: 1,
                lastPage: 1,
                from: 1,
                maxPage: 1,
            },
            plants: [],
            plant: {
                plant_id: '',
                area_id: '',
                plant_code: '',
                plant_name: '',
                latitude:'',
                longitude:'',
                radius:'',
                status: '',
            },
            errors: [],
            status: true,
            areas: [],
            column:'col-8',
        }
    },
    mounted() {
        this.create_plant = this.$store.getters.permissions.filter(function(element){
            return element.ability.ability.includes('plants.create')
        })
        if(this.create_plant.length){
            this.column = 'col-8'
        }else{
            this.column = 'col-12'
        }
        this.index();
        this.getAreas();
    },

    methods: {
        submitForm() {
            let vm = this;
            if (vm.status) {
                vm.addPlant();
            } else {
                vm.updatePlant();
            }
        },
        index() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'paginatePlants', data:vm.meta })
                .then(response => {
                    loader.hide();
                    vm.plants = response.data.data;
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

        addPlant() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'addPlant', data: vm.plant })
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

        deletePlant(plant) {
            let vm = this;
            plant.status = plant.status == 1 ? 0 : 1;
            vm.$store
                .dispatch("post", { uri: "deletePlant", data: plant })
                .then(function (response) {
                    vm.$store.dispatch('success', response.data.message);
                    vm.discard();
                })
                .catch(function (error) {
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },

        editPlant(plant) {
            this.plant = plant;
            this.status = false;
        },

        updatePlant() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'updatePlant', data: vm.plant })
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

        getAreas() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'getAreas' })
                .then(response => {
                    loader.hide();
                    vm.areas = response.data.data;
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
            vm.plant.area_id="";
            vm.plant.plant_code = "";
            vm.plant.plant_name = "";
            vm.plant.latitude = "";
            vm.plant.longitude = "";
            vm.plant.radius = "";
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
