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
                <li class="breadcrumb-item active" aria-current="page">Areas</li>
            </ol>
            <h4 class="main-title mb-2">Areas</h4>
        </div> 
        <div class="row g-2">
            <div class="col-4" v-can="'clusters.create'">
                <form @submit.prevent="submitForm()">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">Add Area</h6>
                            <h6 class="card-title" v-else>Update Area</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-12">
                                    <label class="form-label">Area Code</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Area Code" class="form-control" :class="{ 'is-invalid': errors.cluster_code }" v-model="cluster.cluster_code" ref="cluster_code"/>
                                    <span v-if="errors.cluster_code" class="invalid-feedback">{{ errors.cluster_code[0] }}</span>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Area Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Enter Area name" class="form-control" :class="{ 'is-invalid': errors.cluster_name }" v-model="cluster.cluster_name"/>
                                    <span v-if="errors.cluster_name" class="invalid-feedback">{{ errors.cluster_name[0] }}</span>
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
                        <h6 class="card-title">Areas</h6>
                    </div>
                    <div class="card-body">
                        <input class="form-control mb-2" type="text" placeholder="Type keyword and press enter key" v-model="meta.search" @keypress.enter="search()" />
                        <div class="table-responsive table-responsive-sm">
                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                <thead>
                                    <tr class="" style="background-color: #9b9b9b; color: white;">
                                        <th class="text-center">#</th>
                                        <th @click="sort('cluster_code')">Area Code
                                            <span>
                                                <i v-if="meta.keyword=='cluster_code' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='cluster_code' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span></th>
                                        <th @click="sort('cluster_name')">Area Name
                                        
                                            <span>
                                                <i v-if="meta.keyword=='cluster_name' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='cluster_name' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span></th>
                                       
                                        <th class="text-center" v-can="'clusters.delete'">Status</th>
                                        <th class="text-center" v-can="'clusters.update'">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="clusters.length==0">
                                        <td colspan="6" class="text-center">No records found</td>
                                    </tr>
                                    <tr v-for="cluster, key in clusters" :key="key">
                                        <td class="text-center">{{ meta.from + key }}</td>
                                        <td>{{cluster.cluster_code}}</td>
                                        <td>{{ cluster.cluster_name }}</td>
                                        <td class="text-center" v-can="'clusters.delete'">
                                            <div class="form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" :id="'cluster' + cluster.cluster_id" :checked="cluster.status" :value="cluster.status" @change="deleteCluster(cluster)" />
                                                <label class="custom-control-label" :for="'cluster' + cluster.cluster_id"></label>
                                            </div>
                                        </td>
                                        <td class="text-center" v-can="'clusters.update'">
                                            <a href="javascript:void(0)" v-if="cluster.status" class="text-success me-2" @click="editCluster(cluster)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
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
                keyword: "cluster_id",
                per_page: 10,
                totalRows: 0,
                page: 1,
                lastPage: 1,
                from: 1,
                to: 1,
                maxPage: 1,
                trashed: false,
            },
            clusters: [],
            cluster: {
                cluster_id: '',
                cluster_code: '',
                cluster_name: '',
                status: '',
            },
            status: true,
            errors: [],
        }
    },
    mounted() {
        this.create_cluster = this.$store.getters.permissions.filter(function(element){
                return element.ability.ability.includes('clusters.create')
            })
            if(this.create_cluster.length){
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
                vm.addCluster();
            } else {
                vm.updateCluster();
            }
        },
        index() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'paginateClusters' , data:vm.meta })
                .then(response => {
                    loader.hide();
                    vm.clusters = response.data.data;
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

        addCluster() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'addCluster', data: vm.cluster })
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

        deleteCluster(cluster) {
            let vm = this;
            cluster.status = cluster.status == 1 ? 0 : 1;
            vm.$store
                .dispatch("post", { uri: "deleteCluster", data: cluster })
                .then(function (response) {
                    vm.$store.dispatch('success', response.data.message);
                    vm.discard();
                })
                .catch(function (error) {
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },

        editCluster(cluster) {
            this.cluster = cluster;
            this.status = false;
        },

        updateCluster() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'updateCluster', data: this.cluster })
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
            vm.cluster.cluster_code = "";
            vm.cluster.cluster_name = "";
            vm.$refs.cluster_code.focus();
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
