<template>
    <div class="">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div>
                <ol class="breadcrumb fs-sm mb-1">
                    <li class="breadcrumb-item" aria-current="page">
                        <router-link to="/dashboard">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0)">Review</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Spare Campaign</li>
                </ol>
                <h4 class="main-title mb-0">Spare Campaign</h4>
            </div>
            <router-link to="/spare_campaigns/create" type="submit" class="btn btn-primary" style="float: right;"><i class="ri-list-check"></i> ADD SPARE CAMPAIGN</router-link>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-one">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="card-title">Spare Campaign</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Asset</label>
                                <select class="form-control" :class="{ 'is-invalid': errors.asset_id }" v-model="spare.asset_id">
                                    <option value="">Select Asset</option>
                                    <option value="Ladle">Ladle</option>
                                </select>
                                <span v-if="errors.asset_id" class="invalid-feedback">{{ errors.asset_id[0] }}</span>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Analysis</label>
                                <select class="form-control" :class="{ 'is-invalid': errors.spare_id }" v-model="spare.spare_id">
                                    <option value="">Select Spare</option>
                                    <option value="Wall Contour">Wall Contour</option>
                                    <option value="Bottom Contour">Bottom Contour</option>
                                </select>
                                <span v-if="errors.spare_id" class="invalid-feedback">{{ errors.spare_id[0] }}</span>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">From Date</label>
                                <input type="date" class="form-control" :class="{ 'is-invalid': errors.from_date }" v-model="spare.from_date" />
                                <span v-if="errors.from_date" class="invalid-feedback">{{ errors.from_date[0] }}</span>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">To Date</label>
                                <input type="date" class="form-control" :class="{ 'is-invalid': errors.to_date }" v-model="spare.to_date" />
                                <span v-if="errors.to_date" class="invalid-feedback">{{ errors.to_date[0] }}</span>
                            </div>
                            <div class="col-md-2 mt-auto">
                                <button class="btn btn-primary" @click="submit">Search</button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <tbody v-if="wall">
                                    <tr>
                                        <td class="text-center">
                                            <h6>Date:11-08-2024</h6>
                                            <img src="../../../../public/assets/images/spairs/image1.png" height="180" />
                                        </td>
                                        <td class="text-center">
                                            <h6>Date:12-08-2024</h6>
                                            <img src="../../../../public/assets/images/spairs/image2.png" height="180" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">
                                            <h6>Date:13-08-2024</h6>
                                            <img src="../../../../public/assets/images/spairs/image3.png" height="180" />
                                        </td>
                                        <td class="text-center">
                                            <h6>Date:14-08-2024</h6>
                                            <img src="../../../../public/assets/images/spairs/image4.png" height="180" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">
                                            <h6>Date:15-08-2024</h6>
                                            <img src="../../../../public/assets/images/spairs/image5.png" height="180" />
                                        </td>
                                        <td class="text-center">
                                            <h6>Date:16-08-2024</h6>
                                            <img src="../../../../public/assets/images/spairs/image6.png" height="180" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">
                                            <h6>Date:17-08-2024</h6>
                                            <img src="../../../../public/assets/images/spairs/image7.png" height="180" />
                                        </td>
                                        <td class="text-center">
                                            <h6>Date:18-08-2024</h6>
                                            <img src="../../../../public/assets/images/spairs/image8.png" height="180" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">
                                            <h6>Date:19-08-2024</h6>
                                            <img src="../../../../public/assets/images/spairs/image9.png" height="180" />
                                        </td>
                                        <td class="text-center">
                                            <h6>Date:20-08-2024</h6>
                                            <img src="../../../../public/assets/images/spairs/image10.png" height="180" />
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody v-if="bottom">
                                    <tr>
                                        <td class="text-center">
                                            <h6>Date:11-08-2024</h6>
                                            <img src="../../../../public/assets/images/spairs/image11.png" height="180" />
                                        </td>
                                        <td class="text-center">
                                            <h6>Date:12-08-2024</h6>
                                            <img src="../../../../public/assets/images/spairs/image12.png" height="180" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">
                                            <h6>Date:13-08-2024</h6>
                                            <img src="../../../../public/assets/images/spairs/image13.png" height="180" />
                                        </td>
                                        <td class="text-center">
                                            <h6>Date:14-08-2024</h6>
                                            <img src="../../../../public/assets/images/spairs/image14.png" height="180" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">
                                            <h6>Date:15-08-2024</h6>
                                            <img src="../../../../public/assets/images/spairs/image15.png" height="180" />
                                        </td>
                                        <td class="text-center">
                                            <h6>Date:16-08-2024</h6>
                                            <img src="../../../../public/assets/images/spairs/image16.png" height="180" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">
                                            <h6>Date:17-08-2024</h6>
                                            <img src="../../../../public/assets/images/spairs/image17.png" height="180" />
                                        </td>
                                        <td class="text-center">
                                            <h6>Date:18-08-2024</h6>
                                            <img src="../../../../public/assets/images/spairs/image18.png" height="180" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">
                                            <h6>Date:19-08-2024</h6>
                                            <img src="../../../../public/assets/images/spairs/image19.png" height="180" />
                                        </td>
                                        <td class="text-center">
                                            <h6>Date:20-08-2024</h6>
                                            <img src="../../../../public/assets/images/spairs/image20.png" height="180" />
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
</template>

<script>
import moment from 'moment';
    export default {
        components: {},
        data() {
            return {
                spare: {
                    asset_id: "",
                    spare_id: "",
                    from_date: "",
                    to_date: "",
                },
                wall: false,
                bottom: false,
                errors: [],
            };
        },

        mounted() {
            this.spare.from_date = moment().format('yyyy-MM-DD')
            this.spare.to_date = moment().add(1, 'day').format('yyyy-MM-DD')
        },

        methods: {
            submit() {
                if (this.spare.asset_id == "" || this.spare.spare_id == "" || this.spare.from_date == "" || this.spare.to_date == "") {
                    if (this.spare.asset_id == "") {
                        this.errors.asset_id = ["Asset field cannot be empty"];
                    }
                    if (this.spare.spare_id == "") {
                        this.errors.spare_id = ["Spare field cannot be empty"];
                    }
                    if (this.spare.from_date == "") {
                        this.errors.from_date = ["From Date field cannot be empty"];
                    }
                    if (this.spare.to_date == "") {
                        this.errors.to_date = ["To Date field cannot be empty"];
                    }
                }
                 else {
                    this.errors = [];
                    if (this.spare.spare_id == "Wall Contour") {
                        this.wall = true;
                        this.bottom = false;
                    }
                    if (this.spare.spare_id == "Bottom Contour") {
                        this.bottom = true;
                        this.wall = false;
                    }
                }
            },
        },
    };
</script>
