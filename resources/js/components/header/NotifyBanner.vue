<template>
    <div v-if="closed_banner == 0" class="relative isolate flex items-center gap-x-6 overflow-hidden px-6 py-2.5 sm:px-3.5 sm:before:flex-1 text-brand-200">
        <div class="absolute left-[max(-7rem,calc(50%-52rem))] top-1/2 -z-10 -translate-y-1/2 transform-gpu blur-2xl" aria-hidden="true">
            <div
                class="aspect-577/310 w-144.25 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-30"
                style="
                    clip-path: polygon(
                        74.8% 41.9%,
                        97.2% 73.2%,
                        100% 34.9%,
                        92.5% 0.4%,
                        87.5% 0%,
                        75% 28.6%,
                        58.5% 54.6%,
                        50.1% 56.8%,
                        46.9% 44%,
                        48.3% 17.4%,
                        24.7% 53.9%,
                        0% 27.9%,
                        11.9% 74.2%,
                        24.9% 54.1%,
                        68.6% 100%,
                        74.8% 41.9%
                    );
                "
            />
        </div>
        <div class="absolute left-[max(45rem,calc(50%+8rem))] top-1/2 -z-10 -translate-y-1/2 transform-gpu blur-2xl" aria-hidden="true">
            <div
                class="aspect-577/310 w-144.25 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-30"
                style="
                    clip-path: polygon(
                        74.8% 41.9%,
                        97.2% 73.2%,
                        100% 34.9%,
                        92.5% 0.4%,
                        87.5% 0%,
                        75% 28.6%,
                        58.5% 54.6%,
                        50.1% 56.8%,
                        46.9% 44%,
                        48.3% 17.4%,
                        24.7% 53.9%,
                        0% 27.9%,
                        11.9% 74.2%,
                        24.9% 54.1%,
                        68.6% 100%,
                        74.8% 41.9%
                    );
                "
            />
        </div>
        <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
            <p class="text-sm leading-6 text-brand-200">
                <strong class="font-semibold">THIS IS NOT OFFICIAL OpenGameArt</strong>
                <svg viewBox="0 0 2 2" class="mx-2 inline h-0.5 w-0.5 fill-current" aria-hidden="true"><circle cx="1" cy="1" r="1" /></svg>Just a bored web
                developer
            </p>
        </div>
        <div class="flex flex-1 justify-end">
            <button type="button" class="-m-3 p-3 focus-visible:-outline-offset-4">
                <span class="sr-only">Dismiss</span>
                <Icon icon="memory:alpha-x" class="h-5 w-5 text-brand-200 cursor-pointer" @click="closed_banner = 1" />
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Icon } from '@iconify/vue'

import { useLocalStorage } from '@vueuse/core'

const closed_banner = useLocalStorage('closed_banner', 0)
</script>
