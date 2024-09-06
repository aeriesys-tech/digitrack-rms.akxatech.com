<template>
    <div class="">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div>
                <ol class="breadcrumb fs-sm mb-1">
                    <li class="breadcrumb-item" aria-current="page">
                        <router-link to="/dashboard">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0)">Masters</a>
                    </li>
                    <li class="breadcrumb-item"><router-link to="/assets">Assets</router-link></li>
                    <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
                <h4 class="main-title mb-0">Assets</h4>
            </div>
            <router-link to="/assets" type="submit" class="btn btn-primary" style="float: right;"><i class="ri-list-check"></i> ASSETS</router-link>
        </div>
        <div class="row g-2">
            <div class="col-xl-4 mb-1">
                <div class="row">
                    <div class="col-12 mb-2">
                        <div class="card card-one">
                            <div class="card-header d-flex justify-content-between">
                                <h6 class="card-title">{{asset?.asset_code}}</h6>
                            </div>
                            <div class="card-body">
                                <nav class="nav nav-classic">
                                    <span class="nav-link text-dark"><span>Asset Code</span> <span class="badge text-dark" style="text-wrap: balance;">{{asset.asset_code}}</span></span>
                                    <span class="nav-link text-dark"><span>Asset Name</span> <span class="badge text-dark">{{asset.asset_name}}</span></span>
                                    <span class="nav-link text-dark"><span>Asset Type</span> <span class="badge text-dark">{{asset.asset_type?.asset_type_name}}</span></span>
                                    <span class="nav-link text-dark"><span>Latitude</span> <span class="badge text-dark">{{asset.latitude}}</span></span>
                                    <span class="nav-link text-dark"><span>Longitude</span> <span class="badge text-dark">{{asset.longitude}}</span></span>
                                    <span class="nav-link text-dark"><span>Radius</span> <span class="badge text-dark">{{asset.radius}}</span></span>
                                    <span class="nav-link text-dark"><span>Department</span> <span class="badge text-dark">{{asset.department?.department_name}}</span></span>
                                    <span class="nav-link text-dark"><span>Section</span> <span class="badge text-dark">{{asset.section?.section_name}}</span></span>
                                    <span class="nav-link text-dark"><span>Functional</span> <span class="badge text-dark">{{asset.functional?.functional_name}}</span></span>
                                    <span class="nav-link text-dark" v-for="(zone, index) in asset.zone_name" :key="zone.asset_zone_id">
                                        <span>Zone {{ index + 1 }}</span> 
                                        <span class="badge text-dark">{{ zone.zone_name }}</span>
                                    </span>
                                    <h6 class="nav-link text-dark d-flex justify-content-between" v-for="asset_attribute, key in asset.asset_attributes" :key="key">
                                        {{ asset_attribute.display_name}}
                                        <span  v-if="asset_attribute.field_name == 'Color'" :style="getColor(asset_attribute)">{{ asset_attribute?.asset_attribute_value?.field_value }}</span>
                                        <span v-else>{{ asset_attribute?.asset_attribute_value?.field_value }}</span>
                                    </h6>

                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card card-one">
                            <div class="card-body text-center">
                                <div class="mb-3 mt-3">
                                    <img :src="asset?.QR_Code" class="img" style="width: 150px;" />
                                </div>
                                <h3 class="card-title">{{asset.asset_code}}</h3>
                                <p class="card-text">Please scan the QR Code to get the asset details...</p>
                                <div class="row g-2 g-sm-3 mb-3">
                                    <div class="col-sm">
                                        <button type="button" class="btn btn-primary" @click="downloadQR()"><i class="ri-download-2-line fs-18 lh-1"></i> Download QR</button>
                                    </div>
                                </div>
                            </div>
                            <!-- card-body -->
                        </div>  
                    </div>
                </div>
                
            </div>
            <!-- try -->
            <div class="col-xl-8 mb-2" style="height: 950px; overflow-y: scroll;">
                <div class="row">
                    <div class="col-12 mb-2" v-can="'assetSpares.view'">
                        <div class="card card-one">
                            <div class="card-header d-flex justify-content-between">
                                <h6 class="card-title">Spares</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-5" v-can="'assetSpares.create'">
                                        <!-- <div class="dropdown" @click="toggleAssetZoneStatus()">
                                            <div class="overselect"></div>
                                            <select class="form-control form-control" :class="{'is-invalid':errors.asset_zones}">
                                                <option value="">Select Asset Zone</option>
                                            </select>
                                            <span v-if="errors.asset_zones" class="invalid-feedback">{{ errors.asset_zones[0] }}</span>
                                        </div>
                                        <div class="multiselect" v-if="asset_zone_status_spares">
                                            <ul>
                                                <li class="" v-for="(asset_zone, index) in asset_zones" :key="index">
                                                    <input type="checkbox" :value="asset_zone.asset_zone_id" v-model="spare.asset_zone_id" style="padding: 2px;" />
                                                    <label style="margin-left: 5px;">{{ asset_zone.zone_name }}</label>
                                                </li>
                                            </ul>
                                        </div> -->

                                        <div class="dropdown" @click="toggleAssetZoneStatus('spares')">
                                            <div class="overselect"></div>
                                                <select class="form-control form-control" :class="{'is-invalid':errors.asset_zones}">
                                                    <option value="">Select Asset Zone</option>
                                                </select>
                                                <span v-if="errors.asset_zones" class="invalid-feedback">{{ errors.asset_zones[0] }}</span>
                                            </div>
                                            <div class="multiselect" v-if="asset_zone_status_spares">
                                                <ul>
                                                    <li v-for="(asset_zone, index) in asset_zones" :key="index">
                                                        <input type="checkbox" :value="asset_zone.asset_zone_id" v-model="spare.asset_zones" style="padding: 2px;" @click="updateSpareAssetZone($event, spare)"/>
                                                        <label style="margin-left: 5px;">{{ asset_zone.zone_name }}</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    <div class="col-md-5" v-can="'assetSpares.create'">
                                        <!-- <div class="d-flex justify-content-between" v-can="'assetSpares.create'"> -->
                                        <search
                                            :class="{ 'is-invalid': errors.spare_id }"
                                            :customClass="{ 'is-invalid': errors.spare_id }"
                                            aria-describedby="basic-addon2"
                                            aria-label="Select Spare"
                                            class="my-auto"
                                            :initialize="spare.spare_id"
                                            id="spare_id"
                                            label="spare_name"
                                            label2="spare_code"
                                            placeholder="Select Spare"
                                            :data="spares"
                                            @input=" spare1 => spare.spare_id = spare1"
                                        >
                                        </search>
                                        <span v-if="errors.spare_id" class="invalid-feedback">{{ errors.spare_id[0] }}</span>
                                    </div>
                                    <div class="col-md-2" v-can="'assetSpares.create'">
                                        <!-- <div style="float: left;">
                                            <button class="btn btn-outline-success me-2" @click="addSpare()"><i class="ri-add-circle-line icon-hgt"></i> Add</button>
                                        </div> -->


                                        <button v-if="spare.asset_spare_id" class="btn btn-outline-success me-2" @click="updateSpare()">
                                                    <i class="ri-add-circle-line icon-hgt"></i> Update
                                                </button>
                                                <button v-else class="btn btn-outline-success me-2" @click="addSpare()">
                                                    <i class="ri-add-circle-line icon-hgt"></i> Add
                                                </button>

                                        <!-- <button class="btn border-left btn-outline-success" type="button" @click="addSpare()"><i class="ri-add-circle-line icon-hgt"></i>ADD</button> -->
                                    </div>

                                    <div class="col-12">
                                        <div class="table-responsive table-responsive-sm">
                                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                                <thead>
                                                    <tr class="">
                                                        <th class="text-center">#</th>
                                                        <th @click="sort('asset_zone_id')">
                                                            Asset Zone
                                                            <span>
                                                                <i v-if="meta.keyword=='asset_zone_id' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="meta.keyword=='asset_zone_id' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th @click="sort('spare_type_id')">
                                                            Spare Type
                                                            <span>
                                                                <i v-if="meta.keyword=='spare_type_id' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="meta.keyword=='spare_type_id' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th @click="sort('spare_code')">
                                                            Spare Code
                                                            <span>
                                                                <i v-if="meta.keyword=='spare_code' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="meta.keyword=='spare_code' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th @click="sort('spare_name')">
                                                            Spare Name

                                                            <span>
                                                                <i v-if="meta.keyword=='spare_name' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="meta.keyword=='spare_name' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th class="text-center" v-can="'assetSpares.delete'">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-if="asset_spares.length==0">
                                                        <td colspan="6" class="text-center">No records found</td>
                                                    </tr>
                                                    <tr v-for="spare, key in asset_spares" :key="key">
                                                        <td class="text-center">{{ meta.from + key }}</td>
                                                        <td>{{ spare.asset_zone?.zone_name }}</td>
                                                        <td>{{spare.spare?.spare_type?.spare_type_name}}</td>
                                                        <td>{{spare.spare?.spare_code}}</td>
                                                        <td>{{spare.spare?.spare_name}}</td>
                                                        <td class="text-center" v-can="'assetSpares.delete'">
                                                            <a href="javascript:void(0)" class="text-success me-2" @click="editSpare(spare)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
                                                            <a href="javascript:void(0)" class="text-danger me-2" @click="deleteSpare(spare)"><i class="ri-delete-bin-6-line fs-18 lh-1"></i></a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <select class="form-select from-select-sm width-75" v-model="meta.per_page" @change="onPerPageChange">
                                        <option>5</option>
                                        <option>10</option>
                                        <option>15</option>
                                        <option>20</option>
                                        <option>25</option>
                                        <option>30</option>
                                    </select>
                                    <span>Showing {{ meta.from }} to {{ meta.to }} of {{ meta.totalRows }} entries</span>
                                    <Pagination :maxPage="meta.maxPage" :totalPages="meta.lastPage" :currentPage="meta.page" @pagechanged="onPageChange" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mb-2" v-can="'assetChecks.view'">
                        <div class="card card-one">
                            <div class="card-header d-flex justify-content-between">
                                <h6 class="card-title">Checks</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-4" v-can="'assetChecks.create'">
                                         <!-- <label>Asset Zone</label> -->
                                        <!-- <div class="dropdown" @click="toggleAssetZoneStatus()">
                                            <div class="overselect"></div>
                                            <select class="form-control form-control" :class="{'is-invalid':errors.asset_zones}">
                                                <option value="">Select Asset Zone</option>
                                            </select>
                                            <span v-if="errors.asset_zones" class="invalid-feedback">{{ errors.asset_zones[0] }}</span>
                                        </div>
                                        <div class="multiselect" v-if="asset_zone_status_spares">
                                            <ul>
                                                <li class="" v-for="(asset_zone, index) in asset_zones" :key="index">
                                                    <input type="checkbox" :value="asset_zone.asset_zone_id" v-model="check.asset_zone_id" style="padding: 2px;" />
                                                    <label style="margin-left: 5px;">{{ asset_zone.zone_name }}</label>
                                                </li>
                                            </ul>
                                        </div> -->
                                        <div class="dropdown" @click="toggleAssetZoneStatus('checks')">
                                            <div class="overselect"></div>
                                                <select class="form-control form-control" :class="{'is-invalid':errors.asset_zones}">
                                                    <option value="">Select Asset Zone</option>
                                                </select>
                                                <span v-if="errors.asset_zones" class="invalid-feedback">{{ errors.asset_zones[0] }}</span>
                                            </div>
                                            <div class="multiselect" v-if="asset_zone_status_checks">
                                                <ul>
                                                    <li v-for="(asset_zone, index) in asset_zones" :key="index">
                                                        <input type="checkbox" :value="asset_zone.asset_zone_id" v-model="check.asset_zones" @click="updateCheckAssetZone($event, check)" style="padding: 2px;" />
                                                        <label style="margin-left: 5px;">{{ asset_zone.zone_name }}</label>
                                                    </li>
                                                </ul>
                                            </div>
                                    </div>
                                    <div class="col-md-8" v-can="'assetChecks.create'">
                                        <!-- <label>Check</label> -->
                                        <!-- <div class="d-flex justify-content-between" v-can="'assetChecks.create'"> -->

                                        <search
                                            :class="{ 'is-invalid': errors.check_id }"
                                            :customClass="{ 'is-invalid': errors.check_id }"
                                            aria-describedby="basic-addon2"
                                            aria-label="Select Check"
                                            class="my-auto"
                                            :initialize="check.check_id"
                                            id="check_id"
                                            label="field_name"
                                            placeholder="Select Check"
                                            :data="checks"
                                            @input=" check1 => check.check_id = check1"
                                        >
                                        </search>
                                        <span v-if="errors.check_id" class="invalid-feedback">{{ errors.check_id[0] }}</span>
                                    </div>
                                    <div class="col-md-12" v-can="'assetChecks.create'">
                                        <div class="row align-items-center g-2">
                                            <div class="col-md-3">
                                                <!-- <label>Lcl</label> -->
                                                <input type="text" class="form-control" placeholder="Lcl" v-model="check.lcl">
                                            </div>
                                            <div class="col-md-3">
                                                <!-- <label>Ucl</label> -->
                                                <input type="text" class="form-control" placeholder="Ucl" v-model="check.ucl">
                                            </div>
                                            <div class="col-md-3">
                                                <!-- <label>Default Value</label> -->
                                                <input type="text" class="form-control" placeholder="Default Value" v-model="check.default_value">
                                            </div>
                                            <div class="col-md-3 d-flex justify-content-end">
                                                <button v-if="check.asset_check_id" class="btn btn-outline-success me-2" @click="updateCheck()">
                                                    <i class="ri-add-circle-line icon-hgt"></i> Update
                                                </button>
                                                <button v-else class="btn btn-outline-success me-2" @click="addCheck()">
                                                    <i class="ri-add-circle-line icon-hgt"></i> Add
                                                </button>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-12">
                                        <div class="table-responsive table-responsive-sm">
                                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" width="2%">#</th>
                                                        <th @click="sortCheck('asset_zone_id')">
                                                            Asset Zone
                                                            <span>
                                                                <i v-if="check_meta.keyword=='asset_zone_id' && check_meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="check_meta.keyword=='asset_zone_id' && check_meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th @click="sortCheck('check_id')" width="40%" style="white-space: normal;">
                                                            Field Name
                                                            <span>
                                                                <i v-if="check_meta.keyword=='field_name' && check_meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="check_meta.keyword=='field_name' && check_meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th @click="sortCheck('check_id')" width="10%">
                                                            Field Type
                                                            <span>
                                                                <i v-if="check_meta.keyword=='field_type' && check_meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="check_meta.keyword=='field_type' && check_meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                       
                                                        <th @click="sortCheck('lcl')" width="8%">
                                                           Lcl
                                                            <span>
                                                                <i v-if="check_meta.keyword=='lcl' && check_meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="check_meta.keyword=='lcl' && check_meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th @click="sortCheck('ucl')" width="8%">
                                                           Ucl
                                                            <span>
                                                                <i v-if="check_meta.keyword=='ucl' && check_meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="check_meta.keyword=='ucl' && check_meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th @click="sortCheck('default_value')" width="8%" style="white-space: normal;">
                                                            Default Value
                                                            <span>
                                                                <i v-if="check_meta.keyword=='default_value' && check_meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="check_meta.keyword=='default_value' && check_meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>

                                                        <th class="text-center" v-can="'assetChecks.delete'">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-if="asset_checks.length==0">
                                                        <td colspan="6" class="text-center">No records found</td>
                                                    </tr>
                                                    <tr v-for="check, key in asset_checks" :key="key">
                                                        <td class="text-center">{{ check_meta.from + key }}</td>
                                                        <td>{{ check?.asset_zone?.zone_name }}</td>
                                                        <td style="white-space: normal;">{{check?.check?.field_name}}</td>
                                                        <td>{{ check?.check?.field_type }}</td>
                                                        <td>{{ check.lcl }}</td>
                                                        <td>{{ check.ucl }}</td>
                                                        <td  style="white-space: normal;">{{ check.default_value }}</td>
                                                        <td class="text-center" v-can="'assetChecks.delete'">
                                                            <a href="javascript:void(0)" class="text-success me-2" @click="editCheck(check)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
                                                            <a href="javascript:void(0)" class="text-danger me-2" @click="deleteCheck(check)"><i class="ri-delete-bin-6-line fs-18 lh-1"></i></a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <select class="form-select from-select-sm width-75" v-model="check_meta.per_page" @change="onPerPageChangeCheck">
                                        <option>5</option>
                                        <option>10</option>
                                        <option>15</option>
                                        <option>20</option>
                                        <option>25</option>
                                        <option>30</option>
                                    </select>
                                    <span>Showing {{ check_meta.from }} to {{ check_meta.to }} of {{ check_meta.totalRows }} entries</span>
                                    <Pagination :maxPage="check_meta.maxPage" :totalPages="check_meta.lastPage" :currentPage="check_meta.page" @pagechanged="onPageChangeCheck" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mb-2">
                        <div class="card card-one">
                            <div class="card-header d-flex justify-content-between">
                                <h6 class="card-title">Services</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-4" v-can="'assetChecks.create'">
                                        <div class="dropdown" @click="toggleAssetZoneStatus('services')">
                                            <div class="overselect"></div>
                                                <select class="form-control form-control" :class="{'is-invalid':errors.asset_zones}">
                                                    <option value="">Select Asset Zone</option>
                                                </select>
                                                <span v-if="errors.asset_zones" class="invalid-feedback">{{ errors.asset_zones[0] }}</span>
                                            </div>
                                            <div class="multiselect" v-if="asset_zone_status_services">
                                                <ul>
                                                    <li v-for="(asset_zone, index) in asset_zones" :key="index">
                                                        <input type="checkbox" :value="asset_zone.asset_zone_id" v-model="service.asset_zones" style="padding: 2px;" @click="updateServiceAssetZone($event, service)" />
                                                        <label style="margin-left: 5px;">{{ asset_zone.zone_name }}</label>
                                                    </li>
                                                </ul>
                                            </div>
                                    </div>
                                    <div class="col-md-5">
                                        <search
                                            :class="{ 'is-invalid': errors.service_id }"
                                            :customClass="{ 'is-invalid': errors.service_id }"
                                            aria-describedby="basic-addon2"
                                            aria-label="Select Check"
                                            class="my-auto"
                                            :initialize="service.service_id"
                                            id="service_id"
                                            label="service_name"
                                            placeholder="Select Service"
                                            :data="services"
                                            @input=" service1 => service.service_id = service1"
                                        >
                                        </search>
                                        <span v-if="errors.service_id" class="invalid-feedback">{{ errors.service_id[0] }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <!-- <div style="float: left;">
                                            <button class="btn btn-outline-success me-2" @click="addService()"><i class="ri-add-circle-line icon-hgt"></i> Add</button>
                                        </div> -->

                                        <button v-if="service.asset_service_id" class="btn btn-outline-success me-2" @click="updateService()">
                                                    <i class="ri-add-circle-line icon-hgt"></i> Update
                                                </button>
                                                <button v-else class="btn btn-outline-success me-2" @click="addService()">
                                                    <i class="ri-add-circle-line icon-hgt"></i> Add
                                                </button>

                                    </div>

                                    <div class="col-12">
                                        <div class="table-responsive table-responsive-sm">
                                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                                <thead>
                                                    <tr class="">
                                                        <th class="text-center">#</th>
                                                        <th>
                                                            Asset Zone
                                                            <span>
                                                                <i v-if="check_meta.keyword=='asset_zone_id' && check_meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="check_meta.keyword=='asset_zone_id' && check_meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th>
                                                            Service Type
                                                            <span>
                                                                <i v-if="check_meta.keyword=='service_type_id' && check_meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="check_meta.keyword=='service_type_id' && check_meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th>
                                                            Service Name
                                                            <span>
                                                                <i v-if="check_meta.keyword=='service_name' && check_meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="check_meta.keyword=='service_name' && check_meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th>
                                                            Service Code
                                                            <span>
                                                                <i v-if="check_meta.keyword=='service_code' && check_meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="check_meta.keyword=='service_code' && check_meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th class="text-center" v-can="'assetChecks.delete'">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-if="asset_services.length==0">
                                                        <td colspan="6" class="text-center">No records found</td>
                                                    </tr>
                                                    <tr v-for="service, key in asset_services" :key="key">
                                                        <td class="text-center">{{ check_meta_service.from + key }}</td>
                                                        <td>{{ service?.asset_zone?.zone_name }}</td>
                                                        <td>{{ service?.service?.service_type?.service_type_name }}</td>
                                                        <td>{{ service?.service?.service_name }}</td>
                                                        <td>{{ service?.service?.service_code }}</td>
                                                        <td class="text-center">
                                                            <a href="javascript:void(0)" class="text-success me-2" @click="editService(service)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
                                                            <a href="javascript:void(0)" class="text-danger me-2" @click="deleteService(service)"><i class="ri-delete-bin-6-line fs-18 lh-1"></i></a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <select class="form-select from-select-sm width-75" v-model="check_meta_service.per_page" @change="onPerPageChangeCheck">
                                        <option>5</option>
                                        <option>10</option>
                                        <option>15</option>
                                        <option>20</option>
                                        <option>25</option>
                                        <option>30</option>
                                    </select>
                                    <span>Showing {{ check_meta_service.from }} to {{ check_meta_service.to }} of {{ check_meta_service.totalRows }} entries</span>
                                    <Pagination :maxPage="check_meta_service.maxPage" :totalPages="check_meta_service.lastPage" :currentPage="check_meta_service.page" @pagechanged="onPageChangeCheck" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mb-2">
                        <div class="card card-one">
                            <div class="card-header d-flex justify-content-between">
                                <h6 class="card-title">Variables</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-5">
                                        <div class="dropdown" @click="toggleAssetZoneStatus('variables')">
                                            <div class="overselect"></div>
                                                <select class="form-control form-control" :class="{'is-invalid':errors.asset_zones}">
                                                    <option value="">Select Asset Zone</option>
                                                </select>
                                                <span v-if="errors.asset_zones" class="invalid-feedback">{{ errors.asset_zones[0] }}</span>
                                            </div>
                                        <div class="multiselect" v-if="asset_zone_status_variables">
                                            <ul>
                                                <li v-for="(asset_zone, index) in asset_zones" :key="index">
                                                    <input type="checkbox" :value="asset_zone.asset_zone_id" v-model="variable.asset_zones" style="padding: 2px;" @click="updateVariableAssetZone($event, variable)" />
                                                    <label style="margin-left: 5px;">{{ asset_zone.zone_name }}</label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <!-- <div class="d-flex justify-content-between" v-can="'assetSpares.create'"> -->
                                        <search
                                            :class="{ 'is-invalid': errors.variable_id }"
                                            :customClass="{ 'is-invalid': errors.variable_id }"
                                            aria-describedby="basic-addon2"
                                            aria-label="Select Variable"
                                            class="my-auto"
                                            :initialize="variable.variable_id"
                                            id="variable_id"
                                            label="variable_name"
                                            label2="variable_code"
                                            placeholder="Select Variable"
                                            :data="variables"
                                            @input=" variable1 => variable.variable_id = variable1"
                                        >
                                        </search>
                                        <span v-if="errors.variable_id" class="invalid-feedback">{{ errors.variable_id[0] }}</span>
                                    </div>
                                    <div class="col-md-2">
                                        <!-- <div style="float: left;">
                                            <button class="btn btn-outline-success me-2" @click="addVariable()"><i class="ri-add-circle-line icon-hgt"></i> Add</button>
                                        </div> -->



                                        <button v-if="variable.asset_variable_id" class="btn btn-outline-success me-2" @click="updateVariable()">
                                                    <i class="ri-add-circle-line icon-hgt"></i> Update
                                                </button>
                                                <button v-else class="btn btn-outline-success me-2" @click="addVariable()">
                                                    <i class="ri-add-circle-line icon-hgt"></i> Add
                                                </button>
                                        <!-- <button class="btn border-left btn-outline-success" type="button" @click="addSpare()"><i class="ri-add-circle-line icon-hgt"></i>ADD</button> -->
                                    </div>

                                    <div class="col-12">
                                        <div class="table-responsive table-responsive-sm">
                                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                                <thead>
                                                    <tr class="">
                                                        <th class="text-center">#</th>
                                                        <th>
                                                            Asset Zone
                                                            <span>
                                                                <i v-if="variable_meta_service.keyword=='asset_zone_id' && variable_meta_service.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="variable_meta_service.keyword=='asset_zone_id' && variable_meta_service.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th>
                                                            Variable Type
                                                            <span>
                                                                <i v-if="variable_meta_service.keyword=='variable_type_id' && variable_meta_service.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="variable_meta_service.keyword=='variable_type_id' && variable_meta_service.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th>
                                                            Variable Code
                                                            <span>
                                                                <i v-if="variable_meta_service.keyword=='variable_name' && variable_meta_service.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="variable_meta_service.keyword=='variable_name' && variable_meta_service.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th>
                                                            Variable Name
                                                            <span>
                                                                <i v-if="variable_meta_service.keyword=='variable_name' && variable_meta_service.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="variable_meta_service.keyword=='variable_name' && variable_meta_service.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th class="text-center">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-if="asset_variables.length==0">
                                                        <td colspan="6" class="text-center">No records found</td>
                                                    </tr>
                                                    <tr v-for="variable, key in asset_variables" :key="key">
                                                        <td class="text-center">{{ variable_meta_service.from + key }}</td>
                                                        <td>{{ variable.asset_zone?.zone_name }}</td>
                                                        <td>{{variable.variable?.variable_type?.variable_type_name}}</td>
                                                        <td>{{variable.variable?.variable_code}}</td>
                                                        <td>{{variable.variable?.variable_name}}</td>
                                                        <td class="text-center">
                                                            <a href="javascript:void(0)" class="text-success me-2" @click="editVariable(variable)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
                                                            <a href="javascript:void(0)" class="text-danger me-2" @click="deleteVariable(variable)"><i class="ri-delete-bin-6-line fs-18 lh-1"></i></a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <select class="form-select from-select-sm width-75" v-model="variable_meta_service.per_page" @change="onPerPageChange">
                                        <option>5</option>
                                        <option>10</option>
                                        <option>15</option>
                                        <option>20</option>
                                        <option>25</option>
                                        <option>30</option>
                                    </select>
                                    <span>Showing {{ variable_meta_service.from }} to {{ variable_meta_service.to }} of {{ variable_meta_service.totalRows }} entries</span>
                                    <Pagination :maxPage="variable_meta_service.maxPage" :totalPages="variable_meta_service.lastPage" :currentPage="variable_meta_service.page" @pagechanged="onPageChange" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mb-2">
                        <div class="card card-one">
                            <div class="card-header d-flex justify-content-between">
                                <h6 class="card-title">Data Sources</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-5">
                                        <!-- <div class="dropdown" @click="toggleAssetZoneStatus()">
                                            <div class="overselect"></div>
                                            <select class="form-control form-control" :class="{'is-invalid':errors.asset_zones}">
                                                <option value="">Select Asset Zone</option>
                                            </select>
                                            <span v-if="errors.asset_zones" class="invalid-feedback">{{ errors.asset_zones[0] }}</span>
                                        </div>
                                        <div class="multiselect" v-if="asset_zone_status">
                                            <ul>
                                                <li class="" v-for="(asset_zone, index) in asset_zones" :key="index">
                                                    <input type="checkbox" :value="asset_zone.asset_zone_id" v-model="datasource.asset_zone_id" style="padding: 2px;" />
                                                    <label style="margin-left: 5px;">{{ asset_zone.zone_name }}</label>
                                                </li>
                                            </ul>
                                        </div> -->
                                        <div class="dropdown" @click="toggleAssetZoneStatus('datasources')">
                                            <div class="overselect"></div>
                                                <select class="form-control form-control" :class="{'is-invalid':errors.asset_zones}">
                                                    <option value="">Select Asset Zone</option>
                                                </select>
                                                <span v-if="errors.asset_zones" class="invalid-feedback">{{ errors.asset_zones[0] }}</span>
                                            </div>
                                            <div class="multiselect" v-if="asset_zone_status_datasources">
                                                <ul>
                                                    <li v-for="(asset_zone, index) in asset_zones" :key="index">
                                                        <input type="checkbox" :value="asset_zone.asset_zone_id" v-model="datasource.asset_zones" style="padding: 2px;"  @click="updateDataSourceAssetZone($event, datasource)"/>
                                                        <label style="margin-left: 5px;">{{ asset_zone.zone_name }}</label>
                                                    </li>
                                                </ul>
                                            </div>
                                    </div>
                                    <div class="col-md-5">
                                        <!-- <div class="d-flex justify-content-between" v-can="'assetSpares.create'"> -->
                                        <search
                                            :class="{ 'is-invalid': errors.variable_id }"
                                            :customClass="{ 'is-invalid': errors.data_source_id }"
                                            aria-describedby="basic-addon2"
                                            aria-label="Select Data Source"
                                            class="my-auto"
                                            :initialize="datasource.data_source_id"
                                            id="data_source_id"
                                            label="data_source_name"
                                            label2="data_source_code"
                                            placeholder="Select Data Source"
                                            :data="data_sources"
                                            @input=" datasource1 => datasource.data_source_id = datasource1"
                                        >
                                        </search>
                                        <span v-if="errors.data_source_id" class="invalid-feedback">{{ errors.data_source_id[0] }}</span>
                                    </div>
                                    <div class="col-md-2">
                                        <!-- <div style="float: left;">
                                            <button class="btn btn-outline-success me-2" @click="addDataSource()"><i class="ri-add-circle-line icon-hgt"></i> Add</button>
                                        </div> -->


                                        <button v-if="datasource.asset_data_source_id" class="btn btn-outline-success me-2" @click="updateDataSource()">
                                            <i class="ri-add-circle-line icon-hgt"></i> Update
                                        </button>
                                        <button v-else class="btn btn-outline-success me-2" @click="addDataSource()">
                                            <i class="ri-add-circle-line icon-hgt"></i> Add
                                        </button>
                                        <!-- <button class="btn border-left btn-outline-success" type="button" @click="addSpare()"><i class="ri-add-circle-line icon-hgt"></i>ADD</button> -->
                                    </div>

                                    <div class="col-12">
                                        <div class="table-responsive table-responsive-sm">
                                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                                <thead>
                                                    <tr class="">
                                                        <th class="text-center">#</th>
                                                        <th>
                                                            Asset Zone
                                                            <span>
                                                                <i v-if="datasource_meta_service.keyword=='asset_zone_id' && datasource_meta_service.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="datasource_meta_service.keyword=='asset_zone_id' && datasource_meta_service.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th>
                                                            Data Source Type
                                                            <span>
                                                                <i v-if="datasource_meta_service.keyword=='data_source_type_id' && datasource_meta_service.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="datasource_meta_service.keyword=='data_source_type_id' && datasource_meta_service.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th>
                                                            Data Source Code
                                                            <span>
                                                                <i v-if="datasource_meta_service.keyword=='data_source_code' && datasource_meta_service.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="datasource_meta_service.keyword=='data_source_code' && datasource_meta_service.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th>
                                                            Data Source Name
                                                            <span>
                                                                <i v-if="datasource_meta_service.keyword=='data_source_name' && datasource_meta_service.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="datasource_meta_service.keyword=='data_source_name' && datasource_meta_service.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th class="text-center">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-if="asset_data_sources.length==0">
                                                        <td colspan="6" class="text-center">No records found</td>
                                                    </tr>
                                                    <tr v-for="data_source, key in asset_data_sources" :key="key">
                                                        <td class="text-center">{{ datasource_meta_service.from + key }}</td>
                                                        <td>{{ data_source.asset_zone?.zone_name }}</td>
                                                        <td>{{ data_source.data_source?.data_source_type?.data_source_type_name }}</td>
                                                        <td>{{ data_source?.data_source?.data_source_code }}</td>
                                                        <td>{{ data_source?.data_source?.data_source_name }}</td>
                                                        <td class="text-center">
                                                            <a href="javascript:void(0)" class="text-success me-2" @click="editDataSource(data_source)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
                                                            <a href="javascript:void(0)" class="text-danger me-2" @click="deleteDataSource(data_source)"><i class="ri-delete-bin-6-line fs-18 lh-1"></i></a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <select class="form-select from-select-sm width-75" v-model="datasource_meta_service.per_page" @change="onPerPageChange">
                                        <option>5</option>
                                        <option>10</option>
                                        <option>15</option>
                                        <option>20</option>
                                        <option>25</option>
                                        <option>30</option>
                                    </select>
                                    <span>Showing {{ datasource_meta_service.from }} to {{ datasource_meta_service.to }} of {{ datasource_meta_service.totalRows }} entries</span>
                                    <Pagination :maxPage="datasource_meta_service.maxPage" :totalPages="datasource_meta_service.lastPage" :currentPage="datasource_meta_service.page" @pagechanged="onPageChange" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- accessories start -->
                    <div class="col-12 mb-2">
                        <div class="card card-one">
                            <div class="card-header d-flex justify-content-between">
                                <h6 class="card-title">Accessories</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <!-- <div class="dropdown" @click="toggleAssetZoneStatus()">
                                            <div class="overselect"></div>
                                            <select class="form-control form-control" :class="{'is-invalid':errors.asset_zones}">
                                                <option value="">Select Asset Zone</option>
                                            </select>
                                            <span v-if="errors.asset_zones" class="invalid-feedback">{{ errors.asset_zones[0] }}</span>
                                        </div>
                                        <div class="multiselect" v-if="asset_zone_status">
                                            <ul>
                                                <li class="" v-for="(asset_zone, index) in asset_zones" :key="index">
                                                    <input type="checkbox" :value="asset_zone.asset_zone_id" v-model="datasource.asset_zone_id" style="padding: 2px;" />
                                                    <label style="margin-left: 5px;">{{ asset_zone.zone_name }}</label>
                                                </li>
                                            </ul>
                                        </div> -->
                                        <div class="dropdown" @click="toggleAssetZoneStatus('accessories')">
                                            <div class="overselect"></div>
                                                <select class="form-control form-control" :class="{'is-invalid':errors.asset_zones}">
                                                    <option value="">Select Asset Zone</option>
                                                </select>
                                                <span v-if="errors.asset_zones" class="invalid-feedback">{{ errors.asset_zones[0] }}</span>
                                            </div>
                                            <div class="multiselect" v-if="asset_zone_status_accessories">
                                                <ul>
                                                    <li v-for="(asset_zone, index) in asset_zones" :key="index">
                                                        <input type="checkbox" :value="asset_zone.asset_zone_id" v-model="accessory.asset_zone_id" style="padding: 2px;" />
                                                        <label style="margin-left: 5px;">{{ asset_zone.zone_name }}</label>
                                                    </li>
                                                </ul>
                                            </div>
                                    </div>
                                    <div class="col-md-4">
                                        <!-- <div class="d-flex justify-content-between" v-can="'assetSpares.create'"> -->
                                            <search
                                            :class="{ 'is-invalid': errors.accessory_type_id }"
                                            :customClass="{ 'is-invalid': errors.accessory_type_id }"
                                            aria-describedby="basic-addon2"
                                            aria-label="Select Accessory"
                                            class="my-auto"
                                            :initialize="accessory.accessory_type_id"
                                            id="accessory_type_id"
                                            label="accessory_type_name"
                                            placeholder="Select Accessory"
                                            :data="accessory_types"
                                            @input=" accessory1 => accessory.accessory_type_id = accessory1"
                                        >
                                        </search>
                                        <span v-if="errors.accessory_type_id" class="invalid-feedback">{{ errors.accessory_type_id[0] }}</span>
                                    </div>
                                    <div class="col-md-4">
                                    <input type="text" placeholder="Enter accessory name" class="form-control" :class="{'is-invalid':errors?.accessory_name}" v-model="accessory.accessory_name" />
                                    <span v-if="errors?.accessory_name" class="invalid-feedback">{{ errors.accessory_name[0] }}</span>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="file" class="form-control" id="attachment" ref="attachment" name="attachment" :class="{ 'is-invalid': errors.attachment }" />
                                        <span v-if="errors?.attachment" class="invalid-feedback">{{ errors.attachment[0] }}</span>
                                    </div>
                                    <div class="col-md-2">
                                        <div style="float: left;">
                                            <button class="btn btn-outline-success me-2" @click="addAccessory()"><i class="ri-add-circle-line icon-hgt"></i> Add</button>
                                        </div>
                                        <!-- <button class="btn border-left btn-outline-success" type="button" @click="addSpare()"><i class="ri-add-circle-line icon-hgt"></i>ADD</button> -->
                                    </div>

                                    <div class="col-12">
                                        <div class="table-responsive table-responsive-sm">
                                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                                <thead>
                                                    <tr class="">
                                                        <th class="text-center">#</th>
                                                        <th>
                                                            Asset Zone
                                                            <span>
                                                                <i v-if="accessory_meta_service.keyword=='asset_zone_id' && accessory_meta_service.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="accessory_meta_service.keyword=='asset_zone_id' && accessory_meta_service.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th>
                                                            Accessory Type
                                                            <span>
                                                                <i v-if="accessory_meta_service.keyword=='accessory_type_id' && accessory_meta_service.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="accessory_meta_service.keyword=='accessory_type_id' && accessory_meta_service.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th>
                                                            Accessory Name
                                                            <span>
                                                                <i v-if="accessory_meta_service.keyword=='accessory_name' && accessory_meta_service.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="accessory_meta_service.keyword=='accessory_name' && accessory_meta_service.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th>
                                                            Attachment
                                                            <span>
                                                                <i v-if="accessory_meta_service.keyword=='attachment' && accessory_meta_service.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="accessory_meta_service.keyword=='attachment' && accessory_meta_service.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th class="text-center">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-if="asset_accessories.length==0">
                                                        <td colspan="6" class="text-center">No records found</td>
                                                    </tr>
                                                    <tr v-for="accessory, key in asset_accessories" :key="key">
                                                        <td class="text-center">{{ accessory_meta_service.from + key }}</td>
                                                        <td>{{ accessory.asset_zone?.zone_name }}</td>
                                                        <td>{{ accessory.accessory_type.accessory_type_name }}</td>
                                                        <td>{{ accessory.accessory_name }}</td>
                                                        <td>  <a v-if="accessory.attachment" :href="getAttachmentUrl(accessory.attachment)" target="_blank" rel="noopener noreferrer">
                                                            {{ getAttachmentName(accessory.attachment) }}
                                                            </a>
                                                            <span v-else>No attachment</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="javascript:void(0)" class="text-danger me-2" @click="deleteAccessory(accessory)"><i class="ri-delete-bin-6-line fs-18 lh-1"></i></a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <select class="form-select from-select-sm width-75" v-model="accessory_meta_service.per_page" @change="onPerPageChange">
                                        <option>5</option>
                                        <option>10</option>
                                        <option>15</option>
                                        <option>20</option>
                                        <option>25</option>
                                        <option>30</option>
                                    </select>
                                    <span>Showing {{ accessory_meta_service.from }} to {{ accessory_meta_service.to }} of {{ accessory_meta_service.totalRows }} entries</span>
                                    <Pagination :maxPage="accessory_meta_service.maxPage" :totalPages="accessory_meta_service.lastPage" :currentPage="accessory_meta_service.page" @pagechanged="onPageChange" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- accessories end -->
                </div>
            </div>
            <!-- try ends -->
        </div>
    </div>
</template>
<script>
 import axios from "axios";
    import Search from "@/components/Search.vue";
    import Pagination from "@/components/Pagination.vue";
    export default {
        name: "Assets.View",
        components: { Search, Pagination },
        data() {
            return {
                meta: {
                    search: "",
                    order_by: "asc",
                    keyword: "spare_id",
                    per_page: 5,
                    totalRows: 0,
                    page: 1,
                    lastPage: 1,
                    from: 1,
                    to: 1,
                    maxPage: 1,
                    trashed: false,
                    asset_id: "",
                },
                check_meta: {
                    search: "",
                    order_by: "asc",
                    keyword: "check_id",
                    per_page: 5,
                    totalRows: 0,
                    page: 1,
                    lastPage: 1,
                    from: 1,
                    to: 1,
                    maxPage: 1,
                    trashed: false,
                    asset_id: "",
                },
                check_meta_service: {
                    search: "",
                    order_by: "asc",
                    keyword: "service_id",
                    per_page: 5,
                    totalRows: 0,
                    page: 1,
                    lastPage: 1,
                    from: 1,
                    to: 1,
                    maxPage: 1,
                    trashed: false,
                    asset_id: "",
                },
                variable_meta_service: {
                    search: "",
                    order_by: "asc",
                    keyword: "variable_id",
                    per_page: 5,
                    totalRows: 0,
                    page: 1,
                    lastPage: 1,
                    from: 1,
                    to: 1,
                    maxPage: 1,
                    trashed: false,
                    asset_id: "",
                },
                datasource_meta_service: {
                    search: "",
                    order_by: "asc",
                    keyword: "data_source_id",
                    per_page: 5,
                    totalRows: 0,
                    page: 1,
                    lastPage: 1,
                    from: 1,
                    to: 1,
                    maxPage: 1,
                    trashed: false,
                    asset_id: "",
                },
                accessory_meta_service: {
                    search: "",
                    order_by: "asc",
                    keyword: "asset_accessory_id",
                    per_page: 5,
                    totalRows: 0,
                    page: 1,
                    lastPage: 1,
                    from: 1,
                    to: 1,
                    maxPage: 1,
                    trashed: false,
                    asset_id: "",
                },
                asset: {
                    asset_id: "",
                    plant_id: "",
                    asset_code: "",
                    asset_name: "",
                    asset_type_id: "",
                    section_id: "",
                    serial_no: "",
                    status: "",
                    QR_Code: "",
                },
                spare: {
                    spare_id: "",
                    asset_spare_id:"",
                    spare_code: "",
                    spare_name: "",
                    asset_id: "",
                    asset_zone_id:'',
                    asset_zones:[],
                },
                check: {
                    check_id: "",
                    check_code: "",
                    check_name: "",
                    asset_id: "",
                    lcl:"",
                    ucl:"",
                    default_value:"",
                    asset_check_id:"",
                    asset_zone_id:'',
                    asset_zones:[],
                },
                service: {
                    service_id: "",
                    service_code: "",
                    service_name: "",
                    asset_id: "",
                    asset_zone_id:'',
                    asset_service_id:"",
                    asset_zones:[],
                },
                datasource: {
                    data_source_id: "",
                    data_source_code: "",
                    data_source_name: "",
                    asset_id: "",
                    asset_zone_id:'',
                    asset_zones:[],
                    asset_data_source_id:"",
                },
                variable: {
                    variable_id: "",
                    variable_code: "",
                    variable_name: "",
                    asset_id: "",
                    asset_zone_id:'',
                    asset_zones:[],
                    asset_variable_id:"",
                },
                accessory: {
                    accessory_id: "",
                    asset_id: "",
                    asset_zone_id:[],
                    accessory_name:"",
                    accessory_type_id:"",
                    attachment:"",
                },
               
                device_code: "",
                spares: [],
                asset_spares: [],
                checks: [],
                asset_checks: [],
                asset_services: [],
                asset_variables: [],
                asset_data_sources: [],
                accessory_types:[],
                asset_accessories:[],
                asset_zones:[],
                errors: [],
                status: true,
                asset_zone_status:false,

                asset_zone_status_variables: false,
                asset_zone_status_datasources: false,
                asset_zone_status_spares: false,
                asset_zone_status_checks: false,
                asset_zone_status_services: false,
                asset_zone_status_accessories: false,
            };
        },
        watch:{
            'check.check_id':function(){
                if(typeof this.check.check_id === 'number' && !this.check.asset_check_id)
                    this.getCheck()
            }
        },
        beforeRouteEnter(to, from, next) {
            next((vm) => {
                vm.asset.asset_id = to.params.asset_id;
                let uri = { uri: "getAsset", data: { asset_id: to.params.asset_id } };
                vm.$store
                    .dispatch("post", uri)
                    .then(function (response) {
                        vm.asset = response.data.data
                        vm.getSpares();
                        vm.getQRCode();
                    })
                    .catch(function (error) {
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            });
        },
        mounted() {

        },
        methods: {
            getAttachmentUrl(attachment) {
            return `${attachment}`;
            },
            getAttachmentName(attachment) {
            // Extract the file name from the attachment path
            return attachment.split('/').pop();
            },
            updateCheckAssetZone(event, check) {
                if(check.asset_check_id){
                    this.check.asset_zones = []
                    this.check.asset_zones = [parseInt(event.target.value, 10)]
                    this.check.asset_zone_id = event.target.value
                }
            },
            updateSpareAssetZone(event, spare) {
                if(spare.asset_spare_id){
                    this.spare.asset_zones = []
                    this.spare.asset_zones = [parseInt(event.target.value, 10)]
                    this.spare.asset_zone_id = event.target.value
                }
            },
            updateServiceAssetZone(event, service) {
                if(service.asset_service_id){
                    this.service.asset_zones = []
                    this.service.asset_zones = [parseInt(event.target.value, 10)]
                    this.service.asset_zone_id = event.target.value
                }
            },
            updateVariableAssetZone(event, variable) {
                if(variable.asset_variable_id){
                    this.variable.asset_zones = []
                    this.variable.asset_zones = [parseInt(event.target.value, 10)]
                    this.variable.asset_zone_id = event.target.value
                }
            },
            updateDataSourceAssetZone(event, datasource) {
                if(datasource.asset_data_source_id){
                    this.datasource.asset_zones = []
                    this.datasource.asset_zones = [parseInt(event.target.value, 10)]
                    this.datasource.asset_zone_id = event.target.value
                }
            },
            toggleAssetZoneStatus(type) 
            {
                this.asset_zone_status_variables = false;
                this.asset_zone_status_datasources = false;
                this.asset_zone_status_spares = false;
                this.asset_zone_status_checks = false;
                this.asset_zone_status_services = false;
                this.asset_zone_status_accessories=false;

                if (type === 'variables') {
                    this.asset_zone_status_variables = !this.asset_zone_status_variables;
                } else if (type === 'datasources') {
                    this.asset_zone_status_datasources = !this.asset_zone_status_datasources;
                } else if (type === 'spares') {
                    console.log("type", type, this.asset_zone_status_spares)
                    this.asset_zone_status_spares = !this.asset_zone_status_spares;
                } else if (type === 'checks') {
                    this.asset_zone_status_checks = !this.asset_zone_status_checks;
                } else if (type === 'services') {
                    this.asset_zone_status_services = !this.asset_zone_status_services;
                }else if (type === 'accessories') {
                    this.asset_zone_status_accessories = !this.asset_zone_status_accessories;
                }
            },
            getColor(asset_parameter){
                let color = 'color:black'
                if(asset_parameter.field_name = 'Color'){
                    color = 'background-color:'+asset_parameter.asset_parameter_value?.field_value +'; color:white; padding:5px'
                }  
                return color

            },
            editSpare(spare){
                console.log("spare",spare)
                this.spare.spare_id = spare.spare_id
                this.spare.asset_id = spare.asset_id
                this.spare.asset_spare_id = spare.asset_spare_id
                this.spare.spare_code = spare.spare_code
                this.spare.spare_name = spare.spare_name
                this.spare.asset_zones = []
                console.log("asset_zone_id111---",spare.asset_zone_id)
                this.spare.asset_zones.push(spare.asset_zone_id)
                console.log("asset_zone_id---",this.spare.asset_zone_id)
                this.spare.asset_zone_id = spare.asset_zone_id
            },
            editCheck(check){
                this.check.asset_id = check.asset_id
                this.check.asset_check_id = check.asset_check_id
                this.check.check_id = check.check_id
                this.check.lcl = check.lcl
                this.check.ucl = check.ucl
                this.check.default_value = check.default_value
                this.check.asset_zones = []
                this.check.asset_zones.push(check.asset_zone_id)
                this.check.asset_zone_id = check.asset_zone_id
            },
            editService(service){
                this.service.service_id = service.service_id
                this.service.asset_id = service.asset_id
                this.service.asset_service_id = service.asset_service_id
                this.service.service_code = service.service_code
                this.service.service_name = service.service_name
                this.service.asset_zones = []
                this.service.asset_zones.push(service.asset_zone_id)
                this.service.asset_zone_id = service.asset_zone_id
            },
            editVariable(variable){
                this.variable.variable_id = variable.variable_id
                this.variable.asset_id = variable.asset_id
                this.variable.asset_variable_id = variable.asset_variable_id
                this.variable.variable_code = variable.variable_code
                this.variable.variable_name = variable.variable_name
                this.variable.asset_zones = []
                this.variable.asset_zones.push(variable.asset_zone_id)
                this.variable.asset_zone_id = variable.asset_zone_id
            },
            editDataSource(datasource){
                this.datasource.data_source_id = datasource.data_source_id
                this.datasource.asset_id = datasource.asset_id
                this.datasource.asset_data_source_id = datasource.asset_data_source_id
                this.datasource.data_source_code = datasource.data_source_code
                this.datasource.data_source_name = datasource.data_source_name
                this.datasource.asset_zones = []
                this.datasource.asset_zones.push(datasource.asset_zone_id)
                this.datasource.asset_zone_id = datasource.asset_zone_id
            },
            getSpares() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getAssetTypeSpares" ,data: vm.asset })
                    .then((response) => {
                        loader.hide();
                        vm.spares = response.data.data;
                        vm.getAssetSpares();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getChecks() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getAssetTypeChecks" ,data: vm.asset})
                    .then((response) => {
                        loader.hide();
                        vm.checks = response.data.data;
                        vm.getAssetChecks();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            getVariables() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getAssetTypeVariables" ,data: vm.asset })
                    .then((response) => {
                        loader.hide();
                        vm.variables = response.data.data;
                        vm.getAssetVariables();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getDataSources() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getAssetTypeDataSources" ,data: vm.asset })
                    .then((response) => {
                        loader.hide();
                        vm.data_sources = response.data.data;
                        vm.getAssetDataSources();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getCheck() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getCheck" ,data: vm.check})
                    .then((response) => {
                        loader.hide();
                        // vm.check = response.data.data;
                        vm.check.lcl = response.data.data.lcl;
                        vm.check.ucl = response.data.data.uclcl;
                        vm.check.default_value = response.data.data.default_value;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getServices() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getAssetTypeServices" ,data: vm.asset })
                    .then((response) => {
                        loader.hide();
                        vm.services = response.data.data;
                        vm.getAssetServices();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getAssetSpares() {
                let vm = this;
                vm.meta.asset_id = vm.asset.asset_id;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "paginateAssetSpares", data: vm.meta })
                    .then((response) => {
                        loader.hide();
                        vm.asset_spares = response.data.data;
                        vm.meta.totalRows = response.data.meta.total;
                        vm.meta.from = response.data.meta.from;
                        vm.meta.lastPage = response.data.meta.last_page;
                        vm.meta.maxPage = vm.meta.lastPage >= 3 ? 3 : vm.meta.lastPage;
                        vm.getChecks();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getAssetChecks() {
                let vm = this;
                vm.check_meta.asset_id = vm.asset.asset_id;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "paginateAssetChecks", data: vm.check_meta })
                    .then((response) => {
                        loader.hide();
                        vm.asset_checks = response.data.data;
                        vm.check_meta.totalRows = response.data.meta.total;
                        vm.check_meta.from = response.data.meta.from;
                        vm.check_meta.lastPage = response.data.meta.last_page;
                        vm.check_meta.maxPage = vm.check_meta.lastPage >= 3 ? 3 : vm.check_meta.lastPage;
                        vm.getServices();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getAssetServices() {
                let vm = this;
                vm.check_meta_service.asset_id = vm.asset.asset_id;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "paginateAssetServices", data: vm.check_meta_service })
                    .then((response) => {
                        loader.hide();
                        vm.asset_services = response.data.data;
                        vm.check_meta_service.totalRows = response.data.meta.total;
                        vm.check_meta_service.from = response.data.meta.from;
                        vm.check_meta_service.lastPage = response.data.meta.last_page;
                        vm.check_meta_service.maxPage = vm.check_meta_service.lastPage >= 3 ? 3 : vm.check_meta_service.lastPage;
                        vm.getVariables();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getAssetVariables() {
                let vm = this;
                vm.variable_meta_service.asset_id = vm.asset.asset_id;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "paginateAssetVariables", data: vm.variable_meta_service })
                    .then((response) => {
                        loader.hide();
                        vm.asset_variables = response.data.data;
                        vm.variable_meta_service.totalRows = response.data.meta.total;
                        vm.variable_meta_service.from = response.data.meta.from;
                        vm.variable_meta_service.lastPage = response.data.meta.last_page;
                        vm.variable_meta_service.maxPage = vm.variable_meta_service.lastPage >= 3 ? 3 : vm.variable_meta_service.lastPage;
                        vm.getDataSources();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getAssetDataSources() {
                let vm = this;
                vm.datasource_meta_service.asset_id = vm.asset.asset_id;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "paginateAssetDataSources", data: vm.datasource_meta_service })
                    .then((response) => {
                        loader.hide();
                        vm.asset_data_sources = response.data.data;
                        vm.datasource_meta_service.totalRows = response.data.meta.total;
                        vm.datasource_meta_service.from = response.data.meta.from;
                        vm.datasource_meta_service.lastPage = response.data.meta.last_page;
                        vm.datasource_meta_service.maxPage = vm.datasource_meta_service.lastPage >= 3 ? 3 : vm.meta.lastPage;
                        vm.getAccessoryTypes();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
           
            getQRCode() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getAssetQRCode", data: vm.asset })
                    .then((response) => {
                        loader.hide();
                        vm.asset.QR_Code = response.data.QRCode;
                        vm.getAssetZones();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getAssetZones() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getAssetZones", data: vm.asset })
                    .then((response) => {
                        loader.hide();
                        console.log("SS", response.data)
                        vm.asset_zones = response.data.data;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            downloadQR() {
                let vm = this;
                window.open(vm.$store.state.apiUrl + "downloadAssetQRCode?asset_code=" + vm.asset.asset_code);
            },
            addSpare() {
                let vm = this;
                vm.spare.asset_id = vm.asset.asset_id;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "addAssetSpare", data: vm.spare })
                    .then((response) => {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.spare.spare_id = "";
                        vm.errors = [];
                        vm.spare.asset_zone_id=[];
                        vm.getAssetSpares();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            updateSpare(){
                let vm = this
                let loader = vm.$loading.show();
                let uri = { uri: "updateAssetSpare", data: vm.spare };
                vm.$store
                    .dispatch("post", uri)
                    .then(function (response) {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.spare.spare_id = "";
                        vm.spare.asset_zone_id= "";
                        vm.spare.asset_spare_id="";
                        vm.spare.asset_zones = [];
                        vm.getAssetSpares();
                    })
                    .catch(function (error) {
                        loader.hide();
                        console.log("error",error)
                        vm.errors = error.response?.data?.errors;
                        vm.$store.dispatch("error", error?.response?.data?.message);
                    });
            },
            addCheck() {
                let vm = this;
                vm.check.asset_id = vm.asset.asset_id;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "addAssetCheck", data: vm.check })
                    .then((response) => {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.check.check_id = "";
                        vm.check.lcl = "";
                        vm.check.ucl = "";
                        vm.check.default_value = "";
                        vm.errors = [];
                        vm.check.asset_zone_id=[];
                        vm.getAssetChecks();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            addService() {
                let vm = this;
                vm.service.asset_id = vm.asset.asset_id;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "addAssetService", data: vm.service })
                    .then((response) => {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.service.service_id = "";
                        vm.errors = [];
                        vm.service.asset_zone_id='';
                        vm.service.asset_zones=[];
                        vm.getAssetServices();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            updateService(){
                let vm = this
                let loader = vm.$loading.show();
                let uri = { uri: "updateAssetService", data: vm.service };
                vm.$store
                    .dispatch("post", uri)
                    .then(function (response) {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.service.service_id = "";
                        vm.service.asset_zone_id= "";
                        vm.service.asset_service_id = "";
                        vm.service.asset_zones = [];
                        vm.getAssetServices();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            addVariable() {
                let vm = this;
                vm.variable.asset_id = vm.asset.asset_id;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "addAssetVariable", data: vm.variable })
                    .then((response) => {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.variable.variable_id = "";
                        vm.errors = [];
                        vm.variable.asset_zone_id='';
                        vm.variable.asset_zones=[];
                        vm.getAssetVariables();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            updateVariable(){
                let vm = this
                let loader = vm.$loading.show();
                let uri = { uri: "updateAssetVariable", data: vm.variable };
                vm.$store
                    .dispatch("post", uri)
                    .then(function (response) {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.variable.variable_id = "";
                        vm.variable.asset_zone_id= "";
                        vm.variable.asset_variable_id="";
                        vm.variable.asset_zones = [];
                        vm.getAssetVariables();
                    })
                    .catch(function (error) {
                        loader.hide();
                        console.log("error",error)
                        vm.errors = error.response?.data?.errors;
                        vm.$store.dispatch("error", error?.response?.data?.message);
                    });
            },
            addDataSource(){
                let vm = this;
                vm.datasource.asset_id = vm.asset.asset_id;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "addAssetDataSource", data: vm.datasource })
                    .then((response) => {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.datasource.data_source_id = "";
                        vm.errors = [];
                        vm.datasource.asset_zone_id='';
                        vm.datasource.asset_zones=[];
                        vm.getAssetDataSources();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            updateDataSource(){
                let vm = this
                let loader = vm.$loading.show();
                let uri = { uri: "updateAssetDataSource", data: vm.datasource };
                vm.$store
                    .dispatch("post", uri)
                    .then(function (response) {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.datasource.data_source_id = "";
                        vm.datasource.asset_zone_id= "";
                        vm.datasource.asset_data_source_id="";
                        vm.datasource.asset_zones = [];
                        vm.getAssetDataSources();
                    })
                    .catch(function (error) {
                        loader.hide();
                        console.log("error",error)
                        vm.errors = error.response?.data?.errors;
                        vm.$store.dispatch("error", error?.response?.data?.message);
                    });
            },
            deleteSpare(spare) {
                let vm = this;
                // spare.asset_id = vm.asset.asset_id;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "forceDeleteAssetSpare", data: spare })
                    .then((response) => {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.spare.spare_id = "";
                        vm.errors = [];
                        vm.getAssetSpares();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            updateCheck(){
                let vm = this
                let loader = vm.$loading.show();
                // console.log('check:----',check)
                let uri = { uri: "updateAssetCheck", data: vm.check };
                vm.$store
                    .dispatch("post", uri)
                    .then(function (response) {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.check.check_id = "";
                        vm.check.lcl = "";
                        vm.check.ucl = "";
                        vm.check.default_value = "";
                        vm.check.asset_check_id = "";
                        vm.check.asset_zones = [];
                        vm.getAssetChecks();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            deleteCheck(check) {
                let vm = this;
                // check.asset_id = vm.asset.asset_id;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "forceDeleteAssetCheck", data: check })
                    .then((response) => {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.check.check_id = "";
                        vm.errors = [];
                        vm.getAssetChecks();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.$store.dispatch("warning", error.response.data.message);
                    });
            },
            deleteService(service) {
                let vm = this;
                // check.asset_id = vm.asset.asset_id;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "forceDeleteAssetService", data: service })
                    .then((response) => {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.service.service_id = "";
                        vm.errors = [];
                        vm.getAssetServices();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            deleteVariable(variable) {
                let vm = this;
                // spare.asset_id = vm.asset.asset_id;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "deleteAssetVariable", data: variable })
                    .then((response) => {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.variable.variable_id = "";
                        vm.errors = [];
                        vm.getAssetVariables();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            deleteDataSource(datasource) {
                let vm = this;
                // spare.asset_id = vm.asset.asset_id;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "deleteAssetDataSource", data: datasource })
                    .then((response) => {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.datasource.data_source_id = "";
                        vm.errors = [];
                        vm.getAssetDataSources();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            deleteAccessory(accessory) {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "deleteAssetAccessory", data: accessory })
                    .then((response) => {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.accessory.accessory_id = "";
                        vm.errors = [];
                        vm.getAssetAccessories();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getAccessoryTypes() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getAccessoryTypes" ,data: vm.asset })
                    .then((response) => {
                        loader.hide();
                        vm.accessory_types = response.data.data;
                        vm.getAssetAccessories();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getAssetAccessories() {
                let vm = this;
                vm.accessory_meta_service.asset_id = vm.asset.asset_id;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "paginateAssetAccessories", data: vm.accessory_meta_service })
                    .then((response) => {
                        loader.hide();
                        vm.asset_accessories = response.data.data;
                        vm.accessory_meta_service.totalRows = response.data.meta.total;
                        vm.accessory_meta_service.from = response.data.meta.from;
                        vm.accessory_meta_service.lastPage = response.data.meta.last_page;
                        vm.accessory_meta_service.maxPage = vm.accessory_meta_service.lastPage >= 3 ? 3 : vm.accessory_meta_service.lastPage;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            addAccessory() {
                let vm = this;
                vm.accessory.asset_id = vm.asset.asset_id;
                console.log("ddd",vm.$refs.attachment)
                console.log("accceess--",vm.accessory)
                let loader = vm.$loading.show();
                const data = new FormData();
                console.log("sss--",typeof(JSON.stringify(vm.accessory.asset_zone_id)))
                data.append("asset_id", vm.accessory.asset_id);
                data.append("asset_zone_id", JSON.stringify(vm.accessory.asset_zone_id));
                data.append("accessory_type_id", vm.accessory.accessory_type_id);
                data.append("accessory_name", vm.accessory.accessory_name);
                data.append("attachment", vm.$refs.attachment.files[0]);
                

                axios
                    .post(vm.$store.state.apiUrl + "addAssetAccessory", data, {
                        headers: {
                            "Content-Type": "multipart/form-data",
                            Authorization: "Bearer" + " " + vm.$store.getters.token,
                        },
                    })
                    .then((response) => {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.accessory.accessory_id = "";
                        vm.errors = [];
                        vm.accessory.asset_zone_id=[];
                        vm.getAssetAccessories();
                    })

                // vm.$store
                //     .dispatch("post", { uri: "addAssetAccessory", data: vm.accessory })
                //     .then((response) => {
                //         loader.hide();
                //         vm.$store.dispatch("success", response.data.message);
                //         vm.accessory.accessory_id = "";
                //         vm.errors = [];
                //         vm.accessory.asset_zone_id=[];
                //         vm.getAssetAccessories();
                //     })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            onPageChange(page) {
                this.meta.page = page;
                this.getAssetSpares();
            },
            sort(field) {
                this.meta.keyword = field;
                this.meta.order_by = this.meta.order_by == "asc" ? "desc" : "asc";
                this.getAssetSpares();
            },
            sortVariable(field) {
                this.meta.keyword = field;
                this.meta.order_by = this.meta.order_by == "asc" ? "desc" : "asc";
                this.getAssetVariables();
            },
            onPerPageChange() {
                let vm = this;
                vm.meta.page = 1;
                vm.getAssetSpares();
            },

            onPageChangeCheck(page) {
                this.check_meta.page = page;
                this.getAssetChecks();
            },
            sortCheck(field) {
                this.check_meta.keyword = field;
                this.check_meta.order_by = this.check_meta.order_by == "asc" ? "desc" : "asc";
                this.getAssetChecks();
            },
            onPerPageChangeCheck() {
                let vm = this;
                vm.check_meta.page = 1;
                vm.getAssetChecks();
            },
        },
    };
</script>
<style scoped>
    .badge {
        /* color: #6e7985; */
        font-size: 14px;
        opacity: inherit;
        margin-left: auto;
    }

    .border-left {
        border-top-left-radius: 0px;
        border-bottom-left-radius: 0px;
    }

    /* multi seleect css */

    .dropdown {
    position: relative;
    cursor: pointer;
}
.multiselect {
    position: relative;
}
.multiselect ul {
    border: 1px solid #ddd;
    border-top: 0;
    border-radius: 0 0 3px 3px;
    left: 0px;
    padding: 8px 8px;
    top: -0.1rem;
    width: 100%;
    list-style: none;
    max-height: 150px;
    overflow: auto;
    background: white;
}
.overselect {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
}
</style>
