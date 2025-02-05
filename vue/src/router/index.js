import { createRouter, createWebHashHistory } from "vue-router";
import store from "@/store/index.js";

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
import GenerateQR from "@/views/masters/assets/GenerateQRCode.vue";
import AssetAccessories from "@/views/masters/assets/AssetAccessories.vue";

// asset template
import AssetTemplates from "@/views/masters/assetTemplates/Index.vue";
import CreateAssetTemplate from "@/views/masters/assetTemplates/Create.vue";
import AssetTemplateView from "@/views/masters/assetTemplates/ViewAssetTemplate.vue";

//Registers
import Activities from "@/views/registers/activity/Index.vue";
import CreateActivity from "@/views/registers/activity/Create.vue";

import CreateHealthCheck from "@/views/registers/health_check/Create.vue";
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

import CreateService from "@/views/list_parameters/service/Create.vue";
import Services from "@/views/list_parameters/service/Index.vue";

import CreateSpare from "@/views/list_parameters/spares/Create.vue";
import Spares from "@/views/list_parameters/spares/Index.vue";

import CreateVariable from "@/views/list_parameters/variables/Create.vue";
import Variables from "@/views/list_parameters/variables/Index.vue";

import CreateDataSource from "@/views/list_parameters/data_source/Create.vue";
import DataSources from "@/views/list_parameters/data_source/Index.vue";

import CreateBreakDownList from "@/views/list_parameters/break_down_list/Create.vue";
import BreakDownLists from "@/views/list_parameters/break_down_list/Index.vue";

// user management
import Role from "@/views/userManagement/Role.vue";
import CreateUser from "@/views/userManagement/users/Create.vue";
import Users from "@/views/userManagement/users/Index.vue";
import Permissions from "@/views/userManagement/Permissions.vue";

//Reports
import AssetReports from "@/views/reports/AssetReports.vue";
import Deviations from "@/views/reports/Deviations.vue";
import CompletedDeviations from "@/views/reports/CompletedDeviations.vue";
import Pendings from "@/views/reports/Pendings.vue";
import UpcomingJobs from "@/views/reports/UpcomingJobs.vue";
import BreakDownReports from "@/views/reports/BreakDownReports.vue";

import PendingJobs from "@/views/reports/PendingJobs.vue";
import DeviationReport from "@/views/reports/DeviationReport.vue";

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
import CreateAssetAttribute from "@/views/attributes/asset_attributes/Create.vue";
import AssetAttributes from "@/views/attributes/asset_attributes/Index.vue";

import CreateSpareAttribute from "@/views/attributes/spare_attributes/Create.vue";
import SpareAttributes from "@/views/attributes/spare_attributes/Index.vue";

import CreateDataSourceAttribute from "@/views/attributes/data_source_attributes/Create.vue";
import DataSourceAttributes from "@/views/attributes/data_source_attributes/Index.vue";

import CreateVariableAttribute from "@/views/attributes/variable_attributes/Create.vue";
import VariableAttributes from "@/views/attributes/variable_attributes/Index.vue";

import CreateServiceAttribute from "@/views/attributes/service_attributes/Create.vue";
import ServiceAttributes from "@/views/attributes/service_attributes/Index.vue";

import CreateBreakDownAttribute from "@/views/attributes/breakdown_attributes/Create.vue";
import BreakDownAttributes from "@/views/attributes/breakdown_attributes/Index.vue";

import CreateActivityAttribute from "@/views/attributes/activity_attributes/Create.vue";
import ActivityAttributes from "@/views/attributes/activity_attributes/Index.vue";

// Registers
import ViewProcessRegister from "@/views/registers/process_registers/View.vue";
import CreateProcessRegister from "@/views/registers/process_registers/Create.vue";
import ProcessRegisters from "@/views/registers/process_registers/Index.vue";

import CreateBreakDownRegister from "@/views/registers/break_down_register/Create.vue";
import BreakDownRegisters from "@/views/registers/break_down_register/Index.vue";

import Test from "@/views/Test.vue";

//Reviews
import SpareCompaingn from "@/views/Reviews/SpareCompaign.vue";
import AssetDetails from "@/views/Reviews/AssetDetails.vue";
import Geometric from "@/views/masters/assets/Geometric.vue";
import HorizontalGeometric from "@/views/masters/assets/HGeometric.vue";
import ProcessTrends from "@/views/Reviews/ProcessTrends.vue";
import ViewAssetReport from "@/views/reports/ViewAssetReport.vue";

import DownloadedReports from "@/views/DownloadedReports.vue";

import Error1 from "@/views/Error.vue";
const routes = [
  {
    path: "/",
    name: "login",
    component: Login,
    meta: { unAuth: true, permission: "All" },
  },
  {
    path: "/signup",
    name: "Signup",
    component: Signup,
    meta: { unAuth: true, permission: "All" },
  },
  {
    path: "/profile",
    name: "Profile",
    component: Profile,
    meta: { auth: true, permission: "All" },
  },
  {
    path: "/dashboard",
    name: "Dashboard",
    component: Dashboard,
    meta: { auth: true, permission: "All" },
  },
  {
    path: "/forgot_password",
    name: "ForgotPassword",
    component: ForgotPassword,
    meta: { unAuth: true, permission: "All" },
  },
  {
    path: "/reset_password",
    name: "ResetPassword",
    component: ResetPassword,
    meta: { unAuth: true, permission: "All" },
  },

  {
    path: "/permissions",
    name: "Permissions",
    component: Permissions,
    meta: { auth: true, permission: "All" },
  },

  // lists
  {
    path: "/list_parameters",
    name: "Lists",
    component: Lists,
    meta: { auth: true, permission: "list_parameters.view" },
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
    meta: { auth: true, permission: "asset_types.view" },
  },
  {
    path: "/service_types",
    name: "ServiceType",
    component: ServiceTypeVue,
    meta: { auth: true, permission: "service_types.view" },
  },
  {
    path: "/spare_types",
    name: "SpareType",
    component: SpareTypes,
    meta: { auth: true, permission: "spare_types.view" },
  },
  {
    path: "/activity_types",
    name: "ActivityTypes",
    component: ActivityTypes,
    meta: { auth: true, permission: "activity_types.view" },
  },
  {
    path: "/data_source_type",
    name: "DataSourceTypes",
    component: DataSourceTypes,
    meta: { auth: true, permission: "data_source_types.view" },
  },
  {
    path: "/break_down_type",
    name: "BreakDownTypes",
    component: BreakDownTypes,
    meta: { auth: true, permission: "break_down_types.view" },
  },
  {
    path: "/accessory_type",
    name: "AccessoryTypes",
    component: AccessoryTypes,
    meta: { auth: true, permission: "accessory_types.view" },
  },
  {
    path: "/variable_type",
    name: "VariableTypes",
    component: VariableTypes,
    meta: { auth: true, permission: "variable_types.view" },
  },

  // configuration
  {
    path: "/section",
    name: "Section",
    component: Section,
    meta: { auth: true, permission: "sections.view" },
  },
  {
    path: "/areas",
    name: "Area",
    component: Area,
    meta: { auth: true, permission: "areas.view" },
  },
  {
    path: "/plants",
    name: "Plant",
    component: PlantsVue,
    meta: { auth: true, permission: "shops.view" },
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
    meta: { auth: true, permission: "departments.view" },
  },

  {
    path: "/functional",
    name: "Functional",
    component: Functional,
    meta: { auth: true, permission: "functionals.view" },
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
    meta: { auth: true, permission: "assets.view" },
  },
  {
    path: "/asset/create",
    name: "Assets.Create",
    component: CreateAsset,
    meta: { auth: true, permission: "assets.create" },
  },
  {
    path: "/asset/accessories/:asset_id",
    name: "Assets.accessories",
    component: AssetAccessories,
    meta: { auth: true, permission: "asset_accessories.view" },
  },
  {
    path: "/asset/:asset_id/edit",
    name: "Assets.Edit",
    component: CreateAsset,
    meta: { auth: true, permission: "assets.update" },
  },
  {
    path: "/asset/:asset_id/view",
    name: "Assets.View",
    component: AssetView,
    meta: { auth: true, permission: "assets_views.details" },
  },
  {
    path: "/asset/:assets",
    name: "Assets.DynamicAssets",
    component: DynamicAssets,
    meta: { auth: true, permission: "All" },
  },
  {
    path: "/QR_code/:asset_id/get",
    name: "GenerateQR",
    component: GenerateQR,
    meta: { auth: true, permission: "All" },
  },
  // {
  //   path: "/spares",
  //   name: "Asset.Spare",
  //   component: SpareVue,
  // },

  // asset template
  {
    path: "/asset_templates",
    name: "AssetTemplates",
    component: AssetTemplates,
    meta: { auth: true, permission: "asset_templates.view" },
  },
  {
    path: "/asset_templates/create",
    name: "AssetTemplates.Create",
    component: CreateAssetTemplate,
    meta: { auth: true, permission: "asset_templates.create" },
  },
  {
    path: "/asset_template/:asset_template_id/edit",
    name: "AssetTemplates.Edit",
    component: CreateAssetTemplate,
    meta: { auth: true, permission: "asset_templates.update" },
  },
  {
    path: "/asset_template/:asset_template_id/view",
    name: "AssetTemplates.View",
    component: AssetTemplateView,
    meta: { auth: true, permission: "asset_template_views.details" },
  },

  //registers
  {
    path: "/activities",
    name: "Activities.Index",
    component: Activities,
    meta: { auth: true, permission: "activity_registers.view" },
  },
  {
    path: "/activity/create",
    name: "Activity.Create",
    component: CreateActivity,
    meta: { auth: true, permission: "activity_registers.create" },
  },
  {
    path: "/activity/:user_activity_id/edit",
    name: "Activity.Edit",
    component: CreateActivity,
    meta: { auth: true, permission: "activity_registers.update" },
  },

  // health check register
  {
    path: "/health_checks",
    name: "HealthChecks.Index",
    component: HealthChecks,
    meta: { auth: true, permission: "health_check_registers.view" },
  },
  {
    path: "/health_check/create",
    name: "HealthChecks.Create",
    component: CreateHealthCheck,
    meta: { auth: true, permission: "health_check_registers.create" },
  },
  {
    path: "/health_check/:health_check_id/edit",
    name: "HealthChecks.Edit",
    component: CreateHealthCheck,
    meta: { auth: true, permission: "health_check_registers.view" },
  },

  //User Services
  {
    path: "/user_services",
    name: "UserServices.Index",
    component: UserServices,
    meta: { auth: true, permission: "service_registers.view" },
  },
  {
    path: "/user_service/create",
    name: "UserServices.Create",
    component: CreateUserService,
    meta: { auth: true, permission: "service_registers.create" },
  },
  {
    path: "/user_service/:user_service_id/edit",
    name: "UserServices.Edit",
    component: CreateUserService,
    meta: { auth: true, permission: "service_registers.update" },
  },

  //User Checks
  {
    path: "/user_checks",
    name: "UserChecks.Index",
    component: UserChecks,
    meta: { auth: true, permission: "check_registers.view" },
  },
  {
    path: "/user_check/create",
    name: "UserChecks.Create",
    component: CreateUserCheck,
    meta: { auth: true, permission: "check_registers.create" },
  },
  {
    path: "/user_check/:user_check_id/edit",
    name: "UserChecks.Edit",
    component: CreateUserCheck,
    meta: { auth: true, permission: "check_registers.update" },
  },
  {
    path: "/user_check/:user_check_id/view",
    name: "UserChecks.View",
    component: ViewUserCheck,
    meta: { auth: true, permission: "check_registers.view" },
  },

  //break down register
  {
    path: "/break_down_registers",
    name: "BreakDownRegisters.Index",
    component: BreakDownRegisters,
    meta: { auth: true, permission: "break_down_registers.view" },
  },
  {
    path: "/break_down_registers/create",
    name: "BreakDownRegister.Create",
    component: CreateBreakDownRegister,
    meta: { auth: true, permission: "break_down_registers.create" },
  },
  {
    path: "/break_down_registers/:break_down_list_id/edit",
    name: "BreakDownRegister.Edit",
    component: CreateBreakDownRegister,
    meta: { auth: true, permission: "break_down_registers.view" },
  },

  // list params
  {
    path: "/checks",
    name: "checks.Index",
    component: IndexChecks,
    meta: { auth: true, permission: "checks.view" },
  },
  {
    path: "/checks/create",
    name: "Checks.Create",
    component: CreateCheck,
    meta: { auth: true, permission: "checks.create" },
  },

  {
    path: "/checks/:check_id/edit",
    name: "Checks.Edit",
    component: CreateCheck,
    meta: { auth: true, permission: "checks.update" },
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
    meta: { auth: true, permission: "roles.view" },
  },
  // users
  {
    path: "/users",
    name: "Users.Index",
    component: Users,
    meta: { auth: true, permission: "users.view" },
  },
  {
    path: "/user/create",
    name: "Users.Create",
    component: CreateUser,
    meta: { auth: true, permission: "users.create" },
  },

  {
    path: "/users/:user_id/edit",
    name: "Users.Edit",
    component: CreateUser,
    meta: { auth: true, permission: "users.update" },
  },

  //Asset Parameters
  {
    path: "/asset_attributes",
    name: "AssetAttributes.Index",
    component: AssetAttributes,
    meta: { auth: true, permission: "asset_attributes.view" },
  },
  {
    path: "/asset_attributes/create",
    name: "AssetAttributes.Create",
    component: CreateAssetAttribute,
    meta: { auth: true, permission: "asset_attributes.create" },
  },

  {
    path: "/asset_attributes/:asset_attribute_id/edit",
    name: "AssetAttributes.Edit",
    component: CreateAssetAttribute,
    meta: { auth: true, permission: "asset_attributes.update" },
  },

  //spare Parameters
  {
    path: "/spare_attributes",
    name: "SpareAttributes.Index",
    component: SpareAttributes,
    meta: { auth: true, permission: "spare_attributes.view" },
  },
  {
    path: "/spare_attributes/create",
    name: "SpareAttributes.Create",
    component: CreateSpareAttribute,
    meta: { auth: true, permission: "spare_attributes.create" },
  },

  {
    path: "/spare_attributes/:spare_attribute_id/edit",
    name: "SpareAttributes.Edit",
    component: CreateSpareAttribute,
    meta: { auth: true, permission: "spare_attributes.update" },
  },

  //data source Parameters
  {
    path: "/data_source_attributes",
    name: "DataSourceAttributes.Index",
    component: DataSourceAttributes,
    meta: { auth: true, permission: "data_source_attributes.view" },
  },
  {
    path: "/data_source_attributes/create",
    name: "DataSourceAttributes.Create",
    component: CreateDataSourceAttribute,
    meta: { auth: true, permission: "data_source_attributes.create" },
  },

  {
    path: "/data_source_attributes/:data_source_attribute_id/edit",
    name: "DataSourceAttributes.Edit",
    component: CreateDataSourceAttribute,
    meta: { auth: true, permission: "data_source_attributes.update" },
  },

  //variable Parameters
  {
    path: "/variable_attributes",
    name: "VariableAttributes.Index",
    component: VariableAttributes,
    meta: { auth: true, permission: "variable_attributes.view" },
  },
  {
    path: "/variable_attributes/create",
    name: "VariableAttributes.Create",
    component: CreateVariableAttribute,
    meta: { auth: true, permission: "variable_attributes.create" },
  },

  {
    path: "/variable_attributes/:variable_attribute_id/edit",
    name: "VariableAttributes.Edit",
    component: CreateVariableAttribute,
    meta: { auth: true, permission: "variable_attributes.update" },
  },

  // service attribute
  {
    path: "/service_attributes",
    name: "ServiceAttributes.Index",
    component: ServiceAttributes,
    meta: { auth: true, permission: "service_attributes.view" },
  },
  {
    path: "/service_attributes/create",
    name: "ServiceAttributes.Create",
    component: CreateServiceAttribute,
    meta: { auth: true, permission: "service_attributes.create" },
  },

  {
    path: "/service_attributes/:service_attribute_id/edit",
    name: "ServiceAttributes.Edit",
    component: CreateServiceAttribute,
    meta: { auth: true, permission: "service_attributes.update" },
  },

  // breakdown attribute
  {
    path: "/break_down_attributes",
    name: "BreakDownAttributes.Index",
    component: BreakDownAttributes,
    meta: { auth: true, permission: "break_down_attributes.view" },
  },
  {
    path: "/break_down_attributes/create",
    name: "BreakDownAttributes.Create",
    component: CreateBreakDownAttribute,
    meta: { auth: true, permission: "break_down_attributes.create" },
  },

  {
    path: "/break_down_attributes/:break_down_attribute_id/edit",
    name: "BreakDownAttributes.Edit",
    component: CreateBreakDownAttribute,
    meta: { auth: true, permission: "break_down_attributes.update" },
  },

  // activity attribute
  {
    path: "/activity_attributes",
    name: "ActivityAttributes.Index",
    component: ActivityAttributes,
    meta: { auth: true, permission: "activity_attributes.view" },
  },
  {
    path: "/activity_attributes/create",
    name: "ActivityAttributes.Create",
    component: CreateActivityAttribute,
    meta: { auth: true, permission: "activity_attributes.create" },
  },

  {
    path: "/activity_attributes/:activity_attribute_id/edit",
    name: "ActivityAttributes.Edit",
    component: CreateActivityAttribute,
    meta: { auth: true, permission: "activity_attributes.update" },
  },

  //Deviation
  {
    path: "/deviations",
    name: "Deviations.Index",
    component: Deviations,
    meta: { auth: true, permission: "All" },
  },
  {
    path: "/completed_deviations",
    name: "CompletedDeviations",
    component: CompletedDeviations,
    meta: { auth: true, permission: "All" },
  },
  {
    path: "/breakdown_reports",
    name: "BreakDownReports",
    component: BreakDownReports,
    meta: { auth: true, permission: "break_down_reports.view" },
  },
  {
    path: "/pendings",
    name: "Pendings.Index",
    component: Pendings,
    meta: { auth: true, permission: "All" },
  },

  {
    path: "/UpcomingJobs",
    name: "UpcomingJobs.Index",
    component: UpcomingJobs,
    meta: { auth: true, permission: "All" },
  },

  {
    path: "/downloaded_reports",
    name: "DownloadedReports",
    component: DownloadedReports,
    meta: { auth: true, permission: "downloaded_reports.view" },
  },
  {
    path: "/asset_reports",
    name: "AssetReports.Index",
    component: AssetReports,
    meta: { auth: true, permission: "asset_reports.view" },
  },
  {
    path: "/asset_reports/:type/:id/view",
    name: "ViewAssetReport",
    component: ViewAssetReport,
    meta: { auth: true, permission: "asset_reports.view" },
  },

  // masteres

  {
    path: "/spares",
    name: "Spares.Index",
    component: Spares,
    meta: { auth: true, permission: "spares.view" },
  },
  {
    path: "/spares/create",
    name: "Spares.Create",
    component: CreateSpare,
    meta: { auth: true, permission: "spares.create" },
  },

  {
    path: "/spares/:spare_id/edit",
    name: "Spares.Edit",
    component: CreateSpare,
    meta: { auth: true, permission: "spares.update" },
  },

  {
    path: "/services",
    name: "Services.Index",
    component: Services,
    meta: { auth: true, permission: "services.view" },
  },
  {
    path: "/services/create",
    name: "Services.Create",
    component: CreateService,
    meta: { auth: true, permission: "services.create" },
  },

  {
    path: "/services/:service_id/edit",
    name: "Services.Edit",
    component: CreateService,
    meta: { auth: true, permission: "services.update" },
  },

  {
    path: "/variables",
    name: "Variables.Index",
    component: Variables,
    meta: { auth: true, permission: "variables.view" },
  },
  {
    path: "/variables/create",
    name: "Variables.Create",
    component: CreateVariable,
    meta: { auth: true, permission: "variables.create" },
  },

  {
    path: "/variables/:variable_id/edit",
    name: "Varaibles.Edit",
    component: CreateVariable,
    meta: { auth: true, permission: "variables.update" },
  },

  {
    path: "/data_sources",
    name: "DataSources.Index",
    component: DataSources,
    meta: { auth: true, permission: "data_sources.view" },
  },
  {
    path: "/data_sources/create",
    name: "DataSources.Create",
    component: CreateDataSource,
    meta: { auth: true, permission: "data_sources.create" },
  },

  {
    path: "/data_sources/:data_source_id/edit",
    name: "DataSources.Edit",
    component: CreateDataSource,
    meta: { auth: true, permission: "data_sources.update" },
  },

  {
    path: "/break_down_lists",
    name: "BreakDownLists.Index",
    component: BreakDownLists,
    meta: { auth: true, permission: "All" },
  },
  {
    path: "/break_down_lists/create",
    name: "BreakDownLists.Create",
    component: CreateBreakDownList,
    meta: { auth: true, permission: "All" },
  },

  {
    path: "/break_down_lists/:break_down_list_id/edit",
    name: "BreakDownLists.Edit",
    component: CreateBreakDownList,
    meta: { auth: true, permission: "All" },
  },

  {
    path: "/asset_attributes",
    name: "AssetAttributes",
    component: Test,
    meta: { auth: true, permission: "All" },
  },
  {
    path: "/spre_attributes",
    name: "SpareAttributes",
    component: Test,
    meta: { auth: true, permission: "All" },
  },
  {
    path: "/service_attributes",
    name: "ServiceAttributes",
    component: Test,
    meta: { auth: true, permission: "All" },
  },
  {
    path: "/break_down_attributes",
    name: "BreakDownAttributes",
    component: Test,
    meta: { auth: true, permission: "All" },
  },
  {
    path: "/break_down_lists",
    name: "BreakDownLists",
    component: Test,
    meta: { auth: true, permission: "All" },
  },
  {
    path: "/process_registers",
    name: "ProcessRegisters.Index",
    component: ProcessRegisters,
    meta: { auth: true, permission: "process_registers.view" },
  },
  {
    path: "/process_registers/create",
    name: "CreateProcessRegister.Create",
    component: CreateProcessRegister,
    meta: { auth: true, permission: "process_registers.create" },
  },
  {
    path: "/process_registers/:user_variable_id/edit",
    name: "CreateProcessRegister.Edit",
    component: CreateProcessRegister,
    meta: { auth: true, permission: "process_registers.update" },
  },
  {
    path: "/process_registers/:user_variable_id/view",
    name: "ViewProcessRegister",
    component: ViewProcessRegister,
    meta: { auth: true, permission: "process_registers.view" },
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
    meta: { auth: true, permission: "asset_details.view" },
  },
  {
    path: "/geometric/:asset_id",
    name: "Geometric",
    component: Geometric,
    meta: { auth: true, permission: "asset_details.geometry_types" },
  },
  {
    path: "/horizontal_geometric/:asset_id",
    name: "Horizontal_Geometric",
    component: HorizontalGeometric,
    meta: { auth: true, permission: "asset_details.geometry_types" },
  },

  // spare campaign
  {
    path: "/spare_campaigns",
    name: "SpareCompaingn",
    component: SpareCompaingn,
    meta: { auth: true, permission: "spare_campaigns.view" },
  },

  {
    path: "/process_trends",
    name: "ProcessTrends",
    component: ProcessTrends,
    meta: { auth: true, permission: "process_trends.view" },
  },

  {
    path: "/compliance",
    name: "Compliance",
    component: Test,
    meta: { auth: true, permission: "All" },
  },
  {
    path: "/deviations_review",
    name: "Deviations",
    component: Test,
    meta: { auth: true, permission: "All" },
  },
  {
    path: "/alerts",
    name: "Alerts",
    component: Test,
    meta: { auth: true, permission: "All" },
  },
  {
    path: "/asset_life",
    name: "AssetLife",
    component: Test,
    meta: { auth: true, permission: "All" },
  },
  {
    path: "/upcoming_repairs",
    name: "UpcomingRepairs",
    component: Test,
    meta: { auth: true, permission: "All" },
  },

  //Reports
  {
    path: "/pending_jobs",
    name: "PendingJobs.Index",
    component: PendingJobs,
    meta: { auth: true, permission: "job_pending_reports.view" },
  },
  {
    path: "/deviation_reports",
    name: "DeviationReport.Index",
    component: DeviationReport,
    meta: { auth: true, permission: "deviation_reports.view" },
  },
  {
    path: "/error",
    name: "Error",
    component: Error1,
    meta: { auth: true, permission: "All" },
  },
];

const router = createRouter({
  history: createWebHashHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    // always scroll to top
    return { top: 0 };
  },
  //  }
});

router.beforeEach(function (to, _, next) {
  if (to.path === "/error") {
    next(); // Prevent infinite loop by allowing access to the error page
    return;
  }
  if (to.meta.auth && !store.getters.user) {
    next("/");
  } else if (to.meta.unAuth && store.getters.user) {
    next("/dashboard");
  } else {
    let permissions = store.getters.permissions;
    if (to.meta.permission == "All") {
      next();
    } else if (permissions.length > 0) {
      let permission = permissions.filter(function (el) {
        return el.ability.ability == to.meta.permission || to.meta.permission == "All";
      });
      if (permission.length > 0) {
        next();
      } else {
        next("/error");
      }
    } else {
      next("/error");
    }
  }
});

export default router;
