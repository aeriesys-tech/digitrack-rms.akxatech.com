<!-- <template>
    <div class="page-sign">
        <div class="card card-sign">
      <div class="card-header">
        <a href="../index.html" class="header-logo mb-4">Motor Management</a>
        <h3 class="card-title">Sign In</h3>
        <p class="card-text">Welcome back! Please signin to continue.</p>
      </div>
      <div class="card-body">
        <div class="mb-4">
          <label class="form-label">Email address</label>
          <input type="text" class="form-control" placeholder="Enter your email address">
        </div>
        <div class="mb-4">
          <label class="form-label d-flex justify-content-between">Password <a href="#">Forgot password?</a></label>
          <input type="password" class="form-control" placeholder="Enter your password">
        </div>
        <router-link to="/dashboard" class="btn btn-primary btn-sign">Sign In</router-link>

       
      </div>
      <div class="card-footer">
        Don't have an account? <router-link to="/signup">Create an Account</router-link>
      </div>
    </div>
    </div>
</template> -->

<template>
  <div class="page-sign d-block st py-0">
    <div class="row g-0">
      <div class="col-md-7 col-lg-5 col-xl-4 col-wrapper">
        <div class="card card-sign">
          <div class="card-header">
            <!-- <a href="../index.html" class="header-logo mb-5"><img src="assets/images/brand_logo.png" style="width: 150px;" alt=""></a>
            <h3 class="card-title">Sign In</h3>
            <p class="card-text">Welcome back! Please signin to continue.</p> -->


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
          </div><!-- card-header -->
          <div class="card-body">
            <form @submit.prevent="login">
                <div class="mb-4">
                    <label class="form-label">Email address</label>
                    <input type="text" tabindex=1 class="form-control" placeholder="Enter your email address"
                        :class="{ 'is-invalid': errors.email }" v-model="user.email" ref="email" />
                    <span v-if="errors.email" class="invalid-feedback">{{ errors.email[0] }}</span>
                </div>
                <div class="mb-4">
                    <label class="form-label d-flex justify-content-between">Password <router-link
                            to="/forgot_password">Forgot password?</router-link></label>
                    <input type="password" tabindex=1 class="form-control" placeholder="Enter your password"
                        :class="{ 'is-invalid': errors.password }" v-model="user.password" />
                    <span v-if="errors.password" class="invalid-feedback">{{ errors.password[0] }}</span>
                </div>
                <button method="submit" tabindex=1 class="btn btn-primary btn-sign">Sign In</button>
            </form>
        </div><!-- card-body -->
          <!-- <div class="card-footer">
            Don't have an account? <a href="sign-up-2.html">Create an Account</a>
          </div> -->
        </div><!-- card -->
      </div><!-- col -->
      <div class="col d-none d-lg-block"><img src="assets/images/bg2.jpg" class="auth-img" alt=""></div>
    </div>
  </div>
</template>
<script>
  export default {
    data() {
        return {
            user: {
                email: "",
                password: ""
            },
            errors: [],
        };
    },
    mounted() {
        this.$refs.email.focus();
    },
    methods:{
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

        getPermissions() {
            let vm = this;
            let uri = {
                uri: 'getPermissions',
                data: {
                    role_id: this.$store.getters.user.role.role_id
                }
            };
            let loader = vm.$loading.show();
            this.$store.dispatch('post', uri)
            .then(response => {
                loader.hide();
                vm.$store.dispatch('setPermissions', response.data);
                vm.$router.push("/dashboard");
            })
            .catch(error => {
                loader.hide();
                vm.errors = error.response.data.errors;
                vm.$store.dispatch("error", error.response.data.message);
            })
        },
    }
  }
</script>
<style>
/* .st { */
    /* background-image: linear-gradient(to right, #053aed 40%, #323335 100%)!important; */
    /* background-image: linear-gradient(to right, #323335 40%, #053aed 100%)!important; */
    /* background-image: linear-gradient(to right, #323335 40%, #323335 100%)!important; */
    /* background-image: linear-gradient(to right, #053aed 10%, #323335 100%)!important; */
/* } */

</style>
