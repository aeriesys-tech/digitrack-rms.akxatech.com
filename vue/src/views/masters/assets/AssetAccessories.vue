<template>
    <div class="">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div>
                <ol class="breadcrumb fs-sm mb-1">
                    <li class="breadcrumb-item" aria-current="page">
                        <router-link to="/dashboard">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0)">Masters</a>
                    </li>
                    <li class="breadcrumb-item"><router-link to="/assets">Assets</router-link></li>
                    <li class="breadcrumb-item active" aria-current="page">Accessories</li>
                </ol>
                <h4 class="main-title mb-0">Assets</h4>
            </div>
            <router-link to="/assets" type="submit" class="btn btn-primary" style="float: right;"><i class="ri-list-check"></i> ASSETS</router-link>
        </div>
        <div class="row g-2">
            <div class="col-12 mb-2">
                <div class="card card-one">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="card-title">Accessories</h6>
                    </div>
                    <div class="card-body">
                        <div class="col-12">
                            <div class="table-responsive table-responsive-sm">
                                <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                    <thead>
                                        <tr class="">
                                            <th class="text-center">#</th>
                                            <th>
                                                Asset Zone
                                                <span>
                                                    <i v-if="accessory_meta_service.keyword=='asset_zone_id' && accessory_meta_service.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="accessory_meta_service.keyword=='asset_zone_id' && accessory_meta_service.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span>
                                            </th>
                                            <th>
                                                Accessory Type
                                                <span>
                                                    <i v-if="accessory_meta_service.keyword=='accessory_type_id' && accessory_meta_service.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="accessory_meta_service.keyword=='accessory_type_id' && accessory_meta_service.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span>
                                            </th>
                                            <th>
                                                Accessory Name
                                                <span>
                                                    <i v-if="accessory_meta_service.keyword=='accessory_name' && accessory_meta_service.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="accessory_meta_service.keyword=='accessory_name' && accessory_meta_service.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span>
                                            </th>
                                            <th>
                                                Attachment
                                                <span>
                                                    <i v-if="accessory_meta_service.keyword=='attachment' && accessory_meta_service.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="accessory_meta_service.keyword=='attachment' && accessory_meta_service.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span>
                                            </th>
                                            <!-- <th class="text-center">Actions</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="asset_accessories?.length==0">
                                            <td colspan="5" class="text-center">No records found</td>
                                        </tr>
                                        <tr v-for="accessory, key in asset_accessories" :key="key">
                                            <td class="text-center">{{ accessory_meta_service.from + key }}</td>
                                            <td>{{ accessory.asset_zone?.zone_name }}</td>
                                            <td>{{ accessory.accessory_type.accessory_type_name }}</td>
                                            <td>{{ accessory.accessory_name }}</td>
                                            <td>  <a v-if="accessory.attachment" :href="getAttachmentUrl(accessory.attachment)" target="_blank" rel="noopener noreferrer">
                                                {{ getAttachmentName(accessory.attachment) }}
                                                </a>
                                                <span v-else>No attachment</span>
                                            </td>
                                            <!-- <td class="text-center">
                                                <a href="javascript:void(0)" class="text-danger me-2" @click="deleteAccessory(accessory)"><i class="ri-delete-bin-6-line fs-18 lh-1"></i></a>
                                            </td> -->
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <select class="form-select from-select-sm width-75" v-model="accessory_meta_service.per_page" @change="onPerPageChange">
                                <option>5</option>
                                <option>10</option>
                                <option>15</option>
                                <option>20</option>
                                <option>25</option>
                                <option>30</option>
                            </select>
                            <span>Showing {{ accessory_meta_service.from }} to {{ accessory_meta_service.to }} of {{ accessory_meta_service.totalRows }} entries</span>
                            <Pagination :maxPage="accessory_meta_service.maxPage" :totalPages="accessory_meta_service.lastPage" :currentPage="accessory_meta_service.page" @pagechanged="onPageChange" />
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
    name: "Assets.Accessories",
    data() {
        return {
            asset_accessories:[],
            accessory_meta_service: {
                search: "",
                order_by: "asc",
                keyword: "asset_accessory_id",
                per_page: 5,
                totalRows: 0,
                page: 1,
                lastPage: 1,
                from: 1,
                to: 1,
                maxPage: 1,
                trashed: false,
                asset_id: "",
                asset_type_id:'',
            },

        }
    },
    mounted(){
        this.index()
    },
    methods:{
        index() {
            let vm = this;
            vm.accessory_meta_service.asset_id = vm.$store.getters.asset_id;
            let loader = vm.$loading.show();
            vm.$store
                .dispatch("post", { uri: "paginateAssetAccessories", data: vm.accessory_meta_service })
                .then((response) => {
                    loader.hide();
                    console.log("access reposne", response.data)
                    vm.initial_status = false;
                    vm.asset_accessories = response.data.paginate_accessories;
                    vm.accessory_meta_service.totalRows = response.data.meta.total;
                    // vm.accessory_meta_service.from = response.data.meta.from;
                    vm.accessory_meta_service.lastPage = response.data.meta.last_page;
                    vm.accessory_meta_service.maxPage = vm.accessory_meta_service.lastPage >= 3 ? 3 : vm.accessory_meta_service.lastPage;
                })
                .catch(function (error) {
                    loader.hide();
                    console.log("errors accessories--",error)
                    // vm.errors = error.response.data.errors;
                    // vm.$store.dispatch("error", error.response.data.message);
                });
        },
        getAttachmentUrl(attachment) {
            return `${attachment}`;
        },
        getAttachmentName(attachment) {
            return attachment.split('/').pop();
        }
    }
 }

    

</script>