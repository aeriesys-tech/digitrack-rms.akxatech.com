<template>
    <div class="">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div>
                <ol class="breadcrumb fs-sm mb-1">
                    <li class="breadcrumb-item">
                        <router-link to="/components">Assets</router-link>
                    </li>
                    <li class="breadcrumb-item">
                        Service History
                    </li>
                    <!-- <li class="breadcrumb-item active" aria-current="page">User Management</li> -->
                </ol>
                <!-- <h4 class="main-title mb-0">New Component</h4> -->
            </div>
            <router-link to="/components" type="submit" class="btn btn-sm btn-primary" style="float: right;"><i
                    class="ri-list-check"></i> Assets</router-link>
        </div>
        <div class="row">
            <div class="col-12">
                <form>
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="my-auto">Service Management</h5>
                        </div>
                        <div class="card-body mb-3">
                            <div class="row g-2" style="pointer-events: none;">
                                <div class="col-md-3">
                                    <label class="form-label">Plant</label><span class="text-danger"> *</span>
                                    <select class="form-control">
                                        <option value="">Select</option>
                                        <option selected>Plant</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Asset Code</label><span class="text-danger"> *</span>
                                    <input type="text" disabled="true" class="form-control" :class="getColor()"
                                        :value="getComponentCode" />
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Asset Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Enter component name" class="form-control" value="Motor" />
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Asset Type</label><span class="text-danger"> *</span>
                                    <select class="form-control">
                                        <option value="">Select Type</option>
                                        <option selected>LT</option>
                                        <option>HT</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Asset Group</label><span class="text-danger"> *</span>
                                    <select class="form-control">
                                        <option value="">Select Group</option>
                                        <option selected>Motor</option>
                                        <option>Motor</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Voltage Rating</label><span class="text-danger"> *</span>
                                    <select class="form-control" v-model="voltage">
                                        <option value="">Select Voltage</option>
                                        <option>1. 11000 volts</option>
                                        <option>2. 6600 volts</option>
                                        <option>3. 690 volts</option>
                                        <option>4. 415 volts</option>
                                        <option>5. 230 volts</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Watt Rating</label><span class="text-danger"> *</span>
                                    <select class="form-control" v-model="kw">
                                        <option value="">Select Watt</option>
                                        <option>1. Up to 5.5 KW</option>
                                        <option>2. 7.5 - 22 KW</option>
                                        <option>3. 30 - 110 KW</option>
                                        <option>4. 132 - 250 KW</option>
                                        <option>5. 250 - 500 KW</option>
                                        <option>6. 500 - 1200 KW</option>
                                        <option>7. 1200 - 5500 KW</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Frame Type</label><span class="text-danger"> *</span>
                                    <select class="form-control" v-model="frame">
                                        <option value="">Select Frame</option>
                                        <option value="2M415">xxxxx-63-400</option>
                                        <option value="2M415">M/S/L/X</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Mounting Type</label><span class="text-danger"> *</span>
                                    <select class="form-control" v-model="mounting">
                                        <option value="">Select Mounting</option>
                                        <option>1. B3</option>
                                        <option>2. B5</option>
                                        <option>3. B35</option>
                                        <option>4. V1</option>
                                        <option>5. V3</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Section</label><span class="text-danger"> *</span>
                                    <select class="form-control" v-model="section">
                                        <option value="">Select Section</option>
                                        <option>1. Packing Plant</option>
                                        <option>2. Cement Mill</option>
                                        <option>3. Kiln/PH/ESP</option>
                                        <option>4. Coal Mill</option>
                                        <option>5. Rawmill</option>
                                        <option>6. RMHS</option>
                                        <option>7. Crusher</option>
                                        <option>8. Mines</option>
                                        <option>9. Utility</option>
                                        <option>A. Misc</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Make</label><span class="text-danger"> *</span>
                                    <select class="form-control" v-model="make">
                                        <option value="">Select Make</option>
                                        <option>1. ABB</option>
                                        <option>2. BHEL</option>
                                        <option>3. Seimens</option>
                                        <option>4. SEW</option>
                                        <option>5. EURO Drive</option>
                                        <option>6. BBL</option>
                                        <option>7. CGL</option>
                                        <option>8. Others</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Speed</label><span class="text-danger"> *</span>
                                    <select class="form-control" v-model="speed">
                                        <option value="">Select RPM</option>
                                        <option>1. 2P</option>
                                        <option>2. 4P</option>
                                        <option>3. 6P</option>
                                        <option>4. 8P</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Serial Number</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Enter asset serial number" class="form-control"
                                        v-model="serial_number" />
                                </div>
                                <div class="col-md-12 border border-primary rounded text-primary p-2">
                                    <div class="row" style="pointer-events:auto">
                                        <div class="col-md-5">
                                            <label class="form-label">Symptom/Problem</label>
                                            <input class="form-control form-control-sm" v-model="service.symptoms" placeholder="Specify problem..." type="text">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Select Spare Parts</label>
                                            <select class="form-control form-control-sm" v-model="spare.spare_id">
                                                <option value="">Select</option>
                                                <option v-for="spare, key in spares_available" :value="spare.spare_id">{{
                                                    spare.spare_name }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Action Taken</label>
                                            <select class="form-control form-control-sm" v-model="service.action_taken">
                                                <option value="">Select</option>
                                                <option value="Replaced">Replaced</option>
                                                <option value="Repaired">Repaired</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Service Date</label>
                                            <input type="date" class="form-control form-control-sm" v-model="service.service_date">
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label">&nbsp</label>
                                            <button class="btn btn-sm btn-outline-primary form-control"
                                                @click="addService()">ADD</button>
                                        </div>
                                    </div>
                                    <!-- <label class="form-label">Add Spare Parts</label>
                                    <search :initialize="spare.service_id" id="service_id" label="spare_part"
                                        placeholder="Select Spare" :data="service_history" @change="sparesSelected($event)"
                                        @input="spare_n => spare.service_id = spare_n">
                                    </search> -->
                                </div>
                                <div class="table-responsive mt-3">
                                    <table class="table table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <th colspan="5"><span class="text-primary">Service History</span></th>
                                            </tr>
                                            <tr class="text-center">
                                                <th>#</th>
                                                <th>Symptoms/Problems</th>
                                                <th>Spare Part</th>
                                                <th>Make</th>
                                                <th>Action Taken</th>
                                                <th>Service Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="service, key in service_history" :key="key" v-if="service_history.length">
                                                <td>{{ key + 1 }}</td>
                                                <td>{{ service.symptoms }}</td>
                                                <td>{{ service.spare_part }}</td>
                                                <td>{{ service.make }}</td>
                                                <td>{{ service.action_taken }}</td>
                                                <td>{{ service.service_date }}</td>
                                                <td class="text-center" style="pointer-events:auto">
                                                    <a href="javascript:void(0)" @click="removeService(service)"
                                                        class="text-danger"><i class="ri-delete-bin-line icon_hgt"></i></a>
                                                </td>
                                            </tr>
                                            <tr v-else>
                                                <td colspan="6" class="text-center">
                                                    <span class="text-warning">No records found </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="reset" class="btn btn-sm btn-danger me-2"><i class="ri-close-line icon_hgt"></i>
                                DISCARD</button>
                            <button type="submit" class="btn btn-sm btn-primary"><i
                                    class="ri-save-line icon_hgt"></i>SUBMIT</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
let Search = require("@/components/Search.vue").default;
export default {
    components: { Search },
    data() {
        return {
            voltage: '1. 11000 volts',
            kw: '1. Up to 5.5 KW',
            frame: '2M415',
            device_code: '',
            mounting: '1. B3',
            section: '1. Packing Plant',
            make: '1. ABB',
            speed: '1. 2P',
            serial_number: '67890',
            service_history: [
                {
                    service_id: 1,
                    symptoms: 'Grinding sound with vibration',
                    spare_part: 'Bearing',
                    make: 'SKS',
                    action_taken: 'Replaced',
                    service_date: '2023-02-21'
                },
            ],
            spares_available: [
                {
                    spare_id: 1,
                    spare_code: 'SC-0001',
                    spare_name: 'Bearing',
                    make: 'SKS',
                    availability: 'Yes'
                },
                {
                    spare_id: 2,
                    spare_code: 'SC-0002',
                    spare_name: 'Slip Ring',
                    make: 'Kirloskar',
                    availability: 'Yes'
                },
                {
                    spare_id: 3,
                    spare_code: 'SC-0003',
                    spare_name: 'Bulk Terminal',
                    make: 'Zentom',
                    availability: 'No'
                }
            ],
            spares_list: [],
            spare: {
                spare_id:''
            },
            service: {
                service_id: '',
                symptoms: '',
                spare_part: '',
                make:'',
                action_taken: '',
                service_date: ''
            }
        }
    },
    computed: {
        getComponentCode() {
            let device_code = [this.voltage[0], this.kw[0], this.frame, this.mounting[0], this.section[0], this.make[0], this.speed[0], this.serial_number.substring(0, 5)]
            return device_code.join('-');
        }
    },
    methods: {
        addService() {
            // if (!this.service.service_id) {
            //     return;
            // }
            let spare = this.spares_available.find(ele => { return ele.spare_id == this.spare.spare_id});
            let obj = Object.assign({}, this.service);
            obj.spare_part = spare.spare_name;
            obj.make = spare.make;
            this.service_history.push(obj);
        },

        removeService(service) {
            let index = this.service_history.indexOf(service);
            this.service_history.splice(index, 1);
        },

        getColor() {
            let color = '';
            switch (parseInt(this.voltage[0])) {
                case 1:
                    color = 'first'
                    break;

                case 2:
                    color = 'second'
                    break;

                case 3:
                    color = 'third'
                    break;

                case 4:
                    color = 'fourth'
                    break;

                case 5:
                    color = 'fifth'
                    break;

                default:
                    break;
            }
            return color;
        },

        sparesSelected(event) {
            var result = this.service_history.filter(obj => {
                return obj.service_id == this.spare.service_id;
            });
            let obj = Object.assign({}, result[0]);
            this.spares_list.push(obj);
            console.log(result);
        }
    }
}
</script>
<style scoped>
.first {
    background-color: rgb(255, 143, 143);
}

.second {
    background-color: orange;
}

.third {
    background-color: yellow;
}

.fourth {
    background-color: lightgreen;
}

.fifth {
    background-color: lightblue;
}
</style>