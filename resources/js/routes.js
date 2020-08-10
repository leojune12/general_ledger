import Vue from "vue";
import VueRouter from "vue-router";
import component from "vuetify/lib/util/component";
import ExampleComponent from "./components/ExampleComponent";

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: '',
            component: ExampleComponent,
        }
    ]
});

export default router
