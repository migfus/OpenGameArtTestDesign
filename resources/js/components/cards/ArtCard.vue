<template>
    <div :class="['flex flex-col gap-2 cursor-pointer group relative']">
        <div
            class="w-full h-38 relative bg-cover rounded-2xl border-2 border-brand-950 flex flex-col justify-end z-10 bg-dark-001 cursor-default"
            :style="`background-image: url(${art.preview_image});`"
        >
            <div class="flex flex-col h-full justify-between">
                <div class="p-2 flex gap-1 justify-end relative">
                    <p class="bg-brand-950 px-2 rounded-xl text-sm">{{ art.audio_mp3 ? 'Music' : 'art' }}</p>
                </div>
                <div v-if="art.audio_mp3" class="flex justify-center">
                    <DataTransition v-if="is_playing" class="flex gap-2 items-center cursor-pointer">
                        <Icon
                            icon="memory:stop"
                            class="size-12 bg-brand-950 rounded-full hover:scale-110 transition-all p-2"
                            @click.stop="stopAudio()"
                            @mousedown.stop
                            :style="{ animationDelay: `${200}ms`, transitionDelay: `${200}ms` }"
                            @animationend.once="clearDelays"
                            @transitionend.once="clearDelays"
                        />

                        <Icon
                            icon="memory:pause"
                            class="size-18 bg-brand-900 rounded-full hover:scale-110 transition-all p-2"
                            @click.stop="pauseAudio()"
                            @mousedown.stop
                        />

                        <Icon
                            icon="memory:arrow-right"
                            class="size-12 bg-brand-950 rounded-full hover:scale-110 transition-all p-2"
                            @click.stop="forwardTime()"
                            @mousedown.stop
                            :style="{ animationDelay: `${200}ms`, transitionDelay: `${200}ms` }"
                            @animationend.once="clearDelays"
                            @transitionend.once="clearDelays"
                        />
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

                <a href="#" class="p-2 flex gap-1 bg-linear-to-t from-brand-950 to-transparent rounded-b-xl flex-none">
                    <img :src="art.preview_image" class="size-5 rounded-full border border-brand-950" />
                    <p class="text-sm font-semibold truncate text-brand-200/75 group-hover:text-brand-200 transition-all">[username]</p>
                </a>
            </div>
        </div>
        <a :href="`https://opengameart.org/${art.link}`" class="flex flex-col z-10">
            <p class="font-bold truncate">{{ art.title }}</p>
            <p class="line-clamp-3 text-brand-200/50 group-hover:text-brand-200/75 transition-all">
                Reprehenderit dolore culpa officia occaecat id eiusmod aliquip cillum cupidatat nisi consequat nisi cupidatat. Irure id consequat esse voluptate
                Lorem. Pariatur minim quis cupidatat sit ut velit ad reprehenderit id id et voluptate. Lorem nulla incididunt elit ea ea amet proident non
                mollit.
            </p>
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
                                <div class="flex gap-1">
                                    <img
                                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d9/Icon-round-Question_mark.svg/1200px-Icon-round-Question_mark.svg.png"
                                        class="size-4"
                                    />
                                    <p class="font-semibold truncate text-xs">[username]</p>
                                </div>
                            </div>

                            <DataTransition v-if="is_playing" class="flex gap-2 items-center cursor-pointer mx-auto">
                                <Icon
                                    icon="memory:stop"
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

                                <Icon
                                    icon="memory:arrow-right"
                                    class="size-10 bg-dark-001 text-brand-200 rounded-full hover:scale-110 transition-all p-2"
                                    @click.stop="forwardTime()"
                                    @mousedown.stop
                                    :style="{ animationDelay: `${200}ms`, transitionDelay: `${200}ms` }"
                                    @animationend.once="clearDelays"
                                    @transitionend.once="clearDelays"
                                />
                            </DataTransition>

                            <Icon
                                v-else
                                icon="memory:play"
                                class="size-18 bg-brand-950 rounded-full hover:scale-110 transition-all p-2"
                                @click.stop="playAudio()"
                                @mousedown.stop
                            />

                            <div class="w-32 flex justify-end">
                                <AppButton icon="memory:arrow-right-circle" :href="art.link">View Art</AppButton>
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
        pauseAudio()
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
