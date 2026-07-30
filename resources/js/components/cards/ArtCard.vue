<template>
    <div :class="['flex flex-col gap-1 cursor-pointer group relative']">
        <div :class="['w-full h-38 relative bg-cover rounded-2xl border-2 border-brand-950 flex flex-col justify-end z-10 bg-dark-001 cursor-default']">
            <!-- SECTION: MUSIC / SOUND VISUAL PREVIEW -->
            <div v-if="art.art_category.name == 'Music' || art.art_category.name == 'Sound Effect'" class="absolute h-full w-full">
                <div v-if="!art.user?.image_url" class="bg-brand-950/50 h-full w-full rounded-xl bg-linear-30 to-[#0c1314] from-[#271c38]" />
                <img v-else :src="art.user?.image_url" class="w-full h-full object-cover rounded-2xl pixelated-img" />
            </div>

            <!-- SECTION: ARTS CONTENT -->
            <RouterLink
                v-else-if="art.art_previews.length > 0"
                :to="`/arts/${art.id}`"
                class="bg-brand-950/50 h-38 w-full object-cover rounded-2xl flex absolute "
            >
                <div class="absolute bottom-0">
                    <img
                        v-if="art.art_category.name == 'Music' || art.art_category.name == 'Sound Effect'"
                        :src="previewMusicSoundEffectImage"
                        class="absolute h-full w-full object-cover rounded-2xl z-10"
                    />
                </div>

                <div class="w-full h-full overflow-hidden rounded-2xl aspect-square">
                    <img :src="art.image_preview" class="h-full w-full object-cover pixelated-img" />
                </div>
            </RouterLink>

            <!-- SECTION: OVERLAY -->
            <div class="flex flex-col h-full justify-between">
                <div class="p-2 flex gap-1 justify-end relative">
                    <p v-if="art.art_category" class="bg-brand-950/70 px-2 rounded-xl text-xs font-semibold py-1 backdrop-blur-lg">
                        {{ art.art_category.name }}
                    </p>
                    <p v-else class="bg-brand-950 px-2 rounded-xl text-sm h-4 w-8"></p>
                </div>

                <!-- SECTION: MUSIC / SFX -->
                <div v-if="art.art_category?.name == 'Music' || art.art_category?.name == 'Sound Effect'" class="flex justify-center">
                    <DataTransition v-if="is_playing" class="flex gap-2 items-center cursor-pointer">
                        <Icon
                            icon="memory:rotate-counterclockwise"
                            class="size-12 bg-brand-900/90 backdrop-blur-sm rounded-full hover:scale-110 transition-all p-2"
                            @click.stop="stopAudio()"
                            @mousedown.stop
                            :style="{ animationDelay: `${100}ms`, transitionDelay: `${100}ms` }"
                            @animationend.once="clearDelays"
                            @transitionend.once="clearDelays"
                        />

                        <Icon
                            icon="memory:pause"
                            class="size-18 bg-brand-900/90 backdrop-blur-sm rounded-full hover:scale-110 transition-all p-2"
                            @click.stop="pauseAudio()"
                            @mousedown.stop
                        />

                        <RouterLink
                            :to="`/arts/${art.id}`"
                            class="transition-all"
                            :style="{ animationDelay: `${200}ms`, transitionDelay: `${200}ms` }"
                            @animationend.once="clearDelays"
                            @transitionend.once="clearDelays"
                        >
                            <Icon
                                icon="memory:arrow-right-box"
                                class="size-12 bg-brand-900/90 backdrop-blur-sm rounded-full hover:scale-110 transition-all p-2"
                                @click.stop="forwardTime()"
                                @mousedown.stop
                            />
                        </RouterLink>
                    </DataTransition>

                    <Icon
                        v-else
                        icon="memory:play"
                        class="size-18 bg-brand-900/90 backdrop-blur-sm rounded-full hover:scale-110 transition-all p-2 cursor-pointer"
                        @click.stop="playAudio(art.art_previews[0].url)"
                        @mousedown.stop
                    />

                    <audio ref="audioRef" :src="selected_audio_playback" />
                    <div v-if="is_playing" class="absolute size-18 rounded-full border-b-4 border-[#b7a6d4] animate-spin -z-10 blur-sm"></div>
                    <div ref="waveform"></div>
                </div>

                <a
                    v-if="art.updated_at"
                    :href="`https://opengameart.org/users/${art.user?.url_username}`"
                    class="p-2 flex gap-1 bg-linear-to-t from-dark-001 to-transparent rounded-b-xl justify-between h-12 items-end z-10 hover:from-dark-002 transition-all"
                >
                    <div class="flex gap-2 truncate items-center">
                        <Icon v-if="!art.user" icon="pixelarticons:annoyed" class="size-4" />
                        <Icon v-else-if="!art.user.image_url" icon="pixelarticons:avatar-circle-sharp" class="size-5 rounded-full  text-brand-200"/>
                        <img v-else :src="art.user.image_url" class="size-5 rounded-full border border-brand-950" />
                        <p v-if="!art.user" class="text-sm font-semibold truncate text-brand-200 group-hover:text-brand-100 transition-all">Anonymous</p>
                        <p v-else class="text-sm font-semibold truncate text-brand-200 group-hover:text-brand-100 transition-all">
                            {{ art.user?.username }}
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <div v-if="art.favorites_count" class="flex items-center gap-1">
                            <p class="text-sm">{{ art.favorites_count }}</p>
                            <Icon icon="memory:heart" class="size-3" />
                        </div>
                        <div v-if="art.files.length > 0" class="flex items-center gap-1">
                            <p class="text-sm">{{ formatNumber(art.files.reduce((sum, item) => sum + item.download_count, 0)) }}</p>
                            <Icon icon="memory:arrow-down-bold" class="size-3" />
                        </div>
                    </div>
                </a>

                <div v-else class="p-2 flex gap-1 bg-linear-to-t from-brand-950 to-transparent rounded-b-xl flex-none">
                    <div class="size-5 rounded-full bg-brand-950 animate-pulse" />
                    <div class="animate-pulse bg-red-700 rounded-xl h-4" />
                </div>
            </div>
        </div>

        <RouterLink :to="`/arts/${art.id}`" class="flex flex-col z-10 justify-between">
            <div class="flex flex-col gap-1 grow h-18">
                <!-- TITLE -->
                <div class="flex justify-between gap-2">
                    <p class="font-bold truncate">{{ art.title }}</p>
                </div>

                <!-- DESCRIPTION -->
                <div v-if="art.content" class="line-clamp-2 text-brand-200/50 group-hover:text-brand-200/75 transition-all text-sm" v-html="art.content" />

                <div v-else class="rounded-lg flex gap-1 flex-col mt-2">
                    <div class="h-4 rounded-lg w-ful bg-brand-950 animate-pulse group-hover:bg-dark-001 transition-all" />
                    <div class="h-4 rounded-lg w-full bg-brand-950 animate-pulse group-hover:bg-dark-001 transition-all" />
                    <div class="flex justify-between gap-2 mb-4">
                        <div class="h-4 w-5 rounded-lg bg-brand-950 animate-pulse group-hover:bg-dark-001 transition-all" />
                        <div class="h-4 rounded-lg w-full bg-brand-950 animate-pulse group-hover:bg-dark-001 transition-all" />
                        <div class="h-4 w-8 rounded-lg bg-brand-950 animate-pulse group-hover:bg-dark-001 transition-all" />
                    </div>
                </div>
            </div>

            <!-- COMMMENT -->
            <!-- With Comments -->
            <div v-if="art.art_comments.length > 0" class="flex justify-between items-center gap-2 text-light-001/75">
                <div class="flex gap-2 items-center text-sm truncate">
                    <img :src="art.art_comments[art.art_comments.length - 1].user?.image_url ?? ''" class="rounded-full size-4" />
                    <div class="line-clamp-1 animate-maruyq" v-html="art.art_comments[art.art_comments.length - 1].content" />
                </div>

                <div class="flex gap-1 items-center text-sm ml-2">
                    <p>{{ formatNumber(art.comments_count) }}</p>
                    <Icon icon="memory:comment-text" class="size-4" />
                </div>
            </div>
        </RouterLink>

        <div class="absolute h-full w-full rounded-2xl group-hover:bg-brand-950 transition-all group-hover:scale-[110%] z-0"></div>

        <!-- PLAYER -->
        <Teleport to="body">
            <div>
                <!-- LIST -->
                <BasicTransition>
                    <div v-if="is_playing && art.art_previews.length > 1" class="flex justify-center">
                        <div
                            :class="[
                                show_player && is_playing ? 'translate-y-0 ' : 'translate-y-16 md:translate-y-0',
                                'fixed bg-brand-950/90 backdrop-blur-sm w-full py-6 px-3 z-10 flex flex-col gap-2 transition-all md:rounded-2xl md:w-lg bottom-45 md:bottom-25'
                            ]"
                        >
                            <div class="text-light-001 max-h-33 overflow-y-scroll gap-1 flex flex-col scrollbar-hide">
                                <div
                                    v-for="item in art.art_previews.filter((item) => item.art_preview_category.name == 'audio')"
                                    @click.stop="playAudio(item.url)"
                                    :class="[
                                        selected_audio_playback == item.url ? 'bg-dark-001/50' : '',
                                        'flex gap-2 items-center hover:bg-dark-001/50 transition-all px-3 py-2 rounded-2xl group justify-between cursor-pointer '
                                    ]"
                                >
                                    <div class="flex gap-2 items-center">
                                        <Icon
                                            v-if="selected_audio_playback == item.url"
                                            icon="memory:pause"
                                            class="size-8 bg-dark-001 rounded-full hover:scale-110 transition-all p-2 cursor-pointer group-hover:bg-brand-950 flex-none"
                                            @mousedown.stop
                                        />
                                        <Icon
                                            v-else
                                            icon="memory:play"
                                            class="size-8 bg-dark-001 rounded-full hover:scale-110 transition-all p-2 cursor-pointer group-hover:bg-brand-950 flex-none"
                                            @mousedown.stop
                                        />
                                        <p class="line-clamp-1">{{ item.url.split('/').pop() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </BasicTransition>

                <!-- PLAYER -->
                <BasicTransition>
                    <div v-if="is_playing" class="flex justify-center">
                        <div
                            :class="[
                                show_player && is_playing ? 'translate-y-0 ' : 'translate-y-16 md:translate-y-0',
                                'fixed bg-brand-950/90 backdrop-blur-sm w-full h-44 md:h-24 bottom-0 z-10 flex flex-col gap-2 transition-all md:rounded-t-2xl md:w-lg'
                            ]"
                        >
                            <div class="flex justify-between py-2 text-brand-300 px-4 items-center gap-2">
                                <div class="truncate w-42 flex flex-col">
                                    <p class="font-semibold truncate">{{ art.title }}</p>
                                    <div class="flex gap-1 items-center">
                                        <img :src="art.user?.image_url" class="size-4 rounded-full" />
                                        <p class="font-semibold truncate text-sm">{{ art.user?.username }}</p>
                                    </div>
                                </div>

                                <DataTransition v-if="is_playing" class="flex gap-2 items-center cursor-pointer mx-auto">
                                    <Icon
                                        icon="memory:rotate-counterclockwise"
                                        class="size-10 text-brand-200 bg-dark-001 rounded-full hover:scale-110 transition-all p-2"
                                        @click.stop="stopAudio()"
                                        @mousedown.stop
                                        :style="{ animationDelay: `${200}ms`, transitionDelay: `${200}ms` }"
                                        @animationend.once="clearDelays"
                                        @transitionend.once="clearDelays"
                                    />

                                    <Icon
                                        icon="memory:pause"
                                        class="size-12 bg-dark-001 text-brand-200 rounded-full hover:scale-110 transition-all p-2"
                                        @click.stop="pauseAudio()"
                                        @mousedown.stop
                                    />

                                    <a
                                        :href="art.id"
                                        class="transition-all"
                                        :style="{ animationDelay: `${200}ms`, transitionDelay: `${200}ms` }"
                                        @animationend.once="clearDelays"
                                        @transitionend.once="clearDelays"
                                    >
                                        <Icon
                                            icon="memory:arrow-right-box"
                                            class="size-10 bg-dark-001 text-brand-200 rounded-full hover:scale-110 transition-all p-2"
                                        />
                                    </a>
                                </DataTransition>

                                <Icon
                                    v-else
                                    icon="memory:play"
                                    class="size-18 bg-brand-950 rounded-full hover:scale-110 transition-all p-2"
                                    @click.stop="playAudio()"
                                    @mousedown.stop
                                />

                                <div class="flex gap-2 items-center w-42 justify-end">
                                    <div v-if="art.favorites_count" class="flex items-center gap-1">
                                        <p class="text-sm">{{ art.favorites_count }}</p>
                                        <Icon icon="memory:heart" class="size-4" />
                                    </div>

                                    <div v-if="art.files.length > 0" class="flex items-center gap-1">
                                        <p class="text-sm">{{ formatNumber(art.files.reduce((sum, item) => sum + item.download_count, 0)) }}</p>
                                        <Icon icon="memory:arrow-down-bold" class="size-4" />
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2 px-4">
                                <div class="relative w-full h-6 md:h-4 bg-dark-001 rounded-full p-1">
                                    <div
                                        :class="['absolute left-0 top-1 bottom-1 bg-brand-300 rounded-full']"
                                        :style="{ width: `${(audio_current_time / audio_duration) * 100}%` }"
                                    />
                                    <input
                                        type="range"
                                        min="0"
                                        :max="audio_duration"
                                        step="0.01"
                                        v-model="audio_current_time"
                                        @input="seekAudio"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </BasicTransition>
            </div>
        </Teleport>
    </div>
</template>

<script setup lang="ts">
import { Icon } from '@iconify/vue'
import BasicTransition from '../transitions/BasicTransition.vue'
import DataTransition from '../transitions/DataTransition.vue'

import { Art } from '@/global.interfaces'
import { clearDelays, formatNumber } from '@/utils/utils'
import { computed, nextTick, onBeforeUnmount, onMounted, onUnmounted, ref, useTemplateRef } from 'vue'

const { art, idx } = defineProps<{
    art: Art
    idx: number
}>()

const $emit = defineEmits(['pauseAllAudio'])

const audioRef = useTemplateRef('audioRef')
const is_playing = ref(false)
const audio_duration = ref(0)
const audio_current_time = ref(0)
const selected_audio_playback = ref<string>('')

const show_player = ref(false)
let lastScroll = 0

const previewMusicSoundEffectImage = computed(() => {
    // NOTE: Checks for any art_previews
    if (art.art_previews.length > 0) {
        // NOTE: Checks for any images found on previews
        if (art.art_previews.filter((item) => item.art_preview_category.name == 'image').length > 0) {
            return art.art_previews.filter((item) => item.art_preview_category.name == 'image')[0].url
        } else {
            return art.user?.image_url
        }
    }

    // IF found nothing for images, returns user image instead
    return art.user?.image_url
})

const handleScroll = () => {
    const current = window.scrollY

    if (current > lastScroll) {
        // scrolling down → hide
        show_player.value = false
    } else {
        // scrolling up → show
        show_player.value = true
    }

    lastScroll = current
}

async function playAudio(preview?: string) {
    if (preview) {
        selected_audio_playback.value = preview
        await nextTick()
    }
    if (audioRef.value) {
        audioRef.value.play()
        is_playing.value = true

        window.dispatchEvent(new CustomEvent('pauseAllAudio', { detail: { idx } }))
    } else {
        console.log('playAudio() error')
    }
}

async function urlToBlob(url: string) {
    const res = await fetch(url)
    const blob = await res.blob()
    return blob
}

function onExternalPause(e: Event) {
    const detail = (e as CustomEvent)?.detail
    if (detail?.idx !== idx) {
        pauseAudio()
    }
}

function pauseAudio() {
    if (audioRef.value) {
        audioRef.value.pause()
        is_playing.value = false
    }
}

function stopAudio() {
    if (audioRef.value) {
        // pauseAudio()
        audioRef.value.currentTime = 0
    }
}

function forwardTime(seconds = 10) {
    if (audioRef.value) {
        const dur = audioRef.value.duration
        if (!isFinite(dur)) return
        audioRef.value.currentTime = Math.min((audioRef.value.currentTime || 0) + seconds, dur)
    }
}

function seekAudio() {
    if (audioRef.value) {
        audioRef.value.currentTime = audio_current_time.value
    }
}

onMounted(() => {
    window.addEventListener('pauseAllAudio', onExternalPause as EventListener)
    window.addEventListener('scroll', handleScroll)

    if (audioRef.value) {
        audioRef.value.addEventListener('loadedmetadata', () => {
            audio_duration.value = audioRef.value?.duration || 0
        })

        audioRef.value.addEventListener('timeupdate', () => {
            audio_current_time.value = audioRef.value?.currentTime || 0
        })
    }
})

onUnmounted(() => {
    window.removeEventListener('pauseAllAudio', onExternalPause as EventListener)
})

onBeforeUnmount(() => window.removeEventListener('scroll', handleScroll))
</script>

<style>
.pixelated-img {
    image-rendering: pixelated;
}
</style>
