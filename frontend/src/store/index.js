import { createStore } from 'vuex'
import master from './modules/master'
import inspections from './modules/inspections'

export default createStore({
  modules: {
    master,
    inspections,
  },
})
