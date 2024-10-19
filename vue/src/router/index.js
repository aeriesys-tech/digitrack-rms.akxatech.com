import { createRouter, createWebHashHistory } from "vue-router";
import Login from "@/views/auth/Login.vue";
import Signup from "@/views/auth/Signup.vue";
import Dashboard from "@/views/Dashboard.vue";
import Profile from "@/views/auth/Profile.vue";
import ForgotPassword from "@/views/auth/ForgotPassword.vue";
import ResetPassword from "@/views/auth/ResetPassword.vue";

// list
import Lists from "@/views/Lists.vue";

// configuration
import EquipmentVue from "@/views/masters/Equipments.vue";
import CreateAsset from "@/views/masters/assets/Create.vue";
import EditAsset from "@/views/masters/assets/Edit.vue";
import Assets from "@/views/masters/assets/Index.vue";
import AssetView from "@/views/masters/assets/ViewAsset.vue";
import DynamicAssets from "@/views/masters/assets/ViewDynamicAssets.vue";
import GenerateQR from "@/views/masters/assets/GenerateQRCode.vue"
import AssetAccessories from "@/views/masters/assets/AssetAccessories.vue";

//Registers
import Activities from "@/views/registers/activity/Index.vue";
import CreateActivity from "@/views/registers/activity/Create.vue";

import CreateHealthCheck from "@/views/registers/health_check/Create.vue"
import HealthChecks from "@/views/registers/health_check/Index.vue";


// User Services
import UserServices from "@/views/registers/user_services/Index.vue";
import CreateUserService from "@/views/registers/user_services/Create.vue";

//User checks
import UserChecks from "@/views/registers/check_register/user_checks/Index.vue";
import CreateUserCheck from "@/views/registers/check_register/user_checks/Create.vue";
import ViewUserCheck from "@/views/registers/check_register/user_checks/View.vue";

// Lineage parameter
import Area from "@/views/lineage_parameters/Area.vue";
import PlantsVue from "@/views/lineage_parameters/Plants.vue";
import Section from "@/views/lineage_parameters/Section.vue";
import Frequency from "@/views/lineage_parameters/Frequency.vue";
import Department from "@/views/lineage_parameters/Department.vue";
import Functional from "@/views/lineage_parameters/Functional.vue";

//List params

//import Check from "@/views/list_params/Check.vue";
// import Spare from "@/views/list_parameters/Spare.vue";
import CreateCheck from "@/views/list_parameters/checks/Create.vue";
import IndexChecks from "@/views/list_parameters/checks/Index.vue";
// import DataSources from "@/views/list_parameters/DataSource.vue";

import CreateService from "@/views/list_parameters/service/Create.vue"
import Services from "@/views/list_parameters/service/Index.vue";

import CreateSpare from "@/views/list_parameters/spares/Create.vue"
import Spares from "@/views/list_parameters/spares/Index.vue";

import CreateVariable from "@/views/list_parameters/variables/Create.vue"
import Variables from "@/views/list_parameters/variables/Index.vue";

import CreateDataSource from "@/views/list_parameters/data_source/Create.vue"
import DataSources from "@/views/list_parameters/data_source/Index.vue";

import CreateBreakDownList from "@/views/list_parameters/break_down_list/Create.vue"
import BreakDownLists from "@/views/list_parameters/break_down_list/Index.vue";

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
import ActivityTypes from "@/views/type_parameters/ActivityTypes.vue";
import DataSourceTypes from "@/views/type_parameters/DataSourceType.vue";
import BreakDownTypes from "@/views/type_parameters/BreakDownType.vue";
import AccessoryTypes from "@/views/type_parameters/AccessoryTypes.vue";
import VariableTypes from "@/views/type_parameters/VariableTypes.vue";

//Spares

import ScheduledMaintenanceVue from "@/views/assets/ScheduledMaintenance.vue";
//import { CreateChecks } from '@/views/list_parameters/CreateUser.vue';


// Motor Parameters
//import Voltage from ';

//Attributes
import CreateAssetAttribute from "@/views/attributes/asset_attributes/Create.vue"
import AssetAttributes from "@/views/attributes/asset_attributes/Index.vue";

import CreateSpareAttribute from "@/views/attributes/spare_attributes/Create.vue"
import SpareAttributes from "@/views/attributes/spare_attributes/Index.vue";

import CreateDataSourceAttribute from "@/views/attributes/data_source_attributes/Create.vue"
import DataSourceAttributes from "@/views/attributes/data_source_attributes/Index.vue";

import CreateVariableAttribute from "@/views/attributes/variable_attributes/Create.vue"
import VariableAttributes from "@/views/attributes/variable_attributes/Index.vue";

import CreateServiceAttribute from "@/views/attributes/service_attributes/Create.vue"
import ServiceAttributes from "@/views/attributes/service_attributes/Index.vue";

import CreateBreakDownAttribute from "@/views/attributes/breakdown_attributes/Create.vue"
import BreakDownAttributes from "@/views/attributes/breakdown_attributes/Index.vue";

import CreateActivityAttribute from "@/views/attributes/activity_attributes/Create.vue"
import ActivityAttributes from "@/views/attributes/activity_attributes/Index.vue";


// Registers
import ViewProcessRegister from "@/views/registers/process_registers/View.vue";
import CreateProcessRegister from "@/views/registers/process_registers/Create.vue";
import ProcessRegisters from "@/views/registers/process_registers/Index.vue"

import CreateBreakDownRegister from "@/views/registers/break_down_register/Create.vue";
import BreakDownRegisters from "@/views/registers/break_down_register/Index.vue"


import Test from "@/views/Test.vue";

//Reviews
import SpareCompaingn from "@/views/Reviews/SpareCompaign.vue"
import AssetDetails from "@/views/Reviews/AssetDetails.vue"
import Geometric from "@/views/masters/assets/Geometric.vue"
import ProcessTrends from "@/views/Reviews/ProcessTrends.vue"


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

  // lists
  {
    path: "/list_parameters",
    name: "Lists",
    component: Lists,
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
    name: "ActivityTypes",
    component: ActivityTypes,
  },
  {
    path: "/data_source_type",
    name: "DataSourceTypes",
    component: DataSourceTypes,
  },
  {
    path: "/break_down_type",
    name: "BreakDownTypes",
    component: BreakDownTypes,
  },
  {
    path: "/accessory_type",
    name: "AccessoryTypes",
    component: AccessoryTypes,
  },
  {
    path: "/variable_type",
    name: "VariableTypes",
    component: VariableTypes,
  },

  // configuration
  {
    path: "/section",
    name: "Section",
    component: Section,
  },
  {
    path: "/areas",
    name: "Area",
    component: Area,
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

  {
    path: "/functional",
    name: "Functional",
    component: Functional,
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
    path: "/asset/accessories/:asset_id",
    name: "Assets.accessories",
    component: AssetAccessories,
  },
  {
    path: "/asset/:asset_id/edit",
    name: "Assets.Edit",
    component: CreateAsset,
  },
  {
    path: "/asset/:asset_id/view",
    name: "Assets.View",
    component: AssetView,
  },
  {
    path: "/asset/:assets",
    name: "Assets.DynamicAssets",
    component: DynamicAssets,
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

  // health check register
  {
    path: "/health_checks",
    name: "HealthChecks.Index",
    component: HealthChecks,
  },
  {
    path: "/health_check/create",
    name: "HealthChecks.Create",
    component: CreateHealthCheck,
  },
  {
    path: "/health_check/:health_check_id/edit",
    name: "HealthChecks.Edit",
    component: CreateHealthCheck,
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

  //break down register
  {
    path: "/break_down_registers",
    name: "BreakDownRegisters.Index",
    component: BreakDownRegisters,
  },
  {
    path: "/break_down_registers/create",
    name: "BreakDownRegister.Create",
    component: CreateBreakDownRegister,
  },
  {
    path: "/break_down_registers/:break_down_list_id/edit",
    name: "BreakDownRegister.Edit",
    component: CreateBreakDownRegister,
  },

  // list params
  {
    path: "/checks",
    name: "checks.Index",
    component: IndexChecks,
  },
  {
    path: "/checks/create",
    name: "Checks.Create",
    component: CreateCheck,
  },

  {
    path: "/checks/:check_id/edit",
    name: "Checks.Edit",
    component: CreateCheck,
  },

  // {
  //   path: "/spares",
  //   name: "Spares",
  //   component: Spare,
  // },
  // {
  //   path: "/data_sources",
  //   name: "DataSources",
  //   component: Test,
  // },


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
    name: "AssetAttributes.Index",
    component: AssetAttributes,
  },
  {
    path: "/asset_attributes/create",
    name: "AssetAttributes.Create",
    component: CreateAssetAttribute,
  },

  {
    path: "/asset_attributes/:asset_attribute_id/edit",
    name: "AssetAttributes.Edit",
    component: CreateAssetAttribute,
  },

  //spare Parameters
  {
    path: "/spare_attributes",
    name: "SpareAttributes.Index",
    component: SpareAttributes,
  },
  {
    path: "/spare_attributes/create",
    name: "SpareAttributes.Create",
    component: CreateSpareAttribute,
  },

  {
    path: "/spare_attributes/:spare_attribute_id/edit",
    name: "SpareAttributes.Edit",
    component: CreateSpareAttribute,
  },

  //data source Parameters
  {
    path: "/data_source_attributes",
    name: "DataSourceAttributes.Index",
    component: DataSourceAttributes,
  },
  {
    path: "/data_source_attributes/create",
    name: "DataSourceAttributes.Create",
    component: CreateDataSourceAttribute,
  },

  {
    path: "/data_source_attributes/:data_source_attribute_id/edit",
    name: "DataSourceAttributes.Edit",
    component: CreateDataSourceAttribute,
  },

  //variable Parameters
  {
    path: "/variable_attributes",
    name: "VariableAttributes.Index",
    component: VariableAttributes,
  },
  {
    path: "/variable_attributes/create",
    name: "VariableAttributes.Create",
    component: CreateVariableAttribute,
  },

  {
    path: "/variable_attributes/:variable_attribute_id/edit",
    name: "VariableAttributes.Edit",
    component: CreateVariableAttribute,
  },


  // service attribute
  {
    path: "/service_attributes",
    name: "ServiceAttributes.Index",
    component: ServiceAttributes,
  },
  {
    path: "/service_attributes/create",
    name: "ServiceAttributes.Create",
    component: CreateServiceAttribute,
  },

  {
    path: "/service_attributes/:service_attribute_id/edit",
    name: "ServiceAttributes.Edit",
    component: CreateServiceAttribute,
  },

  // breakdown attribute
  {
    path: "/break_down_attributes",
    name: "BreakDownAttributes.Index",
    component: BreakDownAttributes,
  },
  {
    path: "/break_down_attributes/create",
    name: "BreakDownAttributes.Create",
    component: CreateBreakDownAttribute,
  },

  {
    path: "/break_down_attributes/:break_down_attribute_id/edit",
    name: "BreakDownAttributes.Edit",
    component: CreateBreakDownAttribute,
  },

  // activity attribute
  {
    path: "/activity_attributes",
    name: "ActivityAttributes.Index",
    component: ActivityAttributes,
  },
  {
    path: "/activity_attributes/create",
    name: "ActivityAttributes.Create",
    component: CreateActivityAttribute,
  },

  {
    path: "/activity_attributes/:activity_attribute_id/edit",
    name: "ActivityAttributes.Edit",
    component: CreateActivityAttribute,
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


  // masteres

  {
    path: "/spares",
    name: "Spares.Index",
    component: Spares,
  },
  {
    path: "/spares/create",
    name: "Spares.Create",
    component: CreateSpare,
  },

  {
    path: "/spares/:spare_id/edit",
    name: "Spares.Edit",
    component: CreateSpare,
  },


  {
    path: "/services",
    name: "Services.Index",
    component: Services,
  },
  {
    path: "/services/create",
    name: "Services.Create",
    component: CreateService,
  },

  {
    path: "/services/:service_id/edit",
    name: "Services.Edit",
    component: CreateService,
  },

  {
    path: "/variables",
    name: "Variables.Index",
    component: Variables,
  },
  {
    path: "/variables/create",
    name: "Variables.Create",
    component: CreateVariable,
  },

  {
    path: "/variables/:variable_id/edit",
    name: "Varaibles.Edit",
    component: CreateVariable,
  },

  {
    path: "/data_sources",
    name: "DataSources.Index",
    component: DataSources,
  },
  {
    path: "/data_sources/create",
    name: "DataSources.Create",
    component: CreateDataSource,
  },

  {
    path: "/data_sources/:data_source_id/edit",
    name: "DataSources.Edit",
    component: CreateDataSource,
  },

  {
    path: "/break_down_lists",
    name: "BreakDownLists.Index",
    component: BreakDownLists,
  },
  {
    path: "/break_down_lists/create",
    name: "BreakDownLists.Create",
    component: CreateBreakDownList,
  },

  {
    path: "/break_down_lists/:break_down_list_id/edit",
    name: "BreakDownLists.Edit",
    component: CreateBreakDownList,
  },



  {
    path: "/asset_attributes",
    name: "AssetAttributes",
    component: Test,
  },
  {
    path: "/spre_attributes",
    name: "SpareAttributes",
    component: Test,
  },
  {
    path: "/service_attributes",
    name: "ServiceAttributes",
    component: Test,
  },
  {
    path: "/break_down_attributes",
    name: "BreakDownAttributes",
    component: Test,
  },
  {
    path: "/break_down_lists",
    name: "BreakDownLists",
    component: Test,
  },
  {
    path: "/process_registers",
    name: "ProcessRegisters.Index",
    component: ProcessRegisters,
  },
  {
    path: "/process_registers/create",
    name: "CreateProcessRegister.Create",
    component: CreateProcessRegister,
  },
  {
    path: "/process_registers/:user_variable_id/edit",
    name: "CreateProcessRegister.Edit",
    component: CreateProcessRegister,
  },
  {
    path: "/process_registers/:user_variable_id/view",
    name: "ViewProcessRegister",
    component: ViewProcessRegister,
  },
  {
    path: "/spare_registers",
    name: "SpareRegisters",
    component: Test,
  },
  {
    path: "/qc_registers",
    name: "QCRegisters",
    component: Test,
  },
  {
    path: "/asset_details",
    name: "AssetDetails",
    component: AssetDetails,
  },
  {
    path: "/geometric/:asset_id",
    name: "Geometric",
    component: Geometric,
  },

  // spare campaign
  {
    path: "/spare_campaigns",
    name: "SpareCompaingn",
    component: SpareCompaingn,
  },

  {
    path: "/process_trends",
    name: "ProcessTrends",
    component: ProcessTrends,
  },



  {
    path: "/compliance",
    name: "Compliance",
    component: Test,
  },
  {
    path: "/deviations_review",
    name: "Deviations",
    component: Test,
  },
  {
    path: "/alerts",
    name: "Alerts",
    component: Test,
  },
  {
    path: "/asset_life",
    name: "AssetLife",
    component: Test,
  },
  {
    path: "/upcoming_repairs",
    name: "UpcomingRepairs",
    component: Test,
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
