let actions = {
    SET_DRAWER({commit}, payload) {
        commit('SET_DRAWER_MUTATION', payload)
    },

    SET_DRAWER_LINKS({commit}, payload) {
        commit('SET_DRAWER_LINKS_MUTATION', payload)
    },

    SUCCESS_SNACKBAR({commit}, payload) {
        commit('SUCCESS_SNACKBAR_MUTATION', payload)
    },

    ERROR_SNACKBAR({commit}, payload) {
        commit('ERROR_SNACKBAR_MUTATION', payload)
    },

    TOGGLE_SNACKBAR({commit}, payload) {
        commit('TOGGLE_SNACKBAR_MUTATION')
    },

    OPEN_LOADING_DIALOG({commit}, payload) {
        commit('TOGGLE_LOADING_DIALOG_MUTATION', true)
    },

    CLOSE_LOADING_DIALOG({commit}, payload) {
        commit('TOGGLE_LOADING_DIALOG_MUTATION', false)
    }
}

export default actions
