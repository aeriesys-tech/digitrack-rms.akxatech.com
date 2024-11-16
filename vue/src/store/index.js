import { createStore } from 'vuex';
import { createToast } from "mosha-vue-toastify";
import "mosha-vue-toastify/dist/style.css";
import axios from "axios";

export default createStore({
  state: {
    // apiUrl:"https://digitrack-rms.akxatech.com/api/",
    apiUrl: "http://localhost/digitrack-rms.akxatech.com/laravel/public/api/",
    // apiUrl: "http://172.28.224.1/digitrack-rms.akxatech.com/laravel/public/api/",
    user: null,
    token: '',
    permissions: [],
    current_page: '',
    page: '',
    asset_id: '',
    assets: '',
    meter: '50',
    h_meter: '20',
    asset_templates: '',
    asset_template_id: '',
  },
  getters: {
    user(state) {
      return state.user;
    },
    token(state) {
      return state.token;
    },
    permissions(state) {
      return state.permissions;
    },
    page(state) {
      return state.page;
    },
    current_page(state) {
      return state.current_page;
    },
    asset_id(state) {
      return state.asset_id;
    },
    assets(state) {
      return state.assets;
    },
    meter(state) {
      return state.meter;
    },
    asset_template_id(state) {
      return state.asset_template_id;
    },
    asset_templates(state) {
      return state.asset_templates;
    },
  },
  mutations: {
    setUser(state, user) {
      state.user = user;
    },
    setToken(state, token) {
      state.token = token;
    },
    setPermissions(state, permissions) {
      state.permissions = permissions;
    },
    setPage(state, page) {
      state.page = page;
    },
    setCurrentPage(state, current_page) {
      state.current_page = current_page;
    },
    setAssetId(state, asset_id) {
      state.asset_id = asset_id;
    },
    setAssets(state, assets) {
      state.assets = assets;
    },
    setMeter(state, meter) {
      state.meter = meter;
    },
    setAssetTemplateId(state, asset_template_id) {
      state.asset_template_id = asset_template_id;
    },
    setAssetTemplates(state, asset_templates) {
      state.asset_templates = asset_templates;
    },
  },
  actions: {
    async setUser(context, payload) {
      await context.commit("setUser", payload);
    },
    async setToken(context, payload) {
      await context.commit('setToken', payload);
    },
    async setPermissions(context, payload) {
      await context.commit('setPermissions', payload);
    },
    async setPage(context, payload) {
      await context.commit('setPage', payload);
    },
    async setCurrentPage(context, payload) {
      await context.commit('setCurrentPage', payload);
    },
    async setAssetId(context, payload) {
      await context.commit('SetAssetID', payload);
    },
    async setAssets(context, payload) {
      await context.commit('setAssets', payload);
    },
    async setMeter(context, payload) {
      await context.commit('setMeter', payload);
    },
    async setAssetTemplateId(context, payload) {
      await context.commit('SetAssetTemplateID', payload);
    },
    async setAssetTemplates(context, payload) {
      await context.commit('setAssetTemplates', payload);
    },
    async logout(context) {
      await context.commit('setUser', null);
      await context.commit('setToken', "");
      await context.commit('setPermissions', null);
    },
    auth(context, payload) {
      return new Promise((resolve, reject) => {
        axios.post(this.state.apiUrl + payload.uri, payload.data)
          .then(function (response) {
            resolve(response);
          })
          .catch(function (error) {
            reject(error);
          });
      });
    },
    get(context, payload) {
      return new Promise((resolve, reject) => {
        axios
          .get(this.state.apiUrl + payload.uri)
          .then(function (response) {
            resolve(response);
          })
          .catch(function (error) {
            reject(error);
          });
      });
    },

    post(context, payload) {
      return new Promise((resolve, reject) => {
        axios.post(this.state.apiUrl + payload.uri, payload.data, {
          headers: {
            'Authorization': 'Bearer' + ' ' + context.getters.token
          }
        }).then(function (response) {
          resolve(response);
        })
          .catch(function (error) {
            reject(error);
            if (error?.response?.data?.message == "Unauthenticated.") {
              context.dispatch('logout');
              window.location.href = "/#/"
              window.location.reload();
            }
          });
      });
    },

    async error(context, description) {
      await createToast(
        {
          title: "Error",
          description: description || "The given data was invalid.",
        },
        {
          showIcon: true,
          hideProgressBar: true,
          type: "danger",
          position: "top-right",
        }
      );
    },

    async success(context, description) {
      await createToast(
        {
          title: "Success",
          description: description || "Data is successfuly subbmited.",
        },
        {
          showIcon: true,
          hideProgressBar: true,
          type: "success",
          position: "top-right",
        }
      );
    },

    async info(context, description) {
      await createToast(
        {
          title: "Info",
          description: description || "Not enoguh input data",
        },
        {
          showIcon: true,
          hideProgressBar: true,
          type: "info",
          position: "top-right",
        }
      );
    },
    async warning(context, description) {
      await createToast(
        {
          title: "Warning",
          description: description || "Not enoguh input data",
        },
        {
          showIcon: true,
          hideProgressBar: true,
          type: "warning",
          position: "top-right",
        }
      );
    },
  },
  modules: {
  }
})
