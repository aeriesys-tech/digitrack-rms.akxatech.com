<template>
     <div class="card card-one">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="card-title">Process Registers</h6>
                    </div>
                    <div class="card-body">
                        <input class="form-control form-control-sm mb-2" type="text"
                            placeholder="Type keyword and press enter key" v-model="meta.search"
                            @keypress.enter="search()" />
                        <div class="table-responsive table-responsive-sm">
                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                <thead>
                                    <tr class="" style="background-color: #9b9b9b; color: white;">
                                        <th class="text-center">#</th>
                                        <th @click="sort('asset_id')">
                                            Asset
                                            <span>
                                                <i v-if="meta.keyword == 'asset_id' && meta.order_by == 'asc'"
                                                    class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'asset_id' && meta.order_by == 'desc'"
                                                    class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th @click="sort('reference_no')">
                                            Job No.
                                            <span>
                                                <i v-if="meta.keyword == 'reference_no' && meta.order_by == 'asc'"
                                                    class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'reference_no' && meta.order_by == 'desc'"
                                                    class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                        <th @click="sort('reference_date')">
                                            Job Date & Time
                                            <span>
                                                <i v-if="meta.keyword == 'reference_date' && meta.order_by == 'asc'"
                                                    class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword == 'reference_date' && meta.order_by == 'desc'"
                                                    class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="user_variable, key in user_variables" :key="key">
                                        <td class="text-center">{{ meta.from + key }}</td>
                                        <td @click="showProcess(user_variable, 'process')"><a href="javascript:void(0)">{{ user_variable.asset?.asset_code }}</a></td>
                                        <td>{{ user_variable.job_no }}</td>
                                        <td>{{convertDateFormat( user_variable.job_date) }}</td>
                                    </tr>
                                    <tr v-if="user_variables.length == 0">
                                        <td colspan="5" class="text-center">No records found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <select class="form-select from-select-sm width-75" v-model="meta.per_page"
                                @change="onPerPageChange">
                                <option>10</option>
                                <option>15</option>
                                <option>20</option>
                                <option>25</option>
                                <option>30</option>
                            </select>
                            <span>Showing {{ meta.from }} to {{ meta.to }} of {{ meta.totalRows }} entries</span>
                            <Pagination :maxPage="meta.maxPage" :totalPages="meta.lastPage" :currentPage="parseInt(meta.page)"
                                @pagechanged="onPageChange" />
                        </div>
                    </div>
                </div>
</template>
<script>
import Pagination from "@/components/Pagination.vue";
import moment from "moment";
export default {
    name: "ProcessRegisters.Index",
    components: {
        Pagination,
    },
     props: {
            meta1: {
                type: Object,
            },
        },
    data() {
        return {
            status: true,
            meta: {
                search: "",
                order_by: "desc",
                keyword: "user_variable_id",
                per_page: 10,
                totalRows: 0,
                page: 1,
                lastPage: 1,
                from: 1,
                to: 1,
                maxPage: 1,
                trashed: false,
            },
            user_variables: [],
            errors: [],
            status: true,
        }
    },
    mounted() {
        // this.get_activity = this.$store.getters.permissions.filter(function (element) {
        //     return element.ability.ability.includes("userActivities.update") || element.ability.ability.includes("userActivities.delete");
        // });
        this.$store.commit("setAssetId", '');
        this.index();
    },
    methods: {
        index() {
            let vm = this;
            let loader = vm.$loading.show();
             vm.meta.asset_type_id = vm.meta1.asset_type_id;
                vm.meta.asset_id = vm.meta1.asset_id;
                vm.meta.department_id = vm.meta1.department_id;
                vm.meta.from_date = vm.meta1.from_date;
                vm.meta.to_date = vm.meta1.to_date;
                vm.meta1.keyword = "user_variable_id";
            vm.$store
                .dispatch("post", { uri: "paginateUserVariables", data: vm.meta })
                .then((response) => {
                    loader.hide();
                    vm.user_variables = response.data.data;
                    vm.meta.totalRows = response.data.meta.total;
                    vm.meta.from = response.data.meta.from;
                    vm.meta.lastPage = response.data.meta.last_page;
                    vm.meta.maxPage = vm.meta.lastPage >= 3 ? 3 : vm.meta.lastPage;
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },
        showProcess(user_variable, type) {
            console.log("process",user_variable)
            let vm = this;
            this.$store.commit("setCurrentPage", this.meta.page)
            this.$router.push("/asset_reports/" + type + '/' + user_variable.user_variable_id + "/view");
        },
        convertDateFormat(date) {
            let vm = this;
            return moment(date).format("yyyy-MM-DD HH:mm");
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
        onPerPageChange() {
            let vm = this;
            vm.meta.page = 1;
            vm.index();
        },
    }
}
</script>