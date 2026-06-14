import { describe, it, expect } from 'vitest'
import { reactive, nextTick } from 'vue'
import { mount } from '@vue/test-utils'
import ElementPlus from 'element-plus'
import LotSelector from '@/components/LotSelector.vue'

const itemLots = [
  { id: 1, lot_no: 'L1', allocation: { name: 'METO' }, owner: { name: 'PT A' }, condition: { name: 'Good' }, available_qty: 100 },
  { id: 2, lot_no: 'L2', allocation: { name: 'MTO' }, owner: { name: 'PT B' }, condition: { name: 'Quarantine' }, available_qty: 50 },
  { id: 3, lot_no: 'L3', allocation: { name: 'METO' }, owner: { name: 'PT A' }, condition: { name: 'Used' }, available_qty: 80 },
]

function makeLot() {
  return reactive({
    item_lot_id: null,
    lot_no: null,
    allocation: null,
    owner: null,
    condition: null,
    available_qty: null,
    qty_required: 0,
    inspection_required: true,
  })
}

function mountSelector(lot) {
  return mount(LotSelector, {
    props: { lot, itemLots },
    global: { plugins: [ElementPlus] },
  })
}

describe('LotSelector cascading', () => {
  it('does not resolve a lot while the selection is ambiguous', async () => {
    const lot = makeLot()
    mountSelector(lot)

    lot.allocation = 'METO'
    await nextTick()

    expect(lot.item_lot_id).toBeNull()
  })

  it('resolves item_lot_id and available qty when one lot matches', async () => {
    const lot = makeLot()
    mountSelector(lot)

    lot.lot_no = 'L1'
    await nextTick()

    expect(lot.item_lot_id).toBe(1)
    expect(lot.available_qty).toBe(100)
  })

  it('narrows to a single lot through cascading fields', async () => {
    const lot = makeLot()
    mountSelector(lot)

    lot.allocation = 'METO'
    await nextTick()
    expect(lot.item_lot_id).toBeNull()

    lot.condition = 'Used'
    await nextTick()

    expect(lot.item_lot_id).toBe(3)
    expect(lot.available_qty).toBe(80)
  })
})
