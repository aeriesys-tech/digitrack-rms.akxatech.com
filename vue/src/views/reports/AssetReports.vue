<template>
    <div class="">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div>
                <ol class="breadcrumb fs-sm mb-1">
                    <li class="breadcrumb-item" aria-current="page">
                        <router-link to="/dashboard">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="javascript:void(0)">Asset Reports</a>
                    </li>
                </ol>
                <h4 class="main-title mb-0">Asset Reports</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-one mb-3">
                    <div class="card-header align-items-center d-flex justify-content-between">
                        <h6 class="card-title">Asset Reports</h6>
                        <button class="btn btn-success" @click="downloadExcel()" style="float: right;"><i class="ri-file-excel-line"></i> DOWNLOAD EXCEL</button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-auto">
                                <label class="form-label">Asset Type</label>
                                <select class="form-control mb-3" v-model="meta.asset_type_id" @change="getAsset()">
                                    <option value="">Select Asset Type</option>
                                    <option v-for="asset_type, key in asset_types" :key="key" :value="asset_type.asset_type_id">{{ asset_type.asset_type_name }}</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <label class="form-label">Asset</label>
                                <select class="form-control mb-3" v-model="meta.asset_id" @change="getDepartments()">
                                    <option value="">Select Asset </option>
                                    <option v-for="asset, key in assets" :key="key" :value="asset.asset_id">{{ asset.asset_code}}::{{ asset.asset_name }}</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <label class="form-label">Department</label>
                                <select class="form-control mb-3" v-model="meta.department_id">
                                    <option value="">Select Department</option>
                                    <option v-for="department, key in departments" :key="key" :value="department.department_id">{{ department.department_name }}</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <label class="form-label">Register</label>
                                <select class="form-control mb-3" v-model="meta.register">
                                    <option value="">Select Register</option>
                                    <option value="all_registers">All Registers</option>
                                    <option value="activity_register">Activity Register</option>
                                    <option value="service_register">Service Register</option>
                                    <option value="check_register">Check Register</option>
                                    <option value="process_register">Process Register</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <label class="form-label">From Date</label>
                                <input class="form-control mb-3" type="date" v-model="meta.from_date" />
                            </div>
                            <div class="col-auto">
                                <label class="form-label">To Date</label>
                                <input class="form-control mb-3" type="date" v-model="meta.to_date" />
                            </div>
                            <div class="col-auto mt-auto">
                                <button class="btn mb-3 btn-primary me-2" @click="search">Search</button>
                                <button class="btn mb-3 btn-danger" @click="reset">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-3" v-if="meta.register=='activity_register' || meta.register=='all_registers'">
                    <ActivityRegisterTable ref="activityRegister" :meta1="meta"></ActivityRegisterTable>
                </div>
                <div class="mb-3" v-if="meta.register=='service_register' || meta.register=='all_registers'">
                    <ServiceRegisterTable ref="serviceRegister" :meta1="meta"></ServiceRegisterTable>
                </div>
                <div class="mb-3" v-if="meta.register=='check_register' || meta.register=='all_registers'">
                    <CheckRegisterTable ref="checkRegister" :meta1="meta"></CheckRegisterTable>
                </div>
                <div v-if="meta.register=='process_register' || meta.register=='all_registers'">
                    <ProcessRegisterTable ref="processRegister" :meta1="meta"></ProcessRegisterTable>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import ActivityRegisterTable from "@/components/ActivityRegisterTable.vue";
    import ServiceRegisterTable from "@/components/ServiceRegisterTable.vue";
    import CheckRegisterTable from "@/components/CheckRegisterTable.vue";
    import ProcessRegisterTable from "@/components/ProcessRegisterTable.vue";
    import Pagination from "@/components/Pagination.vue";
    export default {
        components: {
            Pagination,
            ActivityRegisterTable,
            ServiceRegisterTable,
            CheckRegisterTable,
            ProcessRegisterTable,
        },
        data() {
            return {
                meta: {
                    search: "",
                    order_by: "asc",
                    keyword: "user_service_id",
                    per_page: 10,
                    totalRows: 0,
                    page: 1,
                    lastPage: 1,
                    from: 1,
                    to: 1,
                    maxPage: 1,
                    trashed: false,
                    department_id: "",
                    asset_type_id: "",
                    asset_id: "",
                    from_date: "",
                    to_date: "",
                    download: "excel",
                    register: "all_registers",
                },
                user_services: [],
                errors: [],
                asset_types: [],
                assets: [],
                departments: [],
                downloaded_excel: [],
                status: true,
            };
        },
        // beforeRouteEnter(to, from, next) {
        //     next((vm) => {
        //         if(from.name != 'Users.Edit'){
        //             vm.$store.commit("setCurrentPage", vm.meta.page)
        //         }else{
        //             vm.meta.page = vm.$store.getters.current_page
        //         }
        //     });
        // },
        mounted() {
            // this.index();
            this.getAssetTypes();
        },
        methods: {
            index() {
                let vm = this;
                let loader = this.$loading.show();
                this.$store
                    .dispatch("post", { uri: "paginatePendingJobs", data: vm.meta })
                    .then((response) => {
                        loader.hide();
                        vm.user_services = response.data.data;
                        vm.meta.totalRows = response.data.meta.total;
                        vm.meta.from = response.data.meta.from;
                        vm.meta.lastPage = response.data.meta.last_page;
                        const end = vm.meta.from + Number(vm.meta.per_page) - 1;
                        vm.meta.to = end > vm.meta.totalRows ? vm.meta.totalRows : end;
                        vm.meta.maxPage = vm.meta.lastPage >= 3 ? 3 : vm.meta.lastPage;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getAssetTypes() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getAssetTypes" })
                    .then((response) => {
                        loader.hide();
                        vm.asset_types = response.data.data;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getAsset() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getAssetTypeAssets", data: vm.meta })
                    .then((response) => {
                        loader.hide();
                        console.log("dd--", response.data);
                        vm.assets = response.data;
                        console.log(" vm.assets--", vm.assets);
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getDepartments() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getDepartments", data: vm.meta })
                    .then((response) => {
                        loader.hide();
                        vm.departments = response.data.data;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            downloadExcel() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "downloadAllRegisters", data: vm.meta })
                    .then((response) => {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.downloaded_excel = response.data;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            reset() {
                let vm = this;
                vm.meta.asset_type_id = "";
                vm.meta.asset_id = "";
                vm.meta.department_id = "";
                vm.meta.from_date = "";
                vm.meta.to_date = "";
                vm.meta.register = "all_registers";
                vm.errors = [];
                if (this.$refs.activityRegister?.index) {
                    this.$refs.activityRegister.index();
                }
                if (this.$refs.serviceRegister?.index) {
                    this.$refs.serviceRegister.index();
                }
                if (this.$refs.checkRegister?.index) {
                    this.$refs.checkRegister.index();
                }
                if (this.$refs.processRegister?.index) {
                    this.$refs.processRegister.index();
                }
            },
            search() {
                let vm = this;
                vm.meta.page = 1;
                // vm.index();
                if (vm.meta.register === "activity_register" && this.$refs.activityRegister) {
                    this.$refs.activityRegister.index();
                } else if (vm.meta.register === "service_register" && this.$refs.serviceRegister) {
                    this.$refs.serviceRegister.index();
                } else if (vm.meta.register === "check_register" && this.$refs.checkRegister) {
                    this.$refs.checkRegister.index();
                }else if (vm.meta.register === "process_register" && this.$refs.processRegister) {
                    this.$refs.processRegister.index();
                } else if (vm.meta.register === "all_registers") {
                    // Call index for all register components if applicable
                    // if (this.$refs.activityRegister) this.$refs.activityRegister.index();
                    // if (this.$refs.serviceRegister) this.$refs.serviceRegister.index();
                    // if (this.$refs.checkRegister) this.$refs.checkRegister.index();
                      if (this.$refs.activityRegister?.index) {
                            this.$refs.activityRegister.index();
                        }
                        if (this.$refs.serviceRegister?.index) {
                            this.$refs.serviceRegister.index();
                        }
                        if (this.$refs.checkRegister?.index) {
                            this.$refs.checkRegister.index();
                        }
                          if (this.$refs.processRegister?.index) {
                            this.$refs.processRegister.index();
                        }
                }
            },

            onPageChange(page) {
                this.meta.page = page;
                this.index();
            },
            onPerPageChange() {
                let vm = this;
                vm.meta.page = 1;
                vm.index();
            },
            sort(field) {
                this.meta.keyword = field;
                this.meta.order_by = this.meta.order_by == "asc" ? "desc" : "asc";
                this.index();
            },
        },
    };
</script>