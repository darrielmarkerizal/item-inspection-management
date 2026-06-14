import { describe, it, expect } from 'vitest'
import { buildInspectionPayload, splitValidationErrors } from '@/utils/inspectionForm'

describe('buildInspectionPayload', () => {
  it('strips display-only lot fields and keeps the api contract', () => {
    const form = {
      service_type: 'new_arrival',
      location_id: 1,
      items: [
        {
          item_id: 7,
          lots: [
            {
              item_lot_id: 3,
              qty_required: 2,
              inspection_required: true,
              lot_no: 'L1',
              allocation: 'METO',
              owner: 'PT A',
              condition: 'Good',
              available_qty: 100,
            },
          ],
        },
      ],
    }

    const payload = buildInspectionPayload(form)

    expect(payload.items[0]).toEqual({
      item_id: 7,
      lots: [{ item_lot_id: 3, qty_required: 2, inspection_required: true }],
    })
    expect(payload.items[0].lots[0]).not.toHaveProperty('lot_no')
    expect(payload.items[0].lots[0]).not.toHaveProperty('available_qty')
  })
})

describe('splitValidationErrors', () => {
  it('separates metadata field errors from nested item errors', () => {
    const { fields, nested } = splitValidationErrors({
      service_type: ['The service type field is required.'],
      'items.0.lots.0.qty_required': ['Qty required cannot exceed available qty.'],
    })

    expect(fields).toEqual({ service_type: 'The service type field is required.' })
    expect(nested).toEqual(['items.0.lots.0.qty_required: Qty required cannot exceed available qty.'])
  })
})
