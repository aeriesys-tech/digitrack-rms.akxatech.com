<template>
    <div class="page-sign d-block st py-0">
        <div class="row g-0">
            <div class="col-md-7 col-lg-5 col-xl-4 col-wrapper">
                <div class="card card-sign">
                    <div class="card-header">
                        <div class="card-header">
                            <div class="text-center">
                                <img src="../../assets/jsw.png" class="img" width="200" />
                            </div>
                            <div class="text-center">
                                <a href="#" class="text-center header-logo mb-3 mt-2"><span class="title" style="font-size: 24px;">Refractory Management </span></a>
                            </div>
                        </div>
                        <h6 class="card-title">Sign In</h6>
                        <p class="card-text">Welcome back! Please signin to continue.</p>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="login">
                            <div class="mb-4">
                                <label class="form-label">Email address</label>
                                <input type="text" tabindex="1" class="form-control" placeholder="Enter your email address" :class="{ 'is-invalid': errors.email }" v-model="user.email" ref="email" />
                                <span v-if="errors.email" class="invalid-feedback">{{ errors.email[0] }}</span>
                            </div>
                            <div class="mb-4">
                                <label class="form-label d-flex justify-content-between">Password <router-link to="/forgot_password">Forgot password?</router-link></label>
                                <div class="input-group">
                                    <input
                                        tabindex="1"
                                        :type="showPassword ? 'text' : 'password'"
                                        class="form-control border-end-0"
                                        v-model="user.password"
                                        :class="{ 'is-invalid': errors.password }"
                                        placeholder="Password"
                                        aria-label="Password"
                                        aria-describedby="basic-addon2"
                                    />
                                    <span class="input-group-text" :class="{ 'is-invalid': errors.password }" id="basic-addon2" @click="togglePassword">
                                        <i :class="showPassword ? 'ri-eye-line' : 'ri-eye-off-line'"></i>
                                    </span>
                                    <span v-if="errors.password" class="invalid-feedback">{{ errors.password[0] }}</span>
                                </div>
                                <span v-if="errors.password" class="invalid-feedback">{{ errors.password[0] }}</span>
                            </div>
                            <button method="submit" tabindex="1" class="btn btn-primary btn-sign">Sign In</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col d-none d-lg-block"><img src="assets/images/bg2.jpg" class="auth-img" alt="" /></div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                user: {
                    email: "bharatesh.s@akxatech.com",
                    password: "1qaz2wsx",
                },
                showPassword: false,
                errors: [],
            };
        },
        mounted() {
            this.$refs.email.focus();
        },
        methods: {
            login() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("auth", { uri: "login", data: vm.user })
                    .then(function (response) {
                        loader.hide();
                        vm.$store.dispatch("setUser", response.data.user);
                        vm.$store.dispatch("setToken", response.data.token);
                        // vm.$router.push("/dashboard");
                        vm.getPermissions();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            togglePassword() {
                this.showPassword = !this.showPassword;
            },
            getPermissions() {
                let vm = this;
                let uri = {
                    uri: "getPermissions",
                    data: {
                        role_id: this.$store.getters.user.role.role_id,
                    },
                };
                let loader = vm.$loading.show();
                this.$store
                    .dispatch("post", uri)
                    .then((response) => {
                        loader.hide();
                        vm.$store.dispatch("setPermissions", response.data);
                        vm.$router.push("/dashboard");
                    })
                    .catch((error) => {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
        },
    };
</script>
<style>
    .input-group-text.is-invalid {
        border-color: #dc3545;
    }
    .input-group-text {
        font-size: 16px;
        background-color: transparent;
    }
    .input-group:focus-within .input-group-text {
    border-color: #1d4ed8;
}
</style>
