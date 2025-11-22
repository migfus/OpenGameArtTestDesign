import axios from 'axios'

const api = axios.create({
    baseURL: '/api', // auto picks your backend
    withCredentials: true
})

// --- Request Interceptor ---
api.interceptors.request.use(
    (config) => {
        // OPTIONAL: Add token from localStorage, cookies, etc.
        const token = localStorage.getItem('token')

        if (token) {
            config.headers.Authorization = `Bearer ${token}`
        }

        return config
    },
    (error) => Promise.reject(error)
)

// --- Response Interceptor ---
api.interceptors.response.use(
    (response) => response,
    (error) => {
        // Handle 401 (User not logged in)
        if (error.response?.status === 401) {
            console.warn('Unauthorized, redirecting...')
        }

        // Handle Laravel validation errors (422)
        if (error.response?.status === 422) {
            console.warn('Validation Error:', error.response.data.errors)
        }

        return Promise.reject(error)
    }
)

export default api
