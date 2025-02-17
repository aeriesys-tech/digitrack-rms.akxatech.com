<template>
    <div class="">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div>
                <ol class="breadcrumb fs-sm mb-1">
                    <li class="breadcrumb-item" aria-current="page">
                        <router-link to="/dashboard">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0)">Registers</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <router-link to="/process_registers">Process Registers</router-link>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">View</li>
                </ol>
                <h4 class="main-title mb-0">View Process Register</h4>
            </div>
            <router-link to="/process_registers" type="submit" class="btn btn-primary" style="float: right;"><i
                    class="ri-list-check"></i> PROCESS REGISTERS</router-link>

        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-one mb-3">
                    <div class="card-header">
                        <h5 class="card-title">Process Register</h5>
                        <br />
                    </div>
                    <div class="card-body p-0">
                        <div class="setting-item">
                            <div class="row g-2 mb-4">
                                <div class="col-md-4 mb-3">
                                    <h6>Job Number</h6>
                                    <p class="font">{{ user_variable.job_no }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <h6>Asset Code</h6>
                                    <p class="font">{{ user_variable?.asset?.asset_code }}</p>
                                </div>
                                <!-- <div class="col-md-4 mb-3">
                                    <h6>Asset Zone</h6>
                                    <p class="font">{{ user_variable?.asset_zone?.zone_name }}</p>
                                </div> -->
                                <div class="col-md-4 mb-3">
                                    <h6>Job Date</h6>
                                    <p class="font">{{ convertDateFormat(user_variable.reference_date) }}</p>
                                </div>
                                <div class="col-md-12 mt-1">
                                    <h6 class="mb-2">Asset Variables :</h6>
                                    <div class="table-responsive table-responsive-sm row">
                                        <!-- <table
                                            class="table table-responsive table-striped table-responsive-sm table-sm text-nowrap table-bordered mb-0">

                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-left">Variable</th>
                                                    <th class="text-left">Asset Zone</th>
                                                    <th>Value</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="asset_variable, key in user_variable.user_asset_variables"
                                                    :key="key">
                                                    <td class="text-center">{{ key + 1 }}</td>
                                                    <td>{{ asset_variable?.variable?.variable_name }}</td>
                                                    <td>{{ asset_variable?.asset_zone?.zone_name }}</td>
                                                    <td>{{ asset_variable?.value }}</td>

                                                </tr>
                                            </tbody>
                                        </table> -->

                                        <div class="col-md-4" v-for="asset_zone,key in asset_zones" :key="key">
                                            <div class="card" v-if="user_variable.user_asset_variables[key] && user_variable.user_asset_variables[key].some(item => item?.variable?.variable_name)">
                                                <div class="card-header">
                                                    <h6 class="mb-0">{{ asset_zone.zone_name }}</h6>
                                                </div>
                                                <div class="card-body">
                                                    <table class="table table-responsive table-responsive-sm table-sm text-nowrap table-bordered mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th>Variable</th>
                                                                <th>Value</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="user_variable_data, key1 in user_variable.user_asset_variables[key]" :key="key1">
                                                            <td>{{ user_variable_data?.variable?.variable_name}}</td>
                                                                <td>
                                                                    {{user_variable_data.value}}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- <div class="row g-2">
                                <h6>Attachments :</h6>
                                <div class="col-2 mb-2 imageContainer"
                                    v-for="img, key in user_variable.user_attachments" :key="key">
                                    <a
                                        :href="$store.state.apiUrl + 'downloadCheckAttachment?file_name=' + img.file_name">
                                        <img :src="img.attachments" class="img-fluid1 " alt="" :href="img.attachments"
                                            style="width:120px;height:120px;">
                                    </a>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>
<script>
import moment from "moment";
export default {
    name: "ViewAsset",
    data() {
        return {
            user_variable: {
                user_variable_id: '',
                asset_id: '',
                reference_no: '',
                reference_date: '',
                note: '',
                status: '',
                asset_variables: [],
                user_asset_variables:[],
            },
            asset_zones:[],
        }
    },
    beforeRouteEnter(to, from, next) {
        next((vm) => {
            vm.user_variable.user_variable_id = to.params.user_variable_id            
            vm.getUserVariable();
            
        });
    },
    methods: {

        getAssetZones() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store
                .dispatch("post", { uri: "getAssetZones", data: vm.user_variable })
                .then((response) => {
                    loader.hide();
                    vm.asset_zones = response.data.data;
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },
        getUserVariable() {
            let vm = this;
            let loader = vm.$loading.show();
            let uri = { uri: "getUserVariable", data: { user_variable_id: vm.user_variable.user_variable_id } };
            vm.$store
                .dispatch("post", uri)
                .then(function (response) {
                    loader.hide();
                    vm.user_variable = response.data.data;
                    vm.getAssetZones();
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },
        showImage(image) {
            // window.open(image);
            document.getElementById('image')
                .style.display = "block";
            document.getElementById('btnID')
                .style.display = "none";
        },
        downloadImage(image) {
            let vm = this;
            window.open(vm.$store.state.apiUrl + 'downloadCheckAttachment?file_name=' + image);
            // // Replace 'imageURL' with the actual URL of the image you want to download
            // const imageURL = image;

            // // Create a link element
            // const link = document.createElement('a');
            // link.href = imageURL;
            // link.download = 'image.jpg'; // Name of the downloaded file

            // // Simulate click on the link to trigger download
            // document.body.appendChild(link);
            // link.click();
            // document.body.removeChild(link);
        },

        downloadEngineeringDetail() {
            let vm = this;
            vm.errors = [];
            if (vm.engineering.project_id == "") {
                vm.engineering.project_id = ["The project field is required."]
            }
            if (vm.engineering.to_date == "") {
                vm.engineering.to_date = ["The To Date field is required."]
            }
            if (vm.engineering.project_id && vm.engineering.to_date) {
                window.open(vm.$store.getters.apiUrl + 'downloadElectricalDetailReport?project_id=' + vm.engineering.project_id + '&to_date=' + vm.engineering.to_date + '&from_date=' + vm.engineering.from_date + '&isDownload=true');
            }
        },
        convertDateFormat(date) {
            let vm = this;
            return moment(date).format('yyyy-MM-DD')

        },
    }
}
</script>
<style scoped>
.font {
    font-size: 14px;
}

.setting-item .table th:not(:first-child),
.setting-item .table td:not(:first-child) {
    text-align: left;
}

.imageContainer {
    overflow: hidden;
}

.img {
    -webkit-transform: scale(1);
    transform: scale(1);
    -webkit-transition: .3s ease-in-out;
    transition: .3s ease-in-out;
}

.imageContainer>img:hover {
    -webkit-transform: scale(1.3);
    transform: scale(1.3);
}
</style>