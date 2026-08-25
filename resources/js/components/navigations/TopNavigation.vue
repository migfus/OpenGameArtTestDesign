<template>
    <div
        :class="[
            'bg-card px-6 py-2 font-semibold flex justify-between gap-2 text-brand-200 h-18 border-b border-border overflow-visible transition-all duration-500'
        ]"
        :style="{ height: `${$top_navigation_hidden ? '0px' : '70px'}` }"
    >
        <!-- SECTION LEFT  -->
        <div class="flex gap-4 items-center overflow-y-auto">
            <RouterLink to="/" class="flex gap-4 items-center">
                <img :src="`${app_url}/images/icon.png`" class="size-10 image-rendering-pixelated" style="image-rendering: pixelated" />
                <p class="text-3xl anek-latin hidden md:flex">OpenGameArt.org</p>
                <p class="absolute rotate-5 bg-yellow-900/75 px-20 hidden md:flex text-yellow-50 text-lg">Not Official</p>
                <p class="absolute rotate-15 bg-yellow-900/75 text-yellow-50 md:hidden">Not Official</p>
            </RouterLink>
        </div>

        <!-- SECTION: RIGHT -->
        <div class="flex gap-4 items-center">
            <SearchDropdown />

            <AppButton size="sm" icon="memory:plus" icon_only class="inline lg:hidden" />
            <AppButton size="sm" icon="memory:plus" class="lg:flex hidden">Submit Art</AppButton>

            <Icon v-if="auth" icon="pixelarticons:notification" class="size-6 text-brand-200 transition-all rounded hover:bg-dark-001 cursor-pointer" />

            <ProfileDropdown v-if="auth" />

            <RouterLink v-else to="/login" class="relative cursor-pointer">
                <AppButton icon="memory:login">Sign Up</AppButton>
            </RouterLink>
        </div>
    </div>
</template>

<script setup lang="ts">
import ProfileDropdown from '@/components/navigations/ProfileDropdown.vue'
import { Icon } from '@iconify/vue'
import AppButton from '../form/AppButton.vue'
import SearchDropdown from './SearchDropdown.vue'

import { useAuthStore } from '@/stores/auth.store'
import { storeToRefs } from 'pinia'

const $top_navigation_hidden = defineModel<boolean>('top_navigation_hidden', { required: true })
const $authStore = useAuthStore()
const { auth } = storeToRefs($authStore)

const app_url = window.location.origin
</script>
