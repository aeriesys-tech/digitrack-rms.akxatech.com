import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";

import { LoadingPlugin } from 'vue-loading-overlay';
import PrimeVue from 'primevue/config';
import 'vue-loading-overlay/dist/css/index.css';
import 'remixicon/fonts/remixicon.css';

import Highcharts from 'highcharts';
import HighchartsVue from 'highcharts-vue';

const app = createApp(App);
app.use(store);
app.use(router);
app.use(PrimeVue);
app.use(HighchartsVue);
app.use(LoadingPlugin);
// app.directive('can', {
//     mounted: function (el, binding) {
//         let permissions = store.getters.permissions;
//         let user = store.getters.user;
//         if (permissions && permissions.length != 0) {
//             let permission = permissions.filter(element => {
//                 if (user) {
//                     return (element.ability?.ability == binding.value)
//                 }
//             })
//             if (!permission[0]) {
//                 el.parentNode.removeChild(el);
//             }
//         }
//         else {
//             el.parentNode.removeChild(el);
//         }
//     }
// });
// app.directive('can', {
//     mounted(el, binding) {
//         let permissions = store.getters.permissions;
//         let user = store.getters.user;

//         if (!permissions || permissions.length === 0 || !user) {
//             el.parentNode?.removeChild(el);
//             return;
//         }

//         let requiredPermissions = Array.isArray(binding.value) ? binding.value : [binding.value];

//         let hasPermission = requiredPermissions.some(permission =>
//             permissions.some(element => element.ability?.ability === permission)
//         );

//         if (!hasPermission) {
//             el.parentNode?.removeChild(el);
//         }
//     }
// });
app.directive('can', {
    mounted(el, binding) {
        let permissions = store.getters.permissions;
        let user = store.getters.user;

        // If no permissions or no user, remove the element
        if (!permissions || permissions.length === 0 || !user) {
            el.parentNode?.removeChild(el);
            return;
        }

        // Ensure binding value is always an array, even if it's a single string
        let requiredPermissions = Array.isArray(binding.value) ? binding.value : [binding.value];

        // Check if the user has any of the required permissions
        let hasPermission = requiredPermissions.some(permission =>
            permissions.some(element => element.ability?.ability === permission)
        );

        // Remove the element if the user does not have the required permission
        if (!hasPermission) {
            el.parentNode?.removeChild(el);
        }
    }
});




app.mount('#app');