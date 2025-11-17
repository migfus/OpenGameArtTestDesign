<template>
    <div
        :class="[
            'bg-brand-950 px-6 py-2 font-semibold flex justify-between gap-2 text-brand-200 h-18 border-b border-brand-900 overflow-hidden transition-all duration-500'
        ]"
        :style="{ height: `${top_hidden_model ? '0px' : '70px'}` }"
    >
        <!-- SECTION LEFT  -->
        <div class="flex gap-4 items-center overflow-y-auto">
            <RouterLink to="/" class="flex gap-4 items-center">
                <img :src="`${app_url}/images/icon.png`" class="size-10 image-rendering-pixelated" style="image-rendering: pixelated" />
                <p class="text-3xl anek-latin">OpenGameArt</p>
            </RouterLink>

            <RouterLink to="/" class="flex gap-4 items-center">
                <p class="text-xs press-start-2p-regular flex flex-wrap w-42 text-center">Winter Game Jam 2025</p>
            </RouterLink>
        </div>

        <!-- SECTION: RIGHT -->
        <div class="flex gap-4 items-center">
            <AppInput name="Search" v-model="search" noLabel placeholder="Search" />
            <AppButton size="sm" icon="material-symbols:arrow-upward-alt-rounded">Submit Art</AppButton>
            <Icon icon="material-symbols:notifications-rounded" class="size-7 text-brand-200" />
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

const top_hidden_model = defineModel<boolean>()

const search = ref('')
const app_url = window.location.origin
</script>
