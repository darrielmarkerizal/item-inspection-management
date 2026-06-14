import { describe, it, expect, vi } from 'vitest'
import { mount } from '@vue/test-utils'
import { createStore } from 'vuex'
import ElementPlus from 'element-plus'
import InspectionList from '@/views/InspectionList.vue'

const push = vi.fn()
vi.mock('vue-router', () => ({ useRouter: () => ({ push }) }))

function makeStore(fetchList) {
  return createStore({
    modules: {
      inspections: {
        namespaced: true,
        state: () => ({
          list: [],
          pagination: { current_page: 1, last_page: 1, per_page: 15, total: 0 },
          statusCounts: { open: 2, for_review: 1, completed: 0 },
          loading: false,
          error: null,
        }),
        actions: { fetchList },
      },
    },
  })
}

describe('InspectionList', () => {
  it('fetches the open tab on mount', () => {
    const fetchList = vi.fn().mockResolvedValue()

    mount(InspectionList, {
      global: { plugins: [ElementPlus, makeStore(fetchList)] },
    })

    expect(fetchList).toHaveBeenCalledTimes(1)
    expect(fetchList.mock.calls[0][1]).toMatchObject({ 'filter[status]': 'open', page: 1 })
  })

  it('renders tab labels with status counts', () => {
    const wrapper = mount(InspectionList, {
      global: { plugins: [ElementPlus, makeStore(vi.fn().mockResolvedValue())] },
    })

    expect(wrapper.text()).toContain('Open (2)')
    expect(wrapper.text()).toContain('For Review (1)')
    expect(wrapper.text()).toContain('Completed (0)')
  })
})
