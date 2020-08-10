
require('./bootstrap');

window.Vue = require('vue');
window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
};

import 'es6-promise/auto';
import store from "./store/index";
import vuetify from "../plugins/vuetify";
import VueRouter from "./routes";

Vue.component('example-component', require('./components/ExampleComponent').default);

const app = new Vue({
    el: '#app',
    store,
    vuetify,
    router: VueRouter
});
