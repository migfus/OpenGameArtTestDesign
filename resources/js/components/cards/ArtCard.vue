<template>
    <div :class="['flex flex-col gap-2 cursor-pointer group relative']">
        <div
            class="w-full h-38 relative bg-cover rounded-2xl border-2 border-brand-950 flex flex-col justify-end z-10 bg-dark-001"
            :style="`background-image: url(${art.preview_image});`"
        >
            <div class="flex flex-col h-full justify-between">
                <div class="p-2 flex gap-1 justify-end relative">
                    <p class="bg-brand-950 px-2 rounded-xl text-sm">{{ art.audio_mp3 ? 'Music' : 'art' }}</p>
                </div>
                <div v-if="art.audio_mp3" class="flex justify-center">
                    <DataTransition v-if="is_playing" class="flex gap-2 items-center">
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
                        class="size-18 bg-brand-950 rounded-full hover:scale-110 transition-all p-2"
                        @click.stop="playAudio()"
                        @mousedown.stop
                    />
                    <audio ref="audioRef" :src="art.audio_mp3"></audio>
                    <div v-if="is_playing" class="absolute size-18 rounded-full border-b-2 border-brand-200 animate-spin -z-10 blur-sm"></div>
                    <div ref="waveform"></div>
                </div>

                <div class="p-2 flex gap-1 bg-linear-to-t from-brand-950 to-transparent rounded-b-xl flex-none">
                    <img :src="art.preview_image" class="size-5 rounded-full border border-brand-950" />
                    <p class="text-sm font-semibold truncate text-brand-200/75 group-hover:text-brand-200 transition-all">[username]</p>
                </div>
            </div>
        </div>
        <div class="flex flex-col z-10">
            <p class="font-bold truncate">{{ art.title }}</p>
            <p class="line-clamp-3 text-brand-200/50 group-hover:text-brand-200/75 transition-all">
                Reprehenderit dolore culpa officia occaecat id eiusmod aliquip cillum cupidatat nisi consequat nisi cupidatat. Irure id consequat esse voluptate
                Lorem. Pariatur minim quis cupidatat sit ut velit ad reprehenderit id id et voluptate. Lorem nulla incididunt elit ea ea amet proident non
                mollit.
            </p>
        </div>

        <div class="absolute h-full w-full rounded-2xl group-hover:bg-brand-950 transition-all group-hover:scale-[110%] z-0"></div>

        <Teleport to="body">
            <div class="fixed bg-brand-950 w-full h-32">hhh</div>
        </Teleport>
    </div>
</template>

<script setup lang="ts">
import { Art } from '@/globalInterfaces'
import { Icon } from '@iconify/vue'

import { onMounted, onUnmounted, ref, useTemplateRef } from 'vue'
import DataTransition from '../transitions/DataTransition.vue'
import { clearDelays } from '@/utils/utils'

const { art, idx } = defineProps<{
    art: Art
    idx: number
}>()

const $emit = defineEmits(['pauseAllAudio'])

const audioRef = useTemplateRef('audioRef')
const is_playing = ref(false)

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

onMounted(() => {
    window.addEventListener('pauseAllAudio', onExternalPause as EventListener)
})

onUnmounted(() => {
    window.removeEventListener('pauseAllAudio', onExternalPause as EventListener)
})
</script>
