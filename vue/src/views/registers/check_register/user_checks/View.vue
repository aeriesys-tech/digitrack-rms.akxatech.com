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
                        <router-link to="/user_checks">Check Registers</router-link>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">View</li>
                </ol>
                <h4 class="main-title mb-0">View Check Register</h4>
            </div>
            <router-link to="/user_checks" type="submit" class="btn btn-primary" style="float: right;"><i class="ri-list-check"></i> CHECK REGISTERS</router-link>

        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-one mb-3">
                    <div class="card-header">
                        <h5 class="card-title">Check Register</h5>
                        <br />
                    </div>
                    <div class="card-body p-0">
                        <div class="setting-item">
                            <div class="row g-2 mb-4">
                                <div class="col-md-4 mb-3">
                                    <h6>Reference Number</h6>
                                    <p class="font">{{user_check.reference_no}}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <h6>Asset Code</h6>
                                    <p class="font">{{user_check?.asset?.asset_code}}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <h6>Asset Zone</h6>
                                    <p class="font">{{user_check?.asset_zone?.zone_name}}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <h6>Reference Date</h6>
                                    <p class="font">{{convertDateFormat(user_check.reference_date)}}</p>
                                </div>
                                <div class="col-md-12 mt-1">
                                    <h6 class="mb-2">Asset Checks :</h6>
                                    <div class="table-responsive table-responsive-sm">
                                        <table class="table table-responsive table-striped table-responsive-sm table-sm text-nowrap table-bordered mb-0">

                                            <thead>
                                                <!-- <tr>
                                                    <th colspan="9" class="text-center"> <strong>Asset Checks</strong></th>
                                                </tr> -->
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-left">Field name</th>
                                                    <th>Field Type</th>
                                                    <th>Default Value</th>
                                                    <th>Is Required</th>
                                                    <th>Field Values</th>
                                                    <th>LCL</th>
                                                    <th>UCL</th>
                                                    <th>Value</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="check, key in user_check.asset_checks" :key="key">
                                                    <td class="text-center">{{ key + 1 }}</td>
                                                    <td>{{ check.field_name}}</td>
                                                    <td>{{ check.field_type}}</td>
                                                    <td>{{ check.default_value}}</td>
                                                    <td>{{ check.is_required==1 ? 'Yes' : 'No'}}</td>
                                                    <td>{{ check.field_values}}</td>
                                                    <td>{{ check.lcl}}</td>
                                                    <td>{{ check.ucl}}</td>
                                                    <td>{{ check.value}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                            <div class="row g-2" >
                                <h6>Attachments :</h6>
                                <div class="col-2 mb-2 imageContainer" v-for="img, key in user_check.user_attachments" :key="key">
                                    <a :href="$store.state.apiUrl+'downloadCheckAttachment?file_name='+img.file_name">
                                        <img :src="img.attachments" class="img-fluid1 " alt="" :href="img.attachments" style="width:120px;height:120px;">
                                    </a>
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
import moment from "moment";
export default{
    name:"ViewAsset",
    data(){
        return{
            user_check:{
                user_check_id:'',
                asset_id:'',
                reference_no:'',
                reference_date:'',
                note:'',
                status:'',
                asset_checks:[],
            },
        }
    },
    beforeRouteEnter(to, from, next) {
        next((vm) => {
            vm.user_check.user_check_id = to.params.user_check_id
            vm.getUserCheck();
        });
    },
    methods:{
        getUserCheck(){
            let vm = this;
            let loader = vm.$loading.show();
            let uri = { uri: "getUserCheck", data: { user_check_id: vm.user_check.user_check_id } };
            vm.$store
            .dispatch("post", uri)
            .then(function (response) {
                loader.hide();
                vm.user_check = response.data.data;
            })
            .catch(function (error) {
                loader.hide();
                vm.errors = error.response.data.errors;
                vm.$store.dispatch("error", error.response.data.message);
            });
        },
        showImage(image){
            // window.open(image);
            document.getElementById('image')
                .style.display = "block";
            document.getElementById('btnID')
                .style.display = "none";
        },
        downloadImage(image) {
            let vm = this;
            window.open(vm.$store.state.apiUrl+'downloadCheckAttachment?file_name='+image);
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

            downloadEngineeringDetail(){
                let vm = this;
                vm.errors = [];
                if (vm.engineering.project_id == ""){
                    vm.engineering.project_id = ["The project field is required."]
                }
                if (vm.engineering.to_date == ""){
                    vm.engineering.to_date = ["The To Date field is required."]
                }
                if (vm.engineering.project_id && vm.engineering.to_date){
                    window.open(vm.$store.getters.apiUrl+'downloadElectricalDetailReport?project_id='+vm.engineering.project_id+'&to_date='+vm.engineering.to_date+'&from_date='+vm.engineering.from_date+'&isDownload=true');
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
.font{
    font-size: 14px;
}
.setting-item .table th:not(:first-child), .setting-item .table td:not(:first-child) {
    text-align: left;
}
.imageContainer{
overflow: hidden;
}
.img{
    -webkit-transform: scale(1);
    transform: scale(1);
    -webkit-transition: .3s ease-in-out;
    transition: .3s ease-in-out;
}
.imageContainer > img:hover {
    -webkit-transform: scale(1.3);
    transform: scale(1.3);
  }
</style>
