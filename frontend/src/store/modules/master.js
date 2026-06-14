import { getMasterData, getItems } from '@/services/masterData'

const state = () => ({
  serviceTypes: [],
  statuses: [],
  itemCategories: [],
  inspectionTypes: [],
  inspectionParameters: [],
  scopesOfWork: [],
  locations: [],
  customers: [],
  conditions: [],
  owners: [],
  allocations: [],
  items: [],
  loaded: false,
  loading: false,
  error: null,
})

const mutations = {
  SET_MASTER(state, data) {
    state.serviceTypes = data.service_types ?? []
    state.statuses = data.statuses ?? []
    state.itemCategories = data.item_categories ?? []
    state.inspectionTypes = data.inspection_types ?? []
    state.inspectionParameters = data.inspection_parameters ?? []
    state.scopesOfWork = data.scopes_of_work ?? []
    state.locations = data.locations ?? []
    state.customers = data.customers ?? []
    state.conditions = data.conditions ?? []
    state.owners = data.owners ?? []
    state.allocations = data.allocations ?? []
  },
  SET_ITEMS(state, items) {
    state.items = items ?? []
  },
  SET_LOADED(state, value) {
    state.loaded = value
  },
  SET_LOADING(state, value) {
    state.loading = value
  },
  SET_ERROR(state, error) {
    state.error = error
  },
}

const actions = {
  async fetchMasterData({ commit }) {
    const response = await getMasterData()
    commit('SET_MASTER', response.data)
  },
  async fetchItems({ commit }) {
    const response = await getItems()
    commit('SET_ITEMS', response.data)
  },
  async prefetch({ commit, dispatch, state }) {
    if (state.loaded || state.loading) {
      return
    }

    commit('SET_LOADING', true)
    commit('SET_ERROR', null)
    try {
      await Promise.all([dispatch('fetchMasterData'), dispatch('fetchItems')])
      commit('SET_LOADED', true)
    } catch (error) {
      commit('SET_ERROR', error?.message ?? 'Failed to load master data.')
    } finally {
      commit('SET_LOADING', false)
    }
  },
}

const getters = {
  scopeById: (state) => (id) => state.scopesOfWork.find((scope) => scope.id === id) ?? null,
  itemById: (state) => (id) => state.items.find((item) => item.id === id) ?? null,
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
}
