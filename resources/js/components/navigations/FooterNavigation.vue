<template>
    <div class="fixed bg-dark-001/75 backdrop-blur-lg bottom-0 w-full gap-2">
        <RouterLink
            v-for="item in bottom_navigations"
            :to="item.href"
            :class="[false ? 'bg-brand-950' : 'hover:bg-brand-950', 'flex flex-col items-center py-2 flex-1 transition-all']"
        >
            <Icon :icon="item.icon" class="size-6" />
            <p class="text-sm">{{ item.name }}</p>
        </RouterLink>

        <div
            @click="$sidebar_open_model = true"
            :class="[$sidebar_open_model ? 'bg-brand-950' : 'hover:bg-brand-950', 'flex flex-col items-center py-2 flex-1 cursor-pointer transition-all']"
        >
            <Icon icon="memory:apps" class="size-6" />
            <p class="text-sm">More</p>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Icon } from '@iconify/vue'

const $sidebar_open_model = defineModel<boolean>()

const bottom_navigations = [
    {
        name: 'Home',
        icon: 'memory:home-thatched',
        href: ''
    },
    {
        name: 'Explore',
        icon: 'memory:search',
        href: ''
    },
    {
        name: 'Forums',
        icon: 'memory:chat',
        href: ''
    },
    {
        name: 'Collections',
        icon: 'pixelarticons:heart',
        href: ''
    }
]
</script>
