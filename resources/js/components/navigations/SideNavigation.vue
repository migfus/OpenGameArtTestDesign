<template>
    <div class="flex h-full">
        <!-- SECTION: Following -->
        <div class="flex flex-col gap-2 h-[calc(100vh-7rem)] justify-between border-r border-brand-950 px-2 py-2">
            <div class="flex flex-col gap-2">
                <RouterLink v-for="item in following" to="/">
                    <img :src="item.image_url" class="size-10 rounded-xl" />
                </RouterLink>
                <RouterLink to="/" class="bg-brand-950 flex justify-center items-center rounded-xl border border-brand-900 size-10">
                    <Icon icon="ic:baseline-plus" class="size-6 text-brand-200" />
                </RouterLink>
            </div>

            <RouterLink to="/" class="bg-brand-950 flex justify-center items-center rounded-xl border border-brand-900 size-10">
                <Icon icon="heroicons:squares-2x2" class="size-6 text-brand-200" />
            </RouterLink>
        </div>
        <!-- SECTION: Navigations -->
        <div class="flex flex-col gap-2 h-[calc(100vh-7rem)] justify-between border-r border-brand-950 px-2 py-2 w-60">
            <div class="flex flex-col gap-4 px-2">
                <p>Explore</p>

                <RouterLink v-for="item in navigations" :to="item.href" class="flex items-center gap-2 font-semibold">
                    <Icon :icon="item.icon" class="size-6 text-brand-200" />
                    <p>{{ item.name }}</p>
                </RouterLink>
            </div>

            <RouterLink to="/" class="bg-brand-950 flex justify-between px-4 items-center rounded-xl border border-brand-900 h-10">
                <p>Settings</p>
                <Icon icon="mdi:gear-outline" class="size-6 text-brand-200" />
            </RouterLink>
        </div>
        <!-- SECTION: Content -->
        <div class="ml-82">
            <slot></slot>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Icon } from '@iconify/vue'

const navigations = [
    {
        name: 'Home',
        href: '/',
        icon: 'material-symbols:home-outline'
    },
    {
        name: 'Explore',
        href: '/',
        icon: 'mdi:magnify'
    },
    {
        name: 'Personal Collection',
        href: '/',
        icon: 'mdi:heart-outline'
    },
    {
        name: 'Forums',
        href: '/',
        icon: 'mdi:chat-outline'
    },
    {
        name: 'FAQs',
        href: '/',
        icon: 'material-symbols:info-outline'
    },
    {
        name: 'Leaderboards',
        href: '/',
        icon: 'material-symbols:trophy-outline'
    }
]

const following = [
    {
        image_url:
            'https://images.unsplash.com/photo-1761839257046-84e95464cc52?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDF8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwxfHx8ZW58MHx8fHx8'
    },
    {
        image_url:
            'https://plus.unsplash.com/premium_photo-1762518577411-27d3695b994b?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyfHx8ZW58MHx8fHx8'
    },
    {
        image_url:
            'https://images.unsplash.com/photo-1734526040622-a3e96b679f80?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwzfHx8ZW58MHx8fHx8'
    },
    {
        image_url:
            'https://images.unsplash.com/photo-1761839258830-81f87b1c6d62?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDF8MHxmZWF0dXJlZC1waG90b3MtZmVlZHw4fHx8ZW58MHx8fHx8'
    }
]
</script>
