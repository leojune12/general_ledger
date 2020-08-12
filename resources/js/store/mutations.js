let mutations = {
    SET_DRAWER_MUTATION(state, payload) {
        state.drawer = payload
    },

    SET_DRAWER_LINKS_MUTATION(state, payload) {
        state.drawerLinks[payload] = true
    },

    SUCCESS_SNACKBAR_MUTATION(state, payload) {
        state.snackbar['text'] = payload
        state.snackbar['color'] = 'success'
        state.snackbar['value'] = false
        setTimeout(function(){
            state.snackbar['value'] = true
        }, 600)
    },

    ERROR_SNACKBAR_MUTATION(state, payload) {
        state.snackbar['text'] = payload
        state.snackbar['color'] = 'error'
        state.snackbar['value'] = false
        setTimeout(function(){
            state.snackbar['value'] = true
        }, 600)
    },

    TOGGLE_SNACKBAR_MUTATION(state) {
        state.snackbar.value = !state.snackbar.value
    },

    TOGGLE_LOADING_DIALOG_MUTATION(state, payload) {
        state.loadingDialog = payload
    }
}

export default mutations
