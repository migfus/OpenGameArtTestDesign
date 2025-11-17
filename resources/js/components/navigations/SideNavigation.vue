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

                <div class="fixed inset-0 flex">
                    <TransitionChild
                        as="template"
                        enter="transition ease-in-out duration-300 transform"
                        enter-from="-translate-x-full"
                        enter-to="translate-x-0"
                        leave="transition ease-in-out duration-300 transform"
                        leave-from="translate-x-0"
                        leave-to="-translate-x-full"
                    >
                        <DialogPanel class="relative mr-16 flex w-full max-w-xs flex-1">
                            <TransitionChild
                                as="template"
                                enter="ease-in-out duration-300"
                                enter-from="opacity-0"
                                enter-to="opacity-100"
                                leave="ease-in-out duration-300"
                                leave-from="opacity-100"
                                leave-to="opacity-0"
                            >
                                <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                                    <button type="button" class="-m-2.5 p-2.5 rounded-2xl" @click="$show_side_navigation = false">
                                        <span class="sr-only">Close sidebar</span>
                                        <Icon icon="iconoir:xmark" class="h-6 w-6 text-brand-50 cursor-pointer" aria-hidden="true" />
                                    </button>

                                    <RouterLink to="/" class="gap-4 items-center hidden md:flex">
                                        <p class="text-xs press-start-2p-regular flex flex-wrap w-42 text-center">Winter Game Jam 2025</p>
                                    </RouterLink>
                                </div>
                            </TransitionChild>
                            <!-- Sidebar component, swap this element with another sidebar if you like -->
                            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-dark-001 pb-4">
                                <div class="flex h-16 shrink-0 items-center bg-brand-950 px-6">
                                    <RouterLink to="/" class="flex gap-4 items-center">
                                        <img :src="`${app_url}/images/icon.png`" class="size-10 image-rendering-pixelated" style="image-rendering: pixelated" />
                                        <p class="text-3xl anek-latin text-brand-200">OpenGameArt</p>
                                    </RouterLink>
                                </div>
                                <nav class="flex h-screen">
                                    <!-- SECTION: Following -->
                                    <div class="flex-col gap-2 h-min-[calc(100vh-7rem)] justify-between border-r border-brand-950 px-2 flex-none relative">
                                        <DataTransition class="flex flex-col gap-2">
                                            <RouterLink v-for="item in following" to="/">
                                                <img :src="item.image_url" class="size-10 rounded-xl" />
                                            </RouterLink>
                                            <RouterLink to="/" class="bg-brand-950 flex justify-center items-center rounded-xl border border-brand-900 size-10">
                                                <Icon icon="ic:baseline-plus" class="size-6 text-brand-200" />
                                            </RouterLink>
                                        </DataTransition>

                                        <RouterLink to="/" class="bg-brand-950 flex justify-center items-center rounded-xl border border-brand-900 size-10">
                                            <Icon icon="heroicons:squares-2x2" class="size-6 text-brand-200" />
                                        </RouterLink>
                                    </div>
                                    <!-- SECTION: Navigations -->
                                    <div class="flex-col gap-2 h-min-[calc(100vh-7rem)] justify-between px-2 w-60 flex-none text-brand-200">
                                        <DataTransition class="flex flex-col gap-8 overflow-y-auto scrollbar-hide rounded-xl">
                                            <div v-for="(nav, idx) in navigations" :class="[idx == 0 ? '-mt-8' : '', 'flex flex-col gap-4 px-2']">
                                                <p>{{ nav.name }}</p>
                                                <RouterLink v-for="item in nav.links" :to="item.href" class="flex items-center gap-2 font-semibold">
                                                    <Icon :icon="item.icon" class="size-6 text-brand-200" />
                                                    <p>{{ item.name }}</p>
                                                </RouterLink>
                                            </div>

                                            <div v-for="nav in other_links" class="flex flex-col gap-4 px-2">
                                                <p>{{ nav.name }}</p>
                                                <RouterLink v-for="item in nav.links" :to="item.href" class="flex items-center gap-2 font-semibold">
                                                    <img :src="item.image_url" class="size-6 text-brand-200 rounded-full border border-brand-900" />
                                                    <p>{{ item.name }}</p>
                                                </RouterLink>
                                            </div>
                                        </DataTransition>

                                        <RouterLink to="/" class="bg-brand-950 flex justify-between px-4 items-center rounded-xl border border-brand-900 h-10">
                                            <p>Settings</p>
                                            <Icon icon="mdi:gear-outline" class="size-6 text-brand-200" />
                                        </RouterLink>
                                    </div>
                                </nav>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- SECTION: Following -->
        <div class="flex-col gap-2 h-min-[calc(100vh-7rem)] justify-between border-r border-brand-950 px-2 py-2 flex-none relative hidden md:flex">
            <DataTransition class="flex flex-col gap-2">
                <RouterLink v-for="item in following" to="/">
                    <img :src="item.image_url" class="size-10 rounded-xl" />
                </RouterLink>
                <RouterLink to="/" class="bg-brand-950 flex justify-center items-center rounded-xl border border-brand-900 size-10">
                    <Icon icon="ic:baseline-plus" class="size-6 text-brand-200" />
                </RouterLink>
            </DataTransition>

            <RouterLink to="/" class="bg-brand-950 flex justify-center items-center rounded-xl border border-brand-900 size-10 sticky bottom-2">
                <Icon icon="heroicons:squares-2x2" class="size-6 text-brand-200" />
            </RouterLink>
        </div>
        <!-- SECTION: Navigations -->
        <div class="flex-col gap-2 h-min-[calc(100vh-7rem)] justify-between border-r border-brand-950 px-2 py-2 w-60 flex-none hidden md:flex">
            <DataTransition class="flex flex-col gap-8 overflow-y-auto scrollbar-hide rounded-xl">
                <div v-for="(nav, idx) in navigations" :class="[idx == 0 ? '-mt-8' : '', 'flex flex-col gap-4 px-2']">
                    <p>{{ nav.name }}</p>
                    <RouterLink v-for="item in nav.links" :to="item.href" class="flex items-center gap-2 font-semibold">
                        <Icon :icon="item.icon" class="size-6 text-brand-200" />
                        <p>{{ item.name }}</p>
                    </RouterLink>
                </div>

                <div v-for="nav in other_links" class="flex flex-col gap-4 px-2">
                    <p>{{ nav.name }}</p>
                    <RouterLink v-for="item in nav.links" :to="item.href" class="flex items-center gap-2 font-semibold">
                        <img :src="item.image_url" class="size-6 text-brand-200 rounded-full border border-brand-900" />
                        <p>{{ item.name }}</p>
                    </RouterLink>
                </div>
            </DataTransition>

            <RouterLink to="/" class="bg-brand-950 flex justify-between px-4 items-center rounded-xl border border-brand-900 h-10 sticky bottom-2">
                <p>Settings</p>
                <Icon icon="mdi:gear-outline" class="size-6 text-brand-200" />
            </RouterLink>
        </div>
        <!-- SECTION: Content -->
        <div :class="['overflow-y-auto h-min-[calc(100vh-7rem)]']" ref="content_scroll">
            <slot></slot>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Icon } from '@iconify/vue'
import { nextTick, onMounted, onUnmounted, ref, useTemplateRef, watch } from 'vue'
import DataTransition from '../transitions/DataTransition.vue'
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue'

const $hidden_top_navigation = defineModel<boolean>('top_navigation_hidden', { required: true })
const content_scroll = useTemplateRef<HTMLElement>('content_scroll')
let lastScrollY = window.scrollY

const $show_side_navigation = defineModel<boolean>('show_side_navigation', { required: true })

const app_url = window.location.origin

const image_url =
    'https://images.unsplash.com/photo-1761838816945-021a4ebd67bc?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHw0fHx8ZW58MHx8fHx8'

const navigations = [
    {
        name: 'Explore',
        links: [
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
    }
]

const other_links = [
    {
        name: 'New Collections',
        links: [
            {
                name: 'Modern FPS',
                href: '/',
                image_url
            },
            {
                name: 'faceWorks',
                href: '/',
                image_url
            },
            {
                name: 'music',
                href: '/',
                image_url
            },
            {
                name: 'My FF',
                href: '/',
                image_url
            },
            {
                name: 'Open source stuff',
                href: '/',
                image_url
            }
        ]
    },
    {
        name: 'Recent Forums',
        links: [
            {
                name: 'Modern FPS',
                href: '/',
                image_url
            },
            {
                name: 'faceWorks',
                href: '/',
                image_url
            },
            {
                name: 'music',
                href: '/',
                image_url
            },
            {
                name: 'My FF',
                href: '/',
                image_url
            },
            {
                name: 'Open source stuff',
                href: '/',
                image_url
            }
        ]
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

const scrollFadeSetup = (el: Element) => {
    const update = () => {
        el.classList.toggle('at-top', el.scrollTop === 0)
        el.classList.toggle('at-bottom', Math.ceil(el.scrollTop + el.clientHeight) >= el.scrollHeight)
    }
    el.addEventListener('scroll', update, { passive: true })
    window.addEventListener('resize', update)
    update()

    return () => {
        el.removeEventListener('scroll', update)
        window.removeEventListener('resize', update)
    }
}

watch(
    () => image_url,
    (newVal) => {
        if (true) {
            document.querySelectorAll('.scroll-fade').forEach(scrollFadeSetup)
        }
    },
    { immediate: true }
)

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
</script>
