import {createStore} from "vuex";
import state from './state'
import * as actions from './actions'
import * as mutations from './mutations'


const store = createStore({
  state,
  getters: {
    approvedUsers: state => {
      console.log(state.user)
      return state.user.data
    }
  },
  actions,
  mutations,
})

export default store
