<template>
    <RouterLink :to="art.link" :class="['flex flex-col gap-2 cursor-pointer group relative']">
        <div
            class="w-full h-38 relative bg-cover rounded-2xl border-2 border-brand-950 flex flex-col justify-end z-10 bg-dark-001"
            :style="`background-image: url(${art.preview_image});`"
        >
            <div class="flex flex-col h-full justify-between">
                <div class="p-2 flex gap-1 justify-end relative">
                    <p class="bg-brand-950 px-2 rounded-xl text-sm">{{ art.audio_mp3 ? 'mp3' : 'art' }}</p>
                </div>
                <div v-if="art.audio_mp3" class="flex justify-center">
                    <Icon icon="memory:play" class="size-12 bg-brand-950 rounded-full" />
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
    </RouterLink>
</template>

<script setup lang="ts">
import { Art } from '@/globalInterfaces'
import { Icon } from '@iconify/vue'

const { art } = defineProps<{
    art: Art
    idx: number
}>()
</script>
