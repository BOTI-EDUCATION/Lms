/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");
window.Vue = require("vue");
import AppBorne from "./AppBorne.vue";

import "es6-promise/auto";
import VueAxios from "vue-axios";
import VueRouter from "vue-router";
import router from "./routerBorne";
import DefaultWeb from "./layouts/DefaultWeb.vue";
import VueSlimScroll from 'vue-slimscroll';
Vue.use(VueSlimScroll);

Vue.component("default-layout", DefaultWeb);
Vue.component("no-sidebar-layout", DefaultWeb);

// Set Vue globally
window.Vue = Vue;
import vuetify from './vuetify' // path to vuetify export

// Set Vue router
Vue.router = router;
Vue.use(VueRouter);


import "./session";

Vue.use(require("vue-moment"));
axios.defaults.baseURL = document.querySelector("meta[name=base_api]").getAttribute('content') + '/lms_api';


// Set Vue authentication
Vue.use(VueAxios, axios);


// Load Index
Vue.component("validation-errors", {
    data() {
        return {};
    },
    props: ["errors"],
    template: `<div v-if="validationErrors">
                <ul class="alert alert-danger">
                    <li v-for="(value, key, index) in validationErrors">{{ value }}</li>
                </ul>
            </div>`,
    computed: {
        validationErrors() {
            let errors = Object.values(this.errors);
            errors = errors.flat();
            return errors;
        }
    }
});


axios
    .get('init')
    .then((res) => {
        const app = new Vue({
            router,
            vuetify,
            created() {
                const details = { baseurl: document.querySelector("meta[name=base_api]").getAttribute('content'), ...res.data };
                this.$session.config = details;
            },
            render: h => h(AppBorne)
        }).$mount("#borne");
    })
    .catch((error) => console.log(error));




/*
  const app = new Vue({
      el: '#app_web',
      router: router
  });
*/
