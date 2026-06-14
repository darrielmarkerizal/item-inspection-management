import http from './http'

export function listInspections(params = {}) {
  return http.get('/inspections', { params })
}

export function createInspection(payload) {
  return http.post('/inspections', payload)
}

export function showInspection(id, params = {}) {
  return http.get(`/inspections/${id}`, { params })
}

export function updateInspection(id, payload) {
  return http.put(`/inspections/${id}`, payload)
}

export function updateInspectionStatus(id, status) {
  return http.patch(`/inspections/${id}/status`, { status })
}
