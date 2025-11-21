import { createRouter, createWebHistory } from 'vue-router'

const routes = [
    {
        path: '/',
        name: 'home',
        component: () => import('@/pages/pages/home/(Index).vue')
    },
    {
        path: '/explore',
        name: 'explore',
        component: () => import('@/pages/pages/explore/(Index).vue')
    },
    {
        path: '/personal-collections',
        name: 'personal_collections',
        component: () => import('@/pages/pages/home/(Index).vue')
    },
    {
        path: '/forums',
        name: 'forums',
        component: () => import('@/pages/pages/home/(Index).vue')
    },
    {
        path: '/faqs',
        name: 'faqs',
        component: () => import('@/pages/pages/home/(Index).vue')
    },
    {
        path: '/leaderboards',
        name: 'leaderboards',
        component: () => import('@/pages/pages/home/(Index).vue')
    }
]

export default createRouter({
    history: createWebHistory(),
    routes
})
