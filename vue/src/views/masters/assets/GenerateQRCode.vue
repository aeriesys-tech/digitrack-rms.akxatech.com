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
                    <li class="breadcrumb-item active" aria-current="page">QR Code</li>
                </ol>
                <h4 class="main-title mb-0">Asset QR- Code</h4>
            </div>
            <router-link to="/assets" type="submit" class="btn btn-primary" style="float: right;"><i class="ri-list-check"></i> ASSETS</router-link>
        </div>
    </div>
    <div class="row text-center" style="justify-content: center;">
        <div class="col-4">
            <div class="card card-one">
                <div class="card-body text-center">
                    <div class="mb-5 mt-5">
                        <img :src="asset?.QR_Code" class="img" style="width: 200px;" />
                    </div>
                    <h3 class="card-title">{{asset.asset_code}}</h3>
                    <p class="card-text">Please scan the QR Code to get the asset details...</p>
                    <div class="row g-2 g-sm-3 mb-5">
                        <div class="col-sm">
                            <button type="button" class="btn btn-primary" @click="downloadQR()"><i class="ri-download-2-line fs-18 lh-1"></i> Download QR</button>
                        </div>
                    </div>
                </div>
                <!-- card-body -->
            </div>
        </div>
    </div>
    <!-- <div class="content">
        <div class="container d-flex">
          <div class="card card-one">
            <div class="card-body text-center">
              <div class="mb-5 mt-5">
                <img :src="asset?.QR_Code" class="img" style="width: auto;"/>
              </div>
              <h3 class="card-title">{{asset.asset_code}}</h3>
              <p class="card-text mb-5">Please Scan the QR Code to get the asset details...</p>
  
            </div>
          </div>
        </div>
      </div> -->
</template>
<script>
    export default {
        name: "GenerateQR",
        data() {
            return {
                asset: {
                    asset_id: "",
                    asset_code: "",
                    QR_Code: "",
                },
            };
        },
        beforeRouteEnter(to, from, next) {
            next((vm) => {
                vm.asset.asset_id = to.params.asset_id;

                let uri = { uri: "getAsset", data: { asset_id: to.params.asset_id } };
                vm.$store
                    .dispatch("post", uri)
                    .then(function (response) {
                        vm.asset = response.data.data;
                        vm.getQRCode();
                    })
                    .catch(function (error) {
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            });
        },
        methods: {
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
        },
    };
</script>
<style scoped>
    .container {
        justify-content: center;
    }
    .content {
        display: block;
        margin-left: auto;
        margin-right: auto;
        margin-top: auto;
    }
</style>
