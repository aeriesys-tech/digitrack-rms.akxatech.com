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
            <router-link to="/spare_campaigns" type="submit" class="btn btn-primary" style="float: right;"><i class="ri-list-check"></i> SPARE CAMPAIGNS</router-link>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
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
           
        },

        methods: {

        },
    };
</script>
