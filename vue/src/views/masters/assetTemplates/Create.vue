<template>
    <div class="">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div>
                <ol class="breadcrumb fs-sm mb-1">
                    <li class="breadcrumb-item" aria-current="page">
                        <router-link to="/dashboard">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <router-link to="/asset_templates">Asset Templates</router-link>
                    </li>
                    <li class="breadcrumb-item" aria-current="page" v-if="status">New Asset Template</li>
                    <li class="breadcrumb-item active" aria-current="page" v-else>Update Asset Template</li>
                </ol>
                <h4 class="main-title mb-0">Asset Template</h4>
            </div>
            <router-link to="/asset_templates" type="submit" class="btn btn-primary" style="float: right;"><i class="ri-list-check"></i> ASSET TEMPLATES</router-link>
        </div>
        <div class="row">
            <div class="col-12">
                <form @submit.prevent="submitForm()">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">Add Asset Template</h6>
                            <h6 class="card-title" v-else>Update Asset Template</h6>
                        </div>
                        <div class="card-body">
                            <div class="card mb-3">
                                <div class="card-header d-flex justify-content-between">
                                    <h6 class="card-title">Asset Template Details</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <div class="d-flex justify-content-between">
                                                <div><label class="form-label">Asset Type</label><span class="text-danger"> *</span></div>
                                                <a type="button" class="text-danger me-2" @click="reset()"><i class="ri-close-line fs-20 lh-1"></i></a>
                                            </div>
                                            <!-- <label class="form-label">Asset Type</label><span class="text-danger"> *</span> -->
                                            <search
                                                :class="{ 'is-invalid': errors?.asset_type_id }"
                                                :customClass="{ 'is-invalid': errors?.asset_type_id }"
                                                :initialize="asset?.asset_type_id"
                                                id="asset_type_id"
                                                label="asset_type_name"
                                                label2="asset_type_code"
                                                placeholder="Select Asset Type"
                                                :data="asset_attributes"
                                                @input="asset_type => asset.asset_type_id = asset_type"
                                                @selectsearch="getAssetType(asset.asset_type_id)"
                                            >
                                            </search>
                                            <span v-if="errors?.asset_type_id" class="invalid-feedback">{{ errors?.asset_type_id[0] }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Template Code</label><span class="text-danger"> *</span>
                                            <input type="text" placeholder="Enter Template Code" class="form-control" :class="{ 'is-invalid': errors?.template_code }" v-model="asset.template_code" />
                                            <span v-if="errors?.template_code" class="invalid-feedback">Template Code is required</span>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Template Name</label><span class="text-danger"> *</span>
                                            <input type="text" placeholder="Enter Template Name" class="form-control" :class="{ 'is-invalid': errors?.template_name }" v-model="asset.template_name" />
                                            <span v-if="errors?.template_name" class="invalid-feedback">Template Name is required</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header d-flex justify-content-between">
                                    <h6 class="card-title">Lineage Parameters</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <label class="form-label">Department</label>
                                            <!-- <select class="form-control" :class="{ 'is-invalid': errors?.department_id }" v-model="asset.department_id">
                                                <option value="">Select Department</option>
                                                <option v-for="department, key in departments" :key="key" :value="department?.department_id">{{ department?.department_name }} </option>
                                            </select>
                                            <span v-if="errors?.department_id" class="invalid-feedback">{{ errors.department_id[0] }}</span> -->

                                            <!-- <div class="dropdown" @click="toggleDepartmentStatus">
                                                <div class="overselect"></div>
                                                <select class="form-control form-control" :class="{'is-invalid':errors?.department_id}">
                                                    <option value="">Select Department</option>
                                                </select>
                                                <span v-if="errors?.department_id" class="invalid-feedback">{{ errors.department_id[0] }}</span>
                                            </div>
                                            <div class="multiselect" v-if="showDepartment">
                                                <ul>
                                                    <li v-for="department, key in departments"  :key="key">
                                                        <input type="checkbox" :value="department?.department_id" v-model="asset.asset_departments" style="padding: 2px;" @click="updateDepartemnts($event, asset)" />
                                                        <label style="margin-left: 5px;">{{ department?.department_name }}</label>
                                                    </li>
                                                </ul>
                                            </div> -->

                                            <MultiSelect
                                                v-model="asset.asset_departments_obj"
                                                filter
                                                optionLabel="department_name"
                                                :options="departments"
                                                placeholder="Select Department"
                                                :maxSelectedLabels="3"
                                                style="width: 100%; height: 37px;"
                                                :style="errors?.department_id ? error_style : ''"
                                            />
                                            <span v-if="errors?.department_id" class="invalid-feedback">{{ errors.department_id[0] }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Section</label>
                                            <select class="form-control" :class="{ 'is-invalid': errors?.section_id }" v-model="asset.section_id">
                                                <option value="">Select Section</option>
                                                <option v-for="section, key in sections" :key="key" :value="section?.section_id">{{ section?.section_name }}</option>
                                            </select>
                                            <span v-if="errors?.section_id" class="invalid-feedback">{{ errors.section_id[0] }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Functional</label>
                                            <select class="form-control" :class="{ 'is-invalid': errors?.functional_id }" v-model="asset.functional_id">
                                                <option value="">Select Functional</option>
                                                <option v-for="functional, key in functionals" :key="key" :value="functional?.functional_id">{{ functional?.functional_name }} </option>
                                            </select>
                                            <span v-if="errors?.functional_id" class="invalid-feedback">{{ errors.functional_id[0] }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Shop</label><span class="text-danger"> *</span>
                                            <select class="form-control" :class="{ 'is-invalid': errors?.plant_id }" v-model="asset.plant_id" @change="getAreaValue()">
                                                <option value="">Select Shop</option>
                                                <option v-for="plant, key in plants" :key="key" :value="plant?.plant_id">{{ plant.plant_name }} </option>
                                            </select>
                                            <span v-if="errors?.plant_id" class="invalid-feedback">The shop field is required </span>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Area</label><span class="text-danger"> *</span>
                                            <input type="text" disabled="disabled" class="form-control" :value="asset?.area_name" />
                                            <!-- <input type="text" disabled="disabled" v-else class="form-control"  :value="asset?.area?.area_name" /> -->
                                            <!-- <select class="form-control" :class="{ 'is-invalid': errors?.area_id }" v-model="asset.area_id">
                                                <option value="">Select Area</option>
                                                <option v-for="area, key in areas" :key="key" :value="area?.area_id">{{ area?.area_name }} </option>
                                            </select> -->
                                            <span v-if="errors?.area_id" class="invalid-feedback">{{ errors.area_id[0] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-header d-flex justify-content-between">
                                    <h6 class="card-title">Settings</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <label class="form-label">Geometry Type</label>
                                            <!-- <input type="text" placeholder="Enter Geometry Type" class="form-control" :class="{ 'is-invalid': errors?.geometry_type }" v-model="asset.geometry_type" /> -->
                                            <select class="form-control" :class="{ 'is-invalid': errors?.geometry_type }" v-model="asset.geometry_type">
                                                <option value="">Select Geometry Type</option>
                                                <option value="V-Cylindrical">V-Cylindrical (Vertical) </option>
                                                <option value="H-Cylindrical">H-Cylindrical (Horizontal) </option>
                                                <option value="Cubical">Cubical</option>
                                                <option value="Conical">Conical</option>
                                                <option value="Trapezoidal">Trapezoidal</option>
                                                <option value="Others">Others</option>
                                            </select>
                                            <span v-if="errors?.geometry_type" class="invalid-feedback">{{ errors.geometry_type[0] }}</span>
                                        </div>
                                        <div class="col-md-4" v-if="['V-Cylindrical', 'H-Cylindrical'].includes(asset.geometry_type)">
                                            <label class="form-label">Height (in m)</label><span class="text-danger"> *</span>
                                            <input
                                                type="number"
                                                step="any"
                                                placeholder="Enter Height "
                                                class="form-control"
                                                :class="{ 'is-invalid': errors?.zone_name && errors.zone_name.some(error => error.includes('height')) ||  errors?.height }"
                                                v-model="asset.height"
                                            />
                                            <span v-if="errors?.height" class="invalid-feedback">{{ errors.height[0] }}</span>
                                            <!-- <span v-if="errors?.zone_name && errors.zone_name.includes('Height')" class="invalid-feedback">{{ errors.zone_name[0] }}</span> -->
                                            <span v-if="errors?.zone_name && errors.zone_name.some(error => error.includes('height')) " class="invalid-feedback">{{ errors.zone_name[0] }}</span>
                                        </div>
                                        <div class="col-md-4" v-if="['V-Cylindrical', 'H-Cylindrical'].includes(asset.geometry_type)">
                                            <label class="form-label">Diameter (in m)</label><span class="text-danger"> *</span>
                                            <input
                                                type="number"
                                                step="any"
                                                placeholder="Enter Diameter "
                                                class="form-control"
                                                :class="{ 'is-invalid': errors?.zone_name && errors.zone_name.some(error => error.includes('diameter')) ||  errors?.diameter }"
                                                v-model="asset.diameter"
                                            />
                                            <span v-if="errors?.diameter" class="invalid-feedback">{{ errors.diameter[0] }}</span>
                                            <span v-if="errors?.zone_name && errors.zone_name.some(error => error.includes('diameter'))" class="invalid-feedback">{{ errors.zone_name[0] }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">No Of Zones </label><span class="text-danger"> *</span>
                                            <input
                                                type="number"
                                                placeholder="Enter No Of Zones "
                                                class="form-control"
                                                :class="{ 'is-invalid': errors?.no_of_zones }"
                                                v-model="asset.no_of_zones"
                                                min="1"
                                                @input="checkZoneValue($event, asset)"
                                            />
                                            <span v-if="errors?.no_of_zones" class="invalid-feedback">{{ errors.no_of_zones[0] }}</span>
                                        </div>
                                        <div class="row zone-border g-2" v-for="(zone, index) in asset.zone_name" :key="index">
                                            <div class="col-md-4 mt-0">
                                                <label class="form-label">
                                                    Zone {{ index === 0 ? "0 (Overall)" : index }}
                                                </label>
                                                <span class="text-danger"> *</span>
                                                <input type="text" v-model="zone.zone_name" class="form-control" :class="{ 'is-invalid': errors[`zone_name_${index}`] }" :disabled="index === 0" />
                                                <span v-if="errors[`zone_name_${index}`]" class="invalid-feedback">
                                                    {{ errors[`zone_name_${index}`][0] }}
                                                </span>
                                            </div>

                                            <div class="col-md-4 mt-0">
                                                <label class="form-label">Height (in m)</label>
                                                <span v-if="asset.geometry_type=='Cylindrical'" class="text-danger"> *</span>
                                                <input type="number" step="any" v-model="zone.height" class="form-control" :class="{ 'is-invalid': errors[`zone_height_${index}`] }" min="0" />
                                                <span v-if="errors[`zone_height_${index}`]" class="invalid-feedback">
                                                    {{ errors[`zone_height_${index}`][0] }}
                                                </span>
                                            </div>

                                            <div class="col-md-4 mt-0">
                                                <label class="form-label">Diameter (in m)</label>
                                                <span v-if="asset.geometry_type=='Cylindrical'" class="text-danger"> *</span>
                                                <input type="number" step="any" v-model="zone.diameter" class="form-control" :class="{ 'is-invalid': errors[`zone_diameter_${index}`] }" min="0" />
                                                <span v-if="errors[`zone_diameter_${index}`]" class="invalid-feedback">
                                                    {{ errors[`zone_diameter_${index}`][0] }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <label class="form-label">Latitude</label>
                                                <input type="number" placeholder="Enter Latitude" class="form-control" :class="{ 'is-invalid': errors?.latitude }" v-model="asset.latitude" />
                                                <span v-if="errors?.latitude" class="invalid-feedback">{{ errors.latitude[0] }}</span>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Longitude</label>
                                                <input type="number" placeholder="Enter Longitude" class="form-control" :class="{ 'is-invalid': errors?.longitude }" v-model="asset.longitude" />
                                                <span v-if="errors?.longitude" class="invalid-feedback">{{ errors.longitude[0] }}</span>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Radius</label>
                                                <input type="number" placeholder="Enter Radius" class="form-control" :class="{ 'is-invalid': errors?.radius }" v-model="asset.radius" />
                                                <span v-if="errors?.radius" class="invalid-feedback">{{ errors.radius[0] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3" v-if="asset.asset_attributes.length">
                                <div class="card-header d-flex justify-content-between">
                                    <h6 class="card-title">Attributes</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-2">
                                        <div class="col-md-4" v-for="field, key in asset.asset_attributes" :key="key">
                                            <div v-if="field.field_type == 'Text'">
                                                <label class="form-label">{{ field.display_name }}</label><span v-if="field.is_required" class="text-danger">*</span>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    :placeholder="'Enter ' + field.display_name"
                                                    :maxlength="field.field_length"
                                                    :class="{ 'is-invalid': errors[field.display_name] }"
                                                    v-model="field.asset_attribute_value.field_value"
                                                />

                                                <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                            </div>
                                            <div v-if="field.field_type == 'Number'">
                                                <label class="form-label">{{ field.display_name }}</label><span v-if="field.is_required" class="text-danger">*</span>
                                                <!-- <input
                                                    type="number"
                                                    class="form-control"
                                                    min="0"
                                                    oninput="validity.valid||(value='');"
                                                    :placeholder="'Enter ' + field.display_name"
                                                    :class="{ 'is-invalid': errors[field.display_name] }"
                                                    v-model="field.asset_attribute_value.field_value"
                                                /> -->
                                                <input
                                                    type="number"
                                                    class="form-control"
                                                    min="0"
                                                    @input="validateNumberLength(field)"
                                                    :placeholder="'Enter ' + field.display_name"
                                                    :class="{ 'is-invalid': errors[field.display_name] }"
                                                    v-model="field.asset_attribute_value.field_value"
                                                />
                                                <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                            </div>
                                            <div v-if="field.field_type === 'Date'">
                                                <label class="form-label">
                                                    {{ field.display_name }}
                                                    <span v-if="field.is_required" class="text-danger">*</span>
                                                </label>
                                                <input type="date" class="form-control" :placeholder="'Enter ' + field.display_name" :class="{ 'is-invalid': errors[field.display_name] }" v-model="field.asset_attribute_value.field_value" />

                                                <span v-if="errors[field.display_name]" class="invalid-feedback">
                                                    {{ errors[field.display_name][0] }}
                                                </span>
                                            </div>
                                            <div v-if="field.field_type === 'Date&Time'">
                                                <label class="form-label">
                                                    {{ field.display_name }}
                                                    <span v-if="field.is_required" class="text-danger">*</span>
                                                </label>
                                                <input
                                                    type="datetime-local"
                                                    class="form-control"
                                                    :placeholder="'Enter ' + field.display_name"
                                                    :class="{ 'is-invalid': errors[field.display_name] }"
                                                    v-model="field.asset_attribute_value.field_value"
                                                    step="1"
                                                />
                                                <span v-if="errors[field.display_name]" class="invalid-feedback">
                                                    {{ errors[field.display_name][0] }}
                                                </span>
                                            </div>
                                            <div v-if="field.field_type == 'Dropdown'">
                                                <label class="form-label">{{ field.display_name }}</label><span v-if="field.is_required" class="text-danger">*</span>
                                                <select class="form-control" :class="{ 'is-invalid': errors[field.display_name] }" v-model="field.asset_attribute_value.field_value">
                                                    <option :value="field.asset_attribute_value.field_value" v-if="field.asset_attribute_value.field_value"> {{ field.asset_attribute_value.field_value }}</option>
                                                    <option :value="field.asset_attribute_value.field_value" v-else>Select {{ field.display_name }}</option>
                                                    <option v-for="value, key in field.field_values.split(',')" :key="key" :value="value">{{ value }}</option>
                                                </select>
                                                <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                            </div>
                                            <div v-if="field.field_type=='List'">
                                                <label class="form-label">{{field.display_name}}</label><span v-if="field.is_required" class="text-danger">*</span>
                                                <select class="form-control" :class="{'is-invalid': errors[field.display_name]}" v-model="field.asset_attribute_value.field_value">
                                                    <option :value="field.asset_attribute_value?.field_value" v-if="field.asset_attribute_value?.field_value">
                                                        {{field.asset_attribute_value?.field_value}}
                                                    </option>
                                                    <option :value="field.asset_attribute_value?.field_value" v-else>Select {{field.display_name}}</option>
                                                    <option v-for="value, key in field.list_parameter?.field_values.split(',')" :key="key" :value="value.trim()">{{value.trim()}}</option>
                                                </select>
                                                <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                            </div>
                                            <div v-if="field.field_type == 'Color'">
                                                <label class="form-label">{{ field.display_name }}<span v-if="field.is_required" class="text-danger">*</span></label>
                                                <input type="color" class="form-control" v-model="field.asset_attribute_value.field_value" style="height: 2.2rem;" />

                                                <span v-if="errors[field.display_name]" class="invalid-feedback">{{ errors[field.display_name][0] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="button" class="btn btn-danger me-2" @click="discard()"><i class="ri-close-line fs-18 lh-1"></i> Discard</button>
                            <button type="submit" class="btn btn-primary">
                                <span v-if="status"><i class="ri-save-line fs-18 lh-1"></i> Submit</span>
                                <span v-else><i class="ri-save-line fs-18 lh-1"></i> Update</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    import Search from "@/components/Search.vue";
    import MultiSelect from "primevue/multiselect";
    export default {
        name: "AssetTemplates.Create",
        components: { Search, MultiSelect },
        data() {
            return {
                sample: "",
                asset: {
                    asset_template_id: "",
                    plant_id: "",
                    template_code: "",
                    template_name: "",
                    asset_type_id: "",
                    latitude: "",
                    longitude: "",
                    no_of_zones: 0,
                    status: "",
                    asset_attributes: [],
                    department_id: "",
                    asset_departments_obj: [],
                    asset_departments: [],
                    asset_department_ids: [],
                    section_id: "",
                    functional_id: "",
                    area_id: "",
                    area_name: null,
                    radius: "",
                    zone_name: [],
                    deleted_asset_attribute_values: [],
                    area_name: "",
                    deleted_asset_departments: [],
                    deleted_asset_zones: [],
                    geometry_type: "",
                    height: "",
                    diameter: "",
                },

                voltage: {
                    color: "#ffffff",
                    voltage_code: "",
                },
                errors: {
                    no_of_zones: [],
                },

                device_code: "",
                deleted_asset_attribute_values: [],
                deleted_asset_departments: [],
                // asset_attributes: [],
                showDepartment: false,
                departments: [],
                sections: [],
                functionals: [],
                areas: [],
                plants: [],
                asset_types: [],
                show_assets: [],
                errors: [],
                status: true,
                zones: [],
                initial_zone_no: null,
                new_zone_names: [],
                prev_zone_names: [],
                error_style: {
                    "border-color": "#dc3545",
                    "padding-right": "calc(1.5em + 0.812rem)",
                    "background-image": `url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e")`,
                    "background-repeat": "no-repeat",
                    "background-position": "right calc(0.375em + 0.203rem) center",
                    "background-size": "calc(0.75em + 0.406rem) calc(0.75em + 0.406rem)",
                },
            };
        },
        beforeRouteEnter(to, from, next) {
            next((vm) => {
                vm.asset.asset_attributes = [];
                vm.getAssetsDropdown();
                if (to.name == "AssetTemplates.Create") {
                    // vm.$refs.template_code.focus();
                    vm.asset.zone_name.push({
                        zone_name: "Overall",
                    });
                } else {
                    vm.status = false;
                    let uri = { uri: "getAssetTemplate", data: { asset_template_id: to.params.asset_template_id } };
                    vm.$store
                        .dispatch("post", uri)
                        .then(function (response) {
                            vm.asset = response.data.data;
                            vm.initial_zone_no = vm.asset.no_of_zones;
                            vm.prev_zone_names = vm.asset.zone_name;
                            vm.show_assets = response.data.data?.asset_attributes;
                            vm.asset.area_id = vm.asset.area.area_id;
                            vm.asset.area_name = vm.asset.area.area_name;
                            // vm.asset_departments =
                            vm.asset.deleted_asset_departments = [];
                            vm.asset.deleted_asset_zones = [];
                            vm.asset.asset_attributes.map(function (element) {
                                vm.deleted_asset_attribute_values.push(element.asset_attribute_value.template_attribute_value_id);
                            });
                            vm.asset.deleted_asset_attribute_values = [];

                            vm.asset.asset_departments_obj = [];
                            vm.asset.asset_department_ids.map(function (ele) {
                                vm.asset.asset_departments_obj.push({
                                    department_code: ele.department.department_code,
                                    department_id: ele.department.department_id,
                                    status: ele.department.status,
                                    department_name: ele.department.department_name,
                                });
                            });
                        })
                        .catch(function (error) {
                            console.log(error);
                            // vm.errors = error.response.data.errors;
                            // vm.$store.dispatch("error", error.response.data.message);
                        });
                }
            });
        },

        watch: {
            // "asset.no_of_zones": function (newVal) {
            //     let vm = this;
            //     vm.asset.zone_name = [];
            //     if (vm.asset.no_of_zones <= 0) {
            //         vm.asset.no_of_zones = 1;
            //         vm.asset.zone_name.push({
            //             zone_name: "Overall",
            //             height: null, // Add height field
            //             diameter: null, // Add diameter field
            //         });
            //     }
            //     if (vm.status) {
            //         for (let i = 0; i < vm.asset.no_of_zones; i++) {
            //             vm.asset.zone_name.push({
            //                 zone_name: i === 0 ? "Overall" : null,
            //             });
            //         }
            //     } else {
            //         vm.prev_zone_names.map(function (pre_element) {
            //             vm.asset.zone_name.push(pre_element);
            //         });
            //         if (vm.asset.no_of_zones && vm.asset.no_of_zones < vm.prev_zone_names.length) {
            //             vm.asset.no_of_zones = vm.prev_zone_names.length;
            //         }
            //         if (vm.asset.no_of_zones && vm.asset.no_of_zones > vm.prev_zone_names.length) {
            //             let number = vm.asset.no_of_zones - vm.prev_zone_names.length;
            //             vm.new_zone_names = [];
            //             for (let i = 0; i < number; i++) {
            //                 vm.new_zone_names.push({
            //                     zone_name: null,
            //                 });
            //             }
            //             vm.new_zone_names.map(function (element) {
            //                 vm.asset.zone_name.push(element);
            //             });
            //         }
            //     }
            //     console.log("vm.asset.zone_name:----", vm.asset.zone_name);
            // },

            "asset.no_of_zones": function (newVal) {
                let vm = this;

                // Always ensure Zone 0 is "Overall"
                if (newVal === 0) {
                    vm.asset.zone_name = [
                        {
                            zone_name: "Overall",
                            height: null,
                            diameter: null,
                        },
                    ];
                    return; // Exit to avoid further execution
                }

                // Ensure "Overall" is always at index 0
                if (!vm.asset.zone_name.length || vm.asset.zone_name[0].zone_name !== "Overall") {
                    vm.asset.zone_name.unshift({
                        zone_name: "Overall",
                        height: null,
                        diameter: null,
                    });
                }

                // Adjust the number of zones based on the new value
                let requiredZones = newVal + 1; // +1 to account for "Overall"
                if (vm.asset.zone_name.length > requiredZones) {
                    vm.asset.zone_name.splice(requiredZones);
                } else {
                    for (let i = vm.asset.zone_name.length; i < requiredZones; i++) {
                        vm.asset.zone_name.push({
                            zone_name: null,
                            height: null,
                            diameter: null,
                        });
                    }
                }

                console.log("Updated Zones: ", vm.asset.zone_name);
            },
        },

        computed: {
            // selectedColorDisplay() {
            //     return this.selectedColorName ? `${this.selectedColorName} (${this.selectedColor})` : 'Select Color';
            // },
            getComponentCode() {
                // this.device_code = [this.voltage.voltage_code[0], this.watt_rating[0], this.frame.substring(0, 5), this.mounting[0], this.section[0], this.make[0], this.speed[0], this.asset.serial_no.substring(0, 5)]
                this.device_code = [this.voltage.voltage_code, this.watt_rating, this.frame, this.mounting, this.section, this.make, this.speed, this.asset.serial_no];
                return this.device_code.join("");
                // if(this.voltage.voltage_code[0]){
                //     return this.device_code.join('');
                // }else{
                //     return this.device_code.join('-');
                // }
            },
        },
        methods: {
            validateNumberLength(field) {
                if (field.asset_attribute_value.field_value) {
                    const valueStr = String(field.asset_attribute_value.field_value);
                    if (valueStr.length > field.field_length) {
                        field.asset_attribute_value.field_value = valueStr.slice(0, field.field_length);
                        this.errors[field.display_name] = [`${field.display_name} must be at most ${field.field_length} digits.`];
                    } else {
                        this.errors[field.display_name] = null;
                    }
                }
            },
            getZoneNameError(index) {
                let zone_names = Object.entries(this.errors);
                let err = zone_names.filter(function (element) {
                    return element[0].includes("zone_name") && element[0].includes(index);
                });
                console.log(err);
                if (err.length) {
                    return "Zone name field is required";
                }
            },
            checkZoneValue11(event, asset) {
                if (this.asset.no_of_zones <= 0) {
                    this.asset.no_of_zones = 1; // Reset to minimum allowed value
                    this.asset.zone_name.push({
                        zone_name: "Overall",
                    });
                }

                let value = event?.target?.value?.replace(/[^0-9]/g, "");

                if (value >= 1) {
                    let popped_data = asset.no_of_zones - value;
                    for (let i = 0; i < popped_data; i++) {
                        let del_asset_zone = asset.zone_name.pop();
                        this.asset.deleted_asset_zones.push(del_asset_zone.asset_zone_id);
                        this.prev_zone_names.pop();
                    }
                }
                asset.no_of_zones = asset.zone_name.length;
            },

            checkZoneValue(event, asset) {
                let value = parseInt(event?.target?.value) || 0;

                // Ensure Zone 0 is "Overall"
                if (value === 0) {
                    asset.no_of_zones = 0;
                    asset.zone_name = [
                        {
                            zone_name: "Overall",
                            height: null,
                            diameter: null,
                        },
                    ];
                    return;
                }

                // Adjust the number of zones
                let requiredZones = value + 1; // Including "Overall"
                while (asset.zone_name.length < requiredZones) {
                    asset.zone_name.push({
                        zone_name: null,
                        height: null,
                        diameter: null,
                    });
                }

                while (asset.zone_name.length > requiredZones) {
                    asset.zone_name.pop();
                }

                asset.no_of_zones = value;
            },

            // selectColor(colorValue, colorName, field) {
            //     this.selectedColor = colorValue;
            //     this.selectedColorName = colorName;
            //     this.dropdownVisible = false;
            //     field.field_value = colorValue;
            //     this.updateAssetParameters(field);
            // },
            // toggleDropdown() {
            //     this.dropdownVisible = !this.dropdownVisible;
            // },
            updateAssetParameters(field) {
                let apid = this.asset.asset_attributes.filter(function (element) {
                    return element.asset_attribute_id == field.asset_attribute_id;
                });
                if (!apid.length) {
                    this.asset.asset_attributes.push({
                        asset_attribute_id: field.asset_attribute_id,
                        field_value: field.field_value,
                    });
                }
            },
            submitForm() {
                let vm = this;
                if (vm.status) {
                    vm.addAsset();
                } else {
                    vm.updateAsset();
                }
            },
            getAssetsDropdown() {
                let vm = this;
                // let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getAssetTypes" })
                    .then((response) => {
                        // loader.hide();
                        vm.asset_attributes = response.data.data;
                        vm.getDepartments();
                    })
                    .catch(function (error) {
                        // loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            toggleDepartmentStatus() {
                this.showDepartment = !this.showDepartment;
            },
            getDepartments() {
                let vm = this;
                // let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getDepartments" })
                    .then((response) => {
                        // loader.hide();
                        vm.departments = response.data.data;
                        vm.getSections();
                    })
                    .catch(function (error) {
                        // loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            updateDepartemnts(event, asset_departments) {
                let vm = this;
                const isChecked = event.target.checked;
                let departments = asset_departments.asset_department_ids.filter(function (element) {
                    return element.department_id == event.target.value;
                });
                if (departments.length) {
                    let department_id = departments[0].asset_department_id;
                    if (isChecked) {
                        if (vm.asset.deleted_asset_departments.includes(department_id)) {
                            let deleted_asset_departments = vm.asset.deleted_asset_departments.filter(function (element) {
                                return element != department_id;
                            });
                            vm.asset.deleted_asset_departments = deleted_asset_departments;
                        }
                    } else {
                        if (!vm.asset.deleted_asset_departments.includes(department_id)) {
                            console.log(department_id);
                            vm.asset.deleted_asset_departments.push(department_id);
                        }
                    }
                }

                // console.log('Checked IDs:', vm.asset.asset_departments);
                // console.log('Unchecked IDs:', vm.asset.deleted_asset_departments);
            },

            getSections() {
                let vm = this;
                // let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getSections" })
                    .then((response) => {
                        // loader.hide();
                        vm.sections = response.data.data;
                        vm.getFunctionals();
                    })
                    .catch(function (error) {
                        // loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getFunctionals() {
                let vm = this;
                // let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getFunctionals" })
                    .then((response) => {
                        // loader.hide();
                        vm.functionals = response.data.data;
                        vm.getShops();
                    })
                    .catch(function (error) {
                        // loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getShops() {
                let vm = this;
                // let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getPlants" })
                    .then((response) => {
                        // loader.hide();
                        vm.plants = response.data.data;
                        vm.getAreas();
                    })
                    .catch(function (error) {
                        // loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getAreas() {
                let vm = this;
                // let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getAreas" })
                    .then((response) => {
                        // loader.hide();
                        vm.areas = response.data.data;
                    })
                    .catch(function (error) {
                        // loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            validateFields() {
                let isValid = true;
                this.errors = {};

                if (!this.asset.template_code) {
                    this.errors.template_code = ["Asset Code is required"];
                    isValid = false;
                }
                if (!this.asset.template_name) {
                    this.errors.template_name = ["Asset Name is required"];
                    isValid = false;
                }
                if (!this.asset.asset_type_id) {
                    this.errors.asset_type_id = ["Asset Type is required"];
                    isValid = false;
                }
                if (!this.asset.plant_id) {
                    this.errors.plant_id = ["Shop id is required"];
                    isValid = false;
                }
                // if (!this.asset.area_id) {
                //     this.errors.area_id = ["Area id is required"];
                //     isValid = false;
                // }
                if (!this.asset.no_of_zones) {
                    this.errors.no_of_zones = ["No. of zones id is required"];
                    isValid = false;
                } else {
                    // Validate dynamic zone fields
                    this.asset.zone_name.forEach((zone, index) => {
                        if (!zone.zone_name) {
                            this.errors[`zone_name_${index}`] = [`Zone ${index + 1} is required`];
                            isValid = false;
                        }
                    });
                }
                for (const field of Object.values(this.asset.asset_attributes)) {
                    if (field.is_required && !field.asset_attribute_value.field_value) {
                        if (field.field_type === "Color") {
                            // Set default color if not provided
                            field.asset_attribute_value.field_value = "#000000"; // Default to black
                        } else {
                            this.errors[field.display_name] = [`${field.display_name} is required`];
                            isValid = false;
                        }
                    }
                }
                console.log("Validation Errors:", this.errors);
                return isValid;
            },

            addAsset() {
                let vm = this;
                if (!this.validateFields()) {
                    return;
                }

                vm.asset.asset_departments_obj.map(function (ele) {
                    vm.asset.asset_departments.push(ele.department_id);
                });

                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "addAssetTemplate", data: vm.asset })
                    .then((response) => {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.$router.push("/asset_templates");
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            updateAsset() {
                let vm = this;

                vm.asset.deleted_asset_departments = vm.asset?.asset_department_ids.filter((item1) => !vm.asset.asset_departments_obj.some((item2) => item1.department_id === item2.department_id));
                vm.asset.asset_departments = vm.asset?.asset_departments_obj?.map((item) => item.department_id);
                vm.asset.deleted_asset_departments = vm.asset?.deleted_asset_departments?.map((item) => item.template_department_id);

                // Mapping deleted_asset_departments to asset_department_id
                // vm.asset.deleted_asset_departments = vm.asset?.deleted_asset_departments?.map((item) => {
                //     console.log("Deleted asset department item:", item?.template_department_id); // Log each item in deleted_asset_departments
                //     return item?.template_department_id;
                // });

                if (!vm.validateFields()) {
                    return;
                }

                // vm.asset.template_code = vm.device_code.join("");
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "updateAssetTemplate", data: vm.asset })
                    .then((response) => {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.$router.push("/asset_templates");
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },

            getAsset() {
                let vm = this;
                // let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getAsset", data: vm.asset })
                    .then((response) => {
                        // loader.hide();
                        vm.asset = response.data.data;
                    })
                    .catch(function (error) {
                        // loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            discard() {
                let vm = this;
                // vm.asset = {};
                vm.asset.template_code = "";
                vm.asset.template_name = "";
                vm.asset.latitude = "";
                vm.asset.longitude = "";
                vm.asset.radius = "";
                vm.asset.geometry_type = "";
                vm.asset.department_id = "";
                vm.asset.section_id = "";
                vm.asset.functional_id = "";
                vm.errors = [];
                vm.voltage.color = "#ffffff";
                vm.voltage.voltage_code = "";
                vm.asset.serial_no = "";
                vm.watt_rating = "";
                vm.frame = "";
                vm.device_code = "";
                vm.mounting = "";
                vm.section = "";
                vm.make = "";
                vm.speed = "";
                vm.show_assets = [];
                vm.asset.asset_attributes = [];
                vm.asset.asset_type_id = "";
                let discard_zone = vm.asset.zone_name.filter(function (element) {
                    element.zone_name = "";
                });
                // vm.$refs.template_code.focus();
            },
            reset() {
                let vm = this;
                vm.asset.asset_attributes = [];
                vm.asset.asset_type_id = "";
                vm.errors = [];
            },

            getAssetType(asset_type_id) {
                let vm = this;
                // let loader = vm.$loading.show();
                if (vm.deleted_asset_attribute_values.length) {
                    vm.asset.deleted_asset_attribute_values = vm.deleted_asset_attribute_values;
                }
                vm.$store
                    .dispatch("post", { uri: "getAssetsDropdown", data: { asset_type_id: asset_type_id } })
                    .then((response) => {
                        // loader.hide();
                        vm.show_assets = response.data.data;
                        vm.asset.asset_attributes = response.data.data;
                    })
                    .catch(function (error) {
                        // loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getAreaValue() {
                let vm = this;
                let area = vm.plants.filter(function (element) {
                    return element.plant_id == vm.asset.plant_id;
                });
                if (area.length) {
                    if ("Area" in area[0]) {
                        vm.asset.area_id = area[0].Area?.area_id;
                        vm.asset.area_name = area[0].Area?.area_name;
                    }
                }
            },
        },
    };
</script>

<style>
    .color-square {
        width: 16px;
        height: 16px;
        display: inline-block;
        border: 1px solid #000;
    }

    .input-group {
        position: relative;
    }

    .dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1000;
        display: none;
        background-color: #fff;
        border: 1px solid rgba(0, 0, 0, 0.15);
    }

    .dropdown-menu.show {
        display: block;
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
