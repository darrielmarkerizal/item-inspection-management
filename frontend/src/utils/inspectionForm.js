export function buildInspectionPayload(form) {
  return {
    service_type: form.service_type,
    inspection_type_id: form.inspection_type_id,
    scope_of_work_id: form.scope_of_work_id,
    location_id: form.location_id,
    customer_id: form.customer_id,
    related_to: form.related_to,
    dvc_code: form.dvc_code,
    estimated_completion_date: form.estimated_completion_date,
    charge_to_customer: form.charge_to_customer,
    note_to_yard: form.note_to_yard,
    items: (form.items ?? []).map((item) => ({
      item_id: item.item_id,
      lots: (item.lots ?? []).map((lot) => ({
        item_lot_id: lot.item_lot_id,
        qty_required: lot.qty_required,
        inspection_required: lot.inspection_required,
      })),
    })),
  }
}

export function splitValidationErrors(errors = {}) {
  const fields = {}
  const nested = []

  for (const [key, messages] of Object.entries(errors ?? {})) {
    const message = Array.isArray(messages) ? messages[0] : messages

    if (key.startsWith('items')) {
      nested.push(`${key}: ${message}`)
    } else {
      fields[key] = message
    }
  }

  return { fields, nested }
}
