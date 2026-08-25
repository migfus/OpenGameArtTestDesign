<template>
    <div class="flex">
        <!-- SECTION: Following -->
        <!-- FIXME: VUE does not liked 2 <div>s in 1 <template> fix this please -->

        <div
            :class="[
                on_mobile ? '' : 'hidden md:flex',
                'flex-col gap-2 h-min-[calc(100vh-7rem)] justify-between border-r border-brand-950 px-2 py-2 flex-none relative'
            ]"
        >
            <DataTransition class="flex flex-col gap-2">
                <!-- <RouterLink v-for="item in $auth.store.friends" to="/">
                <img :src="item.image_url" class="size-10 rounded-full hover:ring-2 ring-brand-950 transition-all" />
            </RouterLink> -->
                <RouterLink
                    to="/"
                    :class="['bg-card flex justify-center items-center rounded-xl border border-border size-10 hover:bg-dark-001 transition-all']"
                >
                    <Icon icon="ic:baseline-plus" class="size-6 text-brand-200" />
                </RouterLink>
            </DataTransition>

            <BasicTransition>
                <RouterLink
                    v-if="$authStore.token"
                    to="/"
                    class="bg-card flex justify-center items-center rounded-xl border border-border backdrop-blur-lg size-10 fixed bottom-2 hover:bg-dark-001 transition-all"
                >
                    <Icon icon="memory:apps" class="size-6 text-brand-200" />
                </RouterLink>
                <RouterLink
                    v-else
                    to="/login"
                    class="bg-card flex justify-center items-center rounded-xl border border-border backdrop-blur-lg size-10 fixed bottom-2 hover:bg-dark-001 transition-all"
                >
                    <Icon icon="memory:login" class="size-6 text-brand-200" />
                </RouterLink>
            </BasicTransition>
        </div>

        <div
            :class="[
                on_mobile ? 'border-none' : 'hidden md:flex border-r',
                'flex-col gap-2 h-min-[calc(100vh-7rem)] justify-between  border-brand-950 py-2 w-60 flex-none'
            ]"
        >
            <div class="flex flex-col gap-8 overflow-y-auto scrollbar-hide rounded-xl text-brand-200 pt-2">
                <!-- SECTION: NAV -->
                <DataTransition class="-mt-8">
                    <div
                        v-for="(nav, idx) in navigations"
                        class="flex flex-col gap-1 px-2 transition-all"
                        :style="{ animationDelay: `${idx * 100}ms`, transitionDelay: `${idx * 100}ms` }"
                        @animationend.once="clearDelays"
                        @transitionend.once="clearDelays"
                    >
                        <p>{{ nav.name }}</p>
                        <RouterLink
                            v-for="item in nav.links"
                            :to="item.href"
                            @click="$emit('close_sidebar')"
                            :class="[
                                $route.path === item.href ? 'bg-brand-950' : '',
                                'flex items-center gap-2 font-semibold hover:bg-brand-950 p-2 px-4 rounded-xl transition-all'
                            ]"
                        >
                            <Icon :icon="item.icon" class="size-6 text-brand-200" />
                            <p>{{ item.display_name }}</p>
                        </RouterLink>
                    </div>
                </DataTransition>

                <!-- SECTION: COLLECTIONS -->

                <div class="flex flex-col gap-1 px-2">
                    <p class="mx-2">New Collections</p>
                    <OtherLinksLoader v-if="$navigationStore.config.loading" />
                    <DataTransition v-else>
                        <a
                            v-for="(item, idx) in new_collections"
                            :href="`https://opengameart.org/content/${item.id}`"
                            @click="$emit('close_sidebar')"
                            class="flex items-center gap-2 font-semibold hover:bg-brand-950 px-4 py-2 rounded-xl transition-all"
                            :style="{ animationDelay: `${idx * 100}ms`, transitionDelay: `${idx * 100}ms` }"
                            @animationend.once="clearDelays"
                            @transitionend.once="clearDelays"
                        >
                            <img v-if="item.user" :src="item.user.image_url" class="size-6 text-brand-200 rounded-full border border-brand-900" />
                            <div v-else class="size-6 text-brand-200 rounded-full border border-brand-900 flex-none bg-brand-900 animate-pulse" />

                            <div class="truncate grow">
                                <p class="truncate text-sm">{{ item.title }}</p>
                                <p v-if="item.user" class="text-xs font-normal truncate">{{ item.user?.username }}</p>
                                <p v-else class="text-xs font-normal truncate bg-brand-900 h-2.5 animate-pulse rounded-xl" />
                            </div>
                            <p v-if="item.created_at" class="text-xs flex-none">{{ messengerStyleTime(item.created_at) }}</p>
                        </a>
                    </DataTransition>
                </div>

                <div class="flex flex-col gap-1 px-2">
                    <p class="mx-2">Recent Forums</p>
                    <OtherLinksLoader v-if="$navigationStore.config.loading" />
                    <DataTransition v-else>
                        <a
                            v-for="(item, idx) in recent_forums"
                            :href="`https://opengameart.org/forumtopic/${item.id}`"
                            @click="$emit('close_sidebar')"
                            class="flex items-center gap-2 font-semibold hover:bg-brand-950 px-4 py-2 rounded-xl transition-all justify-between"
                            :style="{ animationDelay: `${idx * 100}ms`, transitionDelay: `${idx * 100}ms` }"
                            @animationend.once="clearDelays"
                            @transitionend.once="clearDelays"
                        >
                            <img v-if="item.user" :src="item.user.image_url" class="size-6 text-brand-200 rounded-full border border-brand-900" />
                            <img v-else class="size-6 bg-brand-950 rounded-full border border-brand-900 flex-none animate-pulse" />

                            <div class="truncate grow">
                                <p class="truncate text-sm">{{ item.title }}</p>
                                <p v-if="item.user" class="text-xs font-normal truncate">{{ item.user?.username }}</p>
                                <p v-else class="text-xs font-normal truncate bg-brand-900 h-2.5 animate-pulse rounded-xl" />
                            </div>
                            <p v-if="item.created_at" class="text-xs flex-none">{{ messengerStyleTime(item.created_at) }}</p>
                        </a>
                    </DataTransition>
                </div>

                <div class="flex flex-col gap-1 px-2">
                    <p class="mx-2">Affiliates</p>
                    <OtherLinksLoader v-if="$navigationStore.config.loading" />
                    <DataTransition v-else>
                        <a
                            v-for="(item, idx) in affiliates"
                            :href="item.id"
                            @click="$emit('close_sidebar')"
                            class="flex items-center gap-2 font-semibold hover:bg-brand-950 px-4 py-2 rounded-xl transition-all justify-between cursor-pointer"
                            :style="{ animationDelay: `${idx * 100}ms`, transitionDelay: `${idx * 100}ms` }"
                            @animationend.once="clearDelays"
                            @transitionend.once="clearDelays"
                        >
                            <div class="flex gap-2 items-center truncate">
                                <img v-if="item.image_url" :src="item.image_url" class="size-4 flex-none" />
                                <Icon v-else icon="memory:arrow-right-circle" class="size-4 flex-none" />
                                <p class="truncate text-sm">{{ item.title }}</p>
                            </div>
                        </a>
                    </DataTransition>
                </div>

                <div class="flex flex-col gap-1 px-2 mb-12">
                    <p class="mx-2">Development</p>
                    <a
                        href="https://github.com/migfus/OpenGameArtTestDesign"
                        target="_blank"
                        class="flex items-center gap-2 font-semibold hover:bg-brand-950 px-4 py-2 rounded-xl transition-all"
                    >
                        <Icon icon="mdi:github" />
                        <p class="">Open Source</p>
                    </a>
                    <a
                        href="https://trello.com/b/q3T00dXn/oga-ui-test"
                        target="_blank"
                        class="flex items-center gap-2 font-semibold hover:bg-brand-950 px-4 py-2 rounded-xl transition-all"
                    >
                        <Icon icon="mdi:trello" />
                        <p class="">Progress</p>
                    </a>
                </div>
            </div>

            <BasicTransition>
                <RouterLink
                    v-if="$authStore.token"
                    to="/"
                    :class="[
                        on_mobile ? 'w-60' : 'w-56',
                        'bg-brand-950/75 flex justify-between px-4 items-center rounded-xl border border-brand-900 backdrop-blur-lg h-10 fixed bottom-2 text-brand-200 ml-2 hover:bg-dark-001'
                    ]"
                >
                    <p>Account Settings</p>
                    <Icon icon="memory:dot-hexagon" class="size-6 text-brand-200" />
                </RouterLink>
            </BasicTransition>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Icon } from '@iconify/vue'
import DataTransition from '../transitions/DataTransition.vue'
import OtherLinksLoader from './OtherLinksLoader.vue'
import BasicTransition from '../transitions/BasicTransition.vue'

import { useRoute } from 'vue-router'
import { useNavigationStore } from '@/stores/navigation.store'
import { clearDelays, messengerStyleTime } from '@/utils/utils'
import { computed } from 'vue'
import { useAuthStore } from '@/stores/auth.store'
import { useForumStore } from '@/stores/forum.store'
import { storeToRefs } from 'pinia'
import { useCollectionStore } from '@/stores/collection.store'
import { useAffiliateStore } from '@/stores/affiliate.store'

defineProps<{
    on_mobile?: boolean
}>()

const $route = useRoute()
const $emit = defineEmits(['close_sidebar'])
const $authStore = useAuthStore()

const $navigationStore = useNavigationStore()
const $forumStore = useForumStore()

const $collectionStore = useCollectionStore()
const { new_collections } = storeToRefs($collectionStore)

const $affilateStore = useAffiliateStore()
const { affiliates } = storeToRefs($affilateStore)

const { recent_forums } = storeToRefs($forumStore)

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

const following_filtered = computed(() => {
    if ($authStore.token) {
        return following
    } else {
        return []
    }
})

const navigations = [
    {
        name: 'Explore',
        links: [
            {
                name: '/',
                display_name: 'Home',
                href: '/',
                icon: 'pixelarticons:home'
            },
            {
                name: 'collections',
                display_name: 'Collections',
                href: '/collections',
                icon: 'pixelarticons:heart'
            },
            {
                name: 'arts',
                display_name: 'Explore',
                href: '/arts',
                icon: 'pixelarticons:search'
            },

            {
                name: 'forums',
                display_name: 'Forums',
                href: '/forums',
                icon: 'pixelarticons:message-image'
            },
            {
                name: 'faqs',
                display_name: 'FAQs',
                href: '/faqs',
                icon: 'pixelarticons:info-box'
            },
            {
                name: 'leaderboards',
                display_name: 'Leaderboards',
                href: '/leaderboards',
                icon: 'pixelarticons:chart'
            }
        ]
    }
]

const image_url =
    'https://images.unsplash.com/photo-1761838816945-021a4ebd67bc?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHw0fHx8ZW58MHx8fHx8'

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
</script>
