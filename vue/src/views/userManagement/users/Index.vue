<template>
    <div class="">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div>
                <ol class="breadcrumb fs-sm mb-1">
                    <li class="breadcrumb-item" aria-current="page">
                        <router-link to="/dashboard">Dashboard</router-link></li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a href="javascript:void(0)">User Management</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Users</li>
                </ol>
                <h4 class="main-title mb-0">Users</h4>
            </div>
            <router-link v-can="'users.create'" to="/user/create" class="btn btn-primary" style="float: right;"><i
                    class="ri-list-check"></i> ADD USER</router-link>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-one">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="card-title">Users</h6>
                    </div>
                    <div class="card-body">
                        <input class="form-control form-control-sm mb-2" type="text"
                            placeholder="Type keyword and press enter key" v-model="meta.search" @keypress.enter="search()" />
                        <div class="table-responsive table-responsive-sm">
                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                <tr style="background-color:#9b9b9b;color:white;">
                                        <th class="text-center">#</th>
                                        <th @click="sort('name')">
                                            Name
                                            <span>
                                                <i v-if="meta.keyword == 'name' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'name' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th @click="sort('email')">
                                            Email
                                            <span>
                                                <i v-if="meta.keyword == 'email' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'email' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th @click="sort('role_id')">
                                            Role
                                            <span>
                                                <i v-if="meta.keyword == 'role_id' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'role_id' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th @click="sort('plant_id')">
                                            Plant
                                            <span>
                                                <i v-if="meta.keyword == 'plant_id' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'plant_id' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th @click="sort('mobile_no')">
                                            Mobile No.
                                            <span>
                                                <i v-if="meta.keyword == 'mobile_no' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'mobile_no' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th class="text-center" v-can="'users.delete'">Status</th>
                                        <th class="text-center" v-can="'users.update'">Actions</th>
                                    </tr>
                                <tbody>
                                    <tr v-for="user, key in users" :key="key">
                                        <td class="text-center">{{ meta.from + key }}</td>
                                        <td>{{user.name}}</td>
                                        <td>{{user.email}}</td>
                                        <td>{{ user.role.role}}</td>
                                        <td>{{ user.plant?.plant_name }}</td>
                                        <td>{{ user.mobile_no }}</td>
                                        <td class="text-center" v-can="'users.delete'">
                                            <div class="form-switch" >
                                                <input class="form-check-input"  type="checkbox" role="switch" :id="'user' + user.user_id" :checked="user.status" :value="user.status" @change="deleteUser(user)" />
                                                <label class="custom-control-label" :for="'user' + user.user_id"></label>
                                            </div>
                                        </td>
                                        <td class="text-center" v-can="'users.update'">
                                            <a href="javascript:void(0)" class="text-success" v-if="user.status"
                                                @click="editUser(user)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
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
                search: '',
                order_by: "asc",
                keyword: "user_id",
                per_page: 10,
                totalRows: 0,
                page: 1,
                lastPage: 1,
                from: 1,
                to: 1,
                maxPage: 1,
                trashed: false,
            },
            users: [],
            errors: [],
            status: true,
        }
    },
    beforeRouteEnter(to, from, next) {
        next((vm) => {
            if(from.name != 'Users.Edit'){
                vm.$store.commit("setCurrentPage", vm.meta.page)
            }else{
                vm.meta.page = vm.$store.getters.current_page
            }
        });
    },
    mounted() {
        this.index();
    },

    methods: {
        index() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'paginateUsers', data: vm.meta })
                .then(response => {
                    loader.hide();
                    this.users = response.data.data;
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
        editUser(user) {
            this.$store.commit("setCurrentPage", this.meta.page)
            this.$router.push("/users/" + user.user_id + "/edit");
        },
        deleteUser(user) {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store
                .dispatch("post", {
                    uri: "deleteUser",
                    data: user
                })
                .then((response) => {
                    loader.hide();
                    vm.$store.dispatch("success", response.data.message);
                    vm.index();
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },
        search() {
            let vm = this;
            vm.meta.page = 1;
            vm.index();
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
        
    }
}
</script>
