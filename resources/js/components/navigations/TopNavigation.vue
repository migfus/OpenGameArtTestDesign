<template>
    <div
        :class="[
            'bg-brand-950 px-6 py-2 font-semibold flex justify-between gap-2 text-brand-200 h-18 border-b border-brand-900 overflow-hidden transition-all duration-500'
        ]"
        :style="{ height: `${$top_navigation_hidden ? '0px' : '70px'}` }"
    >
        <!-- SECTION LEFT  -->
        <div class="flex gap-4 items-center overflow-y-auto">
            <RouterLink to="/" class="flex gap-4 items-center">
                <img :src="`${app_url}/images/icon.png`" class="size-10 image-rendering-pixelated" style="image-rendering: pixelated" />
                <p class="text-3xl anek-latin hidden md:flex">OpenGameArt</p>
                <p class="absolute rotate-5 bg-yellow-900/75 px-20 hidden md:flex text-yellow-50 text-lg">Not Official</p>
                <p class="absolute rotate-15 bg-yellow-900/75 text-yellow-50 md:hidden">Not Official</p>
            </RouterLink>

            <RouterLink to="/" class="gap-4 items-center hidden md:flex">
                <p class="text-xs press-start-2p-regular flex flex-wrap w-42 text-center">Winter Game Jam 2025</p>
            </RouterLink>
        </div>

        <!-- SECTION: RIGHT -->
        <div class="flex gap-4 items-center">
            <Icon icon="memory:search" class="size-6 text-brand-200 inline lg:hidden" />
            <AppInput name="Search" v-model="search" noLabel placeholder="Search" class="hidden lg:flex" />

            <AppButton size="sm" icon="memory:plus" icon_only class="inline lg:hidden" />
            <AppButton size="sm" icon="memory:plus" class="lg:flex hidden">Submit Art</AppButton>

            <Icon icon="memory:volume-high" class="size-6 text-brand-200" />
            <Icon icon="memory:bell" class="size-7 text-brand-200" />
            <img
                src="https://images.unsplash.com/photo-1761839257046-84e95464cc52?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDF8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwxfHx8ZW58MHx8fHx8"
                class="size-8 rounded-full border-2 border-brand-900"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import { Icon } from '@iconify/vue'
import AppInput from '../form/AppInput.vue'
import AppButton from '../form/AppButton.vue'

import { ref } from 'vue'

const $top_navigation_hidden = defineModel<boolean>('top_navigation_hidden', { required: true })
const $show_side_navigation = defineModel<boolean>('show_side_navigation', { required: true })

const search = ref('')
const app_url = window.location.origin
</script>
