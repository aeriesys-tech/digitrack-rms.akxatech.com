<template>
    <div>
    <div class="">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div>
                <h4 class="main-title mb-0">Assets</h4>
            </div>
            <router-link to="/components/create" type="submit" class="btn btn-sm btn-primary" style="float: right;"><i
                    class="ri-list-check"></i> ADD Asset</router-link>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body mb-3">
                        <input class="form-control form-control-sm mb-2" type="text"
                            placeholder="Type keyword and press enter key" />
                        <div class="table-responsive table-responsive-sm">
                            <table class="table table-sm text-nowrap table-bordered mb-0">
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-center">#</th>
                                        <th>Asset Code</th>
                                        <th>Name</th>
                                        <th>Serail Number</th>
                                        <th>Make</th>
                                        <th>Plant</th>
                                        <th>Type</th>
                                        <th>Voltage</th>
                                        <th>Speed</th>
                                        <th>Capacity</th>
                                        <th>Frame</th>
                                        <th>Section</th>
                                        <th>View</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td>112M415111167890</td>
                                        <td>Motor</td>
                                        <td>SL-0001</td>
                                        <td>Kirloskar</td>
                                        <td>UTCL-NCJW</td>
                                        <td>LT</td>
                                        <td>230V</td>
                                        <td>200</td>
                                        <td>200kV</td>
                                        <td>Regular</td>
                                        <td>section</td>
                                        <td>
                                            <div>
                                                <select class="form-select form-select-sm" v-model="url"
                                                    @change="getView()">
                                                    <option value="">Select</option>
                                                    <!-- <option value="/components/service">Hired Service</option>
                                                    <option value="/components/inhouse_service">In House Service</option>                                                    
                                                    <option value="/spares">Spares</option> -->
                                                    <option value="/assets/activity">Activity Register</option>
                                                    <option value="/components/maintenance">Maintenance Register</option>
                                                    <option value="/assets/check_list">Check List</option>
                                                </select>
                                            </div>
                                            <!-- <router-link to="/components/service">Service</router-link>                         -->
                                        </td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" class="text-success me-2"><i
                                                    class="ri-pencil-line icon_hgt"></i></a>
                                            <a href="javascript:void(0)" class="text-danger me-2"><i
                                                    class="ri-delete-bin-line icon_hgt"></i></a>
                                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#qrModal"><i
                                                    class="ri-qr-code-line icon_hgt" @click="generateBarCode('112M415111167890')"></i></a>
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
    </div>
    <!-- QR Modal -->
    <div class="modal" tabindex="-1" id="qrModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Asset QR- Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id='barcode' src="https://api.qrserver.com/v1/create-qr-code/?data=HelloWorld&amp;size=100x100"
                        alt="" title="Equipment QR Code" width="200" height="200" />
                    <h1 class="mt-2 bg-danger">112M415111167890</h1>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            url: ''
        }
    },

    methods: {
        getView() {
            this.$router.push(this.url);
        },
        generateBarCode(value) {
            var url = 'https://api.qrserver.com/v1/create-qr-code/?data=' + value + '&amp;size=50x50';
            $('#barcode').attr('src', url);
        }
    }
}
</script>
