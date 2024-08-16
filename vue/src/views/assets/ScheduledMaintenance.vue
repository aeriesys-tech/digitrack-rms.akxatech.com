<template>
    <div class="">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div>
                <h4 class="main-title mb-0">Scheduled Maintenance</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title" style="display: flex; justify-content: space-between;">
                            <div>Asset Name: <span class="text-primary">Motor LT</span></div>
                            <div>Asset Code: <span class="text-primary">1234MST8905</span></div>
                            <div>Plant: <span class="text-primary">Main Plant</span></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3 mb-3">
                                Service Date <span class="text-danger">*</span>
                                <input type="date" class="form-control form-control-sm">
                            </div>
                            <div class="col-sm-3 mb-3">
                                Spares Required <span class="text-danger">*</span>
                                <select name="" id="" class="form-control form-control-sm">
                                    <option value="">Select</option>
                                    <option value="">Bearing</option>
                                </select>
                            </div>
                            <div class="col-sm-3 mb-3">
                                Service Cost <span class="text-danger">*</span>
                                <input type="text" class="form-control form-control-sm">
                            </div>
                            <div class="col-sm-3 mb-3">
                                Next Service Date <span class="text-danger">*</span>
                                <input type="date" class="form-control form-control-sm">
                            </div>
                            <div class="col-sm-12 mb-3">
                                Description <span class="text-danger">*</span>
                                <input type="text" class="form-control form-control-sm">
                            </div>
                            <div class="col-sm-12 mb-3">
                               <button class="btn btn-sm btn-primary" style="float: right;">Submit</button>
                               <button class="btn btn-sm btn-danger me-2" style="float: right;">Discard</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-header">
                        <div class="card-title">
                            Scheduled Maintenance
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="table-responsive-sm">
                            <table class="table text-wrap table-bordered" style="vertical-align: middle;">
                                <thead>
                                    <tr class="text-center" style="vertical-align: middle;">
                                        <th>#</th>
                                        <th>Service Date</th>
                                        <th>Description</th>
                                        <th>Spares Required</th>
                                        <th>Spares Availibility</th>                                                                                
                                        <th>Service Cost</th>
                                        <th>Next Service Date</th>
                                        <th>Service Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tr>
                                        <td class="text-center">1</td>
                                        <td class="text-nowrap">23-Mar-2023</td>
                                        <td>Replace oil</td>
                                        <td>10 No. Oil</td>
                                        <td>
                                            <router-link to="/spares">View</router-link>
                                        </td>                                        
                                        <td>1000</td>
                                        <td class="text-nowrap">30-Mar-2024</td>
                                        <td style="text-align: center;">Pending</td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" class="text-success me-2"><i
                                                    class="ri-pencil-line icon_hgt"></i></a>
                                            <a href="javascript:void(0)" class="text-danger"><i
                                                    class="ri-delete-bin-line icon_hgt"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">2</td>
                                        <td class="text-nowrap">20-Dec-2023</td>
                                        <td>Replace Bearing</td>
                                        <td>Bearings</td>
                                        <td>
                                            <router-link to="/spares">View</router-link>
                                        </td>                                        
                                        <td>4000</td>
                                        <td class="text-nowrap">30-Dec-2024</td>
                                        <td style="text-align: center;">Progress</td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" class="text-success me-2"><i
                                                    class="ri-pencil-line icon_hgt"></i></a>
                                            <a href="javascript:void(0)" class="text-danger"><i
                                                    class="ri-delete-bin-line icon_hgt"></i></a>
                                        </td>
                                    </tr>
                            </table>
                        </div>

                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
    </div>
    </template>
