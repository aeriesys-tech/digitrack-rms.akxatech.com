import { createRouter, createWebHashHistory } from "vue-router";
import Login from "@/views/auth/Login.vue";
import Signup from "@/views/auth/Signup.vue";
import Dashboard from "@/views/Dashboard.vue";
import Profile from "@/views/auth/Profile.vue";
import ForgotPassword from "@/views/auth/ForgotPassword.vue";
import ResetPassword from "@/views/auth/ResetPassword.vue";


// configuration
import EquipmentVue from "@/views/masters/Equipments.vue";
import CreateAsset from "@/views/masters/assets/Create.vue";
import EditAsset from "@/views/masters/assets/Edit.vue";
import Assets from "@/views/masters/assets/Index.vue";
import AssetView from "@/views/masters/assets/ViewAsset.vue";
import GenerateQR from "@/views/masters/assets/GenerateQRCode.vue"

//Registers
import Activities from "@/views/registers/activity/Index.vue";
import CreateActivity from "@/views/registers/activity/Create.vue";

// User Services
import UserServices from "@/views/registers/user_services/Index.vue";
import CreateUserService from "@/views/registers/user_services/Create.vue";

//User checks
import UserChecks from "@/views/registers/check_register/user_checks/Index.vue";
import CreateUserCheck from "@/views/registers/check_register/user_checks/Create.vue";
import ViewUserCheck from "@/views/registers/check_register/user_checks/View.vue";

// Lineage parameter
import ClusterVue from "@/views/lineage_parameters/Cluster.vue";
import PlantsVue from "@/views/lineage_parameters/Plants.vue";
import Section from "@/views/lineage_parameters/Section.vue";
import Frequency from "@/views/lineage_parameters/Frequency.vue";
import Department from "@/views/lineage_parameters/Department.vue";

//List params

//import Check from "@/views/list_params/Check.vue";
import Spare from "@/views/list_parameters/Spare.vue";
import CreateCheck from "@/views/list_parameters/checks/Create.vue";
import IndexChecks from "@/views/list_parameters/checks/Index.vue";
import Service from "@/views/list_parameters/Service.vue";


// user management 
import Role from "@/views/userManagement/Role.vue";
import CreateUser from "@/views/userManagement/users/Create.vue"
import Users from "@/views/userManagement/users/Index.vue";
import Permissions from "@/views/userManagement/Permissions.vue";


//Reports
import Deviations from "@/views/reports/Deviations.vue";
import Pendings from "@/views/reports/Pendings.vue";
import UpcomingJobs from "@/views/reports/UpcomingJobs.vue";

//Asset management
import Components from "@/views/assets/Index.vue";
import ComponentCreate from "@/views/assets/Create.vue";
import AssetActivityVue from "@/views/assets/AssetActivity.vue";
import ServiceVue from "@/views/assets/Service.vue";
import InhouseServiceVue from "@/views/assets/InhouseService.vue";

// Type parameters
import EquipmentTypeVue from "@/views/type_parameters/EquipmentType.vue";
import AssetTypeVue from "@/views/type_parameters/AssetType.vue";
import ServiceTypeVue from "@/views/type_parameters/ServiceType.vue";
import SpareTypes from "@/views/type_parameters/SpareTypes.vue";
import ReasonVue from "@/views/type_parameters/Reason.vue";

//Spares

import ScheduledMaintenanceVue from "@/views/assets/ScheduledMaintenance.vue";
//import { CreateChecks } from '@/views/list_parameters/CreateUser.vue';


// Motor Parameters
//import Voltage from ';

//Asset Parameters
import CreateAssetParameter from "@/views/asset_parameters/Create.vue"
import AssetParameters from "@/views/asset_parameters/Index.vue";


import Test from "@/views/Test.vue";SpareCompaingn
import SpareCompaingn from "@/views/Reviews/SpareCompaign.vue"

const routes = [
  {
    path: "/",
    name: "login",
    component: Login,
  },
  {
    path: "/signup",
    name: "Signup",
    component: Signup,
  },
  {
    path: "/profile",
    name: "Profile",
    component: Profile,
  },
  {
    path: "/dashboard",
    name: "Dashboard",
    component: Dashboard,
  },
  {
    path: "/forgot_password",
    name: "ForgotPassword",
    component: ForgotPassword,
  },
  {
    path: "/reset_password",
    name: "ResetPassword",
    component: ResetPassword,
  },

  {
    path: "/permissions",
    name: "Permissions",
    component: Permissions,
  },

  // Type parameters
  {
    path: "/equipment_types",
    name: "EquipmentType",
    component: EquipmentTypeVue,
  },
  {
    path: "/asset_types",
    name: "AssetType",
    component: AssetTypeVue,
  },
  {
    path: "/service_types",
    name: "ServiceType",
    component: ServiceTypeVue,
  },
  {
    path: "/spare_types",
    name: "SpareType",
    component: SpareTypes,
  },
  {
    path: "/activity_types",
    name: "Reason",
    component: ReasonVue,
  },
  

  // configuration
  {
    path: "/section",
    name: "Section",
    component: Section,
  },
  {
    path: "/areas",
    name: "Cluster",
    component: ClusterVue,
  },
  {
    path: "/plants",
    name: "Plant",
    component: PlantsVue,
  },

  //Frequency 
  {
    path: "/frequency",
    name: "Frequency",
    component: Frequency,
  },
  {
    path: "/department",
    name: "Department",
    component: Department,
  },


  //masters
  {
    path: "/equipment",
    name: "Equipment",
    component: EquipmentVue,
  },
  {
    path: "/assets",
    name: "Assets",
    component: Assets,
  },
  {
    path: "/asset/create",
    name: "Assets.Create",
    component: CreateAsset,
  },
  {
    path: "/asset/:asset_id/edit",
    name: "Assets.Edit",
    component: EditAsset,
  },
  // {
  //   path: "/asset/:asset_id/edit",
  //   name: "Assets.Edit",
  //   component: CreateAsset,
  // },
  {
    path: "/asset/:asset_id/view",
    name: "Assets.View",
    component: AssetView,
  },
  {
    path: "/QR_code/:asset_id/get",
    name: "GenerateQR",
    component: GenerateQR,
  },
  // {
  //   path: "/spares",
  //   name: "Asset.Spare",
  //   component: SpareVue,
  // },

  //registers
  {
    path: "/activities",
    name: "Activities.Index",
    component: Activities,
  },
  {
    path: "/activity/create",
    name: "Activity.Create",
    component: CreateActivity,
  },
  {
    path: "/activity/:user_activity_id/edit",
    name: "Activity.Edit",
    component: CreateActivity,
  },

  //User Services
  {
    path: "/user_services",
    name: "UserServices.Index",
    component: UserServices,
  },
  {
    path: "/user_service/create",
    name: "UserServices.Create",
    component: CreateUserService,
  },
  {
    path: "/user_service/:user_service_id/edit",
    name: "UserServices.Edit",
    component: CreateUserService,
  },

  //User Checks
  {
    path: "/user_checks",
    name: "UserChecks.Index",
    component: UserChecks,
  },
  {
    path: "/user_check/create",
    name: "UserChecks.Create",
    component: CreateUserCheck,
  },
  {
    path: "/user_check/:user_check_id/edit",
    name: "UserChecks.Edit",
    component: CreateUserCheck,
  },
  {
    path: "/user_check/:user_check_id/view",
    name: "UserChecks.View",
    component: ViewUserCheck,
  },

  // list params
  {
    path: "/checks",
    name: "checks.Index",
    component: IndexChecks,
  },
  {
    path: "/checks/create",
    name: "CheckCreate",
    component: CreateCheck,
  },

  {
    path: "/checks/:check_id/edit",
    name: "Checks.Edit",
    component: CreateCheck,
  },
 
  {
    path: "/spares",
    name: "Spares",
    component: Spare,
  },

  {
    path: "/services",
    name: "Services",
    component: Service,
  },
  
  // Component Management
  {
    path: "/components",
    name: "Componet.Index",
    component: Components,
  },
  {
    path: "/components/create",
    name: "Componet.Create",
    component: ComponentCreate,
  },
  {
    path: "/components/service",
    name: "Componet.Service",
    component: ServiceVue,
  },
  {
    path: "/components/inhouse_service",
    name: "Componet.Service2",
    component: InhouseServiceVue,
  },
  {
    path: "/components/maintenance",
    name: "Componet.Maintenance",
    component: ScheduledMaintenanceVue,
  },
  {
    path: "/assets/activity",
    name: "Asset.Activity",
    component: AssetActivityVue,
  },
  // user management
  {
    path: "/role",
    name: "Role",
    component: Role,
  },
  // users
  {
    path: "/users",
    name: "Users.Index",
    component: Users,
  },
  {
    path: "/user/create",
    name: "Users.Create",
    component: CreateUser,
  },

  {
    path: "/users/:user_id/edit",
    name: "Users.Edit",
    component: CreateUser,
  },

  //Asset Parameters
  {
    path: "/asset_attributes",
    name: "AssetParameters.Index",
    component: AssetParameters,
  },
  {
    path: "/asset_attributes/create",
    name: "AssetParameters.Create",
    component: CreateAssetParameter,
  },

  {
    path: "/asset_attributes/:asset_attribute_id/edit",
    name: "AssetParameters.Edit",
    component: CreateAssetParameter,
  },

  //Deviation
  {
    path: "/deviations",
    name: "Deviations.Index",
    component: Deviations,
  },

  {
    path: "/pendings",
    name: "Pendings.Index",
    component: Pendings,
  },

  {
    path: "/UpcomingJobs",
    name: "UpcomingJobs.Index",
    component: UpcomingJobs,
  },



  {
    path: "/data_source_type",
    name: "DataSourceType",
    component:Test,
  },
  {
    path: "/break_down_type",
    name: "BreakDownType",
    component:Test,
  },
  {
    path: "/access_type",
    name: "AccessType",
    component:Test,
  },
  {
    path: "/variable_type",
    name: "VariableType",
    component:Test,
  },

  {
    path: "/asset_attributes",
    name: "AssetAttributes",
    component:Test,
  },
  {
    path: "/spre_attributes",
    name: "SpareAttributes",
    component:Test,
  },
  {
    path: "/data_source_attributes",
    name: "DataSoutrceAttributes",
    component:Test,
  },
  {
    path: "/variable_attributes",
    name: "VariableAttributes",
    component:Test,
  },
  {
    path: "/service_attributes",
    name: "ServiceAttributes",
    component:Test,
  },
  {
    path: "/break_down_attributes",
    name: "BreakDownAttributes",
    component:Test,
  },
  {
    path: "/variables",
    name: "Variables",
    component:Test,
  },
  {
    path: "/data_sources",
    name: "DataSources",
    component:Test,
  },
  {
    path: "/break_down_lists",
    name: "BreakDownLists",
    component:Test,
  },
  {
    path: "/process_registers",
    name: "ProcessRegisters",
    component:Test,
  },
  {
    path: "/spare_registers",
    name: "SpareRegisters",
     component:Test,
  },
  {
    path: "/qc_registers",
    name: "QCRegisters",
     component:Test,
  },
  {
    path: "/asset_details",
    name: "AssetDetails",
     component:Test,
  },
  {
    path: "/spare_compaign",
    name: "SpareCompaingn",
     component:SpareCompaingn,
  },
  {
    path: "/compliance",
    name: "Compliance",
     component:Test,
  },
  {
    path: "/deviations_review",
    name: "Deviations",
     component:Test,
  },
  {
    path: "/alerts",
    name: "Alerts",
     component:Test,
  },
  {
    path: "/asset_life",
    name: "AssetLife",
     component:Test,
  },
  {
    path: "/upcoming_repairs",
    name: "UpcomingRepairs",
     component:Test,
  },


]

const router = createRouter({
  history: createWebHashHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    // always scroll to top
    return { top: 0 }
  },
//  }
});


export default router
