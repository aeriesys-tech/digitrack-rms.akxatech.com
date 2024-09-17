<template>
    <div class="page-sign">
        <div class="card card-sign">
            <div class="card-header d-flex flex-column align-items-center">
                <div class="mb-2">
                    <img src="../../assets/jsw.png" class="img" width="180"/>
                    </div>
                <a href="javascript:void(0)" class="header-logo mb-2" style="font-size: 25px; width: 350px; display: inline-block; white-space: nowrap;">Refractory Management System</a>
                <h3 class="card-title text-center">Reset Your Password</h3>
                <p class="card-text text-center">Enter the otp and reset your password here.</p>
            </div>
            <div class="card-body">
                <form @submit.prevent="resetPassword()">
                    <div class="mb-3">
                        <label class="form-label d-flex justify-content-between">Email</label>
                        <input type="email" class="form-control" placeholder="Enter your Email" :class="{ 'is-invalid': errors.email }" v-model="user.email" ref="email">
                        <!-- <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="ri-mail-fill fs-18 lh-4"></span>
                            </div>
                        </div> -->
                        <span v-if="errors.email" class="invalid-feedback">{{ errors.email[0] }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label d-flex justify-content-between">OTP</label>
                        <input type="text" class="form-control" placeholder="Enter your OTP" :class="{ 'is-invalid': errors.otp }" v-model="user.otp">
                        <span v-if="errors.otp" class="invalid-feedback">{{ errors.otp[0] }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label d-flex justify-content-between">Password</label>
                        <input type="password" class="form-control" placeholder="Enter your password" :class="{ 'is-invalid': errors.password }" v-model="user.password">
                        <span v-if="errors.password" class="invalid-feedback">{{ errors.password[0] }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label d-flex justify-content-between">Confirm Password</label>
                        <input type="password" class="form-control" placeholder="Confirm your password" :class="{ 'is-invalid': errors.confirm_password }" v-model="user.confirm_password">
                        <span v-if="errors.confirm_password" class="invalid-feedback">{{ errors.confirm_password[0] }}</span>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12">
                            <button method="submit" class="btn btn-primary btn-blosignck">Reset Password</button>
                        </div>
                    </div>
                    <div class="text-center">
                        Not you? <router-link to="/">Sign in with different account</router-link>
                      </div>
                </form>
            </div>
            
        </div>
        
        
    </div>
</template>
<script>
    export default {
        name: "reset_password",
        data() {
            return {
                user: {
                    email: "",
                    otp:"",
                    password: "",
                    confirm_password: "",
                },
                errors: [],
            };
        },
        mounted() {
            this.$refs.email.focus();
        },
        methods: {
            resetPassword() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "resetPassword", data: vm.user })
                    .then(function () {
                        loader.hide();
                        vm.$store.dispatch("success", "Password has been reset successfully");
                        vm.$router.push("/");
                        })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
        },
    };
</script>
