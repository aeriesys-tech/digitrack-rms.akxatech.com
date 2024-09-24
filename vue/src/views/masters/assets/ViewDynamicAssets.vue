<template>
    <div class="">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div>
                <ol class="breadcrumb fs-sm mb-1">
                    <li class="breadcrumb-item" aria-current="page">
                        <router-link to="/dashboard">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item"><router-link to="/assets">Assets</router-link></li>
                    <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
                <h4 class="main-title mb-0">Assets</h4>
            </div>
            <router-link :to="'/asset/'+assets.asset_id+'/view'" type="submit" class="btn btn-primary" style="float: right;"><i class="ri-list-check"></i> ASSETS</router-link>
        </div>
        <div class="row g-2">
            <div class="col-12 mb-2">
                <div class="card card-one">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="card-title">{{asset_type}}</h6>
                    </div>
                    <div class="card-body">

                        <!-- Spare -->
                        <div class="row g-2" v-if="asset_type=='Spares'">
                            <div class="col-md-3" v-for="field, key in assets.asset_spare_attributes" :key="key">
                                <div v-if="field.spare_attributes[0].field_type=='Text'">
                                    <label class="form-label">{{field.spare_attributes[0].display_name}}</label><span v-if="field.spare_attributes[0].is_required" class="text-danger">*</span>
                                    <input type="text" class="form-control" :placeholder="'Enter '+ field.spare_attributes[0].display_name" :class="{'is-invalid': errors[field.spare_attributes[0].display_name]}" :value="field.field_value" disabled="true"/>
                                    <span v-if="errors[field.spare_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.spare_attributes[0].display_name][0] }}</span>
                                </div>

                                <div v-if="field.spare_attributes[0].field_type=='Number'">
                                    <label class="form-label">{{field.spare_attributes[0].display_name}}</label><span v-if="field.spare_attributes[0].is_required" class="text-danger">*</span>
                                    <input
                                        type="number"
                                        class="form-control"
                                        min="0"
                                        oninput="validity.valid||(value='');"
                                        :placeholder="'Enter '+ field.spare_attributes[0].display_name"
                                        :class="{'is-invalid': errors[field.spare_attributes[0].display_name]}"
                                        :value="field.field_value"
                                        disabled="true"
                                    />
                                    <span v-if="errors[field.spare_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.spare_attributes[0].display_name][0] }}</span>
                                </div>


                                <div v-if="field.spare_attributes[0].field_type === 'Date'">
                                    <label class="form-label">
                                        {{ field.spare_attributes[0].display_name }}
                                        <span v-if="field.spare_attributes[0].is_required" class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control" :placeholder="'Enter ' + field.spare_attributes[0].display_name" :class="{'is-invalid': errors[field.spare_attributes[0].display_name]}" :value="field.field_value" disabled="true"/>
                                    <span v-if="errors[field.spare_attributes[0].display_name]" class="invalid-feedback">
                                        {{ errors[field.spare_attributes[0].display_name][0] }}
                                    </span>
                                </div>

                                <div v-if="field.spare_attributes[0].field_type === 'Date&Time'">
                                    <label class="form-label">
                                        {{ field.spare_attributes[0].display_name }}
                                        <span v-if="field.spare_attributes[0].is_required" class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="datetime-local"
                                        class="form-control"
                                        :placeholder="'Enter ' + field.spare_attributes[0].display_name"
                                        :class="{'is-invalid': errors[field.spare_attributes[0].display_name]}"
                                        :value="field.field_value"
                                        step="1"
                                        disabled="true"
                                    />
                                    <span v-if="errors[field.spare_attributes[0].display_name]" class="invalid-feedback">
                                        {{ errors[field.spare_attributes[0].display_name][0] }}
                                    </span>
                                </div>
                                <div v-if="field.spare_attributes[0].field_type=='Dropdown'">
                                    <label class="form-label">{{field.spare_attributes[0].display_name}}</label><span v-if="field.spare_attributes[0].is_required" class="text-danger">*</span>
                                    <select class="form-control" :class="{'is-invalid': errors[field.spare_attributes[0].display_name]}" :value="field.field_value" disabled="true">
                                        <option :value="field.field_value" v-if="field.field_value">{{field.field_value}}</option>
                                        <option :value="field.field_value" v-else>Select {{field.spare_attributes[0].display_name}}</option>
                                        <option v-for="value, key in field.spare_attributes[0].field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                    </select>
                                    <span v-if="errors[field.spare_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.spare_attributes[0].display_name][0] }}</span>
                                </div>
                                <div v-if="field.spare_attributes[0].field_type=='Color'">
                                    <label class="form-label">{{ field.spare_attributes[0].display_name }}<span v-if="field.spare_attributes[0].is_required" class="text-danger">*</span></label>
                                    <input type="color" class="form-control" :value="field.field_value" style="height: 2.2rem;" disabled="true"/>
                                    <span v-if="errors[field.spare_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.spare_attributes[0].display_name][0] }}</span>
                                </div>

                                <div v-if="field.spare_attributes[0].field_type=='List'">
                                    <label class="form-label">{{field.spare_attributes[0].display_name}}</label><span v-if="field.spare_attributes[0].is_required" class="text-danger">*</span>
                                    <select class="form-control" :class="{'is-invalid': errors[field.spare_attributes[0].display_name]}" :value="field.field_value" disabled="true">
                                        <option :value="field.spare_attributes[0].spare_attribute_value.field_value" v-if="field.spare_attributes[0].spare_attribute_value.field_value">{{field.spare_attributes[0].spare_attribute_value.field_value}}</option>
                                        <option :value="field.spare_attributes[0].spare_attribute_value.field_value" v-else>Select {{field.spare_attributes[0].display_name}}</option>
                                        <option v-for="value, key in field.spare_attributes[0].list_parameter?.field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                    </select>
                                    <span v-if="errors[field.spare_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.spare_attributes[0].display_name][0] }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Service -->
                        <div class="row g-2" v-if="asset_type=='Services'">
                            <div class="col-md-3" v-for="field, key in assets.asset_service_attributes" :key="key">
                                <div v-if="field.service_attributes[0].field_type=='Text'">
                                    <label class="form-label">{{field.service_attributes[0].display_name}}</label><span v-if="field.service_attributes[0].is_required" class="text-danger">*</span>
                                    <input type="text" class="form-control" :placeholder="'Enter '+ field.service_attributes[0].display_name" :class="{'is-invalid': errors[field.service_attributes[0].display_name]}" :value="field.field_value"  disabled="true"/>
                                    <span v-if="errors[field.service_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.service_attributes[0].display_name][0] }}</span>
                                </div>

                                <div v-if="field.service_attributes[0].field_type=='Number'">
                                    <label class="form-label">{{field.service_attributes[0].display_name}}</label><span v-if="field.service_attributes[0].is_required" class="text-danger">*</span>
                                    <input
                                        type="number"
                                        class="form-control"
                                        min="0"
                                        oninput="validity.valid||(value='');"
                                        :placeholder="'Enter '+ field.service_attributes[0].display_name"
                                        :class="{'is-invalid': errors[field.service_attributes[0].display_name]}"
                                        :value="field.field_value"
                                        disabled="true"
                                    />
                                    <span v-if="errors[field.service_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.service_attributes[0].display_name][0] }}</span>
                                </div>


                                <div v-if="field.service_attributes[0].field_type === 'Date'">
                                    <label class="form-label">
                                        {{ field.service_attributes[0].display_name }}
                                        <span v-if="field.service_attributes[0].is_required" class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control" :placeholder="'Enter ' + field.service_attributes[0].display_name" :class="{'is-invalid': errors[field.service_attributes[0].display_name]}" :value="field.field_value"  disabled="true"/>
                                    <span v-if="errors[field.service_attributes[0].display_name]" class="invalid-feedback">
                                        {{ errors[field.service_attributes[0].display_name][0] }}
                                    </span>
                                </div>

                                <div v-if="field.service_attributes[0].field_type === 'Date&Time'">
                                    <label class="form-label">
                                        {{ field.service_attributes[0].display_name }}
                                        <span v-if="field.service_attributes[0].is_required" class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="datetime-local"
                                        class="form-control"
                                        :placeholder="'Enter ' + field.service_attributes[0].display_name"
                                        :class="{'is-invalid': errors[field.service_attributes[0].display_name]}"
                                        :value="field.field_value"
                                        step="1"
                                        disabled="true"
                                    />
                                    <span v-if="errors[field.service_attributes[0].display_name]" class="invalid-feedback">
                                        {{ errors[field.service_attributes[0].display_name][0] }}
                                    </span>
                                </div>
                                <div v-if="field.service_attributes[0].field_type=='Dropdown'">
                                    <label class="form-label">{{field.service_attributes[0].display_name}}</label><span v-if="field.service_attributes[0].is_required" class="text-danger">*</span>
                                    <select class="form-control" :class="{'is-invalid': errors[field.service_attributes[0].display_name]}" :value="field.field_value"  disabled="true">
                                        <option :value="field.field_value" v-if="field.field_value">{{field.field_value}}</option>
                                        <option :value="field.field_value" v-else>Select {{field.service_attributes[0].display_name}}</option>
                                        <option v-for="value, key in field.service_attributes[0].field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                    </select>
                                    <span v-if="errors[field.service_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.service_attributes[0].display_name][0] }}</span>
                                </div>
                                <div v-if="field.service_attributes[0].field_type=='Color'">
                                    <label class="form-label">{{ field.service_attributes[0].display_name }}<span v-if="field.service_attributes[0].is_required" class="text-danger">*</span></label>
                                    <input type="color" class="form-control" :value="field.field_value" style="height: 2.2rem;"  disabled="true"/>
                                    <span v-if="errors[field.service_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.service_attributes[0].display_name][0] }}</span>
                                </div>

                                <div v-if="field.service_attributes[0].field_type=='List'">
                                    <label class="form-label">{{field.service_attributes[0].display_name}}</label><span v-if="field.service_attributes[0].is_required" class="text-danger">*</span>
                                    <select class="form-control" :class="{'is-invalid': errors[field.service_attributes[0].display_name]}" :value="field.field_value"  disabled="true">
                                        <option :value="field.service_attributes[0].spare_attribute_value.field_value" v-if="field.service_attributes[0].spare_attribute_value.field_value">{{field.service_attributes[0].spare_attribute_value.field_value}}</option>
                                        <option :value="field.service_attributes[0].spare_attribute_value.field_value" v-else>Select {{field.service_attributes[0].display_name}}</option>
                                        <option v-for="value, key in field.service_attributes[0].list_parameter?.field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                    </select>
                                    <span v-if="errors[field.service_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.service_attributes[0].display_name][0] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2" v-if="asset_type=='Variables'">
                            <div class="col-md-3" v-for="field, key in assets.asset_variable_attributes" :key="key">
                                <div v-if="field.variable_attributes[0].field_type=='Text'">
                                    <label class="form-label">{{field.variable_attributes[0].display_name}}</label><span v-if="field.variable_attributes[0].is_required" class="text-danger">*</span>
                                    <input type="text" class="form-control" :placeholder="'Enter '+ field.variable_attributes[0].display_name" :class="{'is-invalid': errors[field.variable_attributes[0].display_name]}" :value="field.field_value" disabled="true"/>
                                    <span v-if="errors[field.variable_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.variable_attributes[0].display_name][0] }}</span>
                                </div>

                                <div v-if="field.variable_attributes[0].field_type=='Number'">
                                    <label class="form-label">{{field.variable_attributes[0].display_name}}</label><span v-if="field.variable_attributes[0].is_required" class="text-danger">*</span>
                                    <input
                                        type="number"
                                        class="form-control"
                                        min="0"
                                        oninput="validity.valid||(value='');"
                                        :placeholder="'Enter '+ field.variable_attributes[0].display_name"
                                        :class="{'is-invalid': errors[field.variable_attributes[0].display_name]}"
                                        :value="field.field_value"
                                        disabled="true"
                                    />
                                    <span v-if="errors[field.variable_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.variable_attributes[0].display_name][0] }}</span>
                                </div>


                                <div v-if="field.variable_attributes[0].field_type === 'Date'">
                                    <label class="form-label">
                                        {{ field.variable_attributes[0].display_name }}
                                        <span v-if="field.variable_attributes[0].is_required" class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control" :placeholder="'Enter ' + field.variable_attributes[0].display_name" :class="{'is-invalid': errors[field.variable_attributes[0].display_name]}" :value="field.field_value" disabled="true"/>
                                    <span v-if="errors[field.variable_attributes[0].display_name]" class="invalid-feedback">
                                        {{ errors[field.variable_attributes[0].display_name][0] }}
                                    </span>
                                </div>

                                <div v-if="field.variable_attributes[0].field_type === 'Date&Time'">
                                    <label class="form-label">
                                        {{ field.variable_attributes[0].display_name }}
                                        <span v-if="field.variable_attributes[0].is_required" class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="datetime-local"
                                        class="form-control"
                                        :placeholder="'Enter ' + field.variable_attributes[0].display_name"
                                        :class="{'is-invalid': errors[field.variable_attributes[0].display_name]}"
                                        :value="field.field_value"
                                        step="1"
                                        disabled="true"
                                    />
                                    <span v-if="errors[field.variable_attributes[0].display_name]" class="invalid-feedback">
                                        {{ errors[field.variable_attributes[0].display_name][0] }}
                                    </span>
                                </div>
                                <div v-if="field.variable_attributes[0].field_type=='Dropdown'">
                                    <label class="form-label">{{field.variable_attributes[0].display_name}}</label><span v-if="field.variable_attributes[0].is_required" class="text-danger">*</span>
                                    <select class="form-control" :class="{'is-invalid': errors[field.variable_attributes[0].display_name]}" :value="field.field_value" disabled="true">
                                        <option :value="field.field_value" v-if="field.field_value">{{field.field_value}}</option>
                                        <option :value="field.field_value" v-else>Select {{field.variable_attributes[0].display_name}}</option>
                                        <option v-for="value, key in field.variable_attributes[0].field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                    </select>
                                    <span v-if="errors[field.variable_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.variable_attributes[0].display_name][0] }}</span>
                                </div>
                                <div v-if="field.variable_attributes[0].field_type=='Color'">
                                    <label class="form-label">{{ field.variable_attributes[0].display_name }}<span v-if="field.variable_attributes[0].is_required" class="text-danger">*</span></label>
                                    <input type="color" class="form-control" :value="field.field_value" style="height: 2.2rem;" disabled="true"/>
                                    <span v-if="errors[field.variable_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.variable_attributes[0].display_name][0] }}</span>
                                </div>

                                <div v-if="field.variable_attributes[0].field_type=='List'">
                                    <label class="form-label">{{field.variable_attributes[0].display_name}}</label><span v-if="field.variable_attributes[0].is_required" class="text-danger">*</span>
                                    <select class="form-control" :class="{'is-invalid': errors[field.variable_attributes[0].display_name]}" :value="field.field_value" disabled="true">
                                        <option :value="field.variable_attributes[0].spare_attribute_value.field_value" v-if="field.variable_attributes[0].spare_attribute_value.field_value">{{field.variable_attributes[0].spare_attribute_value.field_value}}</option>
                                        <option :value="field.variable_attributes[0].spare_attribute_value.field_value" v-else>Select {{field.variable_attributes[0].display_name}}</option>
                                        <option v-for="value, key in field.variable_attributes[0].list_parameter?.field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                    </select>
                                    <span v-if="errors[field.variable_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.variable_attributes[0].display_name][0] }}</span>
                                </div>
                            </div>                            
                        </div>
                        <div class="row g-2" v-if="asset_type=='Data Sources'">
                            <div class="col-md-3" v-for="field, key in assets.asset_datasource_attributes" :key="key">
                                <div v-if="field.data_source_attributes[0].field_type=='Text'">
                                    <label class="form-label">{{field.data_source_attributes[0].display_name}}</label><span v-if="field.data_source_attributes[0].is_required" class="text-danger">*</span>
                                    <input type="text" class="form-control" :placeholder="'Enter '+ field.data_source_attributes[0].display_name" :class="{'is-invalid': errors[field.data_source_attributes[0].display_name]}" :value="field.field_value" disabled="true"/>
                                    <span v-if="errors[field.data_source_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.data_source_attributes[0].display_name][0] }}</span>
                                </div>

                                <div v-if="field.data_source_attributes[0].field_type=='Number'">
                                    <label class="form-label">{{field.data_source_attributes[0].display_name}}</label><span v-if="field.data_source_attributes[0].is_required" class="text-danger">*</span>
                                    <input
                                        type="number"
                                        class="form-control"
                                        min="0"
                                        oninput="validity.valid||(value='');"
                                        :placeholder="'Enter '+ field.data_source_attributes[0].display_name"
                                        :class="{'is-invalid': errors[field.data_source_attributes[0].display_name]}"
                                        :value="field.field_value"
                                        disabled="true"
                                    />
                                    <span v-if="errors[field.data_source_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.data_source_attributes[0].display_name][0] }}</span>
                                </div>


                                <div v-if="field.data_source_attributes[0].field_type === 'Date'">
                                    <label class="form-label">
                                        {{ field.data_source_attributes[0].display_name }}
                                        <span v-if="field.data_source_attributes[0].is_required" class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control" :placeholder="'Enter ' + field.data_source_attributes[0].display_name" :class="{'is-invalid': errors[field.data_source_attributes[0].display_name]}" :value="field.field_value" disabled="true"/>
                                    <span v-if="errors[field.data_source_attributes[0].display_name]" class="invalid-feedback">
                                        {{ errors[field.data_source_attributes[0].display_name][0] }}
                                    </span>
                                </div>

                                <div v-if="field.data_source_attributes[0].field_type === 'Date&Time'">
                                    <label class="form-label">
                                        {{ field.data_source_attributes[0].display_name }}
                                        <span v-if="field.data_source_attributes[0].is_required" class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="datetime-local"
                                        class="form-control"
                                        :placeholder="'Enter ' + field.data_source_attributes[0].display_name"
                                        :class="{'is-invalid': errors[field.data_source_attributes[0].display_name]}"
                                        :value="field.field_value"
                                        step="1"
                                        disabled="true"
                                    />
                                    <span v-if="errors[field.data_source_attributes[0].display_name]" class="invalid-feedback">
                                        {{ errors[field.data_source_attributes[0].display_name][0] }}
                                    </span>
                                </div>
                                <div v-if="field.data_source_attributes[0].field_type=='Dropdown'">
                                    <label class="form-label">{{field.data_source_attributes[0].display_name}}</label><span v-if="field.data_source_attributes[0].is_required" class="text-danger">*</span>
                                    <select class="form-control" :class="{'is-invalid': errors[field.data_source_attributes[0].display_name]}" :value="field.field_value" disabled="true">
                                        <option :value="field.field_value" v-if="field.field_value">{{field.field_value}}</option>
                                        <option :value="field.field_value" v-else>Select {{field.data_source_attributes[0].display_name}}</option>
                                        <option v-for="value, key in field.data_source_attributes[0].field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                    </select>
                                    <span v-if="errors[field.data_source_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.data_source_attributes[0].display_name][0] }}</span>
                                </div>
                                <div v-if="field.data_source_attributes[0].field_type=='Color'">
                                    <label class="form-label">{{ field.data_source_attributes[0].display_name }}<span v-if="field.data_source_attributes[0].is_required" class="text-danger">*</span></label>
                                    <input type="color" class="form-control" :value="field.field_value" style="height: 2.2rem;" disabled="true"/>
                                    <span v-if="errors[field.data_source_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.data_source_attributes[0].display_name][0] }}</span>
                                </div>

                                <div v-if="field.data_source_attributes[0].field_type=='List'">
                                    <label class="form-label">{{field.data_source_attributes[0].display_name}}</label><span v-if="field.data_source_attributes[0].is_required" class="text-danger">*</span>
                                    <select class="form-control" :class="{'is-invalid': errors[field.data_source_attributes[0].display_name]}" :value="field.field_value" disabled="true">
                                        <option :value="field.data_source_attributes[0].spare_attribute_value.field_value" v-if="field.data_source_attributes[0].spare_attribute_value.field_value">{{field.data_source_attributes[0].spare_attribute_value.field_value}}</option>
                                        <option :value="field.data_source_attributes[0].spare_attribute_value.field_value" v-else>Select {{field.data_source_attributes[0].display_name}}</option>
                                        <option v-for="value, key in field.data_source_attributes[0].list_parameter?.field_values.split(',')" :key="key" :value="value">{{value}}</option>
                                    </select>
                                    <span v-if="errors[field.data_source_attributes[0].display_name]" class="invalid-feedback">{{ errors[field.data_source_attributes[0].display_name][0] }}</span>
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

export default {
    name: "DynamicAssets.View",
    data(){
        return {
            asset_type:'',
            assets:[],
            errors:[],
        }
    },

    beforeRouteEnter(to, from, next) {
        next((vm) => {
            document.body.classList.remove('sidebar-hide');
            vm.asset_type = to.params.assets;
            vm.assets = vm.$store.getters.assets;
            console.log('vm.asset_type:-----', vm.asset_type)
            console.log('vm.assets:-----', vm.assets)
        });
    },
    
    mounted(){        
        
    },
    methods:{

    }
}
</script>