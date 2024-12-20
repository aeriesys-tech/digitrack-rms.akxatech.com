<template>
    <div class="">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div>
                <ol class="breadcrumb fs-sm mb-1">
                    <li class="breadcrumb-item" aria-current="page">
                        <router-link to="/dashboard">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item"><router-link to="/asset_templates">Asset Templates</router-link></li>
                    <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
                <h4 class="main-title mb-0">Asset Templates</h4>
            </div>
            <router-link to="/asset_templates" type="submit" class="btn btn-primary" style="float: right;"><i class="ri-list-check"></i> ASSET TEMPLATES</router-link>
        </div>
        <div class="row g-2">
            <div class="col-xl-3 mb-1">
                <div class="row">
                    <div class="col-12 mb-2">
                        <div class="card card-one">
                            <div class="card-header d-flex justify-content-between">
                                <h6 class="card-title">{{asset?.template_code}}</h6>
                            </div>
                            <div class="card-body">
                                <nav class="nav nav-classic">
                                    <span class="nav-link text-dark"><span>Template Code</span> <span class="badge text-dark" style="text-wrap: balance;">{{asset.template_code}}</span></span>
                                    <span class="nav-link text-dark"><span>Template Name</span> <span class="badge text-dark">{{asset.template_name}}</span></span>
                                    <span class="nav-link text-dark"><span>Asset Type</span> <span class="badge text-dark">{{asset.asset_type?.asset_type_name}}</span></span>
                                    <span class="nav-link text-dark"><span>Latitude</span> <span class="badge text-dark">{{asset.latitude}}</span></span>
                                    <span class="nav-link text-dark"><span>Longitude</span> <span class="badge text-dark">{{asset.longitude}}</span></span>
                                    <span class="nav-link text-dark"><span>Radius</span> <span class="badge text-dark">{{asset.radius}}</span></span>
                                    <span class="nav-link text-dark">
                                        <span>Department</span>
                                        <span class="" style="margin-left: auto">
                                            <ul class="list_style_none mb-0 text-end">
                                                <li v-if="asset?.asset_department_ids?.length === 0">No departments</li>
                                                <li v-for="department in asset.asset_department_ids" :key="department.id">
                                                    {{ department.department?.department_name }}
                                                </li>
                                            </ul>
                                        </span>
                                    </span>
                                    <span class="nav-link text-dark"><span>Functional</span> <span class="badge text-dark">{{asset.functional?.functional_name}}</span></span>
                                    <span class="nav-link text-dark" v-for="(zone, index) in asset.zone_name" :key="zone.asset_zone_id">
                                        <span>Zone {{ index + 1 }}</span>
                                        <span class="badge text-dark">{{ zone.zone_name }}</span>
                                    </span>
                                    <h6 class="nav-link text-dark d-flex justify-content-between" v-for="asset_attribute, key in asset.asset_attributes" :key="key">
                                        {{ asset_attribute.display_name}}
                                        <span v-if="asset_attribute.field_name == 'Color'" :style="getColor(asset_attribute)">{{ asset_attribute?.asset_attribute_value?.field_value }}</span>
                                        <span v-else>{{ asset_attribute?.asset_attribute_value?.field_value }}</span>
                                    </h6>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- try -->
            <div class="col-xl-9 mb-2" style="height: 950px; overflow-y: scroll;">
                <div class="row">
                    <div class="col-12 mb-2" v-can="'assetSpares.view'">
                        <div class="card card-one">
                            <div class="card-header d-flex justify-content-between">
                                <h6 class="card-title">Spares</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-3" v-can="'assetSpares.create'">
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

                                        <label class="form-label">Asset Zone</label>
                                        <!-- <div class="dropdown" @click="toggleAssetZoneStatus('spares')">
                                            <div class="overselect"></div>
                                            <select class="form-control form-control" :class="{'is-invalid':errors?.spare_asset_zones}">
                                                <option value="">Select Asset Zone</option>
                                            </select>
                                            <span v-if="errors?.spare_asset_zones" class="invalid-feedback">{{ errors?.spare_asset_zones[0] }}</span>
                                        </div>
                                        <div class="multiselect" v-if="asset_zone_status_spares">
                                            <ul>
                                                <li v-for="(asset_zone, index) in asset_zones" :key="index">
                                                    <input type="checkbox" :value="asset_zone.asset_zone_id" v-model="spare.spare_asset_zones" style="padding: 2px;" @click="updateSpareAssetZone($event, spare)" />
                                                    <label style="margin-left: 5px;">{{ asset_zone.zone_name }}</label>
                                                </li>
                                            </ul>
                                        </div> -->

                                        <MultiSelect v-model="spare.spare_asset_zones_obj"  filter optionLabel="zone_name"
                                            :options="asset_zones"  placeholder="Select Asset Zone" :maxSelectedLabels="3"
                                            style="width: 100%;; height: 37px;" :style="errors?.spare_asset_zones ? error_style : ''" :disabled="spare.spare_zone_read_only" />
                                        <span v-if="errors?.spare_asset_zones" class="invalid-feedback" style="display: block !important">{{ errors?.spare_asset_zones[0] }}</span>

                                    </div>
                                    <div class="col-md-3" v-can="'assetSpares.create'">
                                        <!-- <div class="d-flex justify-content-between" v-can="'assetSpares.create'"> -->
                                        <label class="form-label">Spare</label>
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
                                            @selectsearch="getSpareAttribute(spare.spare_id)"
                                        >
                                        </search>
                                        <span v-if="errors.spare_id" class="invalid-feedback">{{ errors.spare_id[0] }}</span>
                                    </div>
                                    <div class="col-md-3" v-can="'assetSpares.create'">
                                        <label class="form-label">Max Quantity</label>
                                        <input type="number" class="form-control" placeholder="Enter Max Quantity" :class="{ 'is-invalid': errors.quantity }" v-model="spare.quantity" />
                                        <span v-if="errors.quantity" class="invalid-feedback">{{ errors.quantity[0] }}</span>
                                    </div>
                                    <div class="col-md-3" v-for="field, key in spare.asset_spare_attributes" :key="key">
                                        <div v-if="field.spare_attributes[0].field_type=='Text'">
                                            <label class="form-label">{{field.spare_attributes[0].display_name}}</label><span v-if="field.spare_attributes[0].is_required" class="text-danger">*</span>
                                            <input
                                                type="text"
                                                class="form-control"
                                                :placeholder="'Enter '+ field.spare_attributes[0].display_name"
                                                :class="{'is-invalid': errors[field.spare_attributes[0].display_name]}"
                                                v-model="field.field_value"
                                            />
                                            <span v-if="errors[field.spare_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.spare_attributes[0].display_name][0] }}</span>
                                        </div>

                                        <div v-if="field.spare_attributes[0].field_type=='Number'">
                                            <label class="form-label">{{field.spare_attributes[0].display_name}}</label><span v-if="field.spare_attributes[0].is_required" class="text-danger">*</span>
                                            <input
                                                type="number"
                                                class="form-control"
                                                min="0"
                                                oninput="validity.valid||(value='');"
                                                :placeholder="'Enter '+ field.spare_attributes[0].display_name"
                                                :class="{'is-invalid': errors[field.spare_attributes[0].display_name]}"
                                                v-model="field.field_value"
                                            />
                                            <span v-if="errors[field.spare_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.spare_attributes[0].display_name][0] }}</span>
                                        </div>

                                        <div v-if="field.spare_attributes[0].field_type === 'Date'">
                                            <label class="form-label">
                                                {{ field.spare_attributes[0].display_name }}
                                                <span v-if="field.spare_attributes[0].is_required" class="text-danger">*</span>
                                            </label>
                                            <input
                                                type="date"
                                                class="form-control"
                                                :placeholder="'Enter ' + field.spare_attributes[0].display_name"
                                                :class="{'is-invalid': errors[field.spare_attributes[0].display_name]}"
                                                v-model="field.field_value"
                                            />
                                            <span v-if="errors[field.spare_attributes[0].display_name]" class="invalid-feedback">
                                                {{ errors[field.spare_attributes[0].display_name][0] }}
                                            </span>
                                        </div>

                                        <div v-if="field.spare_attributes[0].field_type === 'Date&Time'">
                                            <label class="form-label">
                                                {{ field.spare_attributes[0].display_name }}
                                                <span v-if="field.spare_attributes[0].is_required" class="text-danger">*</span>
                                            </label>

                                            <input
                                                type="datetime-local"
                                                class="form-control"
                                                :placeholder="'Enter ' + field.spare_attributes[0].display_name"
                                                :class="{'is-invalid': errors[field.spare_attributes[0].display_name]}"
                                                v-model="field.field_value"
                                                step="1"
                                            />
                                            <span v-if="errors[field.spare_attributes[0].display_name]" class="invalid-feedback">
                                                {{ errors[field.spare_attributes[0].display_name][0] }}
                                            </span>
                                        </div>
                                        <div v-if="field.spare_attributes[0].field_type=='Dropdown'">
                                            <label class="form-label">{{field.spare_attributes[0].display_name}}</label><span v-if="field.spare_attributes[0].is_required" class="text-danger">*</span>
                                            <select class="form-control" :class="{'is-invalid': errors[field.spare_attributes[0].display_name]}" v-model="field.field_value">
                                                <option :value="field.field_value" v-if="field.field_value">{{field.field_value}}</option>
                                                <option :value="field.field_value" v-else>Select {{field.spare_attributes[0].display_name}}</option>
                                                <option v-for="value, key in field.spare_attributes[0].field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                            </select>
                                            <span v-if="errors[field.spare_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.spare_attributes[0].display_name][0] }}</span>
                                        </div>
                                        <div v-if="field.spare_attributes[0].field_type=='Color'">
                                            <label class="form-label">{{ field.spare_attributes[0].display_name }}<span v-if="field.spare_attributes[0].is_required" class="text-danger">*</span></label>
                                            <input type="color" class="form-control" v-model="field.field_value" style="height: 2.2rem;" />
                                            <span v-if="errors[field.spare_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.spare_attributes[0].display_name][0] }}</span>
                                        </div>

                                        <div v-if="field.spare_attributes[0].field_type=='List'">
                                            <label class="form-label">{{field.spare_attributes[0].display_name}}</label><span v-if="field.spare_attributes[0].is_required" class="text-danger">*</span>
                                            <select class="form-control" :class="{'is-invalid': errors[field.spare_attributes[0].display_name]}" v-model="field.field_value">
                                                <option :value="field.spare_attributes[0].spare_attribute_value?.field_value" v-if="field.spare_attributes[0].spare_attribute_value?.field_value">
                                                    {{field.spare_attributes[0].spare_attribute_value?.field_value}}
                                                </option>
                                                <option :value="field.spare_attributes[0].spare_attribute_value?.field_value" v-else>Select {{field.spare_attributes[0].display_name}}</option>
                                                <option v-for="value, key in field.spare_attributes[0].list_parameter?.field_values.split(',')" :key="key" :value="value.trim()">{{value.trim()}}</option>
                                            </select>
                                            <span v-if="errors[field.spare_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.spare_attributes[0].display_name][0] }}</span>
                                        </div>
                                    </div>

                                    <!-- <div class="col-md-1 pt-4" v-can="'assetSpares.create'">
                                        <button v-if="spare.asset_spare_id" class="btn btn-outline-danger me-2" @click="discard()">
                                            <i class="ri-add-circle-line icon-hgt"></i> Discard
                                        </button>
                                    </div> -->
                                    <div class="col-md-2 pt-4" v-can="'assetSpares.create'">
                                        <button v-if="spare.asset_spare_id" class="btn btn-outline-success me-2" @click="updateSpare()"><i class="ri-add-circle-line icon-hgt"></i> Update</button>
                                        <button v-else class="btn btn-outline-success me-2" @click="addSpare()"><i class="ri-add-circle-line icon-hgt"></i> Add</button>
                                    </div>

                                    <div class="col-12">
                                        <div class="table-responsive table-responsive-sm">
                                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                                <thead>
                                                    <tr class="">
                                                        <th class="text-center">#</th>
                                                        <th @click="sort('asset_zone_id', 'Spares')">
                                                            Asset Zone
                                                            <span>
                                                                <i v-if="meta.keyword=='asset_zone_id' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="meta.keyword=='asset_zone_id' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th @click="sort('spare_type_id', 'Spares')">
                                                            Spare Type
                                                            <span>
                                                                <i v-if="meta.keyword=='spare_type_id' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="meta.keyword=='spare_type_id' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th @click="sort('spare_code', 'Spares')">
                                                            Spare Code
                                                            <span>
                                                                <i v-if="meta.keyword=='spare_code' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="meta.keyword=='spare_code' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th @click="sort('spare_name', 'Spares')">
                                                            Spare Name

                                                            <span>
                                                                <i v-if="meta.keyword=='spare_name' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="meta.keyword=='spare_name' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th @click="sort('quantity', 'Spares')">
                                                            Quantity

                                                            <span>
                                                                <i v-if="meta.keyword=='quantity' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="meta.keyword=='quantity' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th class="text-center" v-can="'assetSpares.delete'">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-if="asset_spares?.length==0">
                                                        <td colspan="7" class="text-center">No records found</td>
                                                    </tr>
                                                    <tr v-for="spare, key in asset_spares" :key="key">
                                                        <td class="text-center">{{ (meta.page-1)*meta.per_page + key+1 }}</td>
                                                        <td>{{ spare.asset_zone?.zone_name }}</td>
                                                        <td>{{spare.spare?.spare_type?.spare_type_name}}</td>
                                                        <td>{{spare.spare?.spare_code}}</td>
                                                        <td>{{spare.spare?.spare_name}}</td>
                                                        <td>{{ spare.quantity }}</td>
                                                        <td class="text-center" v-can="'assetSpares.delete'">
                                                            <a href="javascript:void(0)" class="text-success me-2" @click="editSpare(spare)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
                                                            <!-- <a href="javascript:void(0)" class="text-primary me-2" @click.prevent="viewAsset(spare, 'Spares')"><i class="ri-eye-fill fs-18 lh-1"></i></a> -->

                                                            <a href="javascript:void(0)" class="text-primary me-2" data-bs-toggle="modal" data-bs-target="#exampleModal" @click.prevent="viewAsset(spare, 'Spares')">
                                                                <i class="ri-eye-fill fs-18 lh-1"></i>
                                                            </a>
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
                                    <select class="form-select from-select-sm width-75" v-model="meta.per_page" @change="onPerPageChange('Spares')">
                                        <option>5</option>
                                        <option>10</option>
                                        <option>15</option>
                                        <option>20</option>
                                        <option>25</option>
                                        <option>30</option>
                                    </select>
                                    <span>Showing {{ meta.from }} to {{ meta.to }} of {{ meta.totalRows }} entries</span>
                                    <Pagination :maxPage="meta.maxPage" :totalPages="meta.lastPage" :currentPage="meta.page" @pagechanged="onPageChange" :from="'Spares'" />
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
                                        <!-- <div class="dropdown" @click="toggleAssetZoneStatus('checks')">
                                            <div class="overselect"></div>
                                            <select class="form-control form-control" :class="{'is-invalid':errors.check_asset_zones}">
                                                <option value="">Select Asset Zone</option>
                                            </select>
                                            <span v-if="errors.check_asset_zones" class="invalid-feedback">{{ errors.check_asset_zones[0] }}</span>
                                        </div>
                                        <div class="multiselect" v-if="asset_zone_status_checks">
                                            <ul>
                                                <li v-for="(asset_zone, index) in asset_zones" :key="index">
                                                    <input type="checkbox" :value="asset_zone.asset_zone_id" v-model="check.check_asset_zones" @click="updateCheckAssetZone($event, check)" style="padding: 2px;" />
                                                    <label style="margin-left: 5px;">{{ asset_zone.zone_name }}</label>
                                                </li>
                                            </ul>
                                        </div> -->
                                        <MultiSelect v-model="check.check_asset_zones_obj"  filter optionLabel="zone_name"
                                            :options="asset_zones"  placeholder="Select Asset Zone" :maxSelectedLabels="3"
                                            style="width: 100%;; height: 37px;" :style="errors?.check_asset_zones ? error_style : ''" :disabled="check.check_zone_read_only"/>
                                        <span v-if="errors?.check_asset_zones" class="invalid-feedback" style="display: block !important">{{ errors?.check_asset_zones[0] }}</span>
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
                                                <input type="text" class="form-control" placeholder="Lcl" v-model="check.lcl" />
                                            </div>
                                            <div class="col-md-3">
                                                <!-- <label>Ucl</label> -->
                                                <input type="text" class="form-control" placeholder="Ucl" v-model="check.ucl" />
                                            </div>
                                            <div class="col-md-3">
                                                <!-- <label>Default Value</label> -->
                                                <input type="text" class="form-control" placeholder="Default Value" v-model="check.default_value" />
                                            </div>
                                            <div class="col-md-3 d-flex justify-content-end">
                                                <button v-if="check.asset_check_id" class="btn btn-outline-success me-2" @click="updateCheck()"><i class="ri-add-circle-line icon-hgt"></i> Update</button>
                                                <button v-else class="btn btn-outline-success me-2" @click="addCheck()"><i class="ri-add-circle-line icon-hgt"></i> Add</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="table-responsive table-responsive-sm">
                                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" width="2%">#</th>
                                                        <th @click="sort('asset_zone_id', 'Checks')">
                                                            Asset Zone
                                                            <span>
                                                                <i v-if="check_meta.keyword=='asset_zone_id' && check_meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="check_meta.keyword=='asset_zone_id' && check_meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th @click="sort('check_id', 'Checks')" width="40%" style="white-space: normal;">
                                                            Field Name
                                                            <span>
                                                                <i v-if="check_meta.keyword=='field_name' && check_meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="check_meta.keyword=='field_name' && check_meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th @click="sort('check_id', 'Checks')" width="10%">
                                                            Field Type
                                                            <span>
                                                                <i v-if="check_meta.keyword=='field_type' && check_meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="check_meta.keyword=='field_type' && check_meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>

                                                        <th @click="sort('lcl', 'Checks')" width="8%">
                                                            Lcl
                                                            <span>
                                                                <i v-if="check_meta.keyword=='lcl' && check_meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="check_meta.keyword=='lcl' && check_meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th @click="sort('ucl', 'Checks')" width="8%">
                                                            Ucl
                                                            <span>
                                                                <i v-if="check_meta.keyword=='ucl' && check_meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="check_meta.keyword=='ucl' && check_meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th @click="sort('default_value', 'Checks')" width="8%" style="white-space: normal;">
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
                                                    <tr v-if="asset_checks?.length==0">
                                                        <td colspan="8" class="text-center">No records found</td>
                                                    </tr>
                                                    <tr v-for="check, key in asset_checks" :key="key">
                                                        <td class="text-center">{{(check_meta.page-1)*check_meta.per_page + key+1 }}</td>
                                                        <td>{{ check?.asset_zone?.zone_name }}</td>
                                                        <td style="white-space: normal;">{{check?.check?.field_name}}</td>
                                                        <td>{{ check?.check?.field_type }}</td>
                                                        <td>{{ check.lcl }}</td>
                                                        <td>{{ check.ucl }}</td>
                                                        <td style="white-space: normal;">{{ check.default_value }}</td>
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
                                    <select class="form-select from-select-sm width-75" v-model="check_meta.per_page" @change="onPerPageChange('Checks')">
                                        <option>5</option>
                                        <option>10</option>
                                        <option>15</option>
                                        <option>20</option>
                                        <option>25</option>
                                        <option>30</option>
                                    </select>
                                    <span>Showing {{ check_meta.from }} to {{ check_meta.to }} of {{ check_meta.totalRows }} entries</span>
                                    <Pagination :maxPage="check_meta.maxPage" :totalPages="check_meta.lastPage" :currentPage="check_meta.page" @pagechanged="onPageChange" :from="'Checks'" />
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
                                        <label class="form-label">Asset Zone</label>
                                        <!-- <div class="dropdown" @click="toggleAssetZoneStatus('services')">
                                            <div class="overselect"></div>
                                            <select class="form-control form-control" :class="{'is-invalid':errors.service_asset_zones}">
                                                <option value="">Select Asset Zone</option>
                                            </select>
                                            <span v-if="errors.service_asset_zones" class="invalid-feedback">{{ errors.service_asset_zones[0] }}</span>
                                        </div>
                                        <div class="multiselect" v-if="asset_zone_status_services">
                                            <ul>
                                                <li v-for="(asset_zone, index) in asset_zones" :key="index">
                                                    <input type="checkbox" :value="asset_zone.asset_zone_id" v-model="service.service_asset_zones" style="padding: 2px;" @click="updateServiceAssetZone($event, service)" />
                                                    <label style="margin-left: 5px;">{{ asset_zone.zone_name }}</label>
                                                </li>
                                            </ul>
                                        </div> -->
                                        <MultiSelect v-model="service.service_asset_zones_obj"  filter optionLabel="zone_name"
                                            :options="asset_zones"  placeholder="Select Asset Zone" :maxSelectedLabels="3"
                                            style="width: 100%;; height: 37px;" :style="errors?.service_asset_zones ? error_style : ''" :disabled="service.service_zone_read_only" />
                                        <span v-if="errors?.service_asset_zones" class="invalid-feedback" style="display: block !important">{{ errors?.service_asset_zones[0] }}</span>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Service</label>
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
                                            @selectsearch="getServiceAttribute(service.service_id)"
                                        >
                                        </search>
                                        <span v-if="errors.service_id" class="invalid-feedback">{{ errors.service_id[0] }}</span>
                                    </div>

                                    <div class="col-md-3" v-for="field, key in service.asset_service_attributes" :key="key">
                                        <div v-if="field.service_attributes[0].field_type=='Text'">
                                            <label class="form-label">{{field.service_attributes[0].display_name}}</label><span v-if="field.service_attributes[0].is_required" class="text-danger">*</span>
                                            <input
                                                type="text"
                                                class="form-control"
                                                :placeholder="'Enter '+ field.service_attributes[0].display_name"
                                                :class="{'is-invalid': errors[field.service_attributes[0].display_name]}"
                                                v-model="field.field_value"
                                            />
                                            <span v-if="errors[field.service_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.service_attributes[0].display_name][0] }}</span>
                                        </div>

                                        <div v-if="field.service_attributes[0].field_type=='Number'">
                                            <label class="form-label">{{field.service_attributes[0].display_name}}</label><span v-if="field.service_attributes[0].is_required" class="text-danger">*</span>
                                            <input
                                                type="number"
                                                class="form-control"
                                                min="0"
                                                oninput="validity.valid||(value='');"
                                                :placeholder="'Enter '+ field.service_attributes[0].display_name"
                                                :class="{'is-invalid': errors[field.service_attributes[0].display_name]}"
                                                v-model="field.field_value"
                                            />
                                            <span v-if="errors[field.service_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.service_attributes[0].display_name][0] }}</span>
                                        </div>

                                        <div v-if="field.service_attributes[0].field_type === 'Date'">
                                            <label class="form-label">
                                                {{ field.service_attributes[0].display_name }}
                                                <span v-if="field.service_attributes[0].is_required" class="text-danger">*</span>
                                            </label>
                                            <input
                                                type="date"
                                                class="form-control"
                                                :placeholder="'Enter ' + field.service_attributes[0].display_name"
                                                :class="{'is-invalid': errors[field.service_attributes[0].display_name]}"
                                                v-model="field.field_value"
                                            />
                                            <span v-if="errors[field.service_attributes[0].display_name]" class="invalid-feedback">
                                                {{ errors[field.service_attributes[0].display_name][0] }}
                                            </span>
                                        </div>

                                        <div v-if="field.service_attributes[0].field_type === 'Date&Time'">
                                            <label class="form-label">
                                                {{ field.service_attributes[0].display_name }}
                                                <span v-if="field.service_attributes[0].is_required" class="text-danger">*</span>
                                            </label>

                                            <input
                                                type="datetime-local"
                                                class="form-control"
                                                :placeholder="'Enter ' + field.service_attributes[0].display_name"
                                                :class="{'is-invalid': errors[field.service_attributes[0].display_name]}"
                                                v-model="field.field_value"
                                                step="1"
                                            />
                                            <span v-if="errors[field.service_attributes[0].display_name]" class="invalid-feedback">
                                                {{ errors[field.service_attributes[0].display_name][0] }}
                                            </span>
                                        </div>
                                        <div v-if="field.service_attributes[0].field_type=='Dropdown'">
                                            <label class="form-label">{{field.service_attributes[0].display_name}}</label><span v-if="field.service_attributes[0].is_required" class="text-danger">*</span>
                                            <select class="form-control" :class="{'is-invalid': errors[field.service_attributes[0].display_name]}" v-model="field.field_value">
                                                <option :value="field.field_value" v-if="field.field_value">{{field.field_value}}</option>
                                                <option :value="field.field_value" v-else>Select {{field.service_attributes[0].display_name}}</option>
                                                <option v-for="value, key in field.service_attributes[0].field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                            </select>
                                            <span v-if="errors[field.service_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.service_attributes[0].display_name][0] }}</span>
                                        </div>
                                        <div v-if="field.service_attributes[0].field_type=='Color'">
                                            <label class="form-label">{{ field.service_attributes[0].display_name }}<span v-if="field.service_attributes[0].is_required" class="text-danger">*</span></label>
                                            <input type="color" class="form-control" v-model="field.field_value" style="height: 2.2rem;" />
                                            <span v-if="errors[field.service_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.service_attributes[0].display_name][0] }}</span>
                                        </div>

                                        <div v-if="field.service_attributes[0].field_type=='List'">
                                            <label class="form-label">{{field.service_attributes[0].display_name}}</label><span v-if="field.service_attributes[0].is_required" class="text-danger">*</span>
                                            <select class="form-control" :class="{'is-invalid': errors[field.service_attributes[0].display_name]}" v-model="field.field_value">
                                                <option :value="field.service_attributes[0].service_attribute_value?.field_value" v-if="field.service_attributes[0]?.service_attribute_value?.field_value">
                                                    {{field.service_attributes[0].service_attribute_value?.field_value}}
                                                </option>
                                                <option :value="field.service_attributes[0].service_attribute_value?.field_value" v-else>Select {{field.service_attributes[0].display_name}}</option>
                                                <option v-for="value, key in field.service_attributes[0].list_parameter?.field_values.split(',')" :key="key" :value="value.trim()">{{value.trim()}}</option>
                                            </select>
                                            <span v-if="errors[field.service_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.service_attributes[0].display_name][0] }}</span>
                                        </div>
                                    </div>

                                    <div class="col-md-2 pt-4">
                                        <!-- <div style="float: left;">
                                            <button class="btn btn-outline-success me-2" @click="addService()"><i class="ri-add-circle-line icon-hgt"></i> Add</button>
                                        </div> -->
                                        <button v-if="service.asset_service_id" class="btn btn-outline-success me-2" @click="updateService()"><i class="ri-add-circle-line icon-hgt"></i> Update</button>
                                        <button v-else class="btn btn-outline-success me-2" @click="addService()"><i class="ri-add-circle-line icon-hgt"></i> Add</button>
                                    </div>

                                    <div class="col-12">
                                        <div class="table-responsive table-responsive-sm">
                                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                                <thead>
                                                    <tr class="">
                                                        <th class="text-center">#</th>
                                                        <th @click="sort('asset_zone_id', 'Services')">
                                                            Asset Zone
                                                            <span>
                                                                <i v-if="check_meta.keyword=='asset_zone_id' && check_meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="check_meta.keyword=='asset_zone_id' && check_meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th @click="sort('service_type_id', 'Services')">
                                                            Service Type
                                                            <span>
                                                                <i v-if="check_meta.keyword=='service_type_id' && check_meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="check_meta.keyword=='service_type_id' && check_meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th @click="sort('service_name', 'Services')">
                                                            Service Name
                                                            <span>
                                                                <i v-if="check_meta.keyword=='service_name' && check_meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="check_meta.keyword=='service_name' && check_meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th @click="sort('service_code', 'Services')">
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
                                                    <tr v-if="asset_services?.length==0">
                                                        <td colspan="6" class="text-center">No records found</td>
                                                    </tr>
                                                    <tr v-for="service, key in asset_services" :key="key">
                                                        <td class="text-center">{{(check_meta_service.page-1)*check_meta_service.per_page + key+1 }}</td>
                                                        <td>{{ service?.asset_zone?.zone_name }}</td>
                                                        <td>{{ service?.service?.service_type?.service_type_name }}</td>
                                                        <td>{{ service?.service?.service_name }}</td>
                                                        <td>{{ service?.service?.service_code }}</td>
                                                        <td class="text-center">
                                                            <a href="javascript:void(0)" class="text-success me-2" @click="editService(service)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
                                                            <a href="javascript:void(0)" class="text-primary me-2" data-bs-toggle="modal" data-bs-target="#exampleModal" @click.prevent="viewAsset(service, 'Services')">
                                                                <i class="ri-eye-fill fs-18 lh-1"></i>
                                                            </a>
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
                                    <select class="form-select from-select-sm width-75" v-model="check_meta_service.per_page" @change="onPerPageChange('Services')">
                                        <option>5</option>
                                        <option>10</option>
                                        <option>15</option>
                                        <option>20</option>
                                        <option>25</option>
                                        <option>30</option>
                                    </select>
                                    <span>Showing {{ check_meta_service.from }} to {{ check_meta_service.to }} of {{ check_meta_service.totalRows }} entries</span>
                                    <Pagination :maxPage="check_meta_service.maxPage" :totalPages="check_meta_service.lastPage" :currentPage="check_meta_service.page" @pagechanged="onPageChange" :from="'Services'" />
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
                                    <div class="col-md-4">
                                        <label class="form-label">Asset Zone</label>
                                        <!-- <div class="dropdown" @click="toggleAssetZoneStatus('variables')">
                                            <div class="overselect"></div>
                                            <select class="form-control form-control" :class="{'is-invalid':errors.variable_asset_zones}">
                                                <option value="">Select Asset Zone</option>
                                            </select>
                                            <span v-if="errors.variable_asset_zones" class="invalid-feedback">{{ errors.variable_asset_zones[0] }}</span>
                                        </div>
                                        <div class="multiselect" v-if="asset_zone_status_variables">
                                            <ul>
                                                <li v-for="(asset_zone, index) in asset_zones" :key="index">
                                                    <input type="checkbox" :value="asset_zone.asset_zone_id" v-model="variable.variable_asset_zones" style="padding: 2px;" @click="updateVariableAssetZone($event, variable)" />
                                                    <label style="margin-left: 5px;">{{ asset_zone.zone_name }}</label>
                                                </li>
                                            </ul>
                                        </div> -->
                                        <MultiSelect v-model="variable.variable_asset_zones_obj"  filter optionLabel="zone_name"
                                            :options="asset_zones"  placeholder="Select Asset Zone" :maxSelectedLabels="3"
                                            style="width: 100%;; height: 37px;" :style="errors?.variable_asset_zones ? error_style : ''" :disabled="variable.variable_zone_read_only" />
                                        <span v-if="errors?.variable_asset_zones" class="invalid-feedback" style="display: block !important">{{ errors?.variable_asset_zones[0] }}</span>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Variable</label>
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
                                            @selectsearch="getVariableAttribute(variable.variable_id)"
                                        >
                                        </search>
                                        <span v-if="errors.variable_id" class="invalid-feedback">{{ errors.variable_id[0] }}</span>
                                    </div>

                                    <div class="col-md-3" v-for="field, key in variable.asset_variable_attributes" :key="key">
                                        <div v-if="field.variable_attributes[0].field_type=='Text'">
                                            <label class="form-label">{{field.variable_attributes[0].display_name}}</label><span v-if="field.variable_attributes[0].is_required" class="text-danger">*</span>
                                            <input
                                                type="text"
                                                class="form-control"
                                                :placeholder="'Enter '+ field.variable_attributes[0].display_name"
                                                :class="{'is-invalid': errors[field.variable_attributes[0].display_name]}"
                                                v-model="field.field_value"
                                            />
                                            <span v-if="errors[field.variable_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.variable_attributes[0].display_name][0] }}</span>
                                        </div>

                                        <div v-if="field.variable_attributes[0].field_type=='Number'">
                                            <label class="form-label">{{field.variable_attributes[0].display_name}}</label><span v-if="field.variable_attributes[0].is_required" class="text-danger">*</span>
                                            <input
                                                type="number"
                                                class="form-control"
                                                min="0"
                                                oninput="validity.valid||(value='');"
                                                :placeholder="'Enter '+ field.variable_attributes[0].display_name"
                                                :class="{'is-invalid': errors[field.variable_attributes[0].display_name]}"
                                                v-model="field.field_value"
                                            />
                                            <span v-if="errors[field.variable_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.variable_attributes[0].display_name][0] }}</span>
                                        </div>

                                        <div v-if="field.variable_attributes[0].field_type === 'Date'">
                                            <label class="form-label">
                                                {{ field.variable_attributes[0].display_name }}
                                                <span v-if="field.variable_attributes[0].is_required" class="text-danger">*</span>
                                            </label>
                                            <input
                                                type="date"
                                                class="form-control"
                                                :placeholder="'Enter ' + field.variable_attributes[0].display_name"
                                                :class="{'is-invalid': errors[field.variable_attributes[0].display_name]}"
                                                v-model="field.field_value"
                                            />
                                            <span v-if="errors[field.variable_attributes[0].display_name]" class="invalid-feedback">
                                                {{ errors[field.variable_attributes[0].display_name][0] }}
                                            </span>
                                        </div>

                                        <div v-if="field.variable_attributes[0].field_type === 'Date&Time'">
                                            <label class="form-label">
                                                {{ field.variable_attributes[0].display_name }}
                                                <span v-if="field.variable_attributes[0].is_required" class="text-danger">*</span>
                                            </label>

                                            <input
                                                type="datetime-local"
                                                class="form-control"
                                                :placeholder="'Enter ' + field.variable_attributes[0].display_name"
                                                :class="{'is-invalid': errors[field.variable_attributes[0].display_name]}"
                                                v-model="field.field_value"
                                                step="1"
                                            />
                                            <span v-if="errors[field.variable_attributes[0].display_name]" class="invalid-feedback">
                                                {{ errors[field.variable_attributes[0].display_name][0] }}
                                            </span>
                                        </div>
                                        <div v-if="field.variable_attributes[0].field_type=='Dropdown'">
                                            <label class="form-label">{{field.variable_attributes[0].display_name}}</label><span v-if="field.variable_attributes[0].is_required" class="text-danger">*</span>
                                            <select class="form-control" :class="{'is-invalid': errors[field.variable_attributes[0].display_name]}" v-model="field.field_value">
                                                <option :value="field.field_value" v-if="field.field_value">{{field.field_value}}</option>
                                                <option :value="field.field_value" v-else>Select {{field.variable_attributes[0].display_name}}</option>
                                                <option v-for="value, key in field.variable_attributes[0].field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                            </select>
                                            <span v-if="errors[field.variable_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.variable_attributes[0].display_name][0] }}</span>
                                        </div>
                                        <div v-if="field.variable_attributes[0].field_type=='Color'">
                                            <label class="form-label">{{ field.variable_attributes[0].display_name }}<span v-if="field.variable_attributes[0].is_required" class="text-danger">*</span></label>
                                            <input type="color" class="form-control" v-model="field.field_value" style="height: 2.2rem;" />
                                            <span v-if="errors[field.variable_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.variable_attributes[0].display_name][0] }}</span>
                                        </div>

                                        <div v-if="field.variable_attributes[0].field_type=='List'">
                                            <label class="form-label">{{field.variable_attributes[0].display_name}}</label><span v-if="field.variable_attributes[0].is_required" class="text-danger">*</span>
                                            <select class="form-control" :class="{'is-invalid': errors[field.variable_attributes[0].display_name]}" v-model="field.field_value">
                                                <option :value="field.variable_attributes[0].variable_attribute_value?.field_value" v-if="field.variable_attributes[0].variable_attribute_value?.field_value">
                                                    {{field.variable_attributes[0].variable_attribute_value?.field_value}}
                                                </option>
                                                <option :value="field.variable_attributes[0].variable_attribute_value?.field_value" v-else>Select {{field.variable_attributes[0].display_name}}</option>
                                                <option v-for="value, key in field.variable_attributes[0].list_parameter?.field_values.split(',')" :key="key" :value="value.trim()">{{value.trim()}}</option>
                                            </select>
                                            <span v-if="errors[field.variable_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.variable_attributes[0].display_name][0] }}</span>
                                        </div>
                                    </div>

                                    <div class="col-md-2 pt-4">
                                        <button v-if="variable.asset_variable_id" class="btn btn-outline-success me-2" @click="updateVariable()"><i class="ri-add-circle-line icon-hgt"></i> Update</button>
                                        <button v-else class="btn btn-outline-success me-2" @click="addVariable()"><i class="ri-add-circle-line icon-hgt"></i> Add</button>
                                    </div>

                                    <div class="col-12">
                                        <div class="table-responsive table-responsive-sm">
                                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                                <thead>
                                                    <tr class="">
                                                        <th class="text-center">#</th>
                                                        <th @click="sort('asset_zone_id', 'Variables')">
                                                            Asset Zone
                                                            <span>
                                                                <i v-if="variable_meta_service.keyword=='asset_zone_id' && variable_meta_service.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="variable_meta_service.keyword=='asset_zone_id' && variable_meta_service.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th @click="sort('variable_type_id', 'Variables')">
                                                            Variable Type
                                                            <span>
                                                                <i v-if="variable_meta_service.keyword=='variable_type_id' && variable_meta_service.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="variable_meta_service.keyword=='variable_type_id' && variable_meta_service.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th @click="sort('variable_name', 'Variables')">
                                                            Variable Code
                                                            <span>
                                                                <i v-if="variable_meta_service.keyword=='variable_code' && variable_meta_service.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="variable_meta_service.keyword=='variable_code' && variable_meta_service.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th @click="sort('variable_name', 'Variables')">
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
                                                    <tr v-if="asset_variables?.length==0">
                                                        <td colspan="6" class="text-center">No records found</td>
                                                    </tr>
                                                    <tr v-for="variable, key in asset_variables" :key="key">
                                                        <td class="text-center">{{(variable_meta_service.page-1)*variable_meta_service.per_page + key+1 }}</td>
                                                        <td>{{ variable.asset_zone?.zone_name }}</td>
                                                        <td>{{variable.variable?.variable_type?.variable_type_name}}</td>
                                                        <td>{{variable.variable?.variable_code}}</td>
                                                        <td>{{variable.variable?.variable_name}}</td>
                                                        <td class="text-center">
                                                            <a href="javascript:void(0)" class="text-success me-2" @click="editVariable(variable)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
                                                            <a href="javascript:void(0)" class="text-primary me-2" data-bs-toggle="modal" data-bs-target="#exampleModal" @click.prevent="viewAsset(variable, 'Variables')">
                                                                <i class="ri-eye-fill fs-18 lh-1"></i>
                                                            </a>
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
                                    <select class="form-select from-select-sm width-75" v-model="variable_meta_service.per_page" @change="onPerPageChange('Variables')">
                                        <option>5</option>
                                        <option>10</option>
                                        <option>15</option>
                                        <option>20</option>
                                        <option>25</option>
                                        <option>30</option>
                                    </select>
                                    <span>Showing {{ variable_meta_service.from }} to {{ variable_meta_service.to }} of {{ variable_meta_service.totalRows }} entries</span>
                                    <Pagination :maxPage="variable_meta_service.maxPage" :totalPages="variable_meta_service.lastPage" :currentPage="variable_meta_service.page" @pagechanged="onPageChange" :from="'Variables'" />
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
                                    <div class="col-md-3">
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
                                        <label class="form-label">Asset Zone</label>
                                        <!-- <div class="dropdown" @click="toggleAssetZoneStatus('datasources')">
                                            <div class="overselect"></div>
                                            <select class="form-control form-control" :class="{'is-invalid':errors.data_source_asset_zones}">
                                                <option value="">Select Asset Zone</option>
                                            </select>
                                            <span v-if="errors.data_source_asset_zones" class="invalid-feedback">{{ errors.data_source_asset_zones[0] }}</span>
                                        </div>
                                        <div class="multiselect" v-if="asset_zone_status_datasources">
                                            <ul>
                                                <li v-for="(asset_zone, index) in asset_zones" :key="index">
                                                    <input type="checkbox" :value="asset_zone.asset_zone_id" v-model="datasource.data_source_asset_zones" style="padding: 2px;" @click="updateDataSourceAssetZone($event, datasource)" />
                                                    <label style="margin-left: 5px;">{{ asset_zone.zone_name }}</label>
                                                </li>
                                            </ul>
                                        </div> -->
                                        <MultiSelect v-model="datasource.data_source_asset_zones_obj"  filter optionLabel="zone_name"
                                            :options="asset_zones"  placeholder="Select Asset Zone" :maxSelectedLabels="3"
                                            style="width: 100%;; height: 37px;" :style="errors?.data_source_asset_zones ? error_style : ''" :disabled="datasource.data_source_zone_read_only" />
                                        <span v-if="errors?.data_source_asset_zones" class="invalid-feedback" style="display: block !important">{{ errors?.data_source_asset_zones[0] }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Data Source</label>
                                        <search
                                            :class="{ 'is-invalid': errors.data_source_id }"
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
                                            @selectsearch="getDatasourceAttribute(datasource.data_source_id)"
                                        >
                                        </search>
                                        <span v-if="errors.data_source_id" class="invalid-feedback">{{ errors.data_source_id[0] }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Script</label>
                                        <select class="form-control form-control" v-model="datasource.script" :class="{'is-invalid':errors.script}">
                                            <option value="">Select Script</option>
                                            <option value="Ladle Scanner">Ladle Scanner</option>
                                            <option value="Torpedo Scanner">Torpedo Scanner</option>
                                        </select>
                                        <span v-if="errors.script" class="invalid-feedback">{{ errors.script[0] }}</span>
                                    </div>

                                    <div class="col-md-3" v-for="field, key in datasource.asset_datasource_attributes" :key="key">
                                        <div v-if="field.data_source_attributes[0].field_type=='Text'">
                                            <label class="form-label">{{field.data_source_attributes[0].display_name}}</label><span v-if="field.data_source_attributes[0].is_required" class="text-danger">*</span>
                                            <input
                                                type="text"
                                                class="form-control"
                                                :placeholder="'Enter '+ field.data_source_attributes[0].display_name"
                                                :class="{'is-invalid': errors[field.data_source_attributes[0].display_name]}"
                                                v-model="field.field_value"
                                            />
                                            <span v-if="errors[field.data_source_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.data_source_attributes[0].display_name][0] }}</span>
                                        </div>

                                        <div v-if="field.data_source_attributes[0].field_type=='Number'">
                                            <label class="form-label">{{field.data_source_attributes[0].display_name}}</label><span v-if="field.data_source_attributes[0].is_required" class="text-danger">*</span>
                                            <input
                                                type="number"
                                                class="form-control"
                                                min="0"
                                                oninput="validity.valid||(value='');"
                                                :placeholder="'Enter '+ field.data_source_attributes[0].display_name"
                                                :class="{'is-invalid': errors[field.data_source_attributes[0].display_name]}"
                                                v-model="field.field_value"
                                            />
                                            <span v-if="errors[field.data_source_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.data_source_attributes[0].display_name][0] }}</span>
                                        </div>

                                        <div v-if="field.data_source_attributes[0].field_type === 'Date'">
                                            <label class="form-label">
                                                {{ field.data_source_attributes[0].display_name }}
                                                <span v-if="field.data_source_attributes[0].is_required" class="text-danger">*</span>
                                            </label>
                                            <input
                                                type="date"
                                                class="form-control"
                                                :placeholder="'Enter ' + field.data_source_attributes[0].display_name"
                                                :class="{'is-invalid': errors[field.data_source_attributes[0].display_name]}"
                                                v-model="field.field_value"
                                            />
                                            <span v-if="errors[field.data_source_attributes[0].display_name]" class="invalid-feedback">
                                                {{ errors[field.data_source_attributes[0].display_name][0] }}
                                            </span>
                                        </div>

                                        <div v-if="field.data_source_attributes[0].field_type === 'Date&Time'">
                                            <label class="form-label">
                                                {{ field.data_source_attributes[0].display_name }}
                                                <span v-if="field.data_source_attributes[0].is_required" class="text-danger">*</span>
                                            </label>

                                            <input
                                                type="datetime-local"
                                                class="form-control"
                                                :placeholder="'Enter ' + field.data_source_attributes[0].display_name"
                                                :class="{'is-invalid': errors[field.data_source_attributes[0].display_name]}"
                                                v-model="field.field_value"
                                                step="1"
                                            />
                                            <span v-if="errors[field.data_source_attributes[0].display_name]" class="invalid-feedback">
                                                {{ errors[field.data_source_attributes[0].display_name][0] }}
                                            </span>
                                        </div>
                                        <div v-if="field.data_source_attributes[0].field_type=='Dropdown'">
                                            <label class="form-label">{{field.data_source_attributes[0].display_name}}</label><span v-if="field.data_source_attributes[0].is_required" class="text-danger">*</span>
                                            <select class="form-control" :class="{'is-invalid': errors[field.data_source_attributes[0].display_name]}" v-model="field.field_value">
                                                <option :value="field.field_value" v-if="field.field_value">{{field.field_value}}</option>
                                                <option :value="field.field_value" v-else>Select {{field.data_source_attributes[0].display_name}}</option>
                                                <option v-for="value, key in field.data_source_attributes[0].field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                            </select>
                                            <span v-if="errors[field.data_source_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.data_source_attributes[0].display_name][0] }}</span>
                                        </div>
                                        <div v-if="field.data_source_attributes[0].field_type=='Color'">
                                            <label class="form-label">{{ field.data_source_attributes[0].display_name }}<span v-if="field.data_source_attributes[0].is_required" class="text-danger">*</span></label>
                                            <input type="color" class="form-control" v-model="field.field_value" style="height: 2.2rem;" />
                                            <span v-if="errors[field.data_source_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.data_source_attributes[0].display_name][0] }}</span>
                                        </div>

                                        <div v-if="field.data_source_attributes[0].field_type=='List'">
                                            <label class="form-label">{{field.data_source_attributes[0].display_name}}</label><span v-if="field.data_source_attributes[0].is_required" class="text-danger">*</span>
                                            <select class="form-control" :class="{'is-invalid': errors[field.data_source_attributes[0].display_name]}" v-model="field.field_value">
                                                <option :value="field.data_source_attributes[0].data_source_attribute_value?.field_value" v-if="field.data_source_attributes[0].data_source_attribute_value?.field_value">
                                                    {{field.data_source_attributes[0].data_source_attribute_value?.field_value}}
                                                </option>
                                                <option :value="field.data_source_attributes[0].data_source_attribute_value?.field_value" v-else>Select {{field.data_source_attributes[0].display_name}}</option>
                                                <option v-for="value, key in field.data_source_attributes[0].list_parameter?.field_values.split(',')" :key="key" :value="value.trim()">{{value.trim()}}</option>
                                            </select>
                                            <span v-if="errors[field.data_source_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.data_source_attributes[0].display_name][0] }}</span>
                                        </div>
                                    </div>

                                    <div class="col-md-2 pt-4">
                                        <button v-if="datasource.asset_data_source_id" class="btn btn-outline-success me-2" @click="updateDataSource()"><i class="ri-add-circle-line icon-hgt"></i> Update</button>
                                        <button v-else class="btn btn-outline-success me-2" @click="addDataSource()"><i class="ri-add-circle-line icon-hgt"></i> Add</button>
                                    </div>

                                    <div class="col-12">
                                        <div class="table-responsive table-responsive-sm">
                                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                                <thead>
                                                    <tr class="">
                                                        <th class="text-center">#</th>
                                                        <th @click="sort('asset_zone_id', 'Data Sources')">
                                                            Asset Zone
                                                            <span>
                                                                <i v-if="datasource_meta_service.keyword=='asset_zone_id' && datasource_meta_service.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="datasource_meta_service.keyword=='asset_zone_id' && datasource_meta_service.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th @click="sort('data_source_type_id', 'Data Sources')">
                                                            Data Source Type
                                                            <span>
                                                                <i v-if="datasource_meta_service.keyword=='data_source_type_id' && datasource_meta_service.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="datasource_meta_service.keyword=='data_source_type_id' && datasource_meta_service.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th @click="sort('data_source_code', 'Data Sources')">
                                                            Data Source Code
                                                            <span>
                                                                <i v-if="datasource_meta_service.keyword=='data_source_code' && datasource_meta_service.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="datasource_meta_service.keyword=='data_source_code' && datasource_meta_service.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th @click="sort('data_source_name', 'Data Sources')">
                                                            Data Source Name
                                                            <span>
                                                                <i v-if="datasource_meta_service.keyword=='data_source_name' && datasource_meta_service.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="datasource_meta_service.keyword=='data_source_name' && datasource_meta_service.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th @click="sort('script', 'Data Sources')">
                                                            Script
                                                            <span>
                                                                <i v-if="datasource_meta_service.keyword=='script' && datasource_meta_service.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="datasource_meta_service.keyword=='script' && datasource_meta_service.order_by=='desc'" class="ri-arrow-down-line"></i>
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
                                                        <td class="text-center">{{(datasource_meta_service.page-1)*datasource_meta_service.per_page + key+1 }}</td>
                                                        <td>{{ data_source.asset_zone?.zone_name }}</td>
                                                        <td>{{ data_source.data_source?.data_source_type?.data_source_type_name }}</td>
                                                        <td>{{ data_source?.data_source?.data_source_code }}</td>
                                                        <td>{{ data_source?.data_source?.data_source_name }}</td>
                                                        <td>{{ data_source?.script }}</td>
                                                        <td class="text-center">
                                                            <a href="javascript:void(0)" class="text-success me-2" @click="editDataSource(data_source)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
                                                            <a href="javascript:void(0)" class="text-primary me-2" data-bs-toggle="modal" data-bs-target="#exampleModal" @click.prevent="viewAsset(data_source, 'Data Sources')">
                                                                <i class="ri-eye-fill fs-18 lh-1"></i>
                                                            </a>
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
                                    <select class="form-select from-select-sm width-75" v-model="datasource_meta_service.per_page" @change="onPerPageChange('Data Sources')">
                                        <option>5</option>
                                        <option>10</option>
                                        <option>15</option>
                                        <option>20</option>
                                        <option>25</option>
                                        <option>30</option>
                                    </select>
                                    <span>Showing {{ datasource_meta_service.from }} to {{ datasource_meta_service.to }} of {{ datasource_meta_service.totalRows }} entries</span>
                                    <Pagination :maxPage="datasource_meta_service.maxPage" :totalPages="datasource_meta_service.lastPage" :currentPage="datasource_meta_service.page" @pagechanged="onPageChange" :from="'Data Sources'" />
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
                                        <!-- <div class="dropdown" @click="toggleAssetZoneStatus('accessories')">
                                            <div class="overselect"></div>
                                            <select class="form-control form-control" :class="{'is-invalid':errors.accessory_asset_zone_id}">
                                                <option value="">Select Asset Zone</option>
                                            </select>
                                            <span v-if="errors.accessory_asset_zone_id" class="invalid-feedback">{{ errors.accessory_asset_zone_id[0] }}</span>
                                        </div>
                                        <div class="multiselect" v-if="asset_zone_status_accessories">
                                            <ul>
                                                <li v-for="(asset_zone, index) in asset_zones" :key="index">
                                                    <input type="checkbox" :value="asset_zone.asset_zone_id" v-model="accessory.accessory_asset_zone_id" style="padding: 2px;" />
                                                    <label style="margin-left: 5px;">{{ asset_zone.zone_name }}</label>
                                                </li>
                                            </ul>
                                        </div> -->
                                        <MultiSelect v-model="accessory.accessory_asset_zones_obj"  filter optionLabel="zone_name"
                                            :options="asset_zones"  placeholder="Select Asset Zone" :maxSelectedLabels="3"
                                            style="width: 100%;; height: 37px;" :style="errors?.accessory_asset_zones ? error_style : ''" :disabled="accessory.accessory_zone_read_only" />
                                        <span v-if="errors?.accessory_asset_zones" class="invalid-feedback" style="display: block !important">{{ errors?.accessory_asset_zones[0] }}</span>
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
                                    </div>

                                    <div class="col-12">
                                        <div class="table-responsive table-responsive-sm">
                                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                                <thead>
                                                    <tr class="">
                                                        <th class="text-center">#</th>
                                                        <th @click="sort('asset_zone_id', 'Accessories')">
                                                            Asset Zone
                                                            <span>
                                                                <i v-if="accessory_meta_service.keyword=='asset_zone_id' && accessory_meta_service.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="accessory_meta_service.keyword=='asset_zone_id' && accessory_meta_service.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th @click="sort('accessory_type_id', 'Accessories')">
                                                            Accessory Type
                                                            <span>
                                                                <i v-if="accessory_meta_service.keyword=='accessory_type_id' && accessory_meta_service.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="accessory_meta_service.keyword=='accessory_type_id' && accessory_meta_service.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th @click="sort('accessory_name', 'Accessories')">
                                                            Accessory Name
                                                            <span>
                                                                <i v-if="accessory_meta_service.keyword=='accessory_name' && accessory_meta_service.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="accessory_meta_service.keyword=='accessory_name' && accessory_meta_service.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                                <i v-else class="fas fa-sort"></i>
                                                            </span>
                                                        </th>
                                                        <th @click="sort('attachment', 'Accessories')">
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
                                                    <tr v-if="asset_accessories?.length==0">
                                                        <td colspan="6" class="text-center">No records found</td>
                                                    </tr>
                                                    <tr v-for="accessory, key in asset_accessories" :key="key">
                                                        <td class="text-center">{{(accessory_meta_service.page-1)*accessory_meta_service.per_page + key+1 }}</td>
                                                        <td>{{ accessory.asset_zone?.zone_name }}</td>
                                                        <td>{{ accessory.accessory_type.accessory_type_name }}</td>
                                                        <td>{{ accessory.accessory_name }}</td>
                                                        <td>
                                                            <a v-if="accessory.attachment" :href="getAttachmentUrl(accessory.attachment)" target="_blank" rel="noopener noreferrer">
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
                                    <select class="form-select from-select-sm width-75" v-model="accessory_meta_service.per_page" @change="onPerPageChange('Accessories')">
                                        <option>5</option>
                                        <option>10</option>
                                        <option>15</option>
                                        <option>20</option>
                                        <option>25</option>
                                        <option>30</option>
                                    </select>
                                    <span>Showing {{ accessory_meta_service.from }} to {{ accessory_meta_service.to }} of {{ accessory_meta_service.totalRows }} entries</span>
                                    <Pagination :maxPage="accessory_meta_service.maxPage" :totalPages="accessory_meta_service.lastPage" :currentPage="accessory_meta_service.page" @pagechanged="onPageChange" :from="'Accessories'" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- accessories end -->
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{asset_type}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-12 mb-2">
                            <!-- Spare -->
                            <div class="row g-2" v-if="asset_type=='Spares'">
                                <div class="col-md-4" v-for="field, key in assets1?.asset_spare_attributes" :key="key">
                                    <div v-if="field.spare_attributes[0].field_type=='Text'">
                                        <label class="form-label">{{field.spare_attributes[0].display_name}}</label><span v-if="field.spare_attributes[0].is_required" class="text-danger">*</span>
                                        <input
                                            type="text"
                                            class="form-control"
                                            :placeholder="'Enter '+ field.spare_attributes[0].display_name"
                                            :class="{'is-invalid': errors[field.spare_attributes[0].display_name]}"
                                            :value="field.field_value"
                                            disabled="true"
                                        />
                                        <span v-if="errors[field.spare_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.spare_attributes[0].display_name][0] }}</span>
                                    </div>

                                    <div v-if="field.spare_attributes[0].field_type=='Number'">
                                        <label class="form-label">{{field.spare_attributes[0].display_name}}</label><span v-if="field.spare_attributes[0].is_required" class="text-danger">*</span>
                                        <input
                                            type="number"
                                            class="form-control"
                                            min="0"
                                            oninput="validity.valid||(value='');"
                                            :placeholder="'Enter '+ field.spare_attributes[0].display_name"
                                            :class="{'is-invalid': errors[field.spare_attributes[0].display_name]}"
                                            :value="field.field_value"
                                            disabled="true"
                                        />
                                        <span v-if="errors[field.spare_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.spare_attributes[0].display_name][0] }}</span>
                                    </div>

                                    <div v-if="field.spare_attributes[0].field_type === 'Date'">
                                        <label class="form-label">
                                            {{ field.spare_attributes[0].display_name }}
                                            <span v-if="field.spare_attributes[0].is_required" class="text-danger">*</span>
                                        </label>
                                        <input
                                            type="date"
                                            class="form-control"
                                            :placeholder="'Enter ' + field.spare_attributes[0].display_name"
                                            :class="{'is-invalid': errors[field.spare_attributes[0].display_name]}"
                                            :value="field.field_value"
                                            disabled="true"
                                        />
                                        <span v-if="errors[field.spare_attributes[0].display_name]" class="invalid-feedback">
                                            {{ errors[field.spare_attributes[0].display_name][0] }}
                                        </span>
                                    </div>

                                    <div v-if="field.spare_attributes[0].field_type === 'Date&Time'">
                                        <label class="form-label">
                                            {{ field.spare_attributes[0].display_name }}
                                            <span v-if="field.spare_attributes[0].is_required" class="text-danger">*</span>
                                        </label>

                                        <input
                                            type="datetime-local"
                                            class="form-control"
                                            :placeholder="'Enter ' + field.spare_attributes[0].display_name"
                                            :class="{'is-invalid': errors[field.spare_attributes[0].display_name]}"
                                            :value="field.field_value"
                                            step="1"
                                            disabled="true"
                                        />
                                        <span v-if="errors[field.spare_attributes[0].display_name]" class="invalid-feedback">
                                            {{ errors[field.spare_attributes[0].display_name][0] }}
                                        </span>
                                    </div>
                                    <div v-if="field.spare_attributes[0].field_type=='Dropdown'">
                                        <label class="form-label">{{field.spare_attributes[0].display_name}}</label><span v-if="field.spare_attributes[0].is_required" class="text-danger">*</span>
                                        <select class="form-control" :class="{'is-invalid': errors[field.spare_attributes[0].display_name]}" :value="field.field_value" disabled="true">
                                            <option :value="field.field_value" v-if="field.field_value">{{field.field_value}}</option>
                                            <option :value="field.field_value" v-else>Select {{field.spare_attributes[0].display_name}}</option>
                                            <option v-for="value, key in field.spare_attributes[0].field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                        </select>
                                        <span v-if="errors[field.spare_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.spare_attributes[0].display_name][0] }}</span>
                                    </div>
                                    <div v-if="field.spare_attributes[0].field_type=='Color'">
                                        <label class="form-label">{{ field.spare_attributes[0].display_name }}<span v-if="field.spare_attributes[0].is_required" class="text-danger">*</span></label>
                                        <input type="color" class="form-control" :value="field.field_value" style="height: 2.2rem;" disabled="true" />
                                        <span v-if="errors[field.spare_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.spare_attributes[0].display_name][0] }}</span>
                                    </div>

                                    <div v-if="field.spare_attributes[0].field_type=='List'">
                                        <label class="form-label">{{field.spare_attributes[0].display_name}}</label><span v-if="field.spare_attributes[0].is_required" class="text-danger">*</span>
                                        <select class="form-control" :class="{'is-invalid': errors[field.spare_attributes[0].display_name]}" :value="field.field_value" disabled="true">
                                            <option :value="field.spare_attributes[0].spare_attribute_value.field_value" v-if="field.spare_attributes[0].spare_attribute_value.field_value">
                                                {{field.spare_attributes[0].spare_attribute_value.field_value}}
                                            </option>
                                            <option :value="field.spare_attributes[0].spare_attribute_value.field_value" v-else>Select {{field.spare_attributes[0].display_name}}</option>
                                            <option v-for="value, key in field.spare_attributes[0].list_parameter?.field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                        </select>
                                        <span v-if="errors[field.spare_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.spare_attributes[0].display_name][0] }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Service -->
                            <div class="row g-2" v-if="asset_type=='Services'">
                                <div class="col-md-4" v-for="field, key in assets1?.asset_service_attributes" :key="key">
                                    <div v-if="field.service_attributes[0].field_type=='Text'">
                                        <label class="form-label">{{field.service_attributes[0].display_name}}</label><span v-if="field.service_attributes[0].is_required" class="text-danger">*</span>
                                        <input
                                            type="text"
                                            class="form-control"
                                            :placeholder="'Enter '+ field.service_attributes[0].display_name"
                                            :class="{'is-invalid': errors[field.service_attributes[0].display_name]}"
                                            :value="field.field_value"
                                            disabled="true"
                                        />
                                        <span v-if="errors[field.service_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.service_attributes[0].display_name][0] }}</span>
                                    </div>

                                    <div v-if="field.service_attributes[0].field_type=='Number'">
                                        <label class="form-label">{{field.service_attributes[0].display_name}}</label><span v-if="field.service_attributes[0].is_required" class="text-danger">*</span>
                                        <input
                                            type="number"
                                            class="form-control"
                                            min="0"
                                            oninput="validity.valid||(value='');"
                                            :placeholder="'Enter '+ field.service_attributes[0].display_name"
                                            :class="{'is-invalid': errors[field.service_attributes[0].display_name]}"
                                            :value="field.field_value"
                                            disabled="true"
                                        />
                                        <span v-if="errors[field.service_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.service_attributes[0].display_name][0] }}</span>
                                    </div>

                                    <div v-if="field.service_attributes[0].field_type === 'Date'">
                                        <label class="form-label">
                                            {{ field.service_attributes[0].display_name }}
                                            <span v-if="field.service_attributes[0].is_required" class="text-danger">*</span>
                                        </label>
                                        <input
                                            type="date"
                                            class="form-control"
                                            :placeholder="'Enter ' + field.service_attributes[0].display_name"
                                            :class="{'is-invalid': errors[field.service_attributes[0].display_name]}"
                                            :value="field.field_value"
                                            disabled="true"
                                        />
                                        <span v-if="errors[field.service_attributes[0].display_name]" class="invalid-feedback">
                                            {{ errors[field.service_attributes[0].display_name][0] }}
                                        </span>
                                    </div>

                                    <div v-if="field.service_attributes[0].field_type === 'Date&Time'">
                                        <label class="form-label">
                                            {{ field.service_attributes[0].display_name }}
                                            <span v-if="field.service_attributes[0].is_required" class="text-danger">*</span>
                                        </label>

                                        <input
                                            type="datetime-local"
                                            class="form-control"
                                            :placeholder="'Enter ' + field.service_attributes[0].display_name"
                                            :class="{'is-invalid': errors[field.service_attributes[0].display_name]}"
                                            :value="field.field_value"
                                            step="1"
                                            disabled="true"
                                        />
                                        <span v-if="errors[field.service_attributes[0].display_name]" class="invalid-feedback">
                                            {{ errors[field.service_attributes[0].display_name][0] }}
                                        </span>
                                    </div>
                                    <div v-if="field.service_attributes[0].field_type=='Dropdown'">
                                        <label class="form-label">{{field.service_attributes[0].display_name}}</label><span v-if="field.service_attributes[0].is_required" class="text-danger">*</span>
                                        <select class="form-control" :class="{'is-invalid': errors[field.service_attributes[0].display_name]}" :value="field.field_value" disabled="true">
                                            <option :value="field.field_value" v-if="field.field_value">{{field.field_value}}</option>
                                            <option :value="field.field_value" v-else>Select {{field.service_attributes[0].display_name}}</option>
                                            <option v-for="value, key in field.service_attributes[0].field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                        </select>
                                        <span v-if="errors[field.service_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.service_attributes[0].display_name][0] }}</span>
                                    </div>
                                    <div v-if="field.service_attributes[0].field_type=='Color'">
                                        <label class="form-label">{{ field.service_attributes[0].display_name }}<span v-if="field.service_attributes[0].is_required" class="text-danger">*</span></label>
                                        <input type="color" class="form-control" :value="field.field_value" style="height: 2.2rem;" disabled="true" />
                                        <span v-if="errors[field.service_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.service_attributes[0].display_name][0] }}</span>
                                    </div>

                                    <div v-if="field.service_attributes[0].field_type=='List'">
                                        <label class="form-label">{{field.service_attributes[0].display_name}}</label><span v-if="field.service_attributes[0].is_required" class="text-danger">*</span>
                                        <select class="form-control" :class="{'is-invalid': errors[field.service_attributes[0].display_name]}" :value="field.field_value" disabled="true">
                                            <option :value="field.service_attributes[0].service_attribute_value?.field_value" v-if="field.service_attributes[0].service_attribute_value?.field_value">
                                                {{field.service_attributes[0].service_attribute_value?.field_value}}
                                            </option>
                                            <option :value="field.service_attributes[0].service_attribute_value?.field_value" v-else>Select {{field.service_attributes[0].display_name}}</option>
                                            <option v-for="value, key in field.service_attributes[0].list_parameter?.field_values.split(',')" :key="key" :value="value.trim()">{{value.trim()}}</option>
                                        </select>
                                        <span v-if="errors[field.service_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.service_attributes[0].display_name][0] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-2" v-if="asset_type=='Variables'">
                                <div class="col-md-4" v-for="field, key in assets1?.asset_variable_attributes" :key="key">
                                    <div v-if="field.variable_attributes[0].field_type=='Text'">
                                        <label class="form-label">{{field.variable_attributes[0].display_name}}</label><span v-if="field.variable_attributes[0].is_required" class="text-danger">*</span>
                                        <input
                                            type="text"
                                            class="form-control"
                                            :placeholder="'Enter '+ field.variable_attributes[0].display_name"
                                            :class="{'is-invalid': errors[field.variable_attributes[0].display_name]}"
                                            :value="field.field_value"
                                            disabled="true"
                                        />
                                        <span v-if="errors[field.variable_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.variable_attributes[0].display_name][0] }}</span>
                                    </div>

                                    <div v-if="field.variable_attributes[0].field_type=='Number'">
                                        <label class="form-label">{{field.variable_attributes[0].display_name}}</label><span v-if="field.variable_attributes[0].is_required" class="text-danger">*</span>
                                        <input
                                            type="number"
                                            class="form-control"
                                            min="0"
                                            oninput="validity.valid||(value='');"
                                            :placeholder="'Enter '+ field.variable_attributes[0].display_name"
                                            :class="{'is-invalid': errors[field.variable_attributes[0].display_name]}"
                                            :value="field.field_value"
                                            disabled="true"
                                        />
                                        <span v-if="errors[field.variable_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.variable_attributes[0].display_name][0] }}</span>
                                    </div>

                                    <div v-if="field.variable_attributes[0].field_type === 'Date'">
                                        <label class="form-label">
                                            {{ field.variable_attributes[0].display_name }}
                                            <span v-if="field.variable_attributes[0].is_required" class="text-danger">*</span>
                                        </label>
                                        <input
                                            type="date"
                                            class="form-control"
                                            :placeholder="'Enter ' + field.variable_attributes[0].display_name"
                                            :class="{'is-invalid': errors[field.variable_attributes[0].display_name]}"
                                            :value="field.field_value"
                                            disabled="true"
                                        />
                                        <span v-if="errors[field.variable_attributes[0].display_name]" class="invalid-feedback">
                                            {{ errors[field.variable_attributes[0].display_name][0] }}
                                        </span>
                                    </div>

                                    <div v-if="field.variable_attributes[0].field_type === 'Date&Time'">
                                        <label class="form-label">
                                            {{ field.variable_attributes[0].display_name }}
                                            <span v-if="field.variable_attributes[0].is_required" class="text-danger">*</span>
                                        </label>

                                        <input
                                            type="datetime-local"
                                            class="form-control"
                                            :placeholder="'Enter ' + field.variable_attributes[0].display_name"
                                            :class="{'is-invalid': errors[field.variable_attributes[0].display_name]}"
                                            :value="field.field_value"
                                            step="1"
                                            disabled="true"
                                        />
                                        <span v-if="errors[field.variable_attributes[0].display_name]" class="invalid-feedback">
                                            {{ errors[field.variable_attributes[0].display_name][0] }}
                                        </span>
                                    </div>
                                    <div v-if="field.variable_attributes[0].field_type=='Dropdown'">
                                        <label class="form-label">{{field.variable_attributes[0].display_name}}</label><span v-if="field.variable_attributes[0].is_required" class="text-danger">*</span>
                                        <select class="form-control" :class="{'is-invalid': errors[field.variable_attributes[0].display_name]}" :value="field.field_value" disabled="true">
                                            <option :value="field.field_value" v-if="field.field_value">{{field.field_value}}</option>
                                            <option :value="field.field_value" v-else>Select {{field.variable_attributes[0].display_name}}</option>
                                            <option v-for="value, key in field.variable_attributes[0].field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                        </select>
                                        <span v-if="errors[field.variable_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.variable_attributes[0].display_name][0] }}</span>
                                    </div>
                                    <div v-if="field.variable_attributes[0].field_type=='Color'">
                                        <label class="form-label">{{ field.variable_attributes[0].display_name }}<span v-if="field.variable_attributes[0].is_required" class="text-danger">*</span></label>
                                        <input type="color" class="form-control" :value="field.field_value" style="height: 2.2rem;" disabled="true" />
                                        <span v-if="errors[field.variable_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.variable_attributes[0].display_name][0] }}</span>
                                    </div>

                                    <div v-if="field.variable_attributes[0].field_type=='List'">
                                        <label class="form-label">{{field.variable_attributes[0].display_name}}</label><span v-if="field.variable_attributes[0].is_required" class="text-danger">*</span>
                                        <select class="form-control" :class="{'is-invalid': errors[field.variable_attributes[0].display_name]}" :value="field.field_value" disabled="true">
                                            <option :value="field.variable_attributes[0].variable_attribute_value?.field_value" v-if="field.variable_attributes[0].variable_attribute_value?.field_value">
                                                {{field.variable_attributes[0].variable_attribute_value?.field_value}}
                                            </option>
                                            <option :value="field.variable_attributes[0].variable_attribute_value?.field_value" v-else>Select {{field.variable_attributes[0].display_name}}</option>
                                            <option v-for="value, key in field.variable_attributes[0].list_parameter?.field_values.split(',')" :key="key" :value="value.trim()">{{value.trim()}}</option>
                                        </select>
                                        <span v-if="errors[field.variable_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.variable_attributes[0].display_name][0] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-2" v-if="asset_type=='Data Sources'">
                                <div class="col-md-4" v-for="field, key in assets1?.asset_datasource_attributes" :key="key">
                                    <div v-if="field.data_source_attributes[0].field_type=='Text'">
                                        <label class="form-label">{{field.data_source_attributes[0].display_name}}</label><span v-if="field.data_source_attributes[0].is_required" class="text-danger">*</span>
                                        <input
                                            type="text"
                                            class="form-control"
                                            :placeholder="'Enter '+ field.data_source_attributes[0].display_name"
                                            :class="{'is-invalid': errors[field.data_source_attributes[0].display_name]}"
                                            :value="field.field_value"
                                            disabled="true"
                                        />
                                        <span v-if="errors[field.data_source_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.data_source_attributes[0].display_name][0] }}</span>
                                    </div>

                                    <div v-if="field.data_source_attributes[0].field_type=='Number'">
                                        <label class="form-label">{{field.data_source_attributes[0].display_name}}</label><span v-if="field.data_source_attributes[0].is_required" class="text-danger">*</span>
                                        <input
                                            type="number"
                                            class="form-control"
                                            min="0"
                                            oninput="validity.valid||(value='');"
                                            :placeholder="'Enter '+ field.data_source_attributes[0].display_name"
                                            :class="{'is-invalid': errors[field.data_source_attributes[0].display_name]}"
                                            :value="field.field_value"
                                            disabled="true"
                                        />
                                        <span v-if="errors[field.data_source_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.data_source_attributes[0].display_name][0] }}</span>
                                    </div>

                                    <div v-if="field.data_source_attributes[0].field_type === 'Date'">
                                        <label class="form-label">
                                            {{ field.data_source_attributes[0].display_name }}
                                            <span v-if="field.data_source_attributes[0].is_required" class="text-danger">*</span>
                                        </label>
                                        <input
                                            type="date"
                                            class="form-control"
                                            :placeholder="'Enter ' + field.data_source_attributes[0].display_name"
                                            :class="{'is-invalid': errors[field.data_source_attributes[0].display_name]}"
                                            :value="field.field_value"
                                            disabled="true"
                                        />
                                        <span v-if="errors[field.data_source_attributes[0].display_name]" class="invalid-feedback">
                                            {{ errors[field.data_source_attributes[0].display_name][0] }}
                                        </span>
                                    </div>

                                    <div v-if="field.data_source_attributes[0].field_type === 'Date&Time'">
                                        <label class="form-label">
                                            {{ field.data_source_attributes[0].display_name }}
                                            <span v-if="field.data_source_attributes[0].is_required" class="text-danger">*</span>
                                        </label>

                                        <input
                                            type="datetime-local"
                                            class="form-control"
                                            :placeholder="'Enter ' + field.data_source_attributes[0].display_name"
                                            :class="{'is-invalid': errors[field.data_source_attributes[0].display_name]}"
                                            :value="field.field_value"
                                            step="1"
                                            disabled="true"
                                        />
                                        <span v-if="errors[field.data_source_attributes[0].display_name]" class="invalid-feedback">
                                            {{ errors[field.data_source_attributes[0].display_name][0] }}
                                        </span>
                                    </div>
                                    <div v-if="field.data_source_attributes[0].field_type=='Dropdown'">
                                        <label class="form-label">{{field.data_source_attributes[0].display_name}}</label><span v-if="field.data_source_attributes[0].is_required" class="text-danger">*</span>
                                        <select class="form-control" :class="{'is-invalid': errors[field.data_source_attributes[0].display_name]}" :value="field.field_value" disabled="true">
                                            <option :value="field.field_value" v-if="field.field_value">{{field.field_value}}</option>
                                            <option :value="field.field_value" v-else>Select {{field.data_source_attributes[0].display_name}}</option>
                                            <option v-for="value, key in field.data_source_attributes[0].field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                        </select>
                                        <span v-if="errors[field.data_source_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.data_source_attributes[0].display_name][0] }}</span>
                                    </div>
                                    <div v-if="field.data_source_attributes[0].field_type=='Color'">
                                        <label class="form-label">{{ field.data_source_attributes[0].display_name }}<span v-if="field.data_source_attributes[0].is_required" class="text-danger">*</span></label>
                                        <input type="color" class="form-control" :value="field.field_value" style="height: 2.2rem;" disabled="true" />
                                        <span v-if="errors[field.data_source_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.data_source_attributes[0].display_name][0] }}</span>
                                    </div>

                                    <div v-if="field.data_source_attributes[0].field_type=='List'">
                                        <label class="form-label">{{field.data_source_attributes[0].display_name}}</label><span v-if="field.data_source_attributes[0].is_required" class="text-danger">*</span>
                                        <select class="form-control" :class="{'is-invalid': errors[field.data_source_attributes[0].display_name]}" :value="field.field_value" disabled="true">
                                            <option :value="field.data_source_attributes[0].data_source_attribute_value?.field_value" v-if="field.data_source_attributes[0].data_source_attribute_value?.field_value">
                                                {{field.data_source_attributes[0].data_source_attribute_value?.field_value}}
                                            </option>
                                            <option :value="field.data_source_attributes[0].data_source_attribute_value?.field_value" v-else>Select {{field.data_source_attributes[0].display_name}}</option>
                                            <option v-for="value, key in field.data_source_attributes[0].list_parameter?.field_values.split(',')" :key="key" :value="value.trim()">{{value.trim()}}</option>
                                        </select>
                                        <span v-if="errors[field.data_source_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.data_source_attributes[0].display_name][0] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import axios from "axios";
    import Search from "@/components/Search.vue";
    import Pagination from "@/components/Pagination.vue";
    import MultiSelect from 'primevue/multiselect';
    export default {
        name: "AssetTemplates.View",
        components: { Search, Pagination, MultiSelect },
        data() {
            return {
                meta: {
                    search: "",
                    order_by: "desc",
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
                    asset_type_id: "",
                },
                check_meta: {
                    search: "",
                    order_by: "desc",
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
                    asset_type_id: "",
                    department_id: "",
                },
                check_meta_service: {
                    search: "",
                    order_by: "desc",
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
                    asset_type_id: "",
                },
                variable_meta_service: {
                    search: "",
                    order_by: "desc",
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
                    asset_type_id: "",
                },
                datasource_meta_service: {
                    search: "",
                    order_by: "desc",
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
                    asset_type_id: "",
                },
                accessory_meta_service: {
                    search: "",
                    order_by: "desc",
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
                    asset_type_id: "",
                },
                asset: {
                    asset_template_id: "",
                    plant_id: "",
                    template_code: "",
                    template_name: "",
                    asset_type_id: "",
                    section_id: "",
                    serial_no: "",
                    status: "",
                    QR_Code: "",
                },
                spare: {
                    spare_id: "",
                    asset_spare_id: "",
                    spare_code: "",
                    spare_name: "",
                    asset_id: "",
                    asset_zone_id: "",
                    spare_asset_zones_obj: [],
                    spare_asset_zones: [],
                    quantity: "",
                    asset_spare_attributes: [],
                    initial_spare_id: "",
                    initial_asset_spare_attributes: [],
                    deleted_asset_spare_values: [],
                    spare_zone_read_only: false,
                },
                check: {
                    check_id: "",
                    check_code: "",
                    check_name: "",
                    asset_id: "",
                    lcl: "",
                    ucl: "",
                    default_value: "",
                    asset_check_id: "",
                    asset_zone_id: "",
                    check_asset_zones_obj: [],
                    check_asset_zones: [],
                    check_zone_read_only: false,
                },
                service: {
                    service_id: "",
                    service_code: "",
                    service_name: "",
                    asset_id: "",
                    asset_zone_id: "",
                    asset_service_id: "",
                    service_asset_zones_obj: [],
                    service_asset_zones: [],
                    asset_service_attributes: [],
                    initial_service_id: "",
                    initial_asset_service_attributes: [],
                    deleted_asset_service_values: [],
                    service_zone_read_only: false,
                },
                datasource: {
                    data_source_id: "",
                    data_source_code: "",
                    data_source_name: "",
                    asset_id: "",
                    asset_zone_id: "",
                    data_source_asset_zones_obj: [],
                    data_source_asset_zones: [],
                    asset_data_source_id: "",
                    script: "",
                    asset_datasource_attributes: [],
                    initial_data_source_id: "",
                    initial_asset_datasource_attributes: [],
                    deleted_asset_datasource_values: [],
                    data_source_zone_read_only: false,
                },
                variable: {
                    variable_id: "",
                    variable_code: "",
                    variable_name: "",
                    asset_id: "",
                    asset_zone_id: "",
                    variable_asset_zones_obj: [],
                    variable_asset_zones: [],
                    asset_variable_id: "",
                    asset_variable_attributes: [],
                    initial_variable_id: "",
                    initial_asset_variable_attributes: [],
                    deleted_asset_variable_values: [],
                    variable_zone_read_only: false,
                },
                accessory: {
                    accessory_id: "",
                    asset_id: "",
                    accessory_asset_zones: [],
                    accessory_name: "",
                    accessory_type_id: "",
                    attachment: "",
                    accessory_asset_zones_obj: [],
                    accessory_zone_read_only: false,
                },

                device_code: "",
                spares: [],
                asset_spares: [],
                checks: [],
                asset_checks: [],
                asset_services: [],
                asset_variables: [],
                asset_data_sources: [],
                accessory_types: [],
                asset_accessories: [],
                asset_zones: [],
                errors: [],
                status: true,
                asset_zone_status: false,

                asset_zone_status_variables: false,
                asset_zone_status_datasources: false,
                asset_zone_status_spares: false,
                asset_zone_status_checks: false,
                asset_zone_status_services: false,
                asset_zone_status_accessories: false,

                // on add other api should not call
                initial_status: true,
                isValid: true,
                assets1: [],
                error_style: {
                    'border-color': '#dc3545',
                    'padding-right': 'calc(1.5em + 0.812rem)',
                    'background-image': `url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e")`,
                    'background-repeat': 'no-repeat',
                    'background-position': 'right calc(0.375em + 0.203rem) center',
                    'background-size': 'calc(0.75em + 0.406rem) calc(0.75em + 0.406rem)'
                }
            };
        },
        // watch:{
        //     'check.check_id':function(){
        //         if(typeof this.check.check_id === 'number' && !this.check.asset_check_id)
        //             this.getCheck()
        //     }
    // },
    watch: {
        assets1() {
        this.assets1 = this.$store.getters.assets;
        // You can also add any additional logic here if needed
    }
    },


        beforeRouteEnter(to, from, next) {
            next((vm) => {
                document.body.classList.add("sidebar-hide");
                vm.asset.asset_template_id = to.params.asset_template_id;
                let uri = { uri: "getAssetTemplate", data: { asset_template_id: to.params.asset_template_id } };
                vm.$store
                    .dispatch("post", uri)
                    .then(function (response) {
                         console.log("ASSSs---",response.data.data)
                        vm.asset = response.data.data;
                        // vm.asset_zones = response.data.zones;
                        vm.asset_zones = response?.data?.data?.zone_name;
                        vm.getAssetSpares();
                    })
                    .catch(function (error) {
                        console.log("Error", error);
                        // vm.errors = error.response.data.errors;
                        // vm.$store.dispatch("error", error.response.data.message);
                    });
            });
        },
        mounted() {
            document.body.classList.add("sidebar-hide");
        },
        methods: {
            getAttachmentUrl(attachment) {
                return `${attachment}`;
            },
            getAttachmentName(attachment) {
                return attachment.split("/").pop();
            },
            updateCheckAssetZone(event, check) {
                if (check.asset_check_id) {
                    this.check.check_asset_zones = [];
                    this.check.check_asset_zones = [parseInt(event.target.value, 10)];
                    this.check.asset_zone_id = event.target.value;
                }
            },
            updateSpareAssetZone(event, spare) {
                if (spare.asset_spare_id) {
                    this.spare.spare_asset_zones = [];
                    this.spare.spare_asset_zones = [parseInt(event.target.value, 10)];
                    this.spare.asset_zone_id = event.target.value;
                }
            },
            updateServiceAssetZone(event, service) {
                if (service.asset_service_id) {
                    this.service.service_asset_zones = [];
                    this.service.service_asset_zones = [parseInt(event.target.value, 10)];
                    this.service.asset_zone_id = event.target.value;
                }
            },
            updateVariableAssetZone(event, variable) {
                if (variable.asset_variable_id) {
                    this.variable.variable_asset_zones = [];
                    this.variable.variable_asset_zones = [parseInt(event.target.value, 10)];
                    this.variable.asset_zone_id = event.target.value;
                }
            },
            updateDataSourceAssetZone(event, datasource) {
                if (datasource.asset_data_source_id) {
                    this.datasource.data_source_asset_zones = [];
                    this.datasource.data_source_asset_zones = [parseInt(event.target.value, 10)];
                    this.datasource.asset_zone_id = event.target.value;
                }
            },
            toggleAssetZoneStatus(type) {
                // this.asset_zone_status_variables = false;
                // this.asset_zone_status_datasources = false;
                // this.asset_zone_status_spares = false;
                // this.asset_zone_status_checks = false;
                // this.asset_zone_status_services = false;
                // this.asset_zone_status_accessories=false;

                if (type === "variables") {
                    this.asset_zone_status_variables = !this.asset_zone_status_variables;
                    this.asset_zone_status_datasources = false;
                    this.asset_zone_status_spares = false;
                    this.asset_zone_status_checks = false;
                    this.asset_zone_status_services = false;
                    this.asset_zone_status_accessories = false;
                } else if (type === "datasources") {
                    this.asset_zone_status_datasources = !this.asset_zone_status_datasources;
                    this.asset_zone_status_variables = false;
                    this.asset_zone_status_spares = false;
                    this.asset_zone_status_checks = false;
                    this.asset_zone_status_services = false;
                    this.asset_zone_status_accessories = false;
                } else if (type === "spares") {
                    this.asset_zone_status_spares = !this.asset_zone_status_spares;
                    this.asset_zone_status_variables = false;
                    this.asset_zone_status_datasources = false;
                    this.asset_zone_status_checks = false;
                    this.asset_zone_status_services = false;
                    this.asset_zone_status_accessories = false;
                } else if (type === "checks") {
                    this.asset_zone_status_checks = !this.asset_zone_status_checks;
                    this.asset_zone_status_variables = false;
                    this.asset_zone_status_datasources = false;
                    this.asset_zone_status_spares = false;
                    this.asset_zone_status_services = false;
                    this.asset_zone_status_accessories = false;
                } else if (type === "services") {
                    this.asset_zone_status_services = !this.asset_zone_status_services;
                    this.asset_zone_status_variables = false;
                    this.asset_zone_status_datasources = false;
                    this.asset_zone_status_spares = false;
                    this.asset_zone_status_checks = false;
                    this.asset_zone_status_accessories = false;
                } else if (type === "accessories") {
                    this.asset_zone_status_accessories = !this.asset_zone_status_accessories;
                    this.asset_zone_status_variables = false;
                    this.asset_zone_status_datasources = false;
                    this.asset_zone_status_spares = false;
                    this.asset_zone_status_checks = false;
                    this.asset_zone_status_services = false;
                }
            },
            getColor(asset_parameter) {
                let color = "color:black";
                if ((asset_parameter.field_name = "Color")) {
                    color = "background-color:" + asset_parameter.asset_parameter_value?.field_value + "; color:white; padding:5px";
                }
                return color;
            },
            editSpare(spare) {
                // console.log('spare.asset_zone:----', spare.asset_zone);
                this.spare.spare_id = spare.spare_id;
                this.spare.asset_id = spare.asset_id;
                this.spare.asset_spare_id = spare.asset_spare_id;
                this.spare.spare_code = spare.spare_code;
                this.spare.spare_name = spare.spare_name;
                this.spare.spare_asset_zones = [];
                this.spare.spare_asset_zones.push(spare.asset_zone_id);
                this.spare.spare_asset_zones_obj = []
                this.spare.spare_asset_zones_obj.push({asset_id: spare.asset_zone.asset_id,
                    asset_zone_id: spare.asset_zone.asset_zone_id, status: spare.asset_zone.status,
                    zone_name: spare.asset_zone.zone_name, diameter: spare.asset_zone.diameter,
                    height: spare.asset_zone.height, asset_spares: spare.asset_zone.asset_spares

                });
                this.spare.spare_zone_read_only = true;
                this.spare.asset_zone_id = spare.asset_zone_id;
                this.spare.quantity = spare.quantity;
                this.spare.asset_spare_attributes = spare.asset_spare_attributes;
                this.spare.initial_spare_id = spare.spare_id;
                this.spare.initial_asset_spare_attributes = spare.asset_spare_attributes;
                // console.log('spare.asset_spare_attributes:----', spare?.asset_spare_attributes)
            },
            editCheck(check) {
                this.check.asset_id = check.asset_id;
                this.check.asset_check_id = check.asset_check_id;
                this.check.check_id = check.check_id;
                this.check.lcl = check.lcl;
                this.check.ucl = check.ucl;
                this.check.default_value = check.default_value;
                this.check.check_asset_zones = [];
                this.check.check_asset_zones.push(check.asset_zone_id);
                this.check.asset_zone_id = check.asset_zone_id;
                this.check.check_asset_zones_obj = [];
                this.check.check_asset_zones_obj.push({asset_id: check.asset_zone.asset_id,
                    asset_zone_id: check.asset_zone.asset_zone_id, status: check.asset_zone.status,
                    zone_name: check.asset_zone.zone_name, diameter: check.asset_zone.diameter,
                    height: check.asset_zone.height, asset_spares: check.asset_zone.asset_spares

                });
                this.check.check_zone_read_only = true;
            },
            editService(service) {
                this.service.service_id = service.service_id;
                this.service.asset_id = service.asset_id;
                this.service.asset_service_id = service.asset_service_id;
                this.service.service_code = service.service_code;
                this.service.service_name = service.service_name;
                this.service.service_asset_zones = [];
                this.service.service_asset_zones.push(service.asset_zone_id);
                this.service.asset_zone_id = service.asset_zone_id;
                this.service.asset_service_attributes = service.asset_service_attributes;
                this.service.initial_service_id = service.service_id;
                this.service.initial_asset_service_attributes = service.asset_service_attributes;

                this.service.service_asset_zones_obj = []
                this.service.service_asset_zones_obj.push({asset_id: service.asset_zone.asset_id,
                    asset_zone_id: service.asset_zone.asset_zone_id, status: service.asset_zone.status,
                    zone_name: service.asset_zone.zone_name, diameter: service.asset_zone.diameter,
                    height: service.asset_zone.height, asset_spares: service.asset_zone.asset_spares

                });
                this.service.service_zone_read_only = true;
            },
            editVariable(variable) {
                this.variable.variable_id = variable.variable_id;
                this.variable.asset_id = variable.asset_id;
                this.variable.asset_variable_id = variable.asset_variable_id;
                this.variable.variable_code = variable.variable_code;
                this.variable.variable_name = variable.variable_name;
                this.variable.variable_asset_zones = [];
                this.variable.variable_asset_zones.push(variable.asset_zone_id);
                this.variable.asset_zone_id = variable.asset_zone_id;
                this.variable.asset_variable_attributes = variable.asset_variable_attributes;

                this.variable.initial_variable_id = variable.variable_id;
                this.variable.initial_asset_variable_attributes = variable.asset_variable_attributes;

                this.variable.variable_asset_zones_obj = []
                this.variable.variable_asset_zones_obj.push({asset_id: variable.asset_zone.asset_id,
                    asset_zone_id: variable.asset_zone.asset_zone_id, status: variable.asset_zone.status,
                    zone_name: variable.asset_zone.zone_name, diameter: variable.asset_zone.diameter,
                    height: variable.asset_zone.height, asset_spares: variable.asset_zone.asset_spares

                });
                this.variable.variable_zone_read_only = true;
            },
            editDataSource(datasource) {
                this.datasource.data_source_id = datasource.data_source_id;
                this.datasource.asset_id = datasource.asset_id;
                this.datasource.asset_data_source_id = datasource.asset_data_source_id;
                this.datasource.data_source_code = datasource.data_source_code;
                this.datasource.data_source_name = datasource.data_source_name;
                this.datasource.data_source_asset_zones = [];
                this.datasource.data_source_asset_zones.push(datasource.asset_zone_id);
                this.datasource.asset_zone_id = datasource.asset_zone_id;
                this.datasource.script = datasource.script;
                this.datasource.asset_datasource_attributes = datasource.asset_datasource_attributes;
                this.datasource.initial_data_source_id = datasource.data_source_id;
                this.datasource.initial_asset_datasource_attributes = datasource.asset_datasource_attributes;

                this.datasource.data_source_asset_zones_obj = []
                this.datasource.data_source_asset_zones_obj.push({asset_id: datasource.asset_zone.asset_id,
                    asset_zone_id: datasource.asset_zone.asset_zone_id, status: datasource.asset_zone.status,
                    zone_name: datasource.asset_zone.zone_name, diameter: datasource.asset_zone.diameter,
                    height: datasource.asset_zone.height, asset_spares: datasource.asset_zone.asset_spares

                });
                this.datasource.data_source_zone_read_only = true;
            },

            // getChecks() {
            //     let vm = this;
            //     let loader = vm.$loading.show();
            //     vm.$store
            //         .dispatch("post", { uri: "getAssetTypeChecks" ,data: vm.asset})
            //         .then((response) => {
            //             loader.hide();
            //             vm.checks = response.data.data;
            //             vm.getAssetChecks();
            //         })
            //         .catch(function (error) {
            //             loader.hide();
            //             vm.errors = error.response.data.errors;
            //             vm.$store.dispatch("error", error.response.data.message);
            //         });
            // },

            // getVariables() {
            //     let vm = this;
            //     let loader = vm.$loading.show();
            //     vm.$store
            //         .dispatch("post", { uri: "getAssetTypeVariables" ,data: vm.asset })
            //         .then((response) => {
            //             loader.hide();
            //             vm.variables = response.data.data;
            //             vm.getAssetVariables();
            //         })
            //         .catch(function (error) {
            //             loader.hide();
            //             vm.errors = error.response.data.errors;
            //             vm.$store.dispatch("error", error.response.data.message);
            //         });
            // },
            // getDataSources() {
            //     let vm = this;
            //     let loader = vm.$loading.show();
            //     vm.$store
            //         .dispatch("post", { uri: "getAssetTypeDataSources" ,data: vm.asset })
            //         .then((response) => {
            //             loader.hide();
            //             vm.data_sources = response.data.data;
            //             vm.getAssetDataSources();
            //         })
            //         .catch(function (error) {
            //             loader.hide();
            //             vm.errors = error.response.data.errors;
            //             vm.$store.dispatch("error", error.response.data.message);
            //         });
            // },
            // getCheck() {
            //     let vm = this;
            //     let loader = vm.$loading.show();
            //     vm.$store
            //         .dispatch("post", { uri: "getCheck" ,data: vm.check})
            //         .then((response) => {
            //             loader.hide();
            //             vm.check = response.data.data;
            //             vm.check.lcl = response.data.data.lcl;
            //             vm.check.ucl = response.data.data.uclcl;
            //             vm.check.default_value = response.data.data.default_value;
            //         })
            //         .catch(function (error) {
            //             loader.hide();
            //             vm.errors = error.response.data.errors;
            //             vm.$store.dispatch("error", error.response.data.message);
            //         });
            // },
            // getServices() {
            //     let vm = this;
            //     let loader = vm.$loading.show();
            //     vm.$store
            //         .dispatch("post", { uri: "getAssetTypeServices" ,data: vm.asset })
            //         .then((response) => {
            //             loader.hide();
            //             vm.services = response.data.data;
            //             vm.getAssetServices();
            //         })
            //         .catch(function (error) {
            //             loader.hide();
            //             vm.errors = error.response.data.errors;
            //             vm.$store.dispatch("error", error.response.data.message);
            //         });
            // },

            getAssetSpares() {
                let vm = this;
                vm.meta.asset_id = vm.asset.asset_id;
                vm.meta.asset_type_id = vm.asset.asset_type_id;
                vm.$store
                    .dispatch("post", { uri: "paginateAssetTemplateSpares", data: vm.meta })
                    .then((response) => {
                        vm.asset_spares = response.data.paginate_spares;
                        vm.spares = response?.data?.spares;
                        vm.meta.totalRows = response.data.meta.total;
                        // vm.meta.from = response.data.meta.from;
                        vm.meta.lastPage = response.data.meta.last_page;
                        vm.meta.maxPage = vm.meta.lastPage >= 3 ? 3 : vm.meta.lastPage;
                        // vm.getChecks();
                        if (vm.initial_status) {
                            vm.getAssetChecks();
                        }
                    })
                    .catch(function (error) {
                        console.log("errors", error);
                        // vm.errors = error.response.data.errors;
                        // vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getAssetChecks() {
                let vm = this;
                vm.check_meta.asset_id = vm.asset.asset_id;
                vm.check_meta.asset_type_id = vm.asset.asset_type_id;
                vm.check_meta.department_id = vm.asset.department_id;
                vm.$store
                    .dispatch("post", { uri: "paginateAssetChecks", data: vm.check_meta })
                    .then((response) => {
                        console.log("checks response", response);
                        vm.checks = response.data.checks;
                        vm.asset_checks = response.data.paginate_checks;
                        vm.check_meta.totalRows = response.data.meta.total;
                        // vm.check_meta.from = response.data.meta.from;
                        vm.check_meta.lastPage = response.data.meta.last_page;
                        vm.check_meta.maxPage = vm.check_meta.lastPage >= 3 ? 3 : vm.check_meta.lastPage;
                        // vm.getServices();
                        if (vm.initial_status) {
                            vm.getAssetServices();
                        }
                    })
                    .catch(function (error) {
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getAssetServices() {
                let vm = this;
                vm.check_meta_service.asset_id = vm.asset.asset_id;
                vm.check_meta_service.asset_type_id = vm.asset.asset_type_id;
                vm.$store
                    .dispatch("post", { uri: "paginateAssetServices", data: vm.check_meta_service })
                    .then((response) => {
                        console.log("service paginate", response.data);
                        vm.services = response.data.services;
                        vm.asset_services = response.data.paginate_services;
                        vm.check_meta_service.totalRows = response.data.meta.total;
                        // vm.check_meta_service.from = response.data.meta.from;
                        vm.check_meta_service.lastPage = response.data.meta.last_page;
                        vm.check_meta_service.maxPage = vm.check_meta_service.lastPage >= 3 ? 3 : vm.check_meta_service.lastPage;
                        // vm.getVariables();
                        if (vm.initial_status) {
                            vm.getAssetVariables();
                        }
                    })
                    .catch(function (error) {
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getAssetVariables() {
                let vm = this;
                vm.variable_meta_service.asset_id = vm.asset.asset_id;
                vm.variable_meta_service.asset_type_id = vm.asset.asset_type_id;
                vm.$store
                    .dispatch("post", { uri: "paginateAssetVariables", data: vm.variable_meta_service })
                    .then((response) => {
                        console.log("pagevariable", response.data);
                        vm.variables = response.data.variables;
                        vm.asset_variables = response.data.paginate_variables;
                        vm.variable_meta_service.totalRows = response.data.meta.total;
                        // vm.variable_meta_service.from = response.data.meta.from;
                        vm.variable_meta_service.lastPage = response.data.meta.last_page;
                        vm.variable_meta_service.maxPage = vm.variable_meta_service.lastPage >= 3 ? 3 : vm.variable_meta_service.lastPage;
                        // vm.getDataSources();
                        if (vm.initial_status) {
                            vm.getAssetDataSources();
                        }
                    })
                    .catch(function (error) {
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getAssetDataSources() {
                let vm = this;
                vm.datasource_meta_service.asset_id = vm.asset.asset_id;
                vm.datasource_meta_service.asset_type_id = vm.asset.asset_type_id;
                vm.$store
                    .dispatch("post", { uri: "paginateAssetDataSources", data: vm.datasource_meta_service })
                    .then((response) => {
                        vm.data_sources = response.data.data_sources;
                        vm.asset_data_sources = response.data.paginate_data_sources;
                        vm.datasource_meta_service.totalRows = response.data.meta.total;
                        // vm.datasource_meta_service.from = response.data.meta.from;
                        vm.datasource_meta_service.lastPage = response.data.meta.last_page;
                        vm.datasource_meta_service.maxPage = vm.datasource_meta_service.lastPage >= 3 ? 3 : vm.meta.lastPage;
                        if (vm.initial_status) {
                            vm.getAssetAccessories();
                        }
                    })
                    .catch(function (error) {
                        console.log("error", error);
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            downloadQR() {
                let vm = this;
                window.open(vm.$store.state.apiUrl + "downloadAssetQRCode?template_code=" + vm.asset.template_code);
            },

            validateFields(validation_type) {
                this.isValid = true;
                this.errors = {};

                if (validation_type == "Spares") {
                    for (const field of Object.values(this.spare.asset_spare_attributes)) {
                        if (field.spare_attributes[0].is_required && !field.field_value) {
                            if (field.spare_attributes[0].field_type === "Color") {
                                // Set default color if not provided
                                field.field_value = "#000000"; // Default to black
                            } else {
                                this.errors[field.spare_attributes[0].display_name] = [`${field.spare_attributes[0].display_name} is required`];
                                this.isValid = false;
                            }
                        }
                    }
                }
                if (validation_type == "Services") {
                    for (const field of Object.values(this.service.asset_service_attributes)) {
                        if (field.service_attributes[0].is_required && !field.field_value) {
                            if (field.service_attributes[0].field_type === "Color") {
                                // Set default color if not provided
                                field.field_value = "#000000"; // Default to black
                            } else {
                                this.errors[field.service_attributes[0].display_name] = [`${field.service_attributes[0].display_name} is required`];
                                this.isValid = false;
                            }
                        }
                    }
                }
                if (validation_type == "Variables") {
                    for (const field of Object.values(this.variable.asset_variable_attributes)) {
                        if (field.variable_attributes[0].is_required && !field.field_value) {
                            if (field.variable_attributes[0].field_type === "Color") {
                                // Set default color if not provided
                                field.field_value = "#000000"; // Default to black
                            } else {
                                this.errors[field.variable_attributes[0].display_name] = [`${field.variable_attributes[0].display_name} is required`];
                                this.isValid = false;
                            }
                        }
                    }
                }
                if (validation_type == "Data Sources") {
                    for (const field of Object.values(this.datasource.asset_datasource_attributes)) {
                        if (field.data_source_attributes[0].is_required && !field.field_value) {
                            if (field.data_source_attributes[0].field_type === "Color") {
                                // Set default color if not provided
                                field.field_value = "#000000"; // Default to black
                            } else {
                                this.errors[field.data_source_attributes[0].display_name] = [`${field.data_source_attributes[0].display_name} is required`];
                                this.isValid = false;
                            }
                        }
                    }
                }
            },

            addSpare() {
                let vm = this;
                vm.spare.asset_id = vm.asset.asset_id;
                vm.spare.spare_asset_zones = []
                vm.spare.spare_asset_zones_obj.map(function(ele){
                    vm.spare.spare_asset_zones.push(ele.asset_zone_id)
                })

                let loader = vm.$loading.show();
                vm.validateFields();
                if (vm.isValid) {
                    vm.$store
                        .dispatch("post", { uri: "addAssetSpare", data: vm.spare })
                        .then((response) => {
                            loader.hide();
                            vm.$store.dispatch("success", response.data.message);
                            vm.spare.spare_id = "";
                            vm.spare.asset_zone_id = [];
                            vm.spare.spare_asset_zones = [];
                            vm.spare.spare_asset_zones_obj = [];
                            vm.spare.quantity = "";
                            vm.spare.asset_spare_attributes = [];
                            vm.spare.initial_spare_id = "";
                            vm.spare.initial_asset_spare_attributes = [];
                            vm.spare.deleted_asset_spare_values = [];
                            vm.asset_zone_status_spares = false;
                            vm.errors = [];
                            vm.spare.spare_zone_read_only = false;
                            vm.getAssetSpares();
                        })
                        .catch(function (error) {
                            loader.hide();
                            vm.errors = error.response.data.errors;
                            vm.$store.dispatch("error", error.response.data.message);
                        });
                }
            },
            updateSpare() {
                let vm = this;
                let loader = vm.$loading.show();

                if (vm.spare.spare_id != vm.spare.initial_spare_id) {
                    vm.spare.initial_asset_spare_attributes.map(function (ele) {
                        vm.spare.deleted_asset_spare_values.push(ele.asset_spare_value_id);
                    });
                }
                let uri = { uri: "updateAssetSpare", data: vm.spare };
                vm.$store
                    .dispatch("post", uri)
                    .then(function (response) {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.spare.spare_id = "";
                        vm.spare.asset_zone_id = "";
                        vm.spare.asset_spare_id = "";
                        vm.spare.spare_asset_zones_obj = [];
                        vm.spare.spare_asset_zones = [];
                        vm.spare.quantity = "";
                        vm.spare.asset_spare_attributes = [];
                        vm.spare.initial_spare_id = "";
                        vm.spare.initial_asset_spare_attributes = [];
                        vm.spare.deleted_asset_spare_values = [];
                        vm.asset_zone_status_spares = false;
                        vm.errors = [];
                        vm.spare.spare_zone_read_only = false;
                        vm.getAssetSpares();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response?.data?.errors;
                        vm.$store.dispatch("error", error?.response?.data?.message);
                    });
            },
            addCheck() {
                let vm = this;
                vm.check.asset_id = vm.asset.asset_id;
                vm.check.check_asset_zones = []
                vm.check.check_asset_zones_obj.map(function(ele){
                    vm.check.check_asset_zones.push(ele.asset_zone_id)
                })
                let loader = vm.$loading.show();
                vm.$store.dispatch("post", { uri: "addAssetCheck", data: vm.check })
                .then((response) => {
                    loader.hide();
                    vm.$store.dispatch("success", response.data.message);
                    vm.check.check_id = "";
                    vm.check.lcl = "";
                    vm.check.ucl = "";
                    vm.check.default_value = "";
                    vm.errors = [];
                    vm.check.asset_zone_id = [];
                    vm.check.check_asset_zones_obj = [];
                    vm.check.check_asset_zones = [];
                    vm.asset_zone_status_checks = false;
                    vm.check.check_zone_read_only = false;
                    vm.getAssetChecks();
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
            },
            updateCheck() {
                let vm = this;
                let loader = vm.$loading.show();
                let uri = { uri: "updateAssetCheck", data: vm.check };
                vm.$store.dispatch("post", uri)
                .then(function (response) {
                    loader.hide();
                    vm.$store.dispatch("success", response.data.message);
                    vm.check.check_id = "";
                    vm.check.lcl = "";
                    vm.check.ucl = "";
                    vm.check.default_value = "";
                    vm.check.asset_check_id = "";
                    vm.check.check_asset_zones_obj = [];
                    vm.check.check_asset_zones = [];
                    vm.asset_zone_status_checks = false;
                    vm.check.check_zone_read_only = false;
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
                vm.service.service_asset_zones = []
                vm.service.service_asset_zones_obj.map(function(ele){
                    vm.service.service_asset_zones.push(ele.asset_zone_id)
                })
                let loader = vm.$loading.show();
                vm.$store.dispatch("post", { uri: "addAssetService", data: vm.service })
                .then((response) => {
                    loader.hide();
                    vm.$store.dispatch("success", response.data.message);
                    vm.service.service_id = "";
                    vm.service.asset_zone_id = "";
                    vm.service.service_asset_zones = [];
                    vm.service.asset_service_attributes = [];

                    vm.service.service_asset_zones_obj = [];
                    vm.service.service_zone_read_only = false;

                    vm.service.initial_service_id = "";
                    vm.service.initial_asset_service_attributes = [];
                    vm.service.deleted_asset_service_values = [];

                    vm.asset_zone_status_services = false;
                    vm.errors = [];
                    vm.getAssetServices();
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
            },
            updateService() {
                let vm = this;
                let loader = vm.$loading.show();
                if (vm.service.service_id != vm.service.initial_service_id) {
                    vm.service.initial_asset_service_attributes.map(function (ele) {
                        vm.service.deleted_asset_service_values.push(ele.asset_service_value_id);
                    });
                }

                let uri = { uri: "updateAssetService", data: vm.service };
                vm.$store.dispatch("post", uri)
                .then(function (response) {
                    loader.hide();
                    vm.$store.dispatch("success", response.data.message);
                    vm.service.service_id = "";
                    vm.service.asset_zone_id = "";
                    vm.service.asset_service_id = "";
                    vm.service.service_asset_zones = [];
                    vm.service.asset_service_attributes = [];

                    vm.service.service_asset_zones_obj = [];
                    vm.service.service_zone_read_only = false;

                    vm.service.initial_service_id = "";
                    vm.service.initial_asset_service_attributes = [];
                    vm.service.deleted_asset_service_values = [];

                    vm.asset_zone_status_services = false;
                    vm.errors = [];
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
                vm.variable.variable_asset_zones = []
                vm.variable.variable_asset_zones_obj.map(function(ele){
                    vm.variable.variable_asset_zones.push(ele.asset_zone_id)
                })
                let loader = vm.$loading.show();
                vm.$store.dispatch("post", { uri: "addAssetVariable", data: vm.variable })
                .then((response) => {
                    loader.hide();
                    vm.$store.dispatch("success", response.data.message);
                    vm.variable.variable_id = "";
                    vm.variable.asset_zone_id = "";
                    vm.variable.variable_asset_zones = [];
                    vm.variable.asset_variable_attributes = [];

                    vm.variable.initial_variable_id = "";
                    vm.variable.initial_asset_variable_attributes = [];
                    vm.variable.deleted_asset_variable_values = [];

                    vm.asset_zone_status_variables = false;

                    vm.variable.variable_asset_zones_obj = [];
                    vm.variable.variable_zone_read_only = false;

                    vm.errors = [];
                    vm.getAssetVariables();
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
            },
            updateVariable() {
                let vm = this;
                let loader = vm.$loading.show();

                if (vm.variable.variable_id != vm.variable.initial_variable_id) {
                    vm.variable.initial_asset_variable_attributes.map(function (ele) {
                        vm.variable.deleted_asset_variable_values.push(ele.asset_variable_value_id);
                    });
                }

                let uri = { uri: "updateAssetVariable", data: vm.variable };
                vm.$store.dispatch("post", uri)
                .then(function (response) {
                    loader.hide();
                    vm.$store.dispatch("success", response.data.message);
                    vm.variable.variable_id = "";
                    vm.variable.asset_zone_id = "";
                    vm.variable.asset_variable_id = "";
                    vm.variable.variable_asset_zones = [];
                    vm.variable.asset_variable_attributes = [];
                    vm.variable.initial_variable_id = "";
                    vm.variable.initial_asset_variable_attributes = [];
                    vm.variable.deleted_asset_variable_values = [];
                    vm.asset_zone_status_variables = false;

                    vm.variable.variable_asset_zones_obj = [];
                    vm.variable.variable_zone_read_only = false;

                    vm.errors = [];
                    vm.getAssetVariables();
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response?.data?.errors;
                    vm.$store.dispatch("error", error?.response?.data?.message);
                });
            },
            addDataSource() {
                let vm = this;
                vm.datasource.asset_id = vm.asset.asset_id;
                vm.datasource.data_source_asset_zones = []
                vm.datasource.data_source_asset_zones_obj.map(function(ele){
                    vm.datasource.data_source_asset_zones.push(ele.asset_zone_id)
                })
                let loader = vm.$loading.show();
                vm.$store.dispatch("post", { uri: "addAssetDataSource", data: vm.datasource })
                .then((response) => {
                    loader.hide();
                    vm.$store.dispatch("success", response.data.message);
                    vm.datasource.data_source_id = "";
                    vm.errors = [];
                    vm.datasource.asset_zone_id = "";
                    vm.datasource.data_source_asset_zones = [];
                    vm.datasource.script = "";
                    vm.datasource.asset_datasource_attributes = [];
                    vm.datasource.initial_data_source_id = "";
                    vm.datasource.initial_asset_datasource_attributes = [];
                    vm.datasource.deleted_asset_datasource_values = [];
                    vm.asset_zone_status_datasources = false;

                    vm.datasource.data_source_asset_zones_obj = [];
                    vm.datasource.data_source_zone_read_only = false;

                    vm.getAssetDataSources();
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
            },
            updateDataSource() {
                let vm = this;
                let loader = vm.$loading.show();

                if (vm.datasource.data_source_id != vm.datasource.initial_data_source_id) {
                    vm.datasource.initial_asset_datasource_attributes.map(function (ele) {
                        vm.datasource.deleted_asset_datasource_values.push(ele.asset_data_source_value_id);
                    });
                }

                let uri = { uri: "updateAssetDataSource", data: vm.datasource };
                vm.$store.dispatch("post", uri)
                .then(function (response) {
                    loader.hide();
                    vm.$store.dispatch("success", response.data.message);
                    vm.datasource.data_source_id = "";
                    vm.datasource.asset_zone_id = "";
                    vm.datasource.asset_data_source_id = "";
                    vm.datasource.data_source_asset_zones = [];
                    vm.datasource.script = "";
                    vm.datasource.asset_datasource_attributes = [];
                    vm.datasource.initial_data_source_id = "";
                    vm.datasource.initial_asset_datasource_attributes = [];
                    vm.datasource.deleted_asset_datasource_values = [];
                    vm.asset_zone_status_datasources = false;
                    vm.datasource.data_source_asset_zones_obj = [];
                    vm.datasource.data_source_zone_read_only = false;
                    vm.getAssetDataSources();
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response?.data?.errors;
                    vm.$store.dispatch("error", error?.response?.data?.message);
                });
            },
            deleteSpare(spare) {
                const confirmDelete = confirm("Are you sure you want to delete it ?");

                if (confirmDelete) {
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
                }
            },
            deleteCheck(check) {
                const confirmDelete = confirm("Are you sure you want to delete it ?");
                if (confirmDelete) {
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
                }
            },
            deleteService(service) {
                const confirmDelete = confirm("Are you sure you want to delete it ?");
                if (confirmDelete) {
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
                }
            },

            deleteVariable(variable) {
                const confirmDelete = confirm("Are you sure you want to delete it ?");
                if (confirmDelete) {
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
                }
            },
            deleteDataSource(datasource) {
                const confirmDelete = confirm("Are you sure you want to delete it ?");
                if (confirmDelete) {
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
                }
            },
            deleteAccessory(accessory) {
                const confirmDelete = confirm("Are you sure you want to delete it ?");
                if (confirmDelete) {
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
                }
            },

            getAssetAccessories() {
                let vm = this;
                vm.accessory_meta_service.asset_id = vm.asset.asset_id;
                vm.accessory_meta_service.asset_type_id = vm.asset.asset_type_id;
                // let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "paginateAssetAccessories", data: vm.accessory_meta_service })
                    .then((response) => {
                        // loader.hide();
                        console.log("access reposne", response.data);
                        vm.initial_status = false;
                        vm.accessory_types = response.data.accessory_types;
                        vm.asset_accessories = response.data.paginate_accessories;
                        vm.accessory_meta_service.totalRows = response.data.meta.total;
                        // vm.accessory_meta_service.from = response.data.meta.from;
                        vm.accessory_meta_service.lastPage = response.data.meta.last_page;
                        vm.accessory_meta_service.maxPage = vm.accessory_meta_service.lastPage >= 3 ? 3 : vm.accessory_meta_service.lastPage;
                    })
                    .catch(function (error) {
                        // loader.hide();
                        console.log("errors accessories--", error);
                        // vm.errors = error.response.data.errors;
                        // vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            addAccessory() {
                let vm = this;
                vm.accessory.asset_id = vm.asset.asset_id;
                let loader = vm.$loading.show();
                vm.accessory.accessory_asset_zones = []
                vm.accessory.accessory_asset_zones_obj.map(function(ele){
                    vm.accessory.accessory_asset_zones.push(ele.asset_zone_id)
                })

                const data = new FormData();
                data.append("asset_id", vm.accessory.asset_id);
                data.append("accessory_type_id", vm.accessory.accessory_type_id);
                data.append("accessory_name", vm.accessory.accessory_name);
                vm.accessory.accessory_asset_zones.forEach(zone => {
                    data.append('accessory_asset_zones[]', zone);
                });
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
                        vm.accessory.accessory_name = "";
                        vm.accessory.accessory_asset_zones = [];
                        (vm.accessory.accessory_type_id = ""), (vm.$refs.attachment.value = "");
                        vm.errors = [];
                        vm.asset_zone_status_accessories = false;

                        vm.accessory.accessory_asset_zones_obj = []
                        vm.accessory.accessory_zone_read_only = false;

                        vm.getAssetAccessories();
                    })

                    // vm.$store
                    //     .dispatch("post", { uri: "addAssetAccessory", data: vm.accessory })
                    //     .then((response) => {
                    //         loader.hide();
                    //         vm.$store.dispatch("success", response.data.message);
                    //         vm.accessory.accessory_id = "";
                    //         vm.errors = [];
                    //         vm.accessory.accessory_asset_zone_id=[];
                    //         vm.getAssetAccessories();
                    //     })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            onPageChange(page, from) {
                if (from == "Spares") {
                    this.meta.page = page;
                    this.getAssetSpares();
                }
                if (from == "Checks") {
                    this.check_meta.page = page;
                    this.getAssetChecks();
                }
                if (from == "Services") {
                    this.check_meta_service.page = page;
                    this.getAssetServices();
                }
                if (from == "Variables") {
                    this.variable_meta_service.page = page;
                    this.getAssetVariables();
                }
                if (from == "Data Sources") {
                    this.datasource_meta_service.page = page;
                    this.getAssetDataSources();
                }
                if (from == "Accessories") {
                    this.accessory_meta_service.page = page;
                    this.getAssetAccessories();
                }
            },
            sort(field, from) {
                if (from == "Spares") {
                    this.meta.keyword = field;
                    this.meta.order_by = this.meta.order_by == "asc" ? "desc" : "asc";
                    this.getAssetSpares();
                }
                if (from == "Checks") {
                    this.check_meta.keyword = field;
                    this.check_meta.order_by = this.check_meta.order_by == "asc" ? "desc" : "asc";
                    this.getAssetChecks();
                }
                if (from == "Services") {
                    this.check_meta_service.keyword = field;
                    this.check_meta_service.order_by = this.check_meta_service.order_by == "asc" ? "desc" : "asc";
                    this.getAssetServices();
                }
                if (from == "Variables") {
                    this.variable_meta_service.keyword = field;
                    this.variable_meta_service.order_by = this.variable_meta_service.order_by == "asc" ? "desc" : "asc";
                    this.getAssetVariables();
                }
                if (from == "Data Sources") {
                    this.datasource_meta_service.keyword = field;
                    this.datasource_meta_service.order_by = this.datasource_meta_service.order_by == "asc" ? "desc" : "asc";
                    this.getAssetDataSources();
                }
                if (from == "Accessories") {
                    this.accessory_meta_service.keyword = field;
                    this.accessory_meta_service.order_by = this.accessory_meta_service.order_by == "asc" ? "desc" : "asc";
                    this.getAssetAccessories();
                }
            },

            onPerPageChange(from) {
                if (from == "Spares") {
                    this.meta.page = 1;
                    this.getAssetSpares();
                }
                if (from == "Checks") {
                    this.check_meta.page = 1;
                    this.getAssetChecks();
                }
                if (from == "Services") {
                    this.check_meta_service.page = 1;
                    this.getAssetServices();
                }
                if (from == "Variables") {
                    this.variable_meta_service.page = 1;
                    this.getAssetVariables();
                }
                if (from == "Data Sources") {
                    this.datasource_meta_service.page = 1;
                    this.getAssetDataSources();
                }
                if (from == "Accessories") {
                    this.accessory_meta_service.page = 1;
                    this.getAssetAccessories();
                }
            },

            getSpareAttribute(spare_id) {
                let vm = this;
                let loader = vm.$loading.show();
                vm.spare.asset_spare_attributes = [];
                vm.$store
                    .dispatch("post", { uri: "assetSpareAttributeValues", data: { spare_id: spare_id } })
                    .then((response) => {
                        loader.hide();
                        vm.spare.asset_spare_attributes = response.data.data;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            getServiceAttribute(service_id) {
                let vm = this;
                let loader = vm.$loading.show();
                vm.service.asset_service_attributes = [];
                vm.$store
                    .dispatch("post", { uri: "assetServiceAttributeValues", data: { service_id: service_id } })
                    .then((response) => {
                        loader.hide();
                        vm.service.asset_service_attributes = response.data.data;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getVariableAttribute(variable_id) {
                let vm = this;
                let loader = vm.$loading.show();
                vm.variable.asset_variable_attributes = [];
                vm.$store
                    .dispatch("post", { uri: "assetVariableAttributeValues", data: { variable_id: variable_id } })
                    .then((response) => {
                        loader.hide();
                        vm.variable.asset_variable_attributes = response.data.data;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getDatasourceAttribute(data_source_id) {
                let vm = this;
                let loader = vm.$loading.show();
                vm.datasource.asset_datasource_attributes = [];
                vm.$store
                    .dispatch("post", { uri: "assetDataSourceAttributeValues", data: { data_source_id: data_source_id } })
                    .then((response) => {
                        loader.hide();
                        // console.log('getSpareAttribute:----', response.data.data)
                        vm.datasource.asset_datasource_attributes = response.data.data;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            viewAsset(asset, from) {
                console.log('asset:-----', asset)
                  console.log('from:-----', from)
                // this.$store.commit("setAssets", asset)
                // this.$router.push({ name: 'Assets.DynamicAssets', params: { assets: from } });


                // this.asset_type = from;
                // this.assets1 = asset;



                // try starts
                this.$store.commit("setAssets", asset)
                this.asset_type = from;
                this.assets1 = this.$store.getters.assets;

                // try ends
            },
            discard() {
                let vm = this;
                // vm.asset = {};
                vm.spare.spare_id = "";
                vm.spare.spare_asset_zones = [];
                vm.spare.quantity = "";
                vm.spare.asset_spare_attributes = [];
            },
        },
    };
</script>
<style scoped>
.list_style_none{
    list-style: none;
}
    .department-badge {
        display: inline-block;
        max-width: 200px;
        overflow: hidden;
        white-space: wrap;
    }
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
