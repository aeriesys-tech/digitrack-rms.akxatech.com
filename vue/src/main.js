import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";

import {LoadingPlugin} from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/css/index.css';
import 'remixicon/fonts/remixicon.css';

const app = createApp(App);
app.use(store);
app.use(router);
app.use(LoadingPlugin);
app.directive('can',{
    mounted: function(el, binding){
            let permissions = store.getters.permissions;
            let user = store.getters.user;
            if(permissions && permissions.length != 0){
                let permission = permissions.filter(element => {
                    if(user){
                        return (element.ability?.ability == binding.value)
                    }
                })          
                if(!permission[0]){
                    el.parentNode.removeChild(el);
                }
            }
            else{
                el.parentNode.removeChild(el);
            }
    }
});


app.mount('#app');