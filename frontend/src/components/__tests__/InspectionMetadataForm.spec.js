import { describe, it, expect } from 'vitest'
import { reactive } from 'vue'
import { mount } from '@vue/test-utils'
import { createStore } from 'vuex'
import ElementPlus from 'element-plus'
import InspectionMetadataForm from '@/components/InspectionMetadataForm.vue'

function makeStore() {
  return createStore({
    modules: {
      master: {
        namespaced: true,
        state: () => ({
          serviceTypes: [{ value: 'new_arrival', label: 'New Arrival' }],
          scopesOfWork: [
            { id: 1, name: 'Scope A', parameters: [{ id: 1, name: 'Visual Thread' }] },
          ],
          inspectionTypes: [{ id: 1, name: 'Reg Prep' }],
          customers: [{ id: 1, name: 'PT Santosa' }],
          locations: [{ id: 1, name: 'Moomba' }],
          inspectionParameters: [{ id: 1, name: 'Visual Thread' }],
        }),
      },
    },
  })
}

function makeForm(overrides = {}) {
  return reactive({
    service_type: null,
    inspection_type_id: null,
    scope_of_work_id: null,
    location_id: null,
    customer_id: null,
    related_to: '',
    dvc_code: '',
    estimated_completion_date: null,
    charge_to_customer: false,
    note_to_yard: '',
    items: [],
    ...overrides,
  })
}

describe('InspectionMetadataForm', () => {
  it('shows scope-included parameters when a scope is selected', () => {
    const wrapper = mount(InspectionMetadataForm, {
      props: { form: makeForm({ scope_of_work_id: 1 }) },
      global: { plugins: [ElementPlus, makeStore()] },
    })

    expect(wrapper.text()).toContain('Scope Included')
    expect(wrapper.text()).toContain('Visual Thread')
  })

  it('hides scope-included when no scope is selected', () => {
    const wrapper = mount(InspectionMetadataForm, {
      props: { form: makeForm() },
      global: { plugins: [ElementPlus, makeStore()] },
    })

    expect(wrapper.text()).not.toContain('Scope Included')
  })
})
