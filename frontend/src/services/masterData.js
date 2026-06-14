import http from './http'

export function getMasterData() {
  return http.get('/master-data')
}

export function getItems() {
  return http.get('/items')
}

export function createScopeOfWork(payload) {
  return http.post('/scopes-of-work', payload)
}
