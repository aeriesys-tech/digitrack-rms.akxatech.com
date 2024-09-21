<template>
    <div class="">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div>
                <ol class="breadcrumb fs-sm mb-1">
                    <li class="breadcrumb-item" aria-current="page">
                        <router-link to="/dashboard">Dashboard</router-link></li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a href="javascript:void(0)">User Management</a></li>
                        <li class="breadcrumb-item" aria-current="page">
                        <router-link to="/users">Users</router-link></li>
                    <li class="breadcrumb-item " aria-current="page" v-if="status">New User</li>
                    <li class="breadcrumb-item active" aria-current="page" v-else>Update User</li>
                </ol>
                <h4 class="main-title mb-0">User</h4>
            </div>
            <router-link to="/users" type="submit" class="btn btn-primary" style="float: right;"><i
                    class="ri-list-check"></i> USERS</router-link>
        </div>
        <div class="row">
            <div class="col-12">
                <form @submit.prevent="submitForm">
                <div class="card card-one">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="card-title" v-if="status">New User</h6>
                        <h6 class="card-title" v-else>Update User</h6>
                    </div>
                    <div class="card-body ">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label">Name</label><span class="text-danger"> *</span>
                                <input type="text" placeholder="Name" class="form-control" :class="{'is-invalid':errors.name}" v-model="user.name" ref="name"/>
                                <span v-if="errors.name" class="invalid-feedback">{{ errors.name[0] }}</span>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label><span class="text-danger"> *</span>
                                <input type="email" placeholder="Email" class="form-control" :class="{'is-invalid':errors.email}" v-model="user.email" />
                                <span v-if="errors.email" class="invalid-feedback">{{ errors.email[0] }}</span>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Password</label><span class="text-danger"> *</span>
                                <input type="password" :disabled="!status" placeholder="Password" class="form-control" :class="{'is-invalid':errors.password}" v-model="user.password" />
                                <span v-if="errors.password" class="invalid-feedback">{{ errors.password[0] }}</span>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Mobile No.</label><span class="text-danger"> *</span>
                                <input type="text" placeholder="Mobile No." class="form-control" v-model="user.mobile_no" :class="{'is-invalid':errors.mobile_no}" />
                                <span v-if="errors.mobile_no" class="invalid-feedback">{{ errors.mobile_no[0] }}</span>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Role</label><span class="text-danger"> *</span>
                                <select class="form-control" v-model="user.role_id" :class="{ 'is-invalid': errors.role_id }">
                                    <option value="">Select Role</option>
                                    <option v-for="role, key in roles" :key="key" :value="role.role_id">{{ role.role }}</option>
                                </select>
                                <span v-if="errors.role_id" class="invalid-feedback">{{ errors.role_id[0] }}</span>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Address</label>
                                <textarea rows="2" type="text" placeholder="Address" class="form-control" v-model="user.address" :class="{ 'is-invalid': errors.address }"></textarea>
                                <span v-if="errors.address" class="invalid-feedback">{{ errors.address[0] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                            <button type="button" class="btn btn-danger me-2" @click.prevent="discard()"><i class="ri-close-line fs-18 lh-1"></i> Discard</button>
                            <button type="submit" class="btn  btn-primary" >
                                <span v-if="status"><i class="ri-save-line fs-18 lh-1"></i> Submit</span>
                                <span v-else><i class="ri-save-line fs-18 lh-1"></i> Update</span>
                            </button>
                        </div>
                </div>
            </form>
            </div>
        </div>
    </div>
</template>
<script>
 import Search from "@/components/Search.vue";
export default {
    components: {
            Search,
        },
    name: "User.Create",
    data() {
        return {

            user: {
                user_id: '',
                name: '',
                email: '',
                password: '',
                mobile_no: '',
                role_id: '',
                plant_id: '',
                address: ''
            },
            roles: [],
            plants:[],
            // user_update: false,
            errors: [],
            status:true,
        }
    },
    // beforeRouteEnter(to, from, next) {
    //     next(vm => {
    //         vm.getRoles();
    //         if(to.name == 'UserUpdate'){
    //             vm.user_update = true;
    //             vm.user.user_id = to.params.user_id;
    //             vm.getUser();
    //         }
    //     });
    // },
    beforeRouteEnter(to, from, next) {
            next((vm) => {
                vm.getRoles();
                if (to.name == "Users.Create") {
                    vm.$store.commit("setCurrentPage", 1)
                    vm.$refs.name.focus();
                } else {
                    vm.status = false;
                    let uri = { uri: "getUser", data: { user_id: to.params.user_id } };
                    vm.$store
                        .dispatch("post", uri)
                        .then(function (response) {
                            vm.user = response.data.data;
                        })
                        .catch(function (error) {
                            vm.errors = error.response.data.errors;
                            vm.$store.dispatch("error", error.response.data.message);
                        });
                }
            });
        },
    methods: {
        submitForm() {
                let vm = this;
                if (vm.status) {
                    vm.addUser();
                } else {
                    vm.updateUser();
                }
            },
        getRoles() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'getRoles' })
                .then(response => {
                    loader.hide();
                    vm.roles = response.data.data;
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },

        addUser(){
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'addUser', data:this.user })
                .then(response => {
                    loader.hide();
                    this.$store.dispatch('success',response.data.message);
                    vm.$router.push("/users");
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },

        updateUser(){
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'updateUser', data:this.user })
                .then(response => {
                    loader.hide();
                    this.$store.dispatch('success',response.data.message);
                    this.$router.push('/users');
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },

        getUser(){
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'showUser', data:this.user })
                .then(response => {
                    loader.hide();
                    this.user = response.data.data;
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },
        discard() {
                let vm = this;
                vm.user.name = "";
                vm.user.email = "";
                vm.user.password = "";
                vm.user.mobile_no = "";
                vm.user.address = "";
                vm.user.role_id = "";
                vm.$refs.name.focus();
                vm.errors = [];
                vm.status = true;
            },
    }
}
</script>
