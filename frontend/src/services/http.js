import axios from 'axios'

const http = axios.create({
  baseURL: '/api/v1',
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json',
  },
})

http.interceptors.response.use(
  (response) => response.data,
  (error) => {
    const payload = error.response?.data ?? {}

    return Promise.reject({
      status: error.response?.status ?? 0,
      message: payload.message ?? 'Network error. Please try again.',
      errors: payload.errors ?? null,
    })
  },
)

export default http
