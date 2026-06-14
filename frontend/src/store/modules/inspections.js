import {
  listInspections,
  showInspection,
  createInspection,
  updateInspection,
  updateInspectionStatus,
} from '@/services/inspections'

const state = () => ({
  list: [],
  pagination: { current_page: 1, last_page: 1, per_page: 15, total: 0 },
  statusCounts: { open: 0, for_review: 0, completed: 0 },
  current: null,
  loading: false,
  error: null,
})

const mutations = {
  SET_LIST(state, { items, pagination, status_counts }) {
    state.list = items
    state.pagination = pagination
    state.statusCounts = status_counts
  },
  SET_CURRENT(state, inspection) {
    state.current = inspection
  },
  SET_LOADING(state, value) {
    state.loading = value
  },
  SET_ERROR(state, error) {
    state.error = error
  },
}

const actions = {
  async fetchList({ commit }, params = {}) {
    commit('SET_LOADING', true)
    commit('SET_ERROR', null)
    try {
      const response = await listInspections(params)
      commit('SET_LIST', response.data)
    } catch (error) {
      commit('SET_ERROR', error.message)
      throw error
    } finally {
      commit('SET_LOADING', false)
    }
  },
  async fetchOne({ commit }, { id, params = {} }) {
    commit('SET_LOADING', true)
    commit('SET_ERROR', null)
    try {
      const response = await showInspection(id, params)
      commit('SET_CURRENT', response.data)
      return response.data
    } catch (error) {
      commit('SET_ERROR', error.message)
      throw error
    } finally {
      commit('SET_LOADING', false)
    }
  },
  async create(_context, payload) {
    const response = await createInspection(payload)
    return response.data
  },
  async update(_context, { id, payload }) {
    const response = await updateInspection(id, payload)
    return response.data
  },
  async updateStatus({ commit }, { id, status }) {
    const response = await updateInspectionStatus(id, status)
    commit('SET_CURRENT', response.data)
    return response.data
  },
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
}
