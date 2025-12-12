<template>
    <div :class="['flex flex-col gap-2 cursor-pointer group relative']">
        <div
            class="w-full h-38 relative bg-cover rounded-2xl border-2 border-brand-950 flex flex-col justify-end z-10 bg-dark-001 cursor-default"
            :style="`background-image: url(${art.user ? art.user.image_url : art.preview_image});`"
        >
            <div class="flex flex-col h-full justify-between">
                <div class="p-2 flex gap-1 justify-end relative">
                    <p v-if="art.art_category" class="bg-brand-950 px-2 rounded-xl text-sm">{{ art.art_category.name }}</p>
                    <p v-else class="bg-brand-950 px-2 rounded-xl text-sm h-4 w-8"></p>
                </div>
                <div v-if="art.art_category?.name == 'Music' || art.art_category?.name == 'Sound Effect'" class="flex justify-center">
                    <DataTransition v-if="is_playing" class="flex gap-2 items-center cursor-pointer">
                        <Icon
                            icon="memory:rotate-counterclockwise"
                            class="size-12 bg-brand-950 rounded-full hover:scale-110 transition-all p-2"
                            @click.stop="stopAudio()"
                            @mousedown.stop
                            :style="{ animationDelay: `${100}ms`, transitionDelay: `${100}ms` }"
                            @animationend.once="clearDelays"
                            @transitionend.once="clearDelays"
                        />

                        <Icon
                            icon="memory:pause"
                            class="size-18 bg-brand-900 rounded-full hover:scale-110 transition-all p-2"
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
                                icon="memory:arrow-right"
                                class="size-12 bg-brand-950 rounded-full hover:scale-110 transition-all p-2"
                                @click.stop="forwardTime()"
                                @mousedown.stop
                            />
                        </a>
                    </DataTransition>

                    <Icon
                        v-else
                        icon="memory:play"
                        class="size-18 bg-brand-950 rounded-full hover:scale-110 transition-all p-2 cursor-pointer"
                        @click.stop="playAudio()"
                        @mousedown.stop
                    />

                    <audio ref="audioRef" :src="art.audio_mp3"></audio>
                    <div v-if="is_playing" class="absolute size-18 rounded-full border-b-4 border-[#b7a6d4] animate-spin -z-10 blur-sm"></div>
                    <div ref="waveform"></div>
                </div>

                <a
                    v-if="art.user"
                    :href="`https://opengameart.org/users/${art.user.id}`"
                    class="p-2 flex gap-1 bg-linear-to-t from-dark-001 to-transparent rounded-b-xl justify-between h-12 items-end"
                >
                    <div class="flex gap-2 truncate">
                        <img :src="art.user.image_url" class="size-5 rounded-full border border-brand-950" />
                        <p class="text-sm font-semibold truncate text-brand-200/75 group-hover:text-brand-200 transition-all">
                            {{ art.user?.username }}
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <div v-if="art.favorites_count" class="flex items-center gap-1">
                            <p class="text-sm">{{ art.favorites_count }}</p>
                            <Icon icon="memory:heart" class="size-3" />
                        </div>
                        <div class="flex items-center gap-1">
                            <p class="text-sm">2k</p>
                            <Icon icon="memory:chat" class="size-3" />
                        </div>
                    </div>
                </a>

                <div v-else class="p-2 flex gap-1 bg-linear-to-t from-brand-950 to-transparent rounded-b-xl flex-none">
                    <div class="size-5 rounded-full bg-brand-950 animate-pulse" />
                    <div class="animate-pulse bg-red-700 rounded-xl h-4" />
                </div>
            </div>
        </div>
        <a :href="`https://opengameart.org/content/${art.id}`" class="flex flex-col z-10">
            <div class="flex justify-between gap-2">
                <p class="font-bold truncate">{{ art.title }}</p>
            </div>
            <div v-if="art.content" class="line-clamp-3 text-brand-200/50 group-hover:text-brand-200/75 transition-all text-sm" v-html="art.content" />

            <div v-else class="rounded-lg flex gap-1 flex-col mt-2">
                <div class="h-4 rounded-lg w-ful bg-brand-950 animate-pulse group-hover:bg-dark-001 transition-all" />
                <div class="h-4 rounded-lg w-full bg-brand-950 animate-pulse group-hover:bg-dark-001 transition-all" />
                <div class="h-4 rounded-lg w-full bg-brand-950 animate-pulse group-hover:bg-dark-001 transition-all" />
            </div>
        </a>

        <div class="absolute h-full w-full rounded-2xl group-hover:bg-brand-950 transition-all group-hover:scale-[110%] z-0"></div>

        <Teleport to="body">
            <BasicTransition>
                <div v-if="is_playing" class="flex justify-center">
                    <div
                        :class="[
                            show_player && is_playing ? 'translate-y-0 ' : 'translate-y-16 md:translate-y-0',
                            'fixed bg-brand-950/75 backdrop-blur-sm w-full h-46 md:h-24 bottom-0 z-10 flex flex-col gap-2 transition-all md:rounded-t-2xl md:w-xl '
                        ]"
                    >
                        <div class="flex justify-between py-2 text-brand-300 px-4 items-center">
                            <div class="truncate w-32 flex flex-col">
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
                                        icon="memory:arrow-right"
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

                            <div class="flex gap-2 items-center">
                                <div class="flex items-center gap-1">
                                    <p class="text-xs">2k</p>
                                    <Icon icon="memory:chat" class="size-5" />
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2 px-4">
                            <div class="relative w-full h-6 md:h-4 bg-dark-001 rounded-full p-1">
                                <div
                                    :class="['absolute left-0 top-1 bottom-1 bg-brand-300 rounded-full']"
                                    :style="{ width: `${(audio_current_time / audio_duration) * 100}%` }"
                                ></div>
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
        </Teleport>
    </div>
</template>

<script setup lang="ts">
import { Art } from '@/globalInterfaces'
import { Icon } from '@iconify/vue'

import { onBeforeUnmount, onMounted, onUnmounted, ref, useTemplateRef } from 'vue'
import DataTransition from '../transitions/DataTransition.vue'
import { clearDelays, getFileNameFromUrl } from '@/utils/utils'
import BasicTransition from '../transitions/BasicTransition.vue'
import AppButton from '../form/AppButton.vue'
import { RouterLink } from 'vue-router'
import { MenuItem } from '@headlessui/vue'
import { useResizeObserver } from '@vueuse/core'

const { art, idx } = defineProps<{
    art: Art
    idx: number
}>()

const $emit = defineEmits(['pauseAllAudio'])

const audioRef = useTemplateRef('audioRef')
const is_playing = ref(false)
const audio_duration = ref(0)
const audio_current_time = ref(0)

const show_player = ref(false)
let lastScroll = 0

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

function playAudio() {
    if (audioRef.value) {
        audioRef.value.play()
        is_playing.value = true

        window.dispatchEvent(new CustomEvent('pauseAllAudio', { detail: { idx } }))
    } else {
        alert('no audio file found')
    }
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
