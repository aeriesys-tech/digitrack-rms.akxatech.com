<template>
	<div>
	<div class="d-sm-flex align-items-center justify-content-between">
		<div>
			<ol class="breadcrumb fs-sm mb-1">
				<!-- <li class="breadcrumb-item"><a href="#">Dashboard</a></li> -->
				<!-- <li class="breadcrumb-item active" aria-current="page">Product Management</li> -->
			</ol>
			<h4 class="main-title mb-0">Dashboard</h4>
		</div>
	</div>
	<div class="row g-2 mt-2">
		<div class="col-sm-3">
			<router-link to="/users">
				<div class="task-category p-3 bg-info">
					<h2 class="category-percent mb-2 text-center">{{ total_users }}</h2>
					<div class="divider px-3"><span>
							<h5 class="text-white">USERS</h5>
						</span></div>
				</div>
			</router-link>
		</div>
		<div class="col-sm-3">
			<router-link to="/assets">
				<div class="task-category p-3 bg-warning">
					<h2 class="category-percent mb-2 text-center">{{ total_asset }}</h2>
					<div class="divider px-3"><span>
							<h5 class="text-white">ASSETS</h5>
						</span></div>
				</div>
			</router-link>
		</div>
		<div class="col-sm-3">
			<router-link to="/equipment">
				<div class="task-category p-3 bg-secondary">
					<h2 class="category-percent mb-2 text-center">{{ total_equipment }}</h2>
					<div class="divider px-3"><span>
							<h5 class="text-white">EQUIPMENT</h5>
						</span></div>
				</div>
			</router-link>
		</div>
		<div class="col-sm-3">
			<router-link to="/service_types">
				<div class="task-category p-3" style="background-color: coral;">
					<h2 class="category-percent mb-2 text-center">{{ total_service_types }}</h2>
					<div class="divider px-3"><span>
							<h5 class="text-white">SERVICE TYPES</h5>
						</span></div>
				</div>
			</router-link>
		</div>
		<div class="col-sm-3">
			<router-link to="/Deviations">
				<div style="display: flex; justify-content: center;">
					<span style="position: absolute; margin-top: 40px;" class="h2 text-white">{{ total_deviations }}</span>
					<span style="margin-top: 42%;" class="h5 text-white">DEVIATIONS</span>
					<img src="assets/images/blue_triangle.png" width="200px" height="150px" alt="" style="z-index: -1; position: absolute;">
				</div>
			</router-link>		
		</div>
		
		<div class="col-sm-3">
			<router-link to="/Pendings">
				<div style="display: flex; justify-content: center;">
					<span style="position: absolute; margin-top: 40px;" class="h2 text-white">{{ total_pending_services }}</span>
					<span style="margin-top: 42%;" class="h5 text-white">PENDINGS/ </span><br>
					<span style="margin-top: 42%;" class="h5 text-white">OVERDUES</span>
					<img src="assets/images/pending_triangle.png" width="200px" height="150px" alt="" style="z-index: -1; position: absolute;">
				</div>
			</router-link>		
		</div>

		<div class="col-sm-3">
			<router-link to="/UpcomingJobs">
				<div style="display: flex; justify-content: center;">
					<span style="position: absolute; margin-top: 40px;" class="h2 text-white">{{ total_upcoming_jobs }}</span>
					<span style="margin-top: 40%;" class="h5 text-white">UPCOMING JOBS</span><br>
					<img src="assets/images/purple_triangle.png" width="200px" height="150px" alt="" style="z-index: -1; position: absolute;">
				</div>
			</router-link>		
		</div>
	</div>
</div>
</template>
<script>
export default {
	name: "Dashboard",
	data(){
		return {
			total_users:0,
			total_asset:0,
			total_equipment:0,
			total_service_types:0,
			total_deviations:0,
			total_pending_services:0,
			total_upcoming_jobs:0,
		}
	},
	beforeRouteEnter(to, from, next) {
            next((vm) => {
                if (from.name == "login") {
                    // location.reload()
                    // vm.index();
					vm.$router.go();
                }
				vm.getDashboardContent();
				
            });
        },
		methods:{
			getDashboardContent() {
                let vm = this;
                vm.$store
                    .dispatch("post", { uri: "getDashboardContent" })
                    .then(function (response) {
						vm.total_asset = response.data.asset
						vm.total_equipment = response.data.equipment
						vm.total_users = response.data.user
						vm.total_service_types = response.data.service_type,
						vm.total_deviations = response.data.deviations
						vm.total_pending_services = response.data.pending_services
						vm.total_upcoming_jobs = response.data.upcoming_jobs
					})
                    .catch(function (error) {
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
		}
}
</script>
