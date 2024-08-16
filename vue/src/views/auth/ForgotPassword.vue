<template>
    <div class="page-auth">
    <div class="content">
      <div class="container">
        <form autocomplete="off" @submit.prevent="forgotPassword">
            <div class="card card-auth">
                <div class="card-body text-center">
                    <div class="mb-3">
                    <img src="../../assets/jsw.png" class="img" width="100" />
                    </div>
                    <h3 class="card-title">Forgot your password</h3>
                    <p class="card-text mb-3">Enter your email address and we will send you a OTP to reset your password.</p>

                    <div class="row g-2">
                    <div class="col-sm-8">
                        <input type="email" class="form-control" placeholder="Enter email" :class="{ 'is-invalid': errors.error }" v-model="user.email" ref="email" />
                        <span v-if="errors.error" class="invalid-feedback">{{ errors.error }}</span>
                    </div>
                    <div class="col-sm-4">
                        <button method="submit" class="btn btn-primary">Request OTP</button>
                    </div>
                    </div>
                </div>
            </div>
        </form>
      </div>
    </div>
</div>
</template>
<script>
    export default {
        name: "ForgotPassword",
        data() {
            return {
                user: {
                    email: "",
                },
                errors: [],
            };
        },
        mounted() {
            this.$refs.email.focus();
        },
        methods: {
            forgotPassword() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "forgotPassword", data: vm.user })
                    .then(function (response) {
                        loader.hide();
                        vm.$store.dispatch("success",response.data.success);
                        vm.$router.push("/reset_password");
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
        },
    };
</script>
