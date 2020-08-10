import Vue from 'vue'
import Vuex from 'vuex'
import actions from './actions'
import mutations from './mutations'
import getters from './getters'
import state from "./state";

Vue.use(Vuex);

export default new Vuex.Store({
    //strict: true, //disable in production
    state,
    mutations,
    getters,
    actions
})
