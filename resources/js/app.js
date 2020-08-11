
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
Vue.component('app-layout', require('./components/core/AppLayout').default);
Vue.component('navigation-drawer', require('./components/core/NavigationDrawer').default);
Vue.component('app-bar', require('./components/core/AppBar').default);
Vue.component('view-content', require('./components/core/ViewContent').default);

const app = new Vue({
    el: '#app',
    store,
    vuetify,
    router: VueRouter
});
