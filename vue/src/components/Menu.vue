<template>
    <div class="sidebar sidebar-dark">
        <div class="sidebar-header">
            <!-- <img src="../../src/assets/jswlogo.png" alt="digiTRACK" class="brand-image img-circle1 elevation-3 me-2" style="opacity: 0.8; width:60px;" /> -->
            <!-- <img src="../../src/assets/jsw.png" /> -->
            <router-link to="/dashboard" class="sidebar-logo"><img src="../../src/assets/jsw.png" width="150" /></router-link>
        </div>
        <div id="sidebarMenu" class="sidebar-body">
            <div class="nav-group show">
                <ul class="nav nav-sidebar">
                    <li class="nav-item">
                        <router-link to="/dashboard" :style="{color:dashboardActive}" @click="showTab('Dashboard')" class="nav-link"><i class="ri-command-line"></i> <span>Dashboard</span></router-link>
                    </li>
                </ul>
            </div>
            <div class="nav-group" v-if="permission('UserManagement')" :class="{show:showUser}" @click="showTab('UserManagement')">
                <a href="javascript:void(0)" :style="{color:userManagementColor}" class="nav-label"><i class="ri-user-settings-line icn"></i> User Management</a>
                <ul class="nav nav-sidebar">
                    <li class="nav-item" v-can="'roles.view'">
                        <router-link to="/role" v-bind:class="{ active: $route.path === '/role' }" class="nav-link"><i class="ri-database-2-fill"></i> <span>Roles</span></router-link>
                    </li>
                    <li class="nav-item" v-can="'users.view'">
                        <router-link to="/users" v-bind:class="{ active: $route.path === '/users' || $route.name === 'Users.Create' || $route.path === 'Users.Edit'}" class="nav-link">
                            <i class="ri-user-line"></i> <span>Users</span>
                        </router-link>
                    </li>
                    <li class="nav-item" v-can="'permissions.view'">
                        <router-link to="/permissions" v-bind:class="{ active: $route.path === '/permissions' }" class="nav-link"><i class="ri-sensor-fill"></i> <span>Permissions</span></router-link>
                    </li>
                </ul>
            </div>
            <div class="nav-group" :class="{show:showLinage}" @click="showTab('LineageParameters')" v-if="permission('LineageParameters')">
                <a href="javascript:void(0)" :style="{color:color}" class="nav-label"><i class="ri-equalizer-line icn"></i> Lineage parameters</a>
                <ul class="nav nav-sidebar">
                    <li class="nav-item">
                        <router-link to="/areas" v-bind:class="{ active: $route.path === '/areas' }" class="nav-link"><i class="ri-command-line"></i> <span>Areas</span></router-link>
                    </li>
                    <li class="nav-item" v-can="'plants.view'">
                        <router-link to="/plants" v-bind:class="{ active: $route.path === '/plants' }" class="nav-link"><i class="ri-building-fill"></i> <span>Plants</span></router-link>
                    </li>
                    <li class="nav-item" v-can="'sections.view'">
                        <router-link to="/section" v-bind:class="{ active: $route.path === '/section' }" class="nav-link"><i class="ri-stack-line"></i> <span>Section</span></router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/frequency" v-bind:class="{ active: $route.path === '/frequency' }" class="nav-link"><i class="ri-rfid-line"></i> <span>Frequency</span></router-link>
                    </li>    
                    <li class="nav-item">
                        <router-link to="/department" v-bind:class="{ active: $route.path === '/department' }" class="nav-link"><i class="ri-government-line"></i> <span>Department</span></router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/functional" v-bind:class="{ active: $route.path === '/functional' }" class="nav-link"><i class="ri-rfid-line"></i> <span>Functional</span></router-link>
                    </li> 
                </ul>
            </div>

            <div class="nav-group" :class="{show:showType}" @click="showTab('TypeParameters')" v-if="permission('TypeParameters')">
                <a href="javascript:void(0)" :style="{color:typeParameterColor}" class="nav-label"><i class="ri-corner-up-right-double-line icn"></i> Type Parameters</a>
                <ul class="nav nav-sidebar">
                    <!-- <li class="nav-item" v-can="'equipment_types.view'">
                        <router-link to="/equipment_types" v-bind:class="{ active: $route.path === '/equipment_types' }" class="nav-link"><i class="ri-equalizer-line"></i> <span>Equipment Type</span></router-link>
                    </li> -->
                    <li class="nav-item" v-can="'asset_types.view'">
                        <router-link to="/asset_types" v-bind:class="{ active: $route.path === '/asset_types' }" class="nav-link"><i class="ri-building-3-line"></i> <span>Asset Types</span></router-link>
                    </li>
                    <li class="nav-item" v-can="'service_types.view'">
                        <router-link to="/service_types" v-bind:class="{ active: $route.path === '/service_types' }" class="nav-link"><i class="ri-layout-fill"></i> <span>Service Types</span></router-link>
                    </li>
                    <li class="nav-item" v-can="'spare_types.view'">
                        <router-link to="/spare_types" v-bind:class="{ active: $route.path === '/spare_types' }" class="nav-link"><i class="ri-shapes-line"></i> <span>Spare Types</span></router-link>
                    </li>
                    <li class="nav-item" v-can="'reasons.view'">
                        <router-link to="/activity_types" v-bind:class="{ active: $route.path === '/activity_types' }" class="nav-link"><i class="ri-color-filter-line"></i> <span>Activity Types</span></router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/data_source_type" v-bind:class="{ active: $route.path === '/data_source_type' }" class="nav-link"><i class="ri-focus-line"></i> <span>Data Source Types</span></router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/break_down_type" v-bind:class="{ active: $route.path === '/break_down_type' }" class="nav-link"><i class="ri-focus-line"></i> <span>Break Down Types</span></router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/accessory_type" v-bind:class="{ active: $route.path === '/accessory_type' }" class="nav-link"><i class="ri-focus-line"></i> <span>Accessory Types</span></router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/variable_type" v-bind:class="{ active: $route.path === '/variable_type' }" class="nav-link"><i class="ri-focus-line"></i> <span>Variable Types</span></router-link>
                    </li>
                </ul>
            </div>

            <!-- <div class="nav-group show">
                <ul class="nav nav-sidebar">
                    <li class="nav-item" v-can="'assetParameters.view'">
                        <router-link to="/asset_parameters" :style="{color:AssetParametersColor}" @click="showTab('AssetParameters')" class="nav-link"><i class="ri-stack-fill"></i> <span>Asset Parameters</span></router-link>
                    </li>
                </ul>
            </div> -->
            <div class="nav-group" :class="{show:showAttributes}" @click="showTab('Attributes')">
                <a href="javascript:void(0)" class="nav-label" :style="{color:AttributesColor}"><i class="ri-stack-fill icn"></i> Attributes</a>
                <ul class="nav nav-sidebar">
                    <li class="nav-item" >
                        <router-link to="/asset_attributes" v-bind:class="{ active: $route.path === '/asset_attributes' }" class="nav-link"><i class="ri-focus-line"></i> <span>Asset Attribute</span></router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/spare_attributes" v-bind:class="{ active: $route.path === '/spare_attributes' }" class="nav-link"><i class="ri-focus-line"></i> <span>Spare Attribute</span></router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/data_source_attributes" v-bind:class="{ active: $route.path === '/data_source_attributes' }" class="nav-link"><i class="ri-focus-line"></i> <span>Data Source Attribute</span></router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/variable_attributes" v-bind:class="{ active: $route.path === '/variable_attributes' }" class="nav-link"><i class="ri-focus-line"></i> <span>Variable Attribute</span></router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/service_attributes" v-bind:class="{ active: $route.path === '/service_attributes' }" class="nav-link"><i class="ri-focus-line"></i> <span>Service Attribute</span></router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/break_down_attributes" v-bind:class="{ active: $route.path === '/break_down_attributes' }" class="nav-link"><i class="ri-focus-line"></i> <span>Breakdown Attribute</span></router-link>
                    </li>
                </ul>
            </div>

            <div class="nav-group" v-if="permission('ListParameters')" :class="{show:showList}" @click="showTab('ListParameters')">
                <a href="javascript:void(0)" :style="{color:listParameterColor}" class="nav-label"><i class="ri-list-settings-line icn"></i> Masters</a>
                <ul class="nav nav-sidebar">
                    <li class="nav-item" v-can="'checks.view'">
                        <router-link to="/checks" v-bind:class="{ active: $route.path === '/checks' }" class="nav-link"><i class="ri-command-line"></i> <span>Checks</span></router-link>
                    </li>
                    <li class="nav-item" v-can="'spares.view'">
                        <router-link to="/spares" v-bind:class="{ active: $route.path === '/spares' }" class="nav-link"><i class="ri-align-left"></i> <span>Spares</span></router-link>
                    </li>
                    <li class="nav-item" v-can="'services.view'">
                        <router-link to="/services" v-bind:class="{ active: $route.path === '/services' }" class="nav-link"><i class="ri-drag-move-2-line"></i> <span>Services</span></router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/variables" v-bind:class="{ active: $route.path === '/variables' }" class="nav-link"><i class="ri-archive-drawer-line"></i> <span>Variables</span></router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/data_sources" v-bind:class="{ active: $route.path === '/data_sources' }" class="nav-link"><i class="ri-line-chart-line"></i> <span>Data Source</span></router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/break_down_lists" v-bind:class="{ active: $route.path === '/break_down_lists' }" class="nav-link"><i class="ri-bubble-chart-line"></i> <span>BreakDown List</span></router-link>
                    </li>
                </ul>
            </div>
            <!-- <div class="nav-group" v-if="permission('Masters')" :class="{show:showMaster}" @click="showTab('Masters')">
                <a href="javascript:void(0)" :style="{color:masterColor}" class="nav-label"><i class="ri-settings-5-line icn"></i> Masters</a>
                <ul class="nav nav-sidebar">
                    <li class="nav-item" v-can="'equipment.view'">
                        <router-link to="/equipment" v-bind:class="{ active: $route.path === '/equipment' }" class="nav-link"><img src="assets/images/machine.png" class="me-2" style="width: 22px;" alt=""><span>Equipment</span></router-link>
                    </li>                    
                    <li class="nav-item" v-can="'assets.view'">
                        <router-link to="/assets" v-bind:class="{ active: $route.path === '/assets' || $route.path === '/asset/create' || $route.name === 'Assets.Edit' || $route.name === 'Assets.View' }" class="nav-link"><i class="ri-briefcase-fill"></i><span>Asset</span></router-link>
                    </li>         
                </ul>
            </div> -->
            <div class="nav-group show">
                <ul class="nav nav-sidebar">
                    <li class="nav-item">
                        <router-link
                            to="/assets"
                            :style="{color:assetActive}"
                            @click="showTab('Asset')"
                            v-bind:class="{ active: $route.path === '/assets' || $route.path === '/asset/create' || $route.name === 'Assets.Edit' || $route.name === 'Assets.View' }"
                            class="nav-link"
                        >
                            <i class="ri-briefcase-fill"></i><span>Assets</span>
                        </router-link>
                    </li>
                </ul>
            </div>
            <div class="nav-group" v-if="permission('Registers')" :class="{show:showRegister}" @click="showTab('Registers')">
                <a href="javascript:void(0)" :style="{color:registerColor}" class="nav-label"><i class="ri-links-fill icn"></i> Registers</a>
                <ul class="nav nav-sidebar">
                    <li class="nav-item" v-can="'userActivities.view'">
                        <router-link v-bind:class="{ active: $route.path === '/activities' || $route.name === 'Activity.Create' || $route.name === 'Activity.Edit'}" to="/activities" class="nav-link">
                            <i class="ri-list-check"></i><span>Activity Register</span>
                        </router-link>
                    </li>
                    <li class="nav-item" v-can="'userServices.view'">
                        <router-link v-bind:class="{ active: $route.path === '/user_services' || $route.name === 'UserServices.Create' || $route.name === 'UserServices.Edit'}" to="/user_services" class="nav-link">
                            <i class="ri-flow-chart"></i><span>Service Register</span>
                        </router-link>
                    </li>
                    <li class="nav-item" v-can="'userChecks.view'">
                        <router-link v-bind:class="{ active: $route.path === '/user_checks' || $route.name === 'UserChecks.Create' || $route.path === 'UserChecks.Edit'}" to="/user_checks" class="nav-link">
                            <i class="ri-survey-line"></i><span>Check Register</span>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/process_registers" v-bind:class="{ active: $route.path === '/process_registers' }" class="nav-link"><i class="ri-briefcase-fill"></i><span>Process Register</span></router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/spare_registers" v-bind:class="{ active: $route.path === '/spare_registers' }" class="nav-link"><i class="ri-briefcase-fill"></i><span>Spare Register</span></router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/qc_registers" v-bind:class="{ active: $route.path === '/qc_registers' }" class="nav-link"><i class="ri-briefcase-fill"></i><span>QC Register</span></router-link>
                    </li>
                </ul>
            </div>

            <!-- <div class="nav-group">
                <a href="javascript:void(0)" class="nav-label"><i class="ri-flow-chart icn"></i> Services Register</a>
                <ul class="nav nav-sidebar">
                    <li class="nav-item">
                        <router-link to="/user_services" class="nav-link"><i class="ri-contacts-line"></i><span>User Services</span></router-link>
                    </li>           
                </ul>
            </div>  -->

            <div class="nav-group" :class="{show:showReview}" @click="showTab('Review')">
                <a href="javascript:void(0)" class="nav-label" :style="{color:ReviewColor}"><i class="ri-stack-fill icn"></i> Review</a>
                <ul class="nav nav-sidebar">
                    <li class="nav-item">
                        <router-link to="/asset_details" v-bind:class="{ active: $route.path === '/asset_details' }" class="nav-link"><i class="ri-focus-line"></i> <span>Asset Details</span></router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/spare_compaign" v-bind:class="{ active: $route.path === '/spare_compaign' }" class="nav-link"><i class="ri-focus-line"></i> <span>Spare Compaign</span></router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/compliance" v-bind:class="{ active: $route.path === '/compliance' }" class="nav-link"><i class="ri-focus-line"></i> <span>Compliance</span></router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/deviations_review" v-bind:class="{ active: $route.path === '/deviations_review' }" class="nav-link"><i class="ri-focus-line"></i> <span>Deviations</span></router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/alerts" v-bind:class="{ active: $route.path === '/alerts' }" class="nav-link"><i class="ri-focus-line"></i> <span>Alerts</span></router-link>
                    </li>
                </ul>
            </div>

            <div class="nav-group show">
                <a href="javascript:void(0)" style="color: inherit;" class="nav-label" @click="toggleReports"> <i class="ri-file-chart-line icn"></i>&nbsp; Reports </a>
                <ul class="nav nav-sidebar" v-show="showReports">
                    <li class="nav-item">
                        <a href="javascript:void(0)" class="nav-link"> <i class="ri-file-chart-2-line"></i> <span>Asset Report</span> </a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:void(0)" class="nav-link"> <i class="ri-pass-pending-line"></i> <span>Job Pending Report</span> </a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:void(0)" class="nav-link"> <i class="ri-store-3-fill"></i> <span>Activity Report</span> </a>
                    </li>
                </ul>
            </div>
            <div class="nav-group" :class="{show:showPredictions}" @click="showTab('Predictions')">
                <a href="javascript:void(0)" class="nav-label" :style="{color:PredictionColor}"><i class="ri-stack-fill icn"></i> Predictions</a>
                <ul class="nav nav-sidebar">
                    <li class="nav-item">
                        <router-link to="/asset_life" v-bind:class="{ active: $route.path === '/asset_life' }" class="nav-link"><i class="ri-focus-line"></i> <span>Asset Life</span></router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/upcoming_repairs" v-bind:class="{ active: $route.path === '/upcoming_repairs' }" class="nav-link"><i class="ri-focus-line"></i> <span>Upcoming Repiars</span></router-link>
                    </li>
                </ul>
            </div>
            <!-- <div class="nav-group">
                <a href="javascript:void(0)" :style="{color:reportsColor}" class="nav-label"><i class="ri-file-copy-2-line icn"></i> Reports</a>
                <ul class="nav nav-sidebar">
                    <li class="nav-item">
                        <router-link to="" class="nav-link"><img src="assets/images/generator.png" style="width: 20px;" class="me-2" alt=""><span>Assets Report</span></router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="" class="nav-link"><img src="assets/images/machine.png" class="me-2" style="width: 22px;" alt=""><span>Equipment Report</span></router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="" class="nav-link"><i class="ri-tools-line"></i><span>Spares Report</span></router-link>
                    </li>
                </ul>
            </div> -->
        </div>
        <div class="sidebar-footer">
            <div class="sidebar-footer-top">
                <div class="sidebar-footer-thumb">
                    <!-- <img src="" alt="" /> -->
                    <div class="avatar online" v-if="$store.getters.user?.avatar"><img :src="$store.getters.user?.avatar" alt="" /></div>
                    <div class="avatar online" v-else><img src="../../public/assets/images/default_avatar.png" alt="" /></div>
                </div>

                <div class="sidebar-footer-body">
                    <h6>{{$store.getters?.user?.name}}</h6>
                    <p>{{ $store.getters.user.role?.role }}</p>
                </div>
                <a id="sidebarFooterMenu" href="#" class="dropdown-link"><i class="ri-arrow-down-s-line"></i></a>
            </div>
            <!-- <div class="sidebar-footer-menu">
                <nav class="nav">
                    <router-link class="dropdown-item" to="/profile" ><i class="ri-edit-2-line"></i> Edit Profile</router-link>
                    <a href="javascript:void(0)" @click="logout()"><i class="ri-logout-box-r-line"></i> Log Out</a>
                </nav>
            </div> -->
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                permissions: this.$store.getters.permissions,
                dashboardActive: "white !important",
                color: "rgba(255, 255, 255, .6) !important",
                typeParameterColor: "rgba(255, 255, 255, .6) !important",
                motorParameterColor: "rgba(255, 255, 255, .6) !important",
                listParameterColor: "rgba(255, 255, 255, .6) !important",
                masterColor: "rgba(255, 255, 255, .6) !important",
                registerColor: "rgba(255, 255, 255, .6) !important",
                userManagementColor: "rgba(255, 255, 255, .6) !important",
                reportsColor: "rgba(255, 255, 255, .6) !important",
                AssetParametersColor: "rgba(255, 255, 255, .6) !important",
                AttributesColor: "rgba(255, 255, 255, .6) !important",
                assetActive: "white !important",
                showLinage: false,
                showType: false,
                showMotor: false,
                showList: false,
                showAttributes: false,
                showMaster: false,
                showRegister: false,
                showUser: false,
                AssetParametersActive: false,
                showReports: false,
                showReview: false,
                showPredictions: false,
            };
        },
        watch: {
            $route(to) {
                this.showTab(to.path, to.name);
            },
        },
        methods: {
            toggleReports() {
                this.showReports = !this.showReports;
            },
            logout() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "logout", data: vm.$store.getters.user })
                    .then(function () {
                        loader.hide();
                        vm.$store.dispatch("logout");
                        vm.$router.push("/");
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            showTab(tab, name) {
                this.dashboardActive = "rgba(255, 255, 255, .6) !important";
                this.color = "rgba(255, 255, 255, .6) !important";
                this.typeParameterColor = "rgba(255, 255, 255, .6) !important";
                this.motorParameterColor = "rgba(255, 255, 255, .6) !important";
                this.listParameterColor = "rgba(255, 255, 255, .6) !important";
                this.masterColor = "rgba(255, 255, 255, .6) !important";
                this.registerColor = "rgba(255, 255, 255, .6) !important";
                this.userManagementColor = "rgba(255, 255, 255, .6) !important";
                this.reportsColor = "rgba(255, 255, 255, .6) !important";
                this.AssetParametersColor = "rgba(255, 255, 255, .6) !important";
                this.assetActive = "rgba(255, 255, 255, .6) !important";
                this.AttributesColor = "rgba(255, 255, 255, .6) !important";
                this.ReviewColor = "rgba(255, 255, 255, .6) !important";
                this.PredictionColor = "rgba(255, 255, 255, .6) !important";

                if (tab == "/areas" || tab == "/plants" || tab == "/section" || tab == "/frequency" || tab == "/department") {
                    this.color = "white !important";
                    this.showLinage = !this.showLinage;
                } else if (
                    tab == "/equipment_types" ||
                    tab == "/asset_types" ||
                    tab == "/service_types" ||
                    tab == "/spare_types" ||
                    tab == "/activity_types" ||
                    tab == "/data_source_type" ||
                    tab == "/break_down_type" ||
                    tab == "/accessory_type" ||
                    tab == "/variable_type"
                ) {
                    this.typeParameterColor = "white !important";
                    this.showType = !this.showType;
                } else if (tab == "/voltages" || tab == "/watt_rating" || tab == "/frame" || tab == "/mounting" || tab == "/make" || tab == "/speed") {
                    this.motorParameterColor = "white !important";
                    this.showMotor = !this.showMotor;
                }
                // else if(tab == '/asset_parameters'){
                //     this.AssetParametersColor='white !important';
                //     this.AssetParametersActive = !this.AssetParametersActive
                // }
                else if (tab == "/checks" || tab == "/spares" || tab == "/services" || tab == "/variables" || tab == "/data_sources" || tab == "/break_down_lists") {
                    this.listParameterColor = "white !important";
                    this.showList = !this.showList;
                }
                // else if(tab == '/equipment' || tab == '/assets' || name == 'Assets.Create' || name=='Assets.Edit' || name == 'Assets.View'){
                //     this.masterColor='white !important';
                //     this.showMaster = !this.showMaster;
                // }
                else if (
                    tab == "/activities" ||
                    name == "Activity.Create" ||
                    name == "Activity.Edit" ||
                    tab == "/user_services" ||
                    name == "UserServices.Create" ||
                    name == "UserServices.Edit" ||
                    tab == "/user_checks" ||
                    name == "UserChecks.Create" ||
                    name == "UserChecks.Edit" ||
                    tab == "/process_registers" ||
                    tab == "/spare_registers" ||
                    tab == "/qc_registers"
                ) {
                    this.registerColor = "white !important";
                    this.showRegister = !this.showRegister;
                } else if (tab == "/role" || tab == "/users" || name == "Users.Create" || name == "Users.Edit" || tab == "/permissions") {
                    this.userManagementColor = "white !important";
                    this.showUser = !this.showUser;
                } else if (tab == "/dashboard") {
                    this.dashboardActive = "white !important";
                } else if (tab == "/assets" || name == "Assets.Create" || name == "Assets.Edit" || name == "Assets.View") {
                    this.assetActive = "white !important";
                } else if (tab == "/asset_attributes" || tab == "/spare_attributes" || tab == "/data_source_attributes" || tab == "/variable_attributes" || tab == "/service_attributes" || tab == "/break_down_attributes") {
                    this.AttributesColor = "white !important";
                    this.showAttributes = !this.showAttributes;
                } else if (tab == "/asset_details" || tab == "/spare_compaign" || tab == "/compliance" || tab == "/deviations_review" || tab == "/alerts") {
                    this.ReviewColor = "white !important";
                    this.showReview = !this.showReview;
                } else if (tab == "/asset_life" || tab == "/upcoming_repairs") {
                    this.PredictionColor = "white !important";
                    this.showPredictions = !this.showPredictions;
                }

                switch (tab) {
                    case "Dashboard":
                        this.dashboardActive = "white !important";
                        break;
                    case "LineageParameters":
                        this.color = "white !important";
                        this.showLinage = !this.showLinage;
                        break;
                    case "TypeParameters":
                        this.typeParameterColor = "white !important";
                        this.showType = !this.showType;
                        this.showLinage = false;
                        break;
                    case "MotorParameters":
                        this.motorParameterColor = "white !important";
                        this.showMotor = !this.showMotor;
                        break;
                    case "ListParameters":
                        this.listParameterColor = "white !important";
                        this.showList = !this.showList;
                        break;
                    case "Attributes":
                        this.AttributesColor = "white !important";
                        this.showAttributes = !this.showAttributes;
                        break;
                    case "Masters":
                        this.masterColor = "white !important";
                        this.showMaster = !this.showMaster;
                        break;
                    case "Registers":
                        this.registerColor = "white !important";
                        this.showRegister = !this.showRegister;
                        break;
                    case "UserManagement":
                        this.userManagementColor = "white !important";
                        this.showUser = !this.showUser;
                        break;
                    case "Asset":
                        this.assetActive = "white !important";
                        break;
                    case "Review":
                        this.ReviewColor = "white !important";
                        this.showReview = !this.showReview;
                        break;
                    case "Predictions":
                        this.PredictionColor = "white !important";
                        this.showPredictions = !this.showPredictions;
                        break;
                    default:
                        break;
                }
            },
            permission(ability) {
                let permissions = this.$store.getters.permissions;
                if (permissions && permissions.length != 0) {
                    let permission = [];
                    if (ability == "LineageParameters") {
                        permission = permissions.filter(function (el) {
                            return el.ability.ability == "clusters.view" || el.ability.ability == "plants.view" || el.ability.ability == "sections.view";
                        });
                    } else if (ability == "TypeParameters") {
                        permission = permissions.filter(function (el) {
                            return (
                                el.ability.ability == "equipment_types.view" ||
                                el.ability.ability == "asset_types.view" ||
                                el.ability.ability == "service_types.view" ||
                                el.ability.ability == "spare_types.view" ||
                                el.ability.ability == "reasons.view"
                            );
                        });
                    }
                    if (ability == "MotorParameters") {
                        permission = permissions.filter(function (el) {
                            return (
                                el.ability.ability == "voltages.view" ||
                                el.ability.ability == "watt_ratings.view" ||
                                el.ability.ability == "frames.view" ||
                                el.ability.ability == "mountings.view" ||
                                el.ability.ability == "makes.view" ||
                                el.ability.ability == "speeds.view"
                            );
                        });
                    }
                    if (ability == "ListParameters") {
                        permission = permissions.filter(function (el) {
                            return el.ability.ability == "checks.view" || el.ability.ability == "spares.view" || el.ability.ability == "services.view";
                        });
                    }
                    if (ability == "Masters") {
                        permission = permissions.filter(function (el) {
                            return el.ability.ability == "equipment.view" || el.ability.ability == "assets.view";
                        });
                    }
                    if (ability == "UserManagement") {
                        permission = permissions.filter(function (el) {
                            return el.ability.ability == "users.view" || el.ability.ability == "roles.view" || el.ability.ability == "permissions.view";
                        });
                    }
                    if (ability == "Registers") {
                        permission = permissions.filter(function (el) {
                            return el.ability.ability == "userActivities.view" || el.ability.ability == "userServices.view" || el.ability.ability == "userChecks.view";
                        });
                    }
                    if (permission.length == 0) {
                        return false;
                    } else {
                        return true;
                    }
                }
            },
        },
    };
</script>

<style scoped>
    .sidebar-body .nav-sidebar {
        display: none;
        padding: 0 0 0px;
    }
    .sidebar-body .nav-label {
        font-size: 14px;
        text-transform: none;
        color: rgba(255, 255, 255, 0.6) !important;
    }
    .icn {
        margin-right: 8px;
        line-height: 1;
        font-size: 20px;
        width: 18px;
        opacity: 0.85;
    }
    .sidebar-body .nav-label.show1 {
        color: white !important;
    }
    .sidebar-body .nav-group.show .nav-label::after {
        content: "";
    }
    .sidebar-body {
        overflow-y: auto;
    }
    /* Custom scrollbar */
    .sidebar-body::-webkit-scrollbar {
        width: 2px; /* Set the width of the scrollbar */
    }

    .sidebar-body::-webkit-scrollbar-track {
        background: #f1f1f1; /* Track color */
    }

    .sidebar-body::-webkit-scrollbar-thumb {
        background: #888; /* Scrollbar color */
    }

    .sidebar-body::-webkit-scrollbar-thumb:hover {
        background: #555; /* Scrollbar color on hover */
    }
</style>
