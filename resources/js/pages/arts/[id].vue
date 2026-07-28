<template>
    <div class="flex flex-col gap-4 lg:flex-row sm:p-4 py-4 2xl:max-w-[1580px] 2xl:mx-auto">
        <div class="flex-1 min-w-0 basis-0 flex flex-col gap-4">
            <!-- <div>
                <p>Debug</p>
                <p>Volume: {{ audio_volume }}</p>
            </div> -->
            <!-- SECTION: TITLE -->
            <div class="px-4 sm:px-0">
                <h1 v-if="config.loading || !show_data" class="text-2xl font-semibold bg-brand-950 h-8 w-48 rounded-3xl animate-pulse" />
                <h1 v-else class="text-xl">{{ show_data.title }}</h1>
            </div>

            <!-- SECTION: ART PREVIEW -->
            <MainArtPreviewCard v-model="art_config.selected_preview" :art_previews="show_data?.art_previews ?? []" :author="show_data?.user" />

            <!-- SECTION: PREVIEW SELECTION -->
            <div v-if="config.loading || !show_data" class="flex flex-col max-w-full relative">
                <div class="flex gap-2 items-start justify-between">
                    <DataTransition class="flex gap-2 overflow-x-auto overflow-y-hidden scrollbar-hide whitespace-nowrap">
                        <div v-for="item in [0, 1, 2, 3]" class="flex flex-col gap-2">
                            <div class="size-32 rounded-xl bg-brand-950 animate-pulse" />

                            <div class="flex flex-col gap-1">
                                <button class="bg-brand-950 h-5 w-full animate-pulse rounded-xl" />

                                <div class="flex justify-between gap-2">
                                    <button class="bg-brand-950 h-4 w-16 animate-pulse rounded-xl" />
                                    <button class="bg-brand-950 h-4 w-16 animate-pulse rounded-xl" />
                                </div>
                            </div>
                        </div>
                    </DataTransition>

                    <button class="bg-brand-950 h-8 w-16 animate-pulse rounded-xl" />
                </div>
            </div>
            <div v-else class="flex flex-col max-w-full relative px-4 sm:px-0">
                <div class="flex gap-2 items-start justify-between">
                    <DataTransition
                        class="flex gap-2 overflow-x-auto overflow-y-hidden scrollbar-hide whitespace-nowrap"
                        @scroll="updateFilesOverflow"
                        ref="filesScroller"
                    >
                        <PreviewCard v-for="item in show_data?.art_previews" :art_preview="item" v-model="art_config.selected_preview" />
                    </DataTransition>
                </div>

                <div
                    v-if="files_options.shadow_left"
                    class="pointer-events-none absolute left-0 top-0 h-full w-6 bg-linear-to-r from-dark-001 to-transparent"
                />
                <div
                    v-if="files_options.shadow_right"
                    class="pointer-events-none absolute right-0 top-0 h-full w-6 bg-linear-to-l from-dark-001 to-transparent"
                />
            </div>

            <!-- SECTION: USER INFO -->
            <div class="flex gap-2 items-center justify-between px-4 sm:px-0">
                <!-- USER -->
                <div v-if="config.loading || !show_data" class="flex w-full max-w-54 gap-4 items-center shrink-0">
                    <div class="rounded-full size-12 animate-pulse bg-brand-950" />

                    <div class="flex flex-col gap-2">
                        <div class="h-6 w-32 bg-brand-950 animate-pulse rounded-3xl"></div>

                        <div class="h-4 w-24 bg-brand-950 animate-pulse rounded-3xl"></div>
                    </div>
                </div>

                <div v-else class="flex w-full max-w-54 gap-4 items-center shrink-0">
                    <img :src="show_data?.user?.image_url" class="rounded-full size-12 bg-brand-950 object-cover" />

                    <div class="flex flex-col gap-0">
                        <p class="text-lg font-semibold">{{ show_data.user?.username }}</p>
                        <!-- FIXME: Fix backend scrape for user creation date -->
                        <p class="text-sm text-brand-300">{{ messengerStyleTime(show_data.user?.created_at ?? '') }}</p>
                    </div>
                </div>

                <!-- DATE -->
                <div v-if="config.loading || !show_data" class="flex flex-1 min-w-0 flex-col items-end gap-2">
                    <div class="h-6 bg-brand-950 animate-pulse rounded-3xl w-32" />
                    <div class="relative max-w-full">
                        <div class="flex gap-2 overflow-x-auto scrollbar-hide whitespace-nowrap">
                            <div v-for="item in [0, 1, 2]" class="bg-brand-950 h-5 animate-pulse w-32 rounded-3xl text-sm truncate shrink-0" />
                        </div>
                    </div>
                </div>

                <div v-else class="flex flex-1 min-w-0 flex-col items-end gap-2">
                    <p class="text-sm text-brand-300">{{ messengerStyleTime(show_data.user?.created_at ?? '') }}</p>
                    <div class="relative max-w-full">
                        <RouterLink to="/" class="bg-brand-950 px-2 py-1 rounded-xl text-sm">
                            {{ show_data.art_category.name }}
                        </RouterLink>
                    </div>
                </div>
            </div>

            <!-- SECTION: ART Functions -->
            <div v-if="show_data" class="flex gap-2 justify-between xl:justify-start px-4 sm:px-0">
                <!-- LEFT -->
                <div class="flex items-center bg-brand-950 rounded-3xl">
                    <button
                        :class="[
                            'flex items-center gap-2 px-4 py-2 rounded-3xl cursor-pointer',
                            art_config.friend
                                ? 'bg-limed-spruce-700 hover:bg-limed-spruce-600 text-limed-spruce-50'
                                : 'bg-brand-950 hover:bg-brand-900 text-affair-50'
                        ]"
                        @click="art_config.friend = !art_config.friend"
                    >
                        <Icon :icon="art_config.friend ? 'pixel:check-circle-solid' : 'pixel:plus-solid'" class="size-3" />
                        <p v-if="art_config.friend" class="truncate">Friends</p>
                        <p v-else class="truncate">Add Friend</p>
                    </button>
                    <a
                        :href="`https://opengameart.org/user/${show_data?.user?.id}/contact`"
                        target="_blank"
                        class="flex items-center gap-2 bg-brand-950 hover:bg-brand-900 text-affair-50 px-4 py-2 rounded-3xl cursor-pointer"
                    >
                        <Icon icon="pixelarticons:send" class="size-3" />

                        <p class="inline min-[484px]:hidden">Msg</p>
                        <p class="hidden min-[484px]:inline">Message</p>
                    </a>
                </div>
                <!-- RIGHT -->
                <div class="flex items-center bg-brand-950 rounded-3xl">
                    <button
                        :class="[
                            'flex items-center gap-2 px-4 py-2 rounded-3xl cursor-pointer',
                            art_config.hearted ? 'bg-affair-700 hover:bg-affair-600 text-affair-50' : 'bg-brand-950 hover:bg-brand-900 text-affair-50'
                        ]"
                        @click="art_config.hearted = !art_config.hearted"
                    >
                        <Icon :icon="art_config.hearted ? 'iconamoon:heart-fill' : 'pixelarticons:heart'" class="size-3" />
                        {{ formatNumber(art_config.hearted ? show_data.favorites_count + 1 : show_data.favorites_count) }}
                    </button>
                    <button class="flex items-center gap-2 bg-brand-950 hover:bg-brand-900 text-affair-50 px-4 py-2 rounded-3xl cursor-pointer">
                        <Icon icon="pixelarticons:flag" class="size-3" />
                        <p class="hidden min-[484px]:inline">Report</p>
                    </button>
                </div>
            </div>

            <!-- SECTION: ATTRIBUTES -->
            <div v-if="show_data" class="flex gap-2 justify-end px-4 sm:px-0 relative">
                <!-- UNTESTED: Make the scroll functional -->
                <!-- UNTESTED: Overflow of contents -->
                <div ref="attributesScroller" class="flex gap-2 overflow-x-auto scrollbar-hide whitespace-nowrap" @scroll="updateAttributesOverflow">
                    <a v-for="item in show_data.licenses" :key="item.id" :href="item.url" class="bg-brand-950 px-4 py-1 rounded-3xl text-sm truncate shrink-0">
                        {{ item.name }}
                    </a>
                </div>
                <div
                    v-if="attributes_options.shadow_left"
                    class="pointer-events-none absolute left-0 top-0 h-full w-6 bg-linear-to-r from-dark-001 to-transparent"
                />
                <div
                    v-if="attributes_options.shadow_right"
                    class="pointer-events-none absolute right-0 top-0 h-full w-6 bg-linear-to-l from-dark-001 to-transparent"
                />
            </div>

            <!-- DESCRIPTION -->
            <div v-if="config.loading || !show_data" class="flex flex-col gap-2 items-center p-4 bg-brand-950 rounded-3xl h-32 animate-pulse" />
            <div v-else class="flex flex-col gap-2 items-center p-4 bg-brand-950 sm:rounded-3xl">
                <div v-html="show_data.content" ref="contentRef" :class="[read_more ? '' : 'line-clamp-6', 'w-full']"></div>

                <button v-if="should_show_read_more" class="cursor-pointer" @click="read_more = !read_more">{{ read_more ? 'Read Less' : 'Read More' }}</button>
            </div>

            <!-- SECTION: TAGS -->
            <div v-if="config.loading || !show_data" class="flex flex-col w-full relative">
                <div class="flex gap-2 overflow-x-auto overflow-y-hidden scrollbar-hide whitespace-nowrap">
                    <div
                        v-for="item in [1, 2, 3, 4, 5, 6]"
                        class="bg-brand-950 px-4 py-1 rounded-3xl text-sm flex items-center gap-1 shrink-0 w-32 h-6 animate-pulse"
                    />
                </div>
            </div>
            <div v-else class="flex flex-col w-full relative px-4 sm:px-0">
                <div class="flex gap-2 overflow-x-auto overflow-y-hidden scrollbar-hide whitespace-nowrap" ref="tagsScroller" @scroll="updateTagsOverflow">
                    <RouterLink
                        v-for="item in show_data.tags"
                        :key="item.id"
                        :to="`/arts?field_art_tags_tid=${encodeURIComponent(item.name)}`"
                        class="bg-brand-950 px-4 py-1 rounded-3xl text-sm flex items-center gap-1 shrink-0"
                    >
                        {{ item.name }}
                    </RouterLink>
                </div>

                <div v-if="tags_options.shadow_left" class="pointer-events-none absolute left-0 top-0 h-full w-6 bg-linear-to-r from-dark-001 to-transparent" />
                <div
                    v-if="tags_options.shadow_right"
                    class="pointer-events-none absolute right-0 top-0 h-full w-6 bg-linear-to-l from-dark-001 to-transparent"
                />
            </div>

            <!-- SECTION: FILES TO DOWNLOAD -->

            <div v-if="show_data" class="flex flex-col xl:grid grid-cols-2 gap-2 px-4 sm:px-0">
                <FileCard v-for="file in show_data.files" :key="file.id" :file class="w-full" />
                <FileCard v-for="file in show_data.files" :key="file.id" :file class="w-full" />
                <FileCard v-for="file in show_data.files" :key="file.id" :file class="w-full" />
                <FileCard v-for="file in show_data.files" :key="file.id" :file class="w-full" />
            </div>

            <!-- SECTION: COMMENTS -->
            <div v-if="show_data" class="px-4 sm:px-0">
                <CommentsModal v-model:comment_content="comment_content">
                    <template #comments>
                        <CommentSection v-if="show_data.art_comments.length > 0" v-for="item in show_data.art_comments" :key="item.id" :comment="item" />

                        <div v-else>
                            <p class="text-sm text-brand-400">No comments yet. Be the first to comment!</p>
                        </div>
                    </template>

                    <template #comment_preview>
                        <div v-if="show_data.art_comments.length > 0" class="flex gap-2 items-start">
                            <img :src="show_data.art_comments[0].user?.image_url" class="size-6 rounded-full bg-dark-001" />

                            <div class="flex flex-col gap-0">
                                <div class="text-md flex gap-2 items-center">
                                    <div class="font-semibold hover:bg-brand-950 transition-colors rounded-3xl px-2 -ml-2">
                                        {{ show_data.art_comments[0].user?.username }}
                                    </div>

                                    <p class="text-sm text-brand-400">- {{ messengerStyleTime(show_data.art_comments[0].created_at) }}</p>
                                </div>
                                <div v-html="show_data.art_comments[0].content" class="line-clamp-1" />

                                <div class="flex items-center gap-1">
                                    <Icon icon="pixelarticons:chevron-down-2" class="size-4 shrink-0" />
                                    <p class="text-sm text-brand-400">
                                        {{ show_data.art_comments.length - 1 }} More comment{{ show_data.art_comments.length - 1 > 1 ? 's' : '' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div v-else>
                            <p class="text-sm text-brand-400">No comments yet. Be the first to comment!</p>
                        </div>
                    </template>
                </CommentsModal>
            </div>
        </div>

        <div class="w-full lg:w-46 lg:shrink-0 2xl:w-110 grid grid-cols-2 lg:grid-cols-1 2xl:grid-cols-2 gap-4 px-4 sm:px-0">
            <ArtCard v-for="(item, idx) in weekly_arts" :art="item" :idx="idx" />
        </div>
    </div>
</template>

<script setup lang="ts">
import ArtCard from '@/components/cards/ArtCard.vue'
import MainArtPreviewCard from '@/components/cards/MainArtPreviewCard.vue'
import PreviewCard from '@/components/cards/PreviewCard.vue'
import CommentSection from '@/components/content/CommentSection.vue'
import DataTransition from '@/components/transitions/DataTransition.vue'
import { nextTick, onBeforeUnmount, onMounted, reactive, ref, useTemplateRef, watch, computed } from 'vue'
import { Icon } from '@iconify/vue'
import FileCard from '@/components/cards/FileCard.vue'
import CommentsModal from '@/components/modals/CommentsModal.vue'

import { useArtStore } from '@/stores/art.store'
import { useAuthStore } from '@/stores/auth.store'
import { formatNumber, messengerStyleTime } from '@/utils/utils'
import { storeToRefs } from 'pinia'
import { useRoute } from 'vue-router'
import { ArtPreview } from '@/global.interfaces'

const $artStore = useArtStore()
const { weekly_arts, show_data, config, audio_volume } = storeToRefs($artStore)
const { showAPI } = $artStore

const $authStore = useAuthStore()
const { auth } = storeToRefs($authStore)

const $route = useRoute()

const comment_content = ref('')

const getArtIdFromRoute = () => {
    const params = $route.params

    if (!('id' in params)) return null

    return Array.isArray(params.id) ? (params.id[0] ?? null) : params.id
}

const art_config = reactive<{
    selected_preview: ArtPreview | null
    friend: boolean
    hearted: boolean
}>({
    selected_preview: null,
    friend: true,
    hearted: true
})

const read_more = ref(false)
const contentRef = ref<HTMLDivElement | null>(null)
const should_show_read_more = computed(() => {
    if (!contentRef.value) return false
    if (read_more.value) return true

    const element = contentRef.value
    return element.scrollHeight > element.clientHeight + 5
})
const comment_here = ref('')

const attributes_scroller = useTemplateRef<HTMLElement | null>('attributesScroller')
const attributes_options = reactive({
    shadow_left: false,
    shadow_right: false
})

const files_scroller = useTemplateRef<HTMLElement | null>('filesScroller')
const files_options = reactive({
    shadow_left: false,
    shadow_right: false
})

const tags_scroller = useTemplateRef<HTMLElement | null>('tagsScroller')
const tags_options = reactive({
    shadow_left: false,
    shadow_right: false
})

const updateAttributesOverflow = () => {
    const el = attributes_scroller.value
    if (!el) return

    const hasOverflow = el.scrollWidth > el.clientWidth + 1

    if (!hasOverflow) {
        attributes_options.shadow_left = false
        attributes_options.shadow_right = false
        return
    }

    attributes_options.shadow_left = el.scrollLeft > 0
    attributes_options.shadow_right = el.scrollLeft + el.clientWidth < el.scrollWidth - 1
}

function updateFilesOverflow() {
    const el = files_scroller.value
    if (!el) return

    const hasOverflow = el.scrollWidth > el.clientWidth + 1

    if (!hasOverflow) {
        files_options.shadow_left = false
        files_options.shadow_right = false
        return
    }

    files_options.shadow_left = el.scrollLeft > 0
    files_options.shadow_right = el.scrollLeft + el.clientWidth < el.scrollWidth - 1
}

function updateTagsOverflow() {
    const el = tags_scroller.value
    if (!el) return

    const hasOverflow = el.scrollWidth > el.clientWidth + 1

    if (!hasOverflow) {
        tags_options.shadow_left = false
        tags_options.shadow_right = false
        return
    }

    tags_options.shadow_left = el.scrollLeft > 0
    tags_options.shadow_right = el.scrollLeft + el.clientWidth < el.scrollWidth - 1
}

const handleResize = () => {
    updateAttributesOverflow()
    updateFilesOverflow()
    updateTagsOverflow()
}

onMounted(async () => {
    await nextTick()
    updateAttributesOverflow()
    updateFilesOverflow()
    updateTagsOverflow()

    window.addEventListener('resize', handleResize)
})

onBeforeUnmount(() => {
    window.removeEventListener('resize', handleResize)
})

watch(
    () => $route.params,
    async () => {
        const artId = getArtIdFromRoute()

        if (artId) {
            window.scrollTo({ top: 0, behavior: 'smooth' })

            await showAPI(artId)

            show_data.value && (document.title = `${show_data.value.title} | OGA (Not Official)`)
            art_config.selected_preview = show_data.value?.art_previews[0] ?? null // Init selected art preview

            read_more.value = false

            await nextTick()
        }
    },
    { immediate: true }
)
</script>
