import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const auth = {
  namespaced: true,
  state: {
    edit: false
  },
  mutations: {
    setEdit(state, edit) {
      state.edit = edit;
    },
  }
}

const store = new Vuex.Store({
  modules: {
    auth: auth
  }
})

export default store;