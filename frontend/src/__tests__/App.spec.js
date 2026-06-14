import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import { createStore } from 'vuex'
import ElementPlus from 'element-plus'
import App from '../App.vue'

function makeStore() {
  return createStore({
    modules: {
      master: {
        namespaced: true,
        state: () => ({ loaded: true, loading: false, error: null }),
        actions: { prefetch: () => {} },
      },
    },
  })
}

describe('App', () => {
  it('renders the app shell title', () => {
    const wrapper = mount(App, {
      global: {
        plugins: [ElementPlus, makeStore()],
        stubs: {
          RouterLink: { template: '<a><slot /></a>' },
          RouterView: true,
        },
      },
    })

    expect(wrapper.text()).toContain('Inspection Management')
  })
})
