<template>
    <div class="m-6 flex flex-col items-center gap-4">
        <DataTransition class="flex flex-col gap-4">
            <img
                :src="image"
                class="w-auto h-64 walker top-1/2 left-0"
                :style="{ animationDelay: `${0 * animation_delay}ms`, transitionDelay: `${0 * animation_delay}ms` }"
                @animationend.once="clearDelays"
                @transitionend.once="clearDelays"
            />
            <img
                :src="image"
                class="w-auto h-64 walker top-1/2 left-0"
                :style="{ animationDelay: `${1 * animation_delay * 10}ms`, transitionDelay: `${1 * animation_delay * 10}ms` }"
                @animationend.once="clearDelays"
                @transitionend.once="clearDelays"
            />
        </DataTransition>

        <div class="text-2xl">Redirecting to opengameart.org</div>
        <AppButton @click="redirect()">Open OpenGameArt.org {{ seconds }}</AppButton>
    </div>
</template>

<script setup lang="ts">
import AppButton from '@/components/form/AppButton.vue'
import image from '/public/images/building.png'

import { onMounted, onUnmounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import DataTransition from '@/components/transitions/DataTransition.vue'
import { animation_delay, clearDelays } from '@/utils/utils'

const $route = useRoute()

const seconds = ref<number>(4)
const new_base_url = 'https://opengameart.org'

let timer: number | undefined

onMounted(() => {
    timer = window.setInterval(() => {
        if (seconds.value === 0) {
            redirect()
            clearInterval(timer)
        } else {
            seconds.value = seconds.value - 1
        }
    }, 1000)
})

onUnmounted(() => {
    if (timer !== undefined) {
        clearInterval(timer)
    }
})

function redirect() {
    window.location.href = new_base_url + $route.fullPath
}
</script>

<style>
.walker {
    width: 10rem; /* frame width */
    height: auto; /* frame height */
    background: url('/images/builder.png') left center;
    background-size: cover;

    position: absolute;
    bottom: 20px;

    animation: walkFrames 1s steps(8) infinite, walkMove 4s linear infinite;
}

/* Sprite walk frames */
@keyframes walkFrames {
    from {
        background-position: 0;
    }
    to {
        background-position: -512px;
    }
}

/* Move left → right → left WITH flip */
@keyframes walkMove {
    0% {
        transform: translateX(0) scaleX(1);
    } /* facing right */
    49% {
        transform: translateX(300px) scaleX(1);
    } /* still right */
    50% {
        transform: translateX(300px) scaleX(-1);
    } /* turn around instantly */
    100% {
        transform: translateX(0) scaleX(-1);
    } /* walking left */
}
</style>
