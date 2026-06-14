import { describe, it, expect, vi, beforeEach } from 'vitest'
import { createStore } from 'vuex'
import master from '@/store/modules/master'
import * as masterService from '@/services/masterData'

vi.mock('@/services/masterData')

const masterPayload = {
  data: {
    service_types: [{ value: 'new_arrival', label: 'New Arrival' }],
    statuses: [{ value: 'open', label: 'Open' }],
    item_categories: [],
    inspection_types: [],
    inspection_parameters: [],
    scopes_of_work: [],
    locations: [{ id: 1, name: 'Moomba' }],
    customers: [],
    conditions: [],
    owners: [],
    allocations: [],
  },
}

const itemsPayload = { data: [{ id: 1, code: 'X', lots: [] }] }

function makeStore() {
  return createStore({ modules: { master } })
}

describe('master store', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    masterService.getMasterData.mockResolvedValue(masterPayload)
    masterService.getItems.mockResolvedValue(itemsPayload)
  })

  it('prefetches and caches master data and items', async () => {
    const store = makeStore()

    await store.dispatch('master/prefetch')

    expect(store.state.master.loaded).toBe(true)
    expect(store.state.master.locations).toHaveLength(1)
    expect(store.state.master.items).toHaveLength(1)
  })

  it('does not refetch once loaded (Rule #4)', async () => {
    const store = makeStore()

    await store.dispatch('master/prefetch')
    await store.dispatch('master/prefetch')
    await store.dispatch('master/prefetch')

    expect(masterService.getMasterData).toHaveBeenCalledTimes(1)
    expect(masterService.getItems).toHaveBeenCalledTimes(1)
  })

  it('captures an error and recovers on retry', async () => {
    masterService.getMasterData.mockRejectedValueOnce({ message: 'boom' })
    const store = makeStore()

    await store.dispatch('master/prefetch')
    expect(store.state.master.loaded).toBe(false)
    expect(store.state.master.error).toBe('boom')

    await store.dispatch('master/prefetch')
    expect(store.state.master.loaded).toBe(true)
    expect(store.state.master.error).toBeNull()
  })
})
