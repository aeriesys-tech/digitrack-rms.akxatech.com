<template>
    <div class="">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div>
                <ol class="breadcrumb fs-sm mb-1">
                    <li class="breadcrumb-item" aria-current="page">
                        <router-link to="/dashboard">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0)">Register</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Health Check Register</li>
                </ol>
                <h4 class="main-title mb-0">Health Check Register</h4>
            </div>
            <router-link to="/health_checks" type="submit" class="btn btn-primary" style="float: right;"><i class="ri-list-check"></i> HEALTH CHECK REGISTERS</router-link>
        </div>
        <div class="row">
            <div class="col-12 mb-3">
                <form @submit.prevent="submitForm()">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title">Health Check</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Asset</label><span class="text-danger"> *</span>
                                    <!-- <select class="form-control" :class="{ 'is-invalid': errors.asset_id }" v-model="campaign.asset_id">
                                    <option value="">Select Asset</option>
                                    <option value="Ladle">Ladle</option>
                                </select> -->
                                    <select class="form-control" :class="{ 'is-invalid': errors.asset_id }" v-model="campaign.asset_id" @change="getScripts">
                                        <option value="">Select Asset</option>
                                        <option v-for="asset, key in assets" :key="key" :value="asset.asset_id">{{asset.asset_name}}</option>
                                    </select>
                                    <span v-if="errors.asset_id" class="invalid-feedback">{{ errors.asset_id[0] }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Data Source</label><span class="text-danger"> *</span>
                                    <select class="form-control" :class="{ 'is-invalid': errors.datasource }" v-model="campaign.datasource_id">
                                        <option value="">Select Data Source</option>
                                        <option value="File">File</option>
                                    </select>
                                    <span v-if="errors.datasource" class="invalid-feedback">{{ errors.datasource[0] }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Script</label><span class="text-danger"> *</span>
                                    <select class="form-control" :class="{ 'is-invalid': errors.script }" v-model="campaign.script">
                                        <option value="">Select Script</option>
                                        <option v-for="scr, key in scripts" :value="scr" :key="key">{{scr}}</option>
                                    </select>
                                    <span v-if="errors.datasource" class="invalid-feedback">{{ errors.datasource[0] }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Job DateTime</label><span class="text-danger"> *</span>
                                    <input type="datetime-local" class="form-control" id="job_date_time" name="job_date_time" v-model="campaign.job_date_time" :class="{ 'is-invalid': errors.job_date_time }" @change="convertDateFormat(campaign.job_date_time)"/>
                                    <span v-if="errors.job_date_time" class="invalid-feedback">{{ errors.job_date_time[0] }}</span>
                                </div>                                
                                <div class="col-md-6">
                                    <label class="form-label">File</label><span class="text-danger"> *</span>
                                    <input type="file" class="form-control" id="file" ref="file" name="file" :class="{ 'is-invalid': errors.file }" />
                                    <span v-if="errors.file" class="invalid-feedback">{{ errors.file[0] }}</span>
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
            <div class="col-12" v-if="groupedResults.length">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <tbody>
                                    <!-- <tr v-for="item,key in campaign_results" :key="key">
                                         <td class="text-center">
                                            <h6>{{ item?.location }}</h6>
                                            <img :src="item?.file" height="180" />
                                        </td>
                                    </tr> -->


                                      <tr v-for="(item, index) in groupedResults" :key="index">
                                        <td class="text-center" v-if="item[0]">
                                            <h6>{{ item[0]?.location }}</h6>
                                            <img :src="item[0]?.file" height="180" width="250"/>
                                        </td>
                                        <td class="text-center" v-if="item[1]">
                                            <h6>{{ item[1]?.location }}</h6>
                                            <img :src="item[1]?.file" height="180"  width="250"/>
                                        </td>
                                    </tr>


                                    <!-- <tr v-for="(item, index) in campaign_results" :key="index">
                                        <td class="text-center" v-if="item[0]">
                                            <h6>{{ item[0]?.location }}</h6>
                                            <img :src="item[0]?.file" height="180" />
                                        </td>
                                        <td class="text-center" v-if="item[1]">
                                            <h6>{{ item[1]?.location}}</h6>
                                            <img :src="item[1]?.file" height="180" />
                                        </td>
                                    </tr>
                                    <tr v-if="groupedResults.length==0">
                                        <td colspan="3" class="text-center text-danger">No records found</td>
                                    </tr> -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    import moment from "moment";
    import axios from "axios";
    export default {
        components: {},
        data() {
            return {
                campaign: {
                    asset_id: "",
                    datasource_id: "",
                    file: "",
                    job_date_time:'',
                    script:'',
                },
                assets: [],
                campaign_results:[],
                errors: [],
                status: true,
                scripts:[],
            };
        },

        mounted() {
            this.getAssets();
    },
          computed: {
            groupedResults() {
                const results = this.campaign_results;
                const grouped = [];

                for (let i = 0; i < results.length; i += 2) {
                    grouped.push(results.slice(i, i + 2));
                }

                return grouped;
            },
        },

        methods: {
            submitForm() {
                let vm = this;
                if (vm.status) {
                    vm.addHealthCheck();
                } else {
                    vm.updateHealthCheck();
                }
            },
            getAssets() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "getAssets" })
                    .then((response) => {
                        loader.hide();
                        vm.assets = response.data.data;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            getScripts() {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", { uri: "assetDataSourceScripts", data: vm.campaign})
                    .then((response) => {
                        loader.hide();
                        vm.scripts = response.data;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            convertDateFormat(date) {
                let vm = this;
                const now = new Date();
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0'); // Months are 0-based
                const day = String(now.getDate()).padStart(2, '0');
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                document.getElementById('job_date_time').value = `${year}-${month}-${day}T${hours}:${minutes}:00`;
                // return moment(date).format("yyyy-MM-DD HH:MM");
            },
            addHealthCheck() {
                let vm = this;
                let loader = this.$loading.show();
                const data = new FormData();
                data.append("asset_id", vm.campaign.asset_id);
                data.append("datasource", vm.campaign.datasource_id);
                data.append("file", vm.$refs.file.files[0]);
                data.append("job_date_time", vm.campaign.job_date_time);
                data.append("script", vm.campaign.script);

                // this.$store.dispatch('post', { uri: 'addCampaign', data:data })
                //     .then(response => {
                //         loader.hide();
                //         this.$store.dispatch('success',"Health check created successfully");
                //         vm.$router.push("/health_checks");
                //     })
                axios
                    .post(vm.$store.state.apiUrl + "addCampaign", data, {
                        headers: {
                            "Content-Type": "multipart/form-data",
                            Authorization: "Bearer" + " " + vm.$store.getters.token,
                        },
                    })
                    .then((response) => {
                        loader.hide();
                        this.$store.dispatch("success", "Health check created successfully");

                        // vm.$router.push("/health_checks");
                        vm.campaign_results = response.data[0];
                        vm.errors = [];
                        vm.discard();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            discard() {
            let vm = this;
            vm.campaign.asset_id = "";
            vm.campaign.datasource_id = "";
            vm.campaign.file = null;
            vm.campaign.job_date_time = '';
            vm.campaign.script = '';
            // Reset the file input
            vm.$refs.file.value = null;
            vm.errors = [];
            vm.status = true;
        },
        },
    };
</script>
