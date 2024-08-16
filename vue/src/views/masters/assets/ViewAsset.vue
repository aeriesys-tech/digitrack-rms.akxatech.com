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
                                    <h6 class="nav-link text-dark d-flex justify-content-between" v-for="asset_parameter, key in asset.asset_parameters" :key="key">
                                        {{ asset_parameter.display_name}}
                                        <span  v-if="asset_parameter.field_name == 'Color'" :style="getColor(asset_parameter)">{{ asset_parameter?.asset_parameter_value?.field_value }}</span>
                                        <span v-else>{{ asset_parameter?.asset_parameter_value?.field_value }}</span>
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
            <div class="col-xl-8 mb-2">
                <div class="row">
                    <div class="col-12 mb-2" v-can="'assetSpares.view'">
                        <div class="card card-one">
                            <div class="card-header d-flex justify-content-between">
                                <h6 class="card-title">Spares</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-6" v-can="'assetSpares.create'">
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
                                    <div class="col-md-6" v-can="'assetSpares.create'">
                                        <div style="float: left;">
                                            <button class="btn btn-outline-success me-2" @click="addSpare()"><i class="ri-add-circle-line icon-hgt"></i> Add</button>
                                        </div>
                                        <!-- <button class="btn border-left btn-outline-success" type="button" @click="addSpare()"><i class="ri-add-circle-line icon-hgt"></i>ADD</button> -->
                                    </div>

                                    <div class="col-12">
                                        <div class="table-responsive table-responsive-sm">
                                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                                <thead>
                                                    <tr class="">
                                                        <th class="text-center">#</th>
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
                                                        <td>{{spare.spare?.spare_type?.spare_type_name}}</td>
                                                        <td>{{spare.spare?.spare_code}}</td>
                                                        <td>{{spare.spare?.spare_name}}</td>
                                                        <td class="text-center" v-can="'assetSpares.delete'">
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

                    <div class="col-12" v-can="'assetChecks.view'">
                        <div class="card card-one">
                            <div class="card-header d-flex justify-content-between">
                                <h6 class="card-title">Checks</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-6" v-can="'assetChecks.create'">
                                        <label>Check</label>
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
                                    <div class="col-md-6" v-can="'assetChecks.create'">
                                        <div style="float: left;">
                                          
                                            <td>
                                                <label>Lcl</label>
                                                <input type="text" class="form-control" placeholder="Lcl" v-model="check.lcl">
                                            </td>
                                            <td>
                                                <label>Ucl</label>
                                                <input type="text" class="form-control" placeholder="Ucl" v-model="check.ucl">
                                            </td>
                                            <td>
                                                <label>Default Value</label>
                                                <input type="text" class="form-control" placeholder="Default Value" v-model="check.default_value">
                                            </td>

                                            <div class="d-flex justify-content-end">
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
                                                        <th @click="sortCheck('check_id')" width="10%">
                                                            Frequency
                                                            <span>
                                                                <i v-if="check_meta.keyword=='frequency' && check_meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="check_meta.keyword=='frequency' && check_meta.order_by=='desc'" class="ri-arrow-down-line"></i>
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
                                                        <td style="white-space: normal;">{{check?.check?.field_name}}</td>
                                                        <td>{{ check?.check?.field_type }}</td>
                                                        <td>{{ check.lcl }}</td>
                                                        <td>{{ check.ucl }}</td>
                                                        <td  style="white-space: normal;">{{ check.default_value }}</td>
                                                        <td>{{ check?.check?.frequency?.frequency_name }}</td>
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

                    <div class="col-12">
                        <div class="card card-one">
                            <div class="card-header d-flex justify-content-between">
                                <h6 class="card-title">Services</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <!-- <div class="d-flex justify-content-between" v-can="'assetChecks.create'"> -->

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
                                    <div class="col-md-6">
                                        <div style="float: left;">
                                            <button class="btn btn-outline-success me-2" @click="addService()"><i class="ri-add-circle-line icon-hgt"></i> Add</button>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="table-responsive table-responsive-sm">
                                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                                <thead>
                                                    <tr class="">
                                                        <th class="text-center">#</th>
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
                                                        <th>
                                                            Frequency
                                                            <span>
                                                                <i v-if="check_meta.keyword=='frequency' && check_meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                                <i v-else-if="check_meta.keyword=='frequency' && check_meta.order_by=='desc'" class="ri-arrow-down-line"></i>
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
                                                        <td>{{ service?.service?.service_type?.service_type_name }}</td>
                                                        <td>{{service?.service?.service_name}}</td>
                                                        <td>{{ service?.service?.service_code }}</td>
                                                        <td>{{ service?.service?.frequency?.frequency_name }}</td>
                                                        <td class="text-center">
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
                </div>
            </div>
            <!-- try ends -->
        </div>
    </div>
</template>
<script>
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
                    spare_code: "",
                    spare_name: "",
                    asset_id: "",
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
                },
                service: {
                    service_id: "",
                    service_code: "",
                    service_name: "",
                    asset_id: "",
                },
               
                device_code: "",
                spares: [],
                asset_spares: [],
                checks: [],
                asset_checks: [],
                asset_services: [],
                errors: [],
                status: true,
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
            getColor(asset_parameter){
                let color = 'color:black'
                if(asset_parameter.field_name = 'Color'){
                    color = 'background-color:'+asset_parameter.asset_parameter_value?.field_value +'; color:white; padding:5px'
                }  
                return color

            },
            editCheck(check){
                this.check = check;
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
                        vm.getAssetSpares();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
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
                        vm.getAssetServices();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
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
                // console.log('check:----',check)
                let uri = { uri: "updateAssetCheck", data: vm.check };
                vm.$store
                    .dispatch("post", uri)
                    .then(function () {
                        vm.check.check_id = "";
                        vm.check.lcl = "";
                        vm.check.ucl = "";
                        vm.check.default_value = "";
                        vm.getAssetChecks();
                    })
                    .catch(function (error) {
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
            onPageChange(page) {
                this.meta.page = page;
                this.getAssetSpares();
            },
            sort(field) {
                this.meta.keyword = field;
                this.meta.order_by = this.meta.order_by == "asc" ? "desc" : "asc";
                this.getAssetSpares();
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
</style>
