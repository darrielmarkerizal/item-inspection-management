import { describe, it, expect, vi } from 'vitest'
import { mount } from '@vue/test-utils'
import { createStore } from 'vuex'
import ElementPlus from 'element-plus'
import StatusActions from '@/components/StatusActions.vue'

function makeStore() {
  return createStore({
    modules: {
      inspections: { namespaced: true, actions: { updateStatus: vi.fn() } },
    },
  })
}

function mountActions(statusValue) {
  return mount(StatusActions, {
    props: { inspection: { id: 1, status: { value: statusValue, label: statusValue } } },
    global: { plugins: [ElementPlus, makeStore()] },
  })
}

describe('StatusActions gating', () => {
  it('offers submit-for-review when open', () => {
    expect(mountActions('open').text()).toContain('Submit for Review')
  })

  it('offers complete when for_review', () => {
    const text = mountActions('for_review').text()
    expect(text).toContain('Complete')
    expect(text).not.toContain('Submit for Review')
  })

  it('shows a locked state when completed', () => {
    const text = mountActions('completed').text()
    expect(text).toContain('locked')
    expect(text).not.toContain('Submit for Review')
  })
})
