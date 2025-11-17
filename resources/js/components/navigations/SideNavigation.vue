<template>
    <div :class="['flex h-full']">
        <!-- SECTION: Following -->
        <div class="flex flex-col gap-2 h-min-[calc(100vh-7rem)] justify-between border-r border-brand-950 px-2 py-2 flex-none relative">
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
        <div class="flex flex-col gap-2 h-min-[calc(100vh-7rem)] justify-between border-r border-brand-950 px-2 py-2 w-60 flex-none">
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
import { nextTick, onMounted, onUnmounted, useTemplateRef, watch } from 'vue'
import DataTransition from '../transitions/DataTransition.vue'

const $top_hidden_model = defineModel<boolean>()
const content_scroll = useTemplateRef<HTMLElement>('content_scroll')
let lastScrollY = window.scrollY

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
            $top_hidden_model.value = true
        }
        // Scroll up → show navbar
        else {
            $top_hidden_model.value = false
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
