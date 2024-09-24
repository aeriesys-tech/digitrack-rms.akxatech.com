<template>
    <div class="">
        <div>
            <ol class="breadcrumb fs-sm mb-1">
                <li class="breadcrumb-item">
                    <router-link to="/dashboard">Dashboard</router-link>
                </li>
                <li class="breadcrumb-item" aria-current="page">
                    <a href="javascript:void(0)">User Management</a></li>
                <li class="breadcrumb-item active" aria-current="page">Role</li>
            </ol>
            <h4 class="main-title mb-2">Role</h4>
        </div>
        <div class="row g-2">
            <div class="col-4" v-can="'roles.create'">
                <form @submit.prevent="submitForm()">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">Add Role</h6>
                            <h6 class="card-title" v-else>Update Role</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-12">
                                    <label class="form-label">Role</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Role" :class="{ 'is-invalid': errors.role }"
                                        class="form-control" v-model="role.role" ref="role"/>
                                    <span v-if="errors.role"><small class="text-danger">{{ errors.role[0] }}</small></span>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Description</label>
                                    <textarea rows="2" type="text" placeholder="Description" class="form-control"
                                        v-model="role.description"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <!-- <button type="reset" class="btn btn-danger me-2" @click="discard()"><i
                                    class="ri-close-line fs-18 lh-1"></i> DISCARD</button>
                            <button v-if="!update" type="submit" class="btn btn-primary" @click="addRole()"><i
                                    class="ri-save-line fs-18 lh-1"></i> SUBMIT</button>
                            <button v-else type="submit" class="btn btn-primary" @click="updateRole()"><i
                                    class="ri-save-line fs-18 lh-1"></i> UPDATE</button> -->


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
                        <h6 class="card-title">Roles</h6>
                    </div>
                    <div class="card-body">
                        <input class="form-control form-control-sm mb-2" type="text"
                            placeholder="Type keyword and press enter key" v-model="meta.search" @keypress.enter="search()"/>
                        <div class="table-responsive table-responsive-sm">
                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                <thead>
                                    <tr style="background-color: #9b9b9b; color: white;">
                                        <th class="text-center">#</th>
                                        <th @click="sort('role')">Role
                                            <span>
                                                <i v-if="meta.keyword=='role' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='role' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th @click="sort('description')">Description
                                            <span>
                                                <i v-if="meta.keyword=='description' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='description' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th class="text-center" v-can="'roles.delete'">Status</th>
                                        <th class="text-center" v-can="'roles.update'">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="role, key in roles" :key="key">
                                        <td class="text-center">{{ meta.from + key }}</td>
                                        <td>{{ role.role }}</td>
                                        <td>{{ role.description }}</td>
                                        <td class="text-center" v-can="'roles.delete'">
                                            <div class="form-switch" >
                                                <input class="form-check-input"  type="checkbox" role="switch" :id="'role' + role.role_id" :checked="role.status" :value="role.status" @change="deleteRole(role)" />
                                                <label class="custom-control-label" :for="'role' + role.role_id"></label>
                                            </div>
                                        </td>
                                        <td class="text-center" v-can="'roles.update'">
                                            <a href="javascript:void(0)" class="text-success" v-if="role.status"
                                                @click="editRole(role)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
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
                order_by: "asc",
                keyword: "role_id",
                per_page: 10,
                totalRows: 0,
                page: 1,
                lastPage: 1,
                from: 1,
                to: 1,
                maxPage: 1,
                trashed: false,
            },
            roles: [],
            role: {
                role_id: '',
                role: '',
                description: '',
                status: ''
            },
            status: true,
            errors: [],
            update: false
        }
    },
    mounted() {
        this.create_role = this.$store.getters.permissions.filter(function(element){
            return element.ability.ability.includes('roles.create')
        })
        if(this.create_role.length){
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
                vm.addRole();
            } else {
                vm.updateRole();
            }
        },
        index() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'paginateRoles' , data:vm.meta })
                .then(response => {
                    loader.hide();
                    this.roles = response.data.data;
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

        addRole() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'addRole', data: vm.role })
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

        deleteRole(role) {
            let vm = this;
            let loader = this.$loading.show();
            role.status = role.status == 1 ? 0 : 1;
            vm.$store
                .dispatch("post", { uri: "deleteRole", data: role })
                .then(function (response) {
                    loader.hide();
                    vm.$store.dispatch('success', response.data.message);
                    vm.discard();
                 })
                .catch(function (error) {
                    console.error(error);
                    // loader.hide();
                    // vm.errors = error.response.data.errors;
                    // vm.$store.dispatch("error", error.response.data.message);
                });
        },

        editRole(role) {
            let vm = this;
            this.role = role;
            this.update = true;
            this.status = false;
        },

        updateRole() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'updateRole', data: this.role })
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
            vm.role.role = "";
            vm.role.description= "";
            vm.$refs.role.focus();
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