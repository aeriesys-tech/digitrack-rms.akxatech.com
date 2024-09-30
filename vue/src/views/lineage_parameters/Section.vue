<template>
    <div class="">
        <div>
            <ol class="breadcrumb fs-sm mb-1">
                <li class="breadcrumb-item">
                    <router-link to="/dashboard">Dashboard</router-link>
                </li>
                <li class="breadcrumb-item">
                    <a href="javascript:void(0)">Lineage Parameters</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Sections</li>
            </ol>
            <h4 class="main-title mb-2">Sections</h4>
        </div>
        <div class="row g-2">
            <div class="col-4" v-can="'sections.create'">
                <form @submit.prevent="submitForm()">
                    <div class="card card-one">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title" v-if="status">Add Section</h6>
                            <h6 class="card-title" v-else>Update Section</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-12">
                                    <label class="form-label">Section Code</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Section Code" class="form-control" :class="{ 'is-invalid': errors.section_code }"
                                        v-model="section.section_code" ref="section_code" />
                                    <span v-if="errors.section_code" class="invalid-feedback">{{ errors.section_code[0] }}</span>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Section Name</label><span class="text-danger"> *</span>
                                    <input type="text" placeholder="Section" class="form-control" :class="{ 'is-invalid': errors.section_name }"
                                        v-model="section.section_name" />
                                    <span v-if="errors.section_name" class="invalid-feedback">{{ errors.section_name[0] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="button" class="btn btn-danger me-2" @click="discard()"><i class="ri-close-line fs-18 lh-1"></i> Discard</button>
                            <button type="submit" class="btn btn-primary">
                                <span v-if="status"><i class="ri-save-line fs-18 lh-1"></i> Submit</span>
                                <span v-else><i class="ri-save-line fs-18 lh-1"></i> Update</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div :class="column">
                <div class="card card-one">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="card-title">Sections</h6>
                    </div>
                    <div class="card-body mb-3">
                        <input class="form-control mb-2" type="text" placeholder="Type keyword and press enter key" v-model="meta.search" @keypress.enter="search()" />
                        <div class="table-responsive table-responsive-sm">
                            <table class="table table-sm text-nowrap table-striped table-bordered mb-0">
                                <thead>
                                    <tr class="" style="background-color: #9b9b9b; color: white;">
                                        <th class="text-center">#</th>
                                        <th @click="sort('section_code')">Section Code
                                            <span>
                                                <i v-if="meta.keyword=='section_code' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='section_code' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span></th>
                                        <th @click="sort('section_name')">Section Name

                                            <span>
                                                <i v-if="meta.keyword=='section_name' && meta.order_by=='asc'" class="ri-arrow-up-line"></i>
                                                <i v-else-if="meta.keyword=='section_name' && meta.order_by=='desc'" class="ri-arrow-down-line"></i>
                                                <i v-else class="fas fa-sort"></i>
                                            </span></th>

                                        <th class="text-center" v-can="'sections.delete'">Status</th>
                                        <th class="text-center" v-can="'sections.update'">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="section, key in sections" :key="key">
                                        <td class="text-center">{{ meta.from + key }}</td>
                                        <td>{{ section.section_code }}</td>
                                        <td>{{ section.section_name }}</td>
                                        <td style="display: flex; justify-content: center;" v-can="'sections.delete'">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    :checked="section.status" :value="section.status"
                                                    @change="deleteSection(section)">
                                            </div>
                                        </td>
                                        <td class="text-center" v-can="'sections.update'">
                                            <a v-if="section.status" href="javascript:void(0)" class="text-success me-2"
                                                @click="editSection(section)"><i class="ri-pencil-line fs-18 lh-1"></i></a>
                                            <div v-else style="display: inline; margin-right: 20px;">&nbsp;</div>
                                        </td>
                                    </tr>
                                    <tr v-if="sections.length == 0">
                                        <td colspan="5" class=" text-center">No Records to display</td>
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
                order_by: "asc",
                keyword: "section_id",
                per_page: 10,
                totalRows: 0,
                page: 1,
                lastPage: 1,
                from: 1,
                maxPage: 1,
            },
            sections: [],
            section: {
                section_id: '',
                section_code: '',
                section: '',
                status: '',
                description: ''
            },
            errors: [],
            column:'col-8',
            status: true,
        }
    },
    mounted() {
        this.create_section = this.$store.getters.permissions.filter(function(element){
            return element.ability.ability.includes('sections.create')
        })
        if(this.create_section.length){
            this.column = 'col-8'
        }else{
            this.column = 'col-12'
        }
        this.index();
        this.$refs.section_code.focus();
    },

    methods: {
        submitForm() {
            let vm = this;
            if (vm.status) {
                vm.addSection();
            } else {
                vm.updateSection();
            }
        },
        index() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'paginateSections', data: vm.meta })
                .then(response => {
                    loader.hide();
                    vm.sections = response.data.data;
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
        addSection() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'addSection', data: vm.section })
                .then(response => {
                    loader.hide();
                    vm.$store.dispatch('success', response.data.message);
                    vm.discard();
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },

        deleteSection(section) {
            let vm = this;
            section.status = section.status == 1 ? 0 : 1;
            vm.$store
                .dispatch("post", { uri: "deleteSection", data: section })
                .then(function (response) {
                    vm.$store.dispatch('success', response.data.message);
                    vm.discard();
                })
                .catch(function (error) {
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
        },

        editSection(section) {
            this.section = section;
            this.status = false;
            this.$refs.section_code.focus();
        },

        updateSection() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'updateSection', data: vm.section })
                .then(response => {
                    loader.hide();
                    vm.update = false;
                    vm.$store.dispatch('success', response.data.message);
                    vm.discard();
                })
                .catch(function (error) {
                    loader.hide();
                    vm.errors = error.response.data.errors;
                    vm.$store.dispatch("error", error.response.data.message);
                });
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
        discard() {
            let vm=this;
            vm.section = {};
            vm.$refs.section_code.focus();
            vm.errors = [];
            vm.status = true;
            vm.index();
        },
        onPerPageChange() {
            let vm = this;
            vm.meta.page = 1;
            vm.index();
        },
        search() {
            let vm = this;
            vm.meta.page = 1;
            vm.index();
        },
    }
}
</script>