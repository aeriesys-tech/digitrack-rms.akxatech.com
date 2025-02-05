<template>
        <div class="">
            <div class="d-sm-flex align-items-center justify-content-between mb-2">
                <div>
                    <ol class="breadcrumb fs-sm mb-1">
                        <li class="breadcrumb-item" aria-current="page">
                            <router-link to="/dashboard">Dashboard</router-link></li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a href="javascript:void(0)">Downloaded Reports</a></li>
                        <!-- <li class="breadcrumb-item active" aria-current="page">Pendings/Overdues</li> -->
                    </ol>
                    <h4 class="main-title mb-0">Downloaded Reports</h4>
                </div>
                <!-- <router-link v-can="'users.create'" to="/user/create" class="btn btn-primary" style="float: right;"><i
                        class="ri-list-check"></i> ADD USER</router-link> -->
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title">Downloaded Reports</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <input class="form-control form-control-sm mb-2" type="text"
                                    placeholder="Type keyword and press enter key" v-model="meta.search" @keypress.enter="search()" />
                                </div>
                            </div>

                            <div class="table-responsive table-responsive-sm">
                                <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                    <thead>
                                        <tr style="background-color:#9b9b9b;color:white;">
                                            <th class="text-center">#</th>
                                            <th @click="sort('user_id')">
                                                Name
                                                <span>
                                                    <i v-if="meta.keyword == 'user_id' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword == 'user_id' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span>
                                            </th>
                                            <th @click="sort('report_name')">
                                                Report Name
                                                <span>
                                                    <i v-if="meta.keyword == 'report_name' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword == 'report_name' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span>
                                            </th>
                                            <th @click="sort('date_time')">
                                                Date & Time
                                                <span>
                                                    <i v-if="meta.keyword == 'date_time' && meta.order_by == 'asc'" class="ri-arrow-up-line"></i>
                                                    <i v-else-if="meta.keyword == 'date_time' && meta.order_by == 'desc'" class="ri-arrow-down-line"></i>
                                                    <i v-else class="fas fa-sort"></i>
                                                </span>
                                            </th>
                                            <th class="text-center" v-can="['downloaded_reports.download','downloaded_reports.delete']">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="downloaded_reports.length==0">
                                            <td colspan="10" class="text-center">No records found</td>
                                        </tr>
                                        <tr v-for="downloaded_report, key in downloaded_reports" :key="key">
                                            <td class="text-center">{{ meta.from + key }}</td>
                                            <td>{{ downloaded_report?.user?.name }}</td>
                                             <td>{{downloaded_report?.report_name}}</td>
                                            <td>{{downloaded_report?.date_time}}</td>
                                            <td class="text-center" v-can="['downloaded_reports.download','downloaded_reports.delete']">
                                                <a v-can="'downloaded_reports.download'" href="javascript:void(0)" class="text-success" @click="downloadReport(downloaded_report.file_name)"><i class="ri-download-line fs-18 lh-1 me-2"></i></a>
                                                <a v-can="'downloaded_reports.delete'" href="javascript:void(0)" class="text-danger" @click="deleteReport(downloaded_report)"><i class="ri-delete-bin-6-line fs-18 lh-1"></i></a>
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
                    keyword: "download_report_id",
                    per_page: 10,
                    totalRows: 0,
                    page: 1,
                    lastPage: 1,
                    from: 1,
                    to: 1,
                    maxPage: 1,
                },
                downloaded_reports: [],
                errors: [],
                status: true,
            }
        },
        mounted() {
            this.index();
        },

        methods: {
            index() {
                let vm = this;
                let loader = this.$loading.show();
                this.$store.dispatch('post', { uri: 'paginateDownloadReports', data: vm.meta })
                    .then(response => {
                        loader.hide();
                        vm.downloaded_reports = response.data.data;
                        vm.meta.totalRows = response.data.meta.total;
                        vm.meta.from = response.data.meta.from;
                        vm.meta.lastPage = response.data.meta.last_page;
                        const end = vm.meta.from + Number(vm.meta.per_page) - 1;
                        vm.meta.to = end > vm.meta.totalRows ? vm.meta.totalRows : end;
                        vm.meta.maxPage = vm.meta.lastPage >= 3 ? 3 : vm.meta.lastPage;
                    })
                    .catch(function (error) {
                        loader.hide();
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            },
            downloadReport(file_path) {
                window.open(file_path);
            },
             deleteReport(data) {
                let vm = this;
                 let loader = vm.$loading.show();
                if (confirm("Do You want to Delete this Record?..")) {
                    let uri = { uri: "deleteDownloadReport", data: data };
                    vm.$store
                        .dispatch("post", uri)
                        .then(function (response) {
                            loader.hide();
                            vm.$store.dispatch("success", response.data.message);
                            vm.index();
                        })
                        .catch(function (error) {
                            loader.hide();
                            vm.errors = error.response.data.errors;
                            vm.$store.dispatch("error", error.response.data.message);
                        });
                }
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
            onPerPageChange() {
                let vm = this;
                vm.meta.page = 1;
                vm.index();
            },
            sort(field) {
                this.meta.keyword = field;
                this.meta.order_by = this.meta.order_by == "asc" ? "desc" : "asc";
                this.index();
            },

        }
    }
    </script>
