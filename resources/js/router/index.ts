import { createRouter, createWebHistory } from 'vue-router'

const routes = [{ path: '/', component: () => import('@/pages/pages/home/(Index).vue') }]

export default createRouter({
    history: createWebHistory(),
    routes
})
