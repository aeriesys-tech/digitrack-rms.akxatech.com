<template>
    <div class="">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div>
                <ol class="breadcrumb fs-sm mb-1">
                    <li class="breadcrumb-item">
                        <router-link to="/components">Assets</router-link>
                    </li>
                    <li class="breadcrumb-item">
                        Create
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
                            <h5 class="my-auto">New Asset</h5>
                        </div>
                        <div class="card-body mb-3">
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <label class="form-label">Plant</label><span class="text-danger"> *</span>
                                    <select class="form-control">
                                        <option value="">Select</option>
                                        <option v-for="plant, key in plants" :value="plant.plant_id" :key="key">{{ plant.plant }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Asset Code</label><span class="text-danger"> *</span>
                                    <!-- <input type="text" disabled="true" class="form-control" :class="getColor()"
                                        :value="getComponentCode" /> -->
                                    <input type="text" disabled="true" class="form-control"
                                        :style="'background-color: ' + voltage.color + ';'" :value="getComponentCode"/>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Asset Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Enter component name" class="form-control" />
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Asset Group</label><span class="text-danger"> *</span>
                                    <select class="form-control">
                                        <option value="">Select</option>
                                        <option v-for="asset_group, key in asset_groups" :key="key"
                                            :value="asset_group.asset_group_id">{{ asset_group.asset_group }}</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Voltage Rating</label><span class="text-danger"> *</span>
                                    <select class="form-control" v-model="asset.voltage_code" @change="setVoltage(asset.voltage_code)">
                                        <option value="">Select Voltage</option>
                                        <option v-for="voltage, key in voltages" :value="voltage.voltage_code" :key="key">{{
                                            voltage.voltage }}</option>
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
                                <div class="row">
                                    <div class="col-md-5 border border-primary m-1 rounded text-primary p-2">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <label class="form-label">Add Spare Parts</label>
                                                <select class="form-control form-control-sm" v-model="spare">
                                                    <option :value="{}">Select</option>
                                                    <option v-for="spare, key in spares_available" :value="spare" :key="key">{{
                                                        spare.spare_name }}</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">.</label>
                                                <button class="btn btn-sm btn-outline-primary form-control"
                                                    @click="addSpares()">ADD</button>
                                            </div>
                                        </div>
                                        <!-- <label class="form-label">Add Spare Parts</label>
                                    <search :initialize="spare.spare_id" id="spare_id" label="spare_name"
                                        placeholder="Select Spare" :data="spares_available" @change="sparesSelected($event)"
                                        @input="spare_n => spare.spare_id = spare_n">
                                    </search> -->
                                    </div>
                                    <div class="col-md-5 border border-primary m-1 rounded text-primary p-2"
                                        style="right: 12px; position: absolute;">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <label class="form-label">Add Check List Questions</label>
                                                <select class="form-control form-control-sm" v-model="check_list_question">
                                                    <option :value="{}">Select</option>
                                                    <option v-for="question, key in check_list_questions" :value="question" :key="key">
                                                        {{
                                                            question.parameter_name }}</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">.</label>
                                                <button class="btn btn-sm btn-outline-primary form-control"
                                                    @click="addCheckListQuestion()">ADD</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive mt-3">
                                    <table class="table table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <th colspan="5"><span class="text-primary">Spare Parts List</span></th>
                                            </tr>
                                            <tr class="text-center">
                                                <th>#</th>
                                                <th>Spare Code</th>
                                                <th>Name</th>
                                                <th>Make</th>
                                                <th>Quantity</th>
                                                <th>UOM</th>
                                                <th style="width: 5%;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="spare, key in spares_list" :key="key">
                                                <td>{{ key + 1 }}</td>
                                                <td>{{ spare.spare_code }}</td>
                                                <td>{{ spare.spare_name }}</td>
                                                <td>{{ spare.make }}</td>
                                                <td style="width: 10%;">
                                                    <input type="text" value="1" class="text form-control form-control-sm">
                                                </td>
                                                <td style="width: 10%;">
                                                    <select name="" id="" class="form-control form-control-sm">
                                                        <option value="Units">Units</option>
                                                        <option value="Units">Kg</option>
                                                        <option value="Units">Ltr</option>
                                                    </select>
                                                </td>
                                                <td class="text-center" style="width: 5%;">
                                                    <a href="javascript:void(0)" @click="removeSpares(spare)"
                                                        class="text-danger"><i class="ri-delete-bin-line icon_hgt"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="8" class="text-center">
                                                    <span class="text-warning">No spares added yet...</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="table-responsive mt-3">
                                    <table class="table table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <th colspan="5"><span class="text-primary">Check List Questions</span></th>
                                            </tr>
                                            <tr class="text-center">
                                                <th>#</th>
                                                <th>Parameter Name</th>
                                                <th style="width: 5%;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="question, key in check_list_added" :key="key">
                                                <td>{{ key + 1 }}</td>
                                                <td>{{ question.parameter_name }}</td>
                                                <td class="text-center" style="width: 5%;">
                                                    <a href="javascript:void(0)" @click="removeCheckListQuestion(question)"
                                                        class="text-danger"><i class="ri-delete-bin-line icon_hgt"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    <span class="text-warning">No questions added yet...</span>
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
            plants: [],
            asset_groups: [],
            voltages: [],
            voltage: {
                color: '#ffffff'
            },
            asset: {
                asset_id: 1,
                plant_id: '',
                asset_code: '',
                asset_name: '',
                asset_group_id:'',
                voltage_code: '',
                watt_rating_id: '',
                frame_type_id: '',
                mounting_type_id: '',
                section_id: '',
                make_id: '',
                speed_id: '',
                serial_number: ''
            },
            //only for demo

            kw: '',
            frame: '',
            device_code: '',
            mounting: '',
            section: '',
            make: '',
            speed: '',
            serial_number: '',
            spares_available: [
                {
                    spare_id: 1,
                    spare_code: 'SC-0001',
                    spare_name: 'Bearing',
                    make: 'SKS',
                },
                {
                    spare_id: 2,
                    spare_code: 'SC-0002',
                    spare_name: 'Slip Ring',
                    make: 'Kirloskar',
                },
                {
                    spare_id: 3,
                    spare_code: 'SC-0003',
                    spare_name: 'Bulk Terminal',
                    make: 'Zentom',
                },
                {
                    spare_id: 3,
                    spare_code: 'SC-0004',
                    spare_name: 'Oil',
                    make: 'Castrol',
                }
            ],
            spares_list: [],
            spare: {},
            check_list_questions: [
                {
                    check_list_id: 1, parameter_name: 'Motor is in running condition', response: false
                },
                {
                    check_list_id: 2, parameter_name: 'Running temperature is in normal range', response: false
                },
                {
                    check_list_id: 3, parameter_name: 'All parts are well lubricated', response: false
                },
                {
                    check_list_id: 4, parameter_name: 'Oil viscosity is normal', response: false
                },
                {
                    check_list_id: 5, parameter_name: 'Overload sensor is working', response: false
                },
                {
                    check_list_id: 6, parameter_name: 'High voltage/current protection sensor is working', response: false
                },
                {
                    check_list_id: 7, parameter_name: 'Sound/vibration is normal', response: false
                },
                {
                    check_list_id: 8, parameter_name: 'Motor IR sensor is working', response: false
                },
                {
                    check_list_id: 9, parameter_name: 'Bulk terminal is clean and intact', response: false
                },
                {
                    check_list_id: 10, parameter_name: 'Serial number/QR code is clean and visible', response: false
                },
            ],
            check_list_question: {},
            check_list_added: []
        }
    },
    computed: {
        getComponentCode() {
            let device_code = [this.asset.voltage_code, this.kw[0], this.frame, this.mounting[0], this.section[0], this.make[0], this.speed[0], this.serial_number.substring(0, 5)]
            return device_code.join('-');
        }
    },
    mounted() {
        // this.getPlants();
    },

    methods: {
        getPlants() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'getPlants' })
                .then(response => {
                    loader.hide();
                    this.plants = response.data.data;
                    this.getAssetGroups();
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },
        getAssetGroups() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'getAssetGroups' })
                .then(response => {
                    loader.hide();
                    this.asset_groups = response.data.data;
                    this.getVoltages();
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },
        getVoltages() {
            let vm = this;
            let loader = this.$loading.show();
            this.$store.dispatch('post', { uri: 'getVoltages' })
                .then(response => {
                    loader.hide();
                    this.voltages = response.data.data;
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },

        setVoltage(voltage_code){
            this.voltage = this.voltages.find(ele => {
                return ele.voltage_code == voltage_code;
            })
        },
        //only for demo
        addSpares() {
            if (!this.spare.spare_id) {
                return;
            }
            let obj = Object.assign({}, this.spare);
            this.spares_list.push(obj);
        },

        removeSpares(spare) {
            let index = this.spares_list.indexOf(spare);
            this.spares_list.splice(index, 1);
        },
        addCheckListQuestion() {
            if (!this.check_list_question.check_list_id) {
                return;
            }
            let obj = Object.assign({}, this.check_list_question);
            this.check_list_added.push(obj);
        },

        removeCheckListQuestion(question) {
            let index = this.check_list_added.indexOf(question);
            this.check_list_added.splice(index, 1);
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
            var result = this.spares_available.filter(obj => {
                return obj.spare_id == this.spare.spare_id;
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