<template>
	
    <div class="header-main px-3 px-lg-4">
		<a id="menuSidebar" href="#" class="menu-link me-3 me-lg-4"><i class="ri-menu-2-fill"></i></a>
		<div class="me-auto">
			<!-- <h4 class="mt-1 text-dark">UltraTech Cement Limited</h4> -->
            <!-- <h4 class="mt-2 text-dark">{{ $store.getters?.user?.plant?.plant_name}}</h4> -->
            <img src="../../src/assets/jsw.png" class="brand-image img-circle1 elevation-3 me-2" style="opacity: 0.8; width:200px;" />
		</div>

		<div class="dropdown dropdown-profile ms-3 ms-xl-4 mt-4">
            <a href="#" class="dropdown-link d-flex" data-bs-toggle="dropdown" aria-expanded="false" >
				<div class="text-end me-2">
				<h5 class=" mb-0 text-dark">{{ $store.getters?.user?.name}}</h5>
				<p class="fs-sm text-secondary">{{ $store.getters.user.role?.role }}</p>
				</div>
                <div class="avatar online" v-if="$store.getters.user?.avatar"><img :src="$store.getters.user?.avatar" alt="" /></div>
                <div class="avatar online" v-else><img src="../../public/assets/images/default_avatar.png" alt="" /></div>
            </a>
            <div class="dropdown-menu dropdown-menu-end mt-10-f" aria-expanded="false">
                <div class="dropdown-menu-body">
                    <h5 class="mb-1 text-dark fw-semibold">{{ $store.getters?.user?.name }}</h5>
                    <p class="fs-sm text-secondary">{{ $store.getters.user.role?.role}}</p>
                    <nav class="nav">
                        <router-link class="dropdown-item" to="/profile" ><i class="ri-edit-2-line"></i> Edit Profile</router-link>
                        <a href="javascript:void(0)" @click="logout()"><i class="ri-logout-box-r-line"></i> Log Out</a>
                    </nav>
                </div>
            </div>
        </div> 
	</div>
</template>
<script>
	export default {
		methods: {
			logout() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store
                .dispatch("post", { uri: "logout", data: vm.$store.getters.user })
                .then(function () {
                    loader.hide();
                    vm.$store.dispatch("logout");
                    vm.$store.dispatch("setUser", null);
                    vm.$store.dispatch("setToken", "");
                    // vm.$store.dispatch("setPermissions", null);
                    vm.$router.push("/");
                    //  location.reload();
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },
		}
	}
</script>
