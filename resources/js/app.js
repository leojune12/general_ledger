
require('./bootstrap');

window.Vue = require('vue');
window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
};

import 'es6-promise/auto';
import store from "./store/index";
import vuetify from "../plugins/vuetify";
import VueRouter from "./routes";

//import moment from 'moment'
//import moment from 'moment-timezone'
//moment.tz.setDefault('Asia/Manila')

//Vue.component('example-component', require('./components/ExampleComponent').default);
//Vue.component('app-layout', require('./components/core/AppLayout').default);
Vue.component('navigation-drawer', require('./components/core/NavigationDrawer').default);
Vue.component('app-bar', require('./components/core/AppBar').default);
Vue.component('miscellaneous', require('./components/core/Miscellaneous').default);

Vue.component('view-dashboard', require('./views/dashboard/ViewDashboard').default);
Vue.component('view-ledgers', require('./views/ledgers/ViewLedgers').default);

Vue.component('view-account-codes', require('./views/codes/ViewAccountCodes').default);
Vue.component('view-project-codes', require('./views/codes/ViewProjectCodes').default);

const app = new Vue({
    el: '#app',
    store,
    vuetify,
    router: VueRouter
});
