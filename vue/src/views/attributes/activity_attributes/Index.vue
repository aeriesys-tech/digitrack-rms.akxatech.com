<template>
        <div class="">
            <div class="d-sm-flex align-items-center justify-content-between mb-2">
                <div>
                    <ol class="breadcrumb fs-sm mb-1">
                        <li class="breadcrumb-item" aria-current="page">
                            <router-link to="/dashboard">Dashboard</router-link></li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a href="javascript:void(0)">Attributes</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="javascript:void(0)">Activity Attributes</a></li>
                    </ol>
                    <h4 class="main-title mb-0">Activity Attributes</h4>
                </div>
                <router-link to="/activity_attributes/create" class="btn btn-primary" style="float: right;" ><i
                        class="ri-list-check"></i> ADD ACTIVITY ATTRIBUTE</router-link>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title">Activity Attributes</h6>
                        </div>
                        <div class="card-body">
                            <input class="form-control form-control-sm mb-2" type="text"
                                placeholder="Type keyword and press enter key" v-model="meta.search" @keypress.enter="search()" />
                            <div class="table-responsive table-responsive-sm">
                                <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                    <thead>
                                        <tr style="background-color:#9b9b9b;color:white;">
                                            <th class="text-center">#</th>
                                            <th @click="sort('field_name')">
                                                Field Name
                                                <span>
                                                    <i v-if="meta.keyword == 'field_name' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword == 'field_name' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span>
                                            </th>
                                            <th @click="sort('display_name')">
                                                Display Name
                                                <span>
                                                    <i v-if="meta.keyword == 'display_name' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword == 'display_name' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span>
                                            </th>
                                            <th @click="sort('field_type')">
                                                Field Type
                                                <span>
                                                    <i v-if="meta.keyword == 'field_type' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword == 'field_type' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span>
                                            </th>
                                            <th @click="sort('field_values')">
                                                Field Values
                                                <span>
                                                    <i v-if="meta.keyword == 'field_values' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword == 'field_values' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span>
                                            </th>
                                            <!-- <th @click="sort('list_parameter_id')">
                                                List
                                                <span>
                                                    <i v-if="meta.keyword == 'list_parameter_id' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword == 'list_parameter_id' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span>
                                            </th> -->
                                            <th @click="sort('field_length')">
                                                Field Length
                                                <span>
                                                    <i v-if="meta.keyword == 'field_length' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword == 'field_length' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span>
                                            </th>
                                            <th @click="sort('is_required')">
                                                Is Required
                                                <span>
                                                    <i v-if="meta.keyword == 'is_required' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword == 'is_required' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span>
                                            </th>
                                            <th>
                                                Activity Type
                                                <!-- <span>
                                                    <i v-if="meta.keyword == 'asset_type_id' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword == 'asset_type_id' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span> -->
                                            </th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="activity_attributes.length==0">
                                            <td colspan="10" class="text-center">No records found</td>
                                        </tr>
                                        <tr v-for="activity_attribute, key in activity_attributes" :key="key">
                                            <td class="text-center">{{ meta.from + key }}</td>
                                            <td>{{activity_attribute.field_name}}</td>
                                            <td>{{activity_attribute.display_name}}</td>
                                            <td>{{activity_attribute.field_type}}</td>
                                            <td>{{activity_attribute.field_values}}</td>
                                            <!-- <td>{{activity_attribute?.list_parameter?.list_parameter_name }}</td> -->
                                            <td>{{activity_attribute.field_length}}</td>
                                            <!-- <td v-if="activity_parameter.is_required">Yes</td> -->
                                            <td>{{activity_attribute?.is_required==1 ?'Yes' : 'No'  }}</td>
                                            <td>
                                                <span v-for="activity_attribute_type, key in activity_attribute.activity_attribute_types" :key="key">{{ activity_attribute_type?.activity_type?.reason_name }}, </span>
                                            </td>
                                            <td class="text-center" >
                                                <div class="form-switch" >
                                                    <input class="form-check-input"  type="checkbox" role="switch" :id="'activity_attribute' + activity_attribute.activity_attribute_id" :checked="activity_attribute.status" :value="activity_attribute.status" @change="deleteActivityAttribute(activity_attribute)" />
                                                    <label class="custom-control-label" :for="'user' + activity_attribute.activity_attribute_id"></label>
                                                </div>
                                            </td>
                                            <td class="text-center" >
                                                <a href="javascript:void(0)" class="text-success" v-if="activity_attribute.status"
                                                    @click="editActivityAttribute(activity_attribute)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <select class="form-select from-select-sm width-75" v-model="meta.per_page" @change="onPerPageChange">
                                    <option>10</option>
                                    <option>15</option>
                                    <option>20</option>
                                    <option>25</option>
                                    <option>30</option>
                                </select>
                                <span>Showing {{ meta.from }} to {{ meta.to }} of {{ meta.totalRows }} entries</span>
                                <Pagination :maxPage="meta.maxPage" :totalPages="meta.lastPage" :currentPage="meta.page" @pagechanged="onPageChange" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
    <script>
    import Pagination from "@/components/Pagination.vue";
    export default {
        components: {
            Pagination
        },
        data() {
            return {
                meta: {
                    search: '',
                    order_by: "desc",
                    keyword: "activity_attribute_id",
                    per_page: 10,
                    totalRows: 0,
                    page: 1,
                    lastPage: 1,
                    from: 1,
                    to: 1,
                    maxPage: 1,
                    trashed: false,
                },
                activity_attributes: [],
                errors: [],
                status: true,
            }
        },

        mounted() {
            this.index();
        },
         beforeRouteEnter(to, from, next) {
             next((vm) => {
                if(from.name == 'ActivityAttributes.Edit'){
                    vm.meta.page = vm.$store.getters.current_page
                }else{
                    vm.meta.page = 1
                }
            });
        },
        methods: {
            index() {
                let vm = this;
                let loader = this.$loading.show();
                this.$store.dispatch('post', { uri: 'paginateActivityAttributes', data: vm.meta })
                    .then(response => {
                        loader.hide();
                        this.activity_attributes = response.data.data;
                        this.meta.totalRows = response.data.meta.total;
                        this.meta.from = response.data.meta.from;
                        this.meta.lastPage = response.data.meta.last_page;
                        this.meta.maxPage = vm.meta.lastPage >= 3 ? 3 : vm.meta.lastPage;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            editActivityAttribute(activity_attribute) {
                this.$store.commit("setCurrentPage", this.meta.page)
                this.$router.push("/activity_attributes/" + activity_attribute.activity_attribute_id + "/edit");
            },

            deleteActivityAttribute(activity_attribute) {
                let vm = this;
                let loader = vm.$loading.show();
                vm.$store
                    .dispatch("post", {
                        uri: "deleteActivityAttribute",
                        data: activity_attribute
                    })
                    .then((response) => {
                        loader.hide();
                        vm.$store.dispatch("success", response.data.message);
                        vm.index();
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            search() {
                let vm = this;
                vm.meta.page = 1;
                vm.index();
            },

            onPageChange(page) {
                this.meta.page = page;
                this.index();
            },
            sort(field) {
                this.meta.keyword = field;
                this.meta.order_by = this.meta.order_by == "asc" ? "desc" : "asc";
                this.index();
            },

        }
    }
    </script>
