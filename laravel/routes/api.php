<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\EquipmentTypeController;
use App\Http\Controllers\AssetTypeController;
use App\Http\Controllers\SpareTypeController;
use App\Http\Controllers\ServiceTypeController;
use App\Http\Controllers\CheckController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\AbilityController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\SpareController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssetSpareController;
use App\Http\Controllers\AssetCheckController;
use App\Http\Controllers\ReasonController;
use App\Http\Controllers\UserActivityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserServiceController;
use App\Http\Controllers\UserCheckController;
use App\Http\Controllers\AssetAttributeController;
use App\Http\Controllers\AssetVariableController;
use App\Http\Controllers\AssetServiceController;
use App\Http\Controllers\AssetAccessoryController;
use App\Http\Controllers\AssetDataSourceController;
use App\Http\Controllers\FrequencyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\BreakDownTypeController;
use App\Http\Controllers\DataSourceTypeController;
use App\Http\Controllers\FunctionalController;
use App\Http\Controllers\AccessoryTypeController;
use App\Http\Controllers\VariableTypeController;
use App\Http\Controllers\SpareAttributeController;
use App\Http\Controllers\VariableAttributeController;
use App\Http\Controllers\DataSourceAttributeController;
use App\Http\Controllers\ServiceAttributeController;
use App\Http\Controllers\BreakDownAttributeController;
use App\Http\Controllers\VariableController;
use App\Http\Controllers\DataSourceController;
use App\Http\Controllers\BreakDownListController;
use App\Http\Controllers\ServiceAttributeValueController;
use App\Http\Controllers\SpareAttributeValueController;
use App\Http\Controllers\ListParameterController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\UserVariableController;
use App\Http\Controllers\ActivityAttributeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [UserAuthController::class, 'login']);
Route::post('generateOTP', [UserAuthController::class, 'generateOTP']);
Route::post('verifyOTP', [UserAuthController::class, 'verifyOTP']);
Route::post('forgotPassword', [UserAuthController::class, 'forgotPassword']);
Route::post('resetPassword', [UserAuthController::class, 'resetPassword']);
Route::get('downloadCheckAttachment',[UserCheckController::class, 'downloadCheckAttachment']);
Route::post('addConsent', [UserAuthController::class, 'addConsent']);
Route::post('getAssetRegisterDepartments',[UserCheckController::class, 'getAssetRegisterDepartments']);

Route::get('downloadAssetQRCode',[AssetController::class, 'downloadAssetQRCode']);

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::post('logout',[UserAuthController::class,'logout']);
    Route::post('updateProfile', [UserAuthController::class,'updateProfile']);
    Route::post('me', [UserAuthController::class,'me']);   
    Route::post('changePassword', [UserAuthController::class,'changePassword']);
    Route::post('updateUserProfile', [UserAuthController::class,'updateUserProfile']);
    Route::post('deleteConsent', [UserAuthController::class,'deleteConsent']);

    Route::post('paginateRoles',[RoleController::class, 'paginateRoles']);
    Route::post('getRoles', [RoleController::class, 'getRoles']);
    Route::post('addRole',[RoleController::class, 'addRole']);
    Route::post('getRole',[RoleController::class, 'getRole']);
    Route::post('updateRole',[RoleController::class, 'updateRole']);
    Route::post('deleteRole',[RoleController::class, 'deleteRole']);

    Route::post('paginateUsers',[UserController::class, 'paginateUsers']);
    Route::post('getUsers',[UserController::class, 'getUsers']);
    Route::post('addUser',[UserController::class, 'addUser']);
    Route::post('getUser',[UserController::class, 'getUser']);
    Route::post('updateUser',[UserController::class, 'updateUser']);
    Route::post('deleteUser',[UserController::class, 'deleteUser']);

    Route::post('paginateAreas',[AreaController::class, 'paginateAreas']);
    Route::post('getAreas', [AreaController::class, 'getAreas']);
    Route::post('addArea',[AreaController::class, 'addArea']);
    Route::post('getArea',[AreaController::class, 'getArea']);
    Route::post('updateArea',[AreaController::class, 'updateArea']);
    Route::post('deleteArea',[AreaController::class, 'deleteArea']);

    Route::post('paginatePlants',[PlantController::class, 'paginatePlants']);
    Route::post('getPlants', [PlantController::class, 'getPlants']);
    Route::post('addPlant',[PlantController::class, 'addPlant']);
    Route::post('getPlant',[PlantController::class, 'getPlant']);
    Route::post('updatePlant',[PlantController::class, 'updatePlant']);
    Route::post('deletePlant',[PlantController::class, 'deletePlant']); 

    Route::post('paginateSections',[SectionController::class, 'paginateSections']);
    Route::post('getSections',[SectionController::class, 'getSections']);
    Route::post('addSection',[SectionController::class, 'addSection']);
    Route::post('getSection',[SectionController::class, 'getSection']);
    Route::post('updateSection',[SectionController::class, 'updateSection']);
    Route::post('deleteSection',[SectionController::class, 'deleteSection']);

    Route::post('paginateEquipmentTypes',[EquipmentTypeController::class, 'paginateEquipmentTypes']);
    Route::post('getEquipmentTypes',[EquipmentTypeController::class, 'getEquipmentTypes']);
    Route::post('addEquipmentType',[EquipmentTypeController::class, 'addEquipmentType']);
    Route::post('getEquipmentType',[EquipmentTypeController::class, 'getEquipmentType']);
    Route::post('updateEquipmentType',[EquipmentTypeController::class, 'updateEquipmentType']);
    Route::post('deleteEquipmentType',[EquipmentTypeController::class, 'deleteEquipmentType']);

    Route::post('paginateAssetTypes',[AssetTypeController::class, 'paginateAssetTypes']);
    Route::post('getAssetTypes', [AssetTypeController::class, 'getAssetTypes']);
    Route::post('addAssetType',[AssetTypeController::class, 'addAssetType']);
    Route::post('getAssetType',[AssetTypeController::class, 'getAssetType']);
    Route::post('updateAssetType',[AssetTypeController::class, 'updateAssetType']);
    Route::post('deleteAssetType',[AssetTypeController::class, 'deleteAssetType']);

    Route::post('paginateSpareTypes',[SpareTypeController::class, 'paginateSpareTypes']);
    Route::post('getSpareTypes',[SpareTypeController::class, 'getSpareTypes']);
    Route::post('addSpareType',[SpareTypeController::class, 'addSpareType']);
    Route::post('getSpareType',[SpareTypeController::class, 'getSpareType']);
    Route::post('updateSpareType',[SpareTypeController::class, 'updateSpareType']);
    Route::post('deleteSpareType',[SpareTypeController::class, 'deleteSpareType']);

    Route::post('paginateServiceTypes',[ServiceTypeController::class, 'paginateServiceTypes']);
    Route::post('getServiceTypes',[ServiceTypeController::class, 'getServiceTypes']);
    Route::post('addServiceType',[ServiceTypeController::class, 'addServiceType']);
    Route::post('getServiceType',[ServiceTypeController::class, 'getServiceType']);
    Route::post('updateServiceType',[ServiceTypeController::class, 'updateServiceType']);
    Route::post('deleteServiceType',[ServiceTypeController::class, 'deleteServiceType']);

    Route::post('paginateChecks',[CheckController::class, 'paginateChecks']);
    Route::post('getChecks',[CheckController::class, 'getChecks']);
    Route::post('addCheck',[CheckController::class, 'addCheck']);
    Route::post('getCheck',[CheckController::class, 'getCheck']);
    Route::post('updateCheck',[CheckController::class, 'updateCheck']);
    Route::post('deleteCheck',[CheckController::class, 'deleteCheck']);
    Route::post('getAssetTypeChecks',[CheckController::class, 'getAssetTypeChecks']);

    //Module 
    Route::post('/paginateModules', [ModuleController::class, 'paginateModules']);
    Route::post('/addModule', [ModuleController::class, 'addModule']);
    Route::post('/getModule', [ModuleController::class, 'getModule']);
    Route::post('/getModules', [ModuleController::class, 'getModules']);
    Route::post('/updateModule', [ModuleController::class, 'updateModule']);
    Route::post('/deleteModule', [ModuleController::class, 'deleteModule']);

    //Ability
    Route::post('/paginateAbilities',[AbilityController::class, 'paginateAbilities']);
    Route::post('/addAbility', [AbilityController::class, 'addAbility']);
    Route::post('/getAbility', [AbilityController::class, 'getAbility']);
    Route::post('/getAbilities', [AbilityController::class, 'getAbilities']);
    Route::post('/updateAbility', [AbilityController::class, 'updateAbility']);
    Route::post('/deleteAbility', [AbilityController::class, 'deleteAbility']);
    Route::post('/getPermissions', [AbilityController::class, 'getPermissions']);
    Route::post('/getPermissionStatus', [AbilityController::class, 'getPermissionStatus']);
    Route::post('/deleteAuthorization', [AbilityController::class, 'deleteAuthorization']);
    Route::post('/addPermissions', [AbilityController::class, 'addPermissions']);

    Route::post('paginateEquipment',[EquipmentController::class, 'paginateEquipment']);
    Route::post('getEquipments',[EquipmentController::class, 'getEquipments']);
    Route::post('addEquipment',[EquipmentController::class, 'addEquipment']);
    Route::post('getEquipment',[EquipmentController::class, 'getEquipment']);
    Route::post('updateEquipment',[EquipmentController::class, 'updateEquipment']);
    Route::post('deleteEquipment',[EquipmentController::class, 'deleteEquipment']);

    Route::post('paginateSpares',[SpareController::class, 'paginateSpares']);
    Route::post('getSpares',[SpareController::class, 'getSpares']);
    Route::post('addSpare',[SpareController::class, 'addSpare']);
    Route::post('getSpare',[SpareController::class, 'getSpare']);
    Route::post('updateSpare',[SpareController::class, 'updateSpare']);
    Route::post('deleteSpare',[SpareController::class, 'deleteSpare']);
    Route::post('getAssetTypeSpares',[SpareController::class, 'getAssetTypeSpares']);
    Route::post('getSpareData',[SpareController::class, 'getSpareData']);

    Route::post('paginateServices',[ServiceController::class, 'paginateServices']);
    Route::post('getServices',[ServiceController::class, 'getServices']);
    Route::post('addService',[ServiceController::class, 'addService']);
    Route::post('getService',[ServiceController::class, 'getService']);
    Route::post('updateService',[ServiceController::class, 'updateService']);
    Route::post('deleteService',[ServiceController::class, 'deleteService']);
    Route::post('getAssetTypeServices',[ServiceController::class, 'getAssetTypeServices']);
    Route::post('getServiceData',[ServiceController::class, 'getServiceData']);

    Route::post('paginateAssets',[AssetController::class, 'paginateAssets']);
    Route::post('addAsset',[AssetController::class, 'addAsset']);
    Route::post('updateAsset',[AssetController::class, 'updateAsset']);
    Route::post('getAssets',[AssetController::class, 'getAssets']);
    Route::post('getAsset',[AssetController::class, 'getAsset']);
    Route::post('deleteAsset',[AssetController::class, 'deleteAsset']);
    Route::post('getAssetsDropdown',[AssetController::class, 'getAssetsDropdown']);
    Route::post('getAssetQRCode',[AssetController::class, 'getAssetQRCode']);
    Route::post('getAssetCode',[AssetController::class, 'getAssetCode']);
    Route::post('getAssetdata',[AssetController::class, 'getAssetdata']);
    Route::post('getAssetZones',[AssetController::class, 'getAssetZones']);
    Route::post('forceDeleteAsset',[AssetController::class, 'forceDeleteAsset']);

    Route::post('paginateAssetSpares',[AssetSpareController::class, 'paginateAssetSpares']);
    Route::post('addAssetSpare',[AssetSpareController::class, 'addAssetSpare']);
    Route::post('getAssetSpare',[AssetSpareController::class, 'getAssetSpare']);
    Route::post('getAssetSpares',[AssetSpareController::class, 'getAssetSpares']);
    Route::post('updateAssetSpare',[AssetSpareController::class, 'updateAssetSpare']);
    Route::post('deleteAssetSpare',[AssetSpareController::class, 'deleteAssetSpare']);
    Route::post('forceDeleteAssetSpare',[AssetSpareController::class, 'forceDeleteAssetSpare']);
    Route::post('getAssetServiceSpares',[AssetSpareController::class, 'getAssetServiceSpares']);
    Route::post('assetSpareAttributeValues',[AssetSpareController::class, 'assetSpareAttributeValues']);

    Route::post('paginateAssetServices',[AssetServiceController::class, 'paginateAssetServices']);
    Route::post('addAssetService',[AssetServiceController::class, 'addAssetService']);
    Route::post('getAssetService',[AssetServiceController::class, 'getAssetService']);
    Route::post('getAssetServices',[AssetServiceController::class, 'getAssetServices']);
    Route::post('updateAssetService',[AssetServiceController::class, 'updateAssetService']);
    Route::post('deleteAssetService',[AssetServiceController::class, 'deleteAssetService']);
    Route::post('forceDeleteAssetService',[AssetServiceController::class, 'forceDeleteAssetService']);
    Route::post('getAssetsServices',[AssetServiceController::class, 'getAssetsServices']);
    Route::post('assetServiceAttributeValues',[AssetServiceController::class, 'assetServiceAttributeValues']);
    
    Route::post('paginateAssetChecks',[AssetCheckController::class, 'paginateAssetChecks']);
    Route::post('addAssetCheck',[AssetCheckController::class, 'addAssetCheck']);
    Route::post('getAssetCheck',[AssetCheckController::class, 'getAssetCheck']);
    Route::post('getAssetChecks',[AssetCheckController::class, 'getAssetChecks']);
    Route::post('updateAssetCheck',[AssetCheckController::class, 'updateAssetCheck']);
    Route::post('deleteAssetCheck',[AssetCheckController::class, 'deleteAssetCheck']);
    Route::post('forceDeleteAssetCheck',[AssetCheckController::class, 'forceDeleteAssetCheck']);
    Route::post('deviationAssetChecks',[AssetCheckController::class, 'deviationAssetChecks']);
    Route::post('getAssetRegisterChecks',[AssetCheckController::class, 'getAssetRegisterChecks']);

    Route::post('paginateAssetVariables',[AssetVariableController::class, 'paginateAssetVariables']);
    Route::post('getAssetVariables',[AssetVariableController::class, 'getAssetVariables']);
    Route::post('addAssetVariable',[AssetVariableController::class, 'addAssetVariable']);
    Route::post('getAssetVariable',[AssetVariableController::class, 'getAssetVariable']);
    Route::post('updateAssetVariable',[AssetVariableController::class, 'updateAssetVariable']);
    Route::post('deleteAssetVariable',[AssetVariableController::class, 'deleteAssetVariable']);
    Route::post('getAssetTypeVariables',[AssetVariableController::class, 'getAssetTypeVariables']);
    Route::post('getAssetRegisterVariables',[AssetVariableController::class, 'getAssetRegisterVariables']);
    Route::post('assetVariableAttributeValues',[AssetVariableController::class, 'assetVariableAttributeValues']);

    Route::post('paginateAssetDataSources',[AssetDataSourceController::class, 'paginateAssetDataSources']);
    Route::post('getAssetDataSources',[AssetDataSourceController::class, 'getAssetDataSources']);
    Route::post('addAssetDataSource',[AssetDataSourceController::class, 'addAssetDataSource']);
    Route::post('getAssetDataSource',[AssetDataSourceController::class, 'getAssetDataSource']);
    Route::post('updateAssetDataSource',[AssetDataSourceController::class, 'updateAssetDataSource']);
    Route::post('deleteAssetDataSource',[AssetDataSourceController::class, 'deleteAssetDataSource']);
    Route::post('getAssetTypeDataSources',[AssetDataSourceController::class, 'getAssetTypeDataSources']);
    Route::post('assetDataSourceScripts',[AssetDataSourceController::class, 'assetDataSourceScripts']);
    Route::post('assetDataSourceAttributeValues',[AssetDataSourceController::class, 'assetDataSourceAttributeValues']);

    Route::post('paginateAssetAccessories',[AssetAccessoryController::class, 'paginateAssetAccessories']);
    Route::post('getAssetAccessories',[AssetAccessoryController::class, 'getAssetAccessories']);
    Route::post('addAssetAccessory',[AssetAccessoryController::class, 'addAssetAccessory']);
    Route::post('getAssetAccessory',[AssetAccessoryController::class, 'getAssetAccessory']);
    Route::post('updateAssetAccessory',[AssetAccessoryController::class, 'updateAssetAccessory']);
    Route::post('deleteAssetAccessory',[AssetAccessoryController::class, 'deleteAssetAccessory']);

    Route::post('paginateReasons',[ReasonController::class, 'paginateReasons']);
    Route::post('addReason',[ReasonController::class, 'addReason']);
    Route::post('getReason',[ReasonController::class, 'getReason']);
    Route::post('getReasons',[ReasonController::class, 'getReasons']);
    Route::post('updateReason',[ReasonController::class, 'updateReason']);
    Route::post('deleteReason',[ReasonController::class, 'deleteReason']);

    Route::post('paginateUserActivities',[UserActivityController::class, 'paginateUserActivities']);
    Route::post('addUserActivity',[UserActivityController::class, 'addUserActivity']);
    Route::post('updateUserActivity',[UserActivityController::class, 'updateUserActivity']);
    Route::post('deleteUserActivity',[UserActivityController::class, 'deleteUserActivity']);
    Route::post('getUserActivity',[UserActivityController::class, 'getUserActivity']);
    Route::post('getActivitiesDropdown',[UserActivityController::class, 'getActivitiesDropdown']);

    //Dashboard
    Route::post('getDashboardContent',[DashboardController::class, 'getDashboardContent']);

    Route::post('paginateUserServices',[UserServiceController::class, 'paginateUserServices']);
    Route::post('addUserService',[UserServiceController::class, 'addUserService']);
    Route::post('getUserService',[UserServiceController::class, 'getUserService']);
    Route::post('getUserServices',[UserServiceController::class, 'getUserServices']);
    Route::post('updateUserService',[UserServiceController::class, 'updateUserService']);
    Route::post('deleteUserService',[UserServiceController::class, 'deleteUserService']);
    Route::post('deleteUserSpare',[UserServiceController::class, 'deleteUserSpare']);
    Route::post('getPendingServices',[UserServiceController::class, 'getPendingServices']);
    Route::post('getUpcomingServices',[UserServiceController::class, 'getUpcomingServices']);

    Route::post('paginateUserChecks',[UserCheckController::class, 'paginateUserChecks']);
    Route::post('addUserCheck',[UserCheckController::class, 'addUserCheck']);
    Route::post('updateUserCheck',[UserCheckController::class, 'updateUserCheck']);
    Route::post('getUserCheck',[UserCheckController::class, 'getUserCheck']);
    Route::post('deleteUserCheck',[UserCheckController::class, 'deleteUserCheck']);

    Route::post('paginateAssetAttributes',[AssetAttributeController::class, 'paginateAssetAttributes']);
    Route::post('addAssetAttribute',[AssetAttributeController::class, 'addAssetAttribute']);
    Route::post('updateAssetAttribute',[AssetAttributeController::class, 'updateAssetAttribute']);
    Route::post('getAssetAttribute',[AssetAttributeController::class, 'getAssetAttribute']);
    Route::post('getAssetAttributes',[AssetAttributeController::class, 'getAssetAttributes']);
    Route::post('deleteAssetAttribute',[AssetAttributeController::class, 'deleteAssetAttribute']);

    Route::post('paginateFrequencies',[FrequencyController::class, 'paginateFrequencies']);
    Route::post('addFrequency',[FrequencyController::class, 'addFrequency']);
    Route::post('updateFrequency',[FrequencyController::class, 'updateFrequency']);
    Route::post('getFrequency',[FrequencyController::class, 'getFrequency']);
    Route::post('getFrequencies',[FrequencyController::class, 'getFrequencies']);
    Route::post('deleteFrequency',[FrequencyController::class, 'deleteFrequency']);

    Route::post('paginateDepartments',[DepartmentController::class, 'paginateDepartments']);
    Route::post('addDepartment',[DepartmentController::class, 'addDepartment']);
    Route::post('updateDepartment',[DepartmentController::class, 'updateDepartment']);
    Route::post('getDepartment',[DepartmentController::class, 'getDepartment']);
    Route::post('getDepartments',[DepartmentController::class, 'getDepartments']);
    Route::post('deleteDepartment',[DepartmentController::class, 'deleteDepartment']);

    Route::post('paginateBreakDownTypes',[BreakDownTypeController::class, 'paginateBreakDownTypes']);
    Route::post('addBreakDownType',[BreakDownTypeController::class, 'addBreakDownType']);
    Route::post('updateBreakDownType',[BreakDownTypeController::class, 'updateBreakDownType']);
    Route::post('getBreakDownType',[BreakDownTypeController::class, 'getBreakDownType']);
    Route::post('getBreakDownTypes',[BreakDownTypeController::class, 'getBreakDownTypes']);
    Route::post('deleteBreakDownType',[BreakDownTypeController::class, 'deleteBreakDownType']);

    Route::post('paginateDataSourceTypes',[DataSourceTypeController::class, 'paginateDataSourceTypes']);
    Route::post('addDataSourceType',[DataSourceTypeController::class, 'addDataSourceType']);
    Route::post('updateDataSourceType',[DataSourceTypeController::class, 'updateDataSourceType']);
    Route::post('getDataSourceType',[DataSourceTypeController::class, 'getDataSourceType']);
    Route::post('getDataSourceTypes',[DataSourceTypeController::class, 'getDataSourceTypes']);
    Route::post('deleteDataSourceType',[DataSourceTypeController::class, 'deleteDataSourceType']);

    Route::post('paginateFunctionals',[FunctionalController::class, 'paginateFunctionals']);
    Route::post('addFunctional',[FunctionalController::class, 'addFunctional']);
    Route::post('updateFunctional',[FunctionalController::class, 'updateFunctional']);
    Route::post('getFunctional',[FunctionalController::class, 'getFunctional']);
    Route::post('getFunctionals',[FunctionalController::class, 'getFunctionals']);
    Route::post('deleteFunctional',[FunctionalController::class, 'deleteFunctional']);

    Route::post('paginateAccessoryTypes',[AccessoryTypeController::class, 'paginateAccessoryTypes']);
    Route::post('addAccessoryType',[AccessoryTypeController::class, 'addAccessoryType']);
    Route::post('updateAccessoryType',[AccessoryTypeController::class, 'updateAccessoryType']);
    Route::post('getAccessoryType',[AccessoryTypeController::class, 'getAccessoryType']);
    Route::post('getAccessoryTypes',[AccessoryTypeController::class, 'getAccessoryTypes']);
    Route::post('deleteAccessoryType',[AccessoryTypeController::class, 'deleteAccessoryType']);

    Route::post('paginateVariableTypes',[VariableTypeController::class, 'paginateVariableTypes']);
    Route::post('getVariableTypes',[VariableTypeController::class, 'getVariableTypes']);
    Route::post('addVariableType',[VariableTypeController::class, 'addVariableType']);
    Route::post('getVariableType',[VariableTypeController::class, 'getVariableType']);
    Route::post('updateVariableType',[VariableTypeController::class, 'updateVariableType']);
    Route::post('deleteVariableType',[VariableTypeController::class, 'deleteVariableType']);

    Route::post('paginateSpareAttributes',[SpareAttributeController::class, 'paginateSpareAttributes']);
    Route::post('addSpareAttribute',[SpareAttributeController::class, 'addSpareAttribute']);
    Route::post('updateSpareAttribute',[SpareAttributeController::class, 'updateSpareAttribute']);
    Route::post('getSpareAttribute',[SpareAttributeController::class, 'getSpareAttribute']);
    Route::post('getSpareAttributes',[SpareAttributeController::class, 'getSpareAttributes']);
    Route::post('deleteSpareAttribute',[SpareAttributeController::class, 'deleteSpareAttribute']);

    Route::post('getSparesDropdown',[SpareAttributeValueController::class, 'getSparesDropdown']);

    Route::post('paginateDataSourceAttributes',[DataSourceAttributeController::class, 'paginateDataSourceAttributes']);
    Route::post('addDataSourceAttribute',[DataSourceAttributeController::class, 'addDataSourceAttribute']);
    Route::post('updateDataSourceAttribute',[DataSourceAttributeController::class, 'updateDataSourceAttribute']);
    Route::post('getDataSourceAttribute',[DataSourceAttributeController::class, 'getDataSourceAttribute']);
    Route::post('getDataSourceAttributes',[DataSourceAttributeController::class, 'getDataSourceAttributes']);
    Route::post('deleteDataSourceAttribute',[DataSourceAttributeController::class, 'deleteDataSourceAttribute']);

    Route::post('paginateVariableAttributes',[VariableAttributeController::class, 'paginateVariableAttributes']);
    Route::post('addVariableAttribute',[VariableAttributeController::class, 'addVariableAttribute']);
    Route::post('updateVariableAttribute',[VariableAttributeController::class, 'updateVariableAttribute']);
    Route::post('getVariableAttribute',[VariableAttributeController::class, 'getVariableAttribute']);
    Route::post('getVariableAttributes',[VariableAttributeController::class, 'getVariableAttributes']);
    Route::post('deleteVariableAttribute',[VariableAttributeController::class, 'deleteVariableAttribute']);

    Route::post('paginateServiceAttributes',[ServiceAttributeController::class, 'paginateServiceAttributes']);
    Route::post('addServiceAttribute',[ServiceAttributeController::class, 'addServiceAttribute']);
    Route::post('updateServiceAttribute',[ServiceAttributeController::class, 'updateServiceAttribute']);
    Route::post('getServiceAttribute',[ServiceAttributeController::class, 'getServiceAttribute']);
    Route::post('getServiceAttributes',[ServiceAttributeController::class, 'getServiceAttributes']);
    Route::post('deleteServiceAttribute',[ServiceAttributeController::class, 'deleteServiceAttribute']);
    

    Route::post('getServicesDropdown',[ServiceAttributeValueController::class, 'getServicesDropdown']);

    Route::post('paginateBreakDownAttributes',[BreakDownAttributeController::class, 'paginateBreakDownAttributes']);
    Route::post('addBreakDownAttribute',[BreakDownAttributeController::class, 'addBreakDownAttribute']);
    Route::post('updateBreakDownAttribute',[BreakDownAttributeController::class, 'updateBreakDownAttribute']);
    Route::post('getBreakDownAttribute',[BreakDownAttributeController::class, 'getBreakDownAttribute']);
    Route::post('getBreakDownAttributes',[BreakDownAttributeController::class, 'getBreakDownAttributes']);
    Route::post('deleteBreakDownAttribute',[BreakDownAttributeController::class, 'deleteBreakDownAttribute']);

    Route::post('paginateVariables',[VariableController::class, 'paginateVariables']);
    Route::post('getVariables',[VariableController::class, 'getVariables']);
    Route::post('addVariable',[VariableController::class, 'addVariable']);
    Route::post('getVariable',[VariableController::class, 'getVariable']);
    Route::post('updateVariable',[VariableController::class, 'updateVariable']);
    Route::post('deleteVariable',[VariableController::class, 'deleteVariable']);
    Route::post('getAssetTypeVariables',[VariableController::class, 'getAssetTypeVariables']);
    Route::post('getVariableData',[VariableController::class, 'getVariableData']);
    Route::post('getVariablesDropdown',[VariableController::class, 'getVariablesDropdown']);

    Route::post('paginateDataSources',[DataSourceController::class, 'paginateDataSources']);
    Route::post('getDataSources',[DataSourceController::class, 'getDataSources']);
    Route::post('addDataSource',[DataSourceController::class, 'addDataSource']);
    Route::post('getDataSource',[DataSourceController::class, 'getDataSource']);
    Route::post('updateDataSource',[DataSourceController::class, 'updateDataSource']);
    Route::post('deleteDataSource',[DataSourceController::class, 'deleteDataSource']);
    Route::post('getAssetTypeDataSources',[DataSourceController::class, 'getAssetTypeDataSources']);
    Route::post('getDataSourceData',[DataSourceController::class, 'getDataSourceData']);
    Route::post('getDataSourcesDropdown',[DataSourceController::class, 'getDataSourcesDropdown']);

    Route::post('paginateBreakDownLists',[BreakDownListController::class, 'paginateBreakDownLists']);
    Route::post('getBreakDownLists',[BreakDownListController::class, 'getBreakDownLists']);
    Route::post('addBreakDownList',[BreakDownListController::class, 'addBreakDownList']);
    Route::post('getBreakDownList',[BreakDownListController::class, 'getBreakDownList']);
    Route::post('updateBreakDownList',[BreakDownListController::class, 'updateBreakDownList']);
    Route::post('deleteBreakDownList',[BreakDownListController::class, 'deleteBreakDownList']);
    Route::post('getAssetTypeBreakDownLists',[BreakDownListController::class, 'getAssetTypeBreakDownLists']);
    Route::post('getBreakDownData',[BreakDownListController::class, 'getBreakDownData']);
    Route::post('getBreakDownsDropdown',[BreakDownListController::class, 'getBreakDownsDropdown']);

    Route::post('paginateListParameters',[ListParameterController::class, 'paginateListParameters']);
    Route::post('getListParameters', [ListParameterController::class, 'getListParameters']);
    Route::post('addListParameter',[ListParameterController::class, 'addListParameter']);
    Route::post('getListParameter',[ListParameterController::class, 'getListParameter']);
    Route::post('updateListParameter',[ListParameterController::class, 'updateListParameter']);
    Route::post('deleteListParameter',[ListParameterController::class, 'deleteListParameter']);

    Route::post('paginateCampaigns',[CampaignController::class, 'paginateCampaigns']);
    Route::post('getLocations',[CampaignController::class, 'getLocations']);
    Route::post('addCampaign',[CampaignController::class, 'addCampaign']);
    Route::post('campaignResultImages',[CampaignController::class, 'campaignResultImages']);
    Route::post('deleteHealthCheck',[CampaignController::class, 'deleteHealthCheck']);

    Route::post('paginateUserVariables',[UserVariableController::class, 'paginateUserVariables']);
    Route::post('addUserVariable',[UserVariableController::class, 'addUserVariable']);
    Route::post('updateUserVariable',[UserVariableController::class, 'updateUserVariable']);
    Route::post('getUserVariable',[UserVariableController::class, 'getUserVariable']);
    Route::post('deleteUserVariable',[UserVariableController::class, 'deleteUserVariable']);

    Route::post('paginateActivityAttributes',[ActivityAttributeController::class, 'paginateActivityAttributes']);
    Route::post('addActivityAttribute',[ActivityAttributeController::class, 'addActivityAttribute']);
    Route::post('getActivityAttributes',[ActivityAttributeController::class, 'getActivityAttributes']);
    Route::post('getActivityAttribute',[ActivityAttributeController::class, 'getActivityAttribute']);
    Route::post('updateActivityAttribute',[ActivityAttributeController::class, 'updateActivityAttribute']);
    Route::post('deleteActivityAttribute',[ActivityAttributeController::class, 'deleteActivityAttribute']);
});