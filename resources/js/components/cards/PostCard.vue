<template>
    <RouterLink :to="post.link" :class="['flex flex-col gap-2 cursor-pointer group relative']">
        <div
            class="w-full h-54 aspect-square relative bg-cover rounded-2xl border-2 border-brand-950 flex flex-col justify-end z-10 bg-dark-001"
            :style="`background-image: url(${getAnyPossibleImageFromHtml(post.content_html)[0] ?? post.author_image});`"
        >
            <div class="flex flex-col h-full justify-end aspect-square">
                <div class="p-2 flex gap-1 bg-linear-to-t from-brand-950 to-transparent rounded-b-xl flex-none">
                    <img :src="post.author_image" class="size-5 rounded-full border border-brand-950" />
                    <p class="text-sm font-semibold truncate">{{ post.author_name }}</p>
                </div>
            </div>
        </div>
        <div class="flex flex-col z-10">
            <p class="font-bold truncate">{{ post.title }}</p>
            <p class="line-clamp-3" v-html="removeImageInHTML(post.content_html)"></p>
        </div>

        <div class="absolute h-full w-full rounded-2xl group-hover:bg-brand-950/50 transition-all group-hover:scale-[110%] z-0"></div>
    </RouterLink>
</template>

<script setup lang="ts">
import { Post } from '@/globalInterfaces'
import { getAnyPossibleImageFromHtml, removeImageInHTML } from '@/utils/utils'

defineProps<{
    post: Post
    idx: number
}>()
</script>
