<template>
    <div class="">
        <div>
            <ol class="breadcrumb fs-sm mb-1">
                <li class="breadcrumb-item">
                    <router-link to="/dashboard">Dashboard</router-link>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Profile</li>
            </ol>
            <h4 class="main-title mb-2">Profile</h4>
        </div>
        <div class="row g-2">
            <div class="col-2">
                <div class="card card-one">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="card-title">Avatar</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <img class="mb-2" width="100" :src="user?.avatar" />
                            <input type="file" class="form-control m-auto" id="customFile" @change="onImageChange($event)" accept="image/*" style="width: 98px;" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <form @submit.prevent="updateProfile">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title">Update Profile</h6>
                        </div>
                        <div class="card-body ">
                            <div class="row g-2">
                                <div class="col-md-12">
                                    <label class="form-label">Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Name" class="form-control" :class="{'is-invalid': errors.name}" v-model="user.name" ref="name" />
                                    <span v-if="errors.name" class="invalid-feedback">{{ errors.name[0] }}</span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label><span class="text-danger"> *</span>
                                    <input type="email" placeholder="Email" class="form-control" :class="{'is-invalid': errors.email}" v-model="user.email" />
                                    <span v-if="errors.email" class="invalid-feedback">{{ errors.email[0] }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">Mobile No.</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Mobile No." class="form-control" :class="{'is-invalid': errors.mobile_no}" v-model="user.mobile_no" />
                                    <span v-if="errors.mobile_no" class="invalid-feedback">{{ errors.mobile_no[0] }}</span>
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label">Address</label>
                                    <textarea type="text" placeholder="Address" class="form-control" :class="{'is-invalid': errors.address}" v-model="user.address"></textarea>
                                    <span v-if="errors.address" class="invalid-feedback">{{ errors.address[0] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary"><i class="ri-save-line fs-18 lh-1"></i> Update</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-4">
                <div class="card card-one">
                    <form @submit.prevent="updatePassword">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title">Change Password</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-sm-12">
                                    <label class="form-label">Current Password</label><span class="text-danger"> *</span>
                                    <input
                                        type="password"
                                        placeholder="Current Password"
                                        class="form-control"
                                        :class="{'is-invalid': errors.current_password}"
                                        v-model="user.current_password"
                                        @keyup="test(user.current_password,'current_password')"
                                    />
                                    <span v-if="errors.current_password" class="invalid-feedback">{{ errors.current_password[0] }}</span>
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label">New Password</label><span class="text-danger"> *</span>
                                    <input type="password" placeholder="New Password" class="form-control" :class="{'is-invalid': errors.new_password}" v-model="user.new_password" @keyup="test(user.new_password,'new_password')" />
                                    <span v-if="errors.new_password" class="invalid-feedback">{{ errors.new_password[0] }}</span>
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label">Confirm Password</label><span class="text-danger"> *</span>
                                    <input
                                        type="password"
                                        placeholder="Confirm Password"
                                        class="form-control"
                                        :class="{'is-invalid': errors.confirm_password}"
                                        v-model="user.confirm_password"
                                        @keyup="test(user.confirm_password,'confirm_password')"
                                    />
                                    <span v-if="errors.confirm_password" class="invalid-feedback">{{ errors.confirm_password[0] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary"><i class="ri-save-line fs-18 lh-1"></i> Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name: "Profile",
        data() {
            return {
                status: true,
                image: null,
                user: {
                    user_id: "",
                    name: "",
                    username: "",
                    email: "",
                    avatar: "",
                    role_id:"",
                    role: {
                        role: "",
                    },
                    mobile_no: "",
                    address: "",
                    current_password: "",
                    new_password: "",
                    confirm_password: "",
                },
                roles: [],
                users: [],
                errors: [],
            };
        },

        beforeRouteEnter(to, from, next) {
            next((vm) => {
                vm.getRoles()
                vm.user.user_id = vm.$store.getters.user?.user_id;
                vm.$refs.name.focus();
                let loader = vm.$loading.show();
                let uri = { uri: "me", data: vm.user };
                vm.$store
                    .dispatch("post", uri)
                    .then(function (response) {
                        loader.hide();
                        vm.user = response.data.data;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            });
        },
        methods: {
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

            test(password, field_name) {
                let vm = this;
                if (field_name == "current_password") {
                    vm.errors.current_password = "";
                }

                if (field_name == "new_password") {
                    vm.errors.new_password = "";
                }

                if (field_name == "confirm_password") {
                    vm.errors.confirm_password = "";
                }
            },
            updateProfile() {
                let vm = this;
                let loader = vm.$loading.show();
                let data = new FormData();
                data.append("user_id", vm.user.user_id);
                data.append("name", vm.user.name);
                data.append("email", vm.user.email);
                data.append("mobile_no", vm.user.mobile_no);
                data.append("avatar", vm.image);
                data.append("role_id", vm.user.role_id);
                data.append("address", vm.user.address);

                vm.$store
                    .dispatch("post", { uri: "updateProfile", data: data })
                    .then(function (response) {
                        loader.hide();
                        vm.$store.dispatch("success", "Profile is successfully updated");
                        vm.$store.dispatch("setUser", response.data.data);
                        vm.errors = [];
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            updatePassword() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "changePassword", data: vm.user })
                    .then(function () {
                        loader.hide();
                        vm.$store.dispatch("success", "Password is successfully updated");
                        vm.user.current_password="";
                        vm.user.new_password="";
                        vm.user.confirm_password="";
                        // vm.$router.push('/');
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            onImageChange(e) {
                e.preventDefault();
                let vm = this;
                vm.image = e.target.files[0];
                let reader = new FileReader();
                reader.readAsDataURL(vm.image);
                reader.onload = (e) => {
                    vm.user.avatar = e.target.result;
                };
            },
        },
    };
</script>

<style scoped>
.card-one{
    height:auto !important;
}
</style>