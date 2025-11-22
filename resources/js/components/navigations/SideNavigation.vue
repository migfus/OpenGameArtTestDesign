<template>
    <div :class="['h-full flex']">
        <!-- SECTION: MOBILE SIDENAV -->
        <TransitionRoot as="template" :show="$show_side_navigation">
            <Dialog as="div" class="relative z-50 md:hidden" @close="$show_side_navigation = false">
                <TransitionChild
                    as="template"
                    enter="transition-opacity ease-linear duration-300"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="transition-opacity ease-linear duration-300"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-gray-900/80" />
                </TransitionChild>

                <div class="fixed inset-0 flex flex-row-reverse">
                    <TransitionChild
                        as="template"
                        enter="transition ease-in-out duration-300 transform"
                        enter-from="translate-x-full"
                        enter-to="translate-x-0"
                        leave="transition ease-in-out duration-300 transform"
                        leave-from="translate-x-0"
                        leave-to="translate-x-full"
                    >
                        <DialogPanel class="relative flex w-full max-w-xs flex-1">
                            <TransitionChild
                                as="template"
                                enter="ease-in-out duration-300"
                                enter-from="opacity-0"
                                enter-to="opacity-100"
                                leave="ease-in-out duration-300"
                                leave-from="opacity-100"
                                leave-to="opacity-0"
                            >
                                <div class="absolute right-full bottom-4 flex mr-2 justify-center pt-5 -mb-2">
                                    <button type="button" class="rounded-2xl" @click="$show_side_navigation = false">
                                        <span class="sr-only">Close sidebar</span>
                                        <Icon
                                            icon="iconoir:xmark"
                                            class="size-10 text-brand-50 cursor-pointer bg-brand-950 p-2 rounded-xl border border-brand-900"
                                        />
                                    </button>

                                    <RouterLink to="/" class="gap-4 items-center hidden md:flex">
                                        <p class="text-xs press-start-2p-regular flex flex-wrap w-42 text-center">Winter Game Jam 2025</p>
                                    </RouterLink>
                                </div>
                            </TransitionChild>
                            <!-- Sidebar component, swap this element with another sidebar if you like -->
                            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-dark-001 pb-4 relative">
                                <div class="flex h-16 shrink-0 items-center bg-brand-950 px-6 relative">
                                    <RouterLink to="/" class="flex gap-4 items-center">
                                        <img :src="`${app_url}/images/icon.png`" class="size-10 image-rendering-pixelated" style="image-rendering: pixelated" />
                                        <p class="text-3xl anek-latin text-brand-200">OpenGameArt</p>
                                        <p class="absolute rotate-5 bg-yellow-900/75 px-20 text-yellow-50">Not Official</p>
                                    </RouterLink>
                                </div>

                                <div class="flex -mt-4">
                                    <SideNavigationContent on_mobile @close_sidebar="$show_side_navigation = false" />
                                </div>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- SECTION: Navigations -->

        <SideNavigationContent class="" />
        <!-- SECTION: Content -->
        <div :class="['overflow-y-auto h-min-[calc(100vh-7rem)] grow']" ref="content_scroll">
            <slot></slot>
        </div>

        <FooterNavigation class="flex md:hidden" v-model="$show_side_navigation" />
    </div>
</template>

<script setup lang="ts">
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { Icon } from '@iconify/vue'
import { nextTick, onMounted, onUnmounted, useTemplateRef } from 'vue'
import FooterNavigation from './FooterNavigation.vue'
import SideNavigationContent from './SideNavigationContent.vue'

import { useNavigationStore } from '@/stores/navigationStore'

const $navigationStore = useNavigationStore()

const $hidden_top_navigation = defineModel<boolean>('top_navigation_hidden', { required: true })
const content_scroll = useTemplateRef<HTMLElement>('content_scroll')
let lastScrollY = window.scrollY

const $show_side_navigation = defineModel<boolean>('show_side_navigation', { required: true })

const app_url = window.location.origin

function handleScroll() {
    if (content_scroll.value) {
        const currentY = content_scroll.value.scrollTop
        console.log('y:', currentY)

        // Scroll down → hide navbar
        if (currentY > lastScrollY && currentY > 50) {
            $hidden_top_navigation.value = true
        }
        // Scroll up → show navbar
        else {
            $hidden_top_navigation.value = false
        }

        lastScrollY = currentY
    }
}
onMounted(async () => {
    $navigationStore.getData()

    await nextTick()
    if (content_scroll.value) {
        content_scroll.value.addEventListener('scroll', handleScroll, { passive: true })
        console.log('Listener attached to content_scroll')
    }
})

onUnmounted(() => {
    if (content_scroll.value) {
        content_scroll.value.removeEventListener('scroll', handleScroll)
    }
})

// SECTION: IMPORT

// GET
</script>
