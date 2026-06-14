import { describe, it, expect, vi } from 'vitest'
import { mount } from '@vue/test-utils'
import { createStore } from 'vuex'
import ElementPlus from 'element-plus'
import InspectionDetail from '@/views/InspectionDetail.vue'

vi.mock('vue-router', () => ({ useRouter: () => ({ push: vi.fn() }) }))

const inspection = {
  id: 1,
  request_no: 'REQ-2026-0001',
  service_type: { value: 'new_arrival', label: 'New Arrival' },
  status: { value: 'open', label: 'Open' },
  inspection_type: { id: 1, name: 'Reg Prep' },
  location: { id: 1, name: 'Moomba' },
  customer: { id: 1, name: 'PT Santosa' },
  related_to: 'CO-1',
  dvc_code: 'DVC',
  charge_to_customer: true,
  date_submitted: '2026-05-01',
  estimated_completion_date: '2026-05-05',
  note_to_yard: 'note',
  scope_of_work: {
    id: 1,
    name: 'New Arrival Full Inspection',
    description: 'desc',
    parameters: [{ id: 1, name: 'Visual Thread' }],
  },
  items: [
    {
      id: 10,
      item: { code: 'IT-1' },
      item_description: 'Pipe',
      lots: [
        { id: 100, lot_no: 'L1', allocation: 'METO', owner: 'PT A', condition: 'Good', qty_required: 2, inspection_required: true },
      ],
    },
  ],
  charges: [{ id: 1, order_no: 'CO-001', service_description: 'Inspection', qty: 2, unit_price: 5, total: 10 }],
  status_histories: [
    { id: 1, from_status: null, to_status: { value: 'open', label: 'Open' }, changed_at: '2026-05-01T00:00:00+00:00' },
  ],
}

function makeStore() {
  return createStore({
    modules: {
      inspections: {
        namespaced: true,
        state: () => ({ current: inspection, loading: false, error: null }),
        actions: { fetchOne: vi.fn().mockResolvedValue(inspection), updateStatus: vi.fn() },
      },
    },
  })
}

describe('InspectionDetail', () => {
  it('renders all inspection sections and the status action', () => {
    const wrapper = mount(InspectionDetail, {
      props: { id: 1 },
      global: { plugins: [ElementPlus, makeStore()] },
    })

    const text = wrapper.text()
    expect(text).toContain('REQ-2026-0001')
    expect(text).toContain('Scope of Work')
    expect(text).toContain('Visual Thread')
    expect(text).toContain('Item Information')
    expect(text).toContain('Charges to Customer')
    expect(text).toContain('Status History')
    expect(text).toContain('Submit for Review')
  })
})
