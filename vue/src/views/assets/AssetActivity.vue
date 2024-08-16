<template>
    <div class="">
        <div class="">
            <div class="">
                <div class="col-sm-12">
                    <div class="card p-2">
                        <div class="card-header">
                            <h5>Motor LT</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-4 mb-2">
                                    Activity Date <span class="text-danger">*</span>
                                    <input class="form-control" type="date" />
                                </div>
                                <div class="col-sm-4 mb-2">
                                    Activity Time <span class="text-danger">*</span>
                                    <input class="form-control" type="time" />
                                </div>
                                <div class="col-sm-4 mb-2">
                                    Status <span class="text-danger">*</span>
                                    <select class="form-control">
                                        <option value="">Select One</option>
                                        <option value="Added">Added</option>
                                        <option value="Removed">Removed</option>
                                        <option value="Scrapped">Scrapped</option>
                                    </select>
                                </div>
                                <div class="col-sm-4 mb-2">
                                    Reason for Removal <span class="text-danger">*</span>
                                    <select class="form-control">
                                        <option value="">Select One</option>
                                        <option value="Overhauling">Overhauling</option>
                                        <option value="Damaged">Damaged</option>
                                        <option value="Rewinding">Rewinding</option>
                                        <option value="Scrapped">Scrapped</option>
                                        <option value="Upgrade">Upgrade</option>
                                        <option value="Not Applicable">Not Applicable</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                                <div class="col-sm-4 mb-2">
                                    Equipment
                                    <select class="form-control">
                                        <option value="">Select Equipment</option>
                                        <option>Conveyor</option>
                                    </select>
                                </div>
                                <div class="col-sm-4 mb-2">
                                    Maintenance Cost
                                    <input class="form-control" placeholder="Maintenance Cost" type="number" />
                                </div>
                                <div class="col-sm-12 mb-2">
                                    Note
                                    <textarea class="form-control"></textarea>
                                </div>
                            </div>

                        </div>

                        <div class="form-actions-footer">
                            <div class="text-end">
                                <router-link to="/components" class="btn btn-danger">Discard</router-link>
                                <button type="button" class="btn btn-primary ms-1">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card mt-2">
                    <div class="card-header">
                        <h5>Activities</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                        <th class="row">Equipment</th>
                                        <th>Reason</th>
                                        <th>Maintenance Cost</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>23-Jul-2023</td>
                                        <td>11:30 AM</td>
                                        <td>Removed</td>
                                        <td>Conveyor</td>
                                        <td>Grinding sound</td>
                                        <td>2000</td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" class="text-danger"><i
                                                    class="ri-delete-bin-line icon_hgt"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>24-Jul-2023</td>
                                        <td>11:30 AM</td>
                                        <td>Added</td>
                                        <td>Conveyor</td>
                                        <td></td>
                                        <td>2000</td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" class="text-danger"><i
                                                    class="ri-delete-bin-line icon_hgt"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-actions-footer">
                            <div class="text-end row">
                                <div class="col-sm-2">
                                    <select class="form-control" @change="">
                                        <option>10</option>
                                        <option>15</option>
                                        <option>20</option>
                                        <option>25</option>
                                        <option>30</option>
                                    </select>
                                </div>
                                <div class="col-sm-10">
                                    <!-- <Pagination class="pagination pagination-sm" :maxPage="meta.maxPage"
                                        :totalPages="meta.lastPage" :currentPage="meta.currentPage"
                                        @pagechanged="onPageChange" /> -->
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
</script>
