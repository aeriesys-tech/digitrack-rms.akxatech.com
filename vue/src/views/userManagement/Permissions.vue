<template>
    <div>
    <div class="d-sm-flex align-items-center justify-content-between mb-2">
        <div>
            <ol class="breadcrumb fs-sm mb-1">
                <li class="breadcrumb-item" aria-current="page">
                    <router-link to="/dashboard">Dashboard</router-link>
                </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="javascript:void(0)">User Management</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Permissions</li>
                </ol>
            <h4 class="main-title mb-0">Permissions</h4>
        </div>
        <div class="d-flex align-items-center gap-2 mt-3 mt-md-0">
            <button type="button" class="btn btn-primary d-flex align-items-center gap-2" @click="addPermissions()">
                <i class="ri-git-repository-private-line fs-18 lh-1"></i> Add Permissions
            </button>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-sm-12">
            <div class="card card-one">
                <div class="card-header">
                    <h6 class="card-title">Permissions</h6>
                    <nav class="nav ms-auto">
                        <select class="form-control" :class="{ 'is-invalid': errors.role_id }" v-model="role.role_id" @change="getRoleAbilities">
                            <option value="">Select Role</option>
                            <option v-for="role, key in roles" :key="key" :value="role.role_id"> {{ role.role }}</option>
                        </select>
                    </nav>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6" v-for="role_ability, i in role_abilities" :key="i">
                            <div class="table-responsive text-wrap">
                                <table class="table table-sm table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th colspan="4" class="bold text-primary">
                                                {{ role_ability[0]?.module.module_name }}
                                            </th>
                                        </tr>
                                        <tr class="text-wrap">
                                            <th width="5%" class="text-center">#</th>
                                            <th width="40%">Ability</th>
                                            <th width="40%">Description</th>
                                            <th width="5%" class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="ability, j in role_ability" :key="j">
                                            <td class="text-center">{{ j + 1 }}</td>
                                            <td>{{ getAbilityStatus(role_ability, ability) }}</td>
                                            <td>{{ ability.description }}</td>
                                            <td class="text-center">
                                                <div class=" form-switch">
                                                    <input class="form-check-input " type="checkbox" role="switch" :disabled="ability.is_disabled" :checked="ability.status" :value="ability.status" @change="deleteAuthorization(role_ability, ability,$event)" />
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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
    data() {
        return {
            roles: [],
            role_abilities: [],
            errors: [],
            role:{
                role_id: "",
            },
        };
    },
    mounted() {
        let vm = this;
        vm.role.role_id = vm.$store?.getters?.user?.role_id;
        vm.getRoles();
    },
    methods: {
        getAbilityStatus(role_ability, ability){
            ability.is_disabled = false
            let temp_status = role_ability.filter(function(element){
                let temp_ability = element.ability.split(".")
                if(temp_ability.length && temp_ability[1]== 'view'){
                    return element
                }
            })
            if(temp_status.length){
                if(temp_status[0].status == false){
                    ability.is_disabled = true
                }
            }
            let ability_view = ability.ability.split(".")
            if(ability_view.length){
                if(ability_view[1] == 'view'){
                    ability.is_disabled = false
                }
            }
            return ability.ability
        },
        getRoles() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'getRoles',data:vm.role })
            .then(response => {
                loader.hide();
                vm.roles = response.data.data
                vm.getRoleAbilities();
            })
            .catch(error => {
                loader.hide();
                vm.errors = error.response.data.errors;
                vm.$store.dispatch("error", error.response.data.message);
            })
        },

        getRoleAbilities() {
            let vm = this;
            let loader = vm.$loading.show();
            this.$store.dispatch('post', { uri: 'getPermissionStatus', data: { 'role_id': vm.role.role_id }})
            .then(response => {
                loader.hide();
                vm.role_abilities = response.data;
            })
            .catch(error => {
                loader.hide();
                vm.errors = error.response.data.errors;
                vm.$store.dispatch('error', error.response.data.message);
            });
        },

        addPermissions() {
            let vm = this;
            let loader = vm.$loading.show();
            vm.$store.dispatch('post', { uri: 'addPermissions', data: { role_id: vm.role.role_id } })
            .then(response => {
                loader.hide();
                vm.$store.dispatch('success', 'Permissions updated successfully!');
                vm.getRoleAbilities();
            })
            .catch(error => {
                loader.hide();
                vm.errors = error.response.data.error;
                vm.$store.dispatch("error", error.response.data.message);
            })
        },

        deleteAuthorization(role_ability, authorization, even) {
            let vm = this;
            let temp_ability = authorization.ability.split(".")
            let role_abilities = []
            if(temp_ability.length){
                if(temp_ability[1] == 'view' && !even.target.checked){
                    role_ability.map(function(element){
                        element.status = false
                        role_abilities.push(element)
                    })
                }
            }
            let uri = { uri: 'deleteAuthorization',
                data: {
                    role_id: vm.role.role_id,
                    ability_id: authorization.ability_id,
                    status: authorization.status,
                    role_abilities: role_abilities
                }
            }
            vm.$store.dispatch("post", uri)
            .then(function(){
                vm.getRoleAbilities()
            })
            .catch(function (error) {
                vm.errors = error.response.data.error;
                vm.$store.dispatch("error", error.response.data.message);
            });
        }
    },
};
</script>



