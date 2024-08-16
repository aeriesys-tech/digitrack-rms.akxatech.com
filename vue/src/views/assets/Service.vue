<template>
    <div class="">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div>
                <h4 class="main-title mb-0">Service Management</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title" style="display: flex; justify-content: space-between;">
                            <div>Asset Name: <span class="text-primary">Motor LT</span></div>
                            <div>Asset Code: <span class="text-primary">112M415111167890</span></div>
                            <div>Plant: <span class="text-primary">Main Plant</span></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-9 mb-3">
                                Failure Type <span class="text-danger">*</span>
                                <input type="text" class="form-control form-control-sm" placeholder="Failure description...">
                            </div>
                            <div class="col-sm-3 mb-3">
                                Problem Date <span class="text-danger">*</span>
                                <input type="date" class="form-control form-control-sm">
                            </div> 
                            <div class="col-sm-6 mb-3">
                                Reason/Symptom <span class="text-danger">*</span>
                                <input type="text" class="form-control form-control-sm" placeholder="Symptom description...">
                            </div>
                            <div class="col-sm-6 mb-3">
                                Action Taken <span class="text-danger">*</span>
                                <input type="text" class="form-control form-control-sm" placeholder="Service description">
                            </div>
                            <div class="col-sm-3 mb-3">
                                Vendor Name <span class="text-danger">*</span>
                                <input type="text" class="form-control form-control-sm" placeholder="Enter vendor name">
                            </div>
                            <div class="col-sm-3 mb-3">
                                Service Date <span class="text-danger">*</span>
                                <input type="date" class="form-control form-control-sm">
                            </div>
                            <div class="col-sm-3 mb-3">
                                Service Cost <span class="text-danger">*</span>
                                <input type="text" class="form-control form-control-sm">
                            </div>
                            <div class="col-sm-3 mb-3">
                                Upload Invoice
                                <input type="file" class="form-control form-control-sm">
                            </div>
                            <div class="col-sm-12 mb-3">
                               <button class="btn btn-sm btn-primary" style="float: right;">Submit</button>
                               <button class="btn btn-sm btn-danger me-2" style="float: right;">Discard</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-header">
                        <div class="card-title">
                            Hired Service History
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="table-responsive table-responsive-sm">
                            <table class="table table-sm text-nowrap table-bordered mb-0">
                                <thead>
                                    <tr class="text-center">
                                        <th>#</th>
                                        <th>Problem Date</th>
                                        <th>Failure Type</th>
                                        <th>Reason/Symptom</th>
                                        <th>Action Taken</th>
                                        <th>Service Date</th>
                                        <th>Service Cost</th>
                                        <th>Service Invoice</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td>23-Mar-2023</td>
                                        <td>Dead, Winding coil burned</td>
                                        <td>Overload</td>
                                        <td>Replaced winding</td>
                                        <td>30-Mar-2024</td>
                                        <td>10,000</td>
                                        <td style="text-align: center;"><a href="#" target="_blank"
                                                rel="noopener noreferrer">download</a></td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" class="text-success me-2"><i
                                                    class="ri-pencil-line icon_hgt"></i></a>
                                            <a href="javascript:void(0)" class="text-danger"><i
                                                    class="ri-delete-bin-line icon_hgt"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">2</td>
                                        <td>20-Dec-2023</td>                                        
                                        <td>Bearing failure</td>
                                        <td>Vibration, Grinding Sound</td>
                                        <td>Replaced Bearings</td>
                                        <td>30-Dec-2024</td>
                                        <td>5000</td>
                                        <td style="text-align: center;"><a href="#" target="_blank"
                                                rel="noopener noreferrer">download</a></td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" class="text-success me-2"><i
                                                    class="ri-pencil-line icon_hgt"></i></a>
                                            <a href="javascript:void(0)" class="text-danger"><i
                                                    class="ri-delete-bin-line icon_hgt"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> 
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
    </div></template>
