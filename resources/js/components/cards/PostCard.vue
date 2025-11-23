<template>
    <RouterLink
        :to="post.link"
        class="flex flex-col gap-2 cursor-pointer group relative bg-linear-30 from-[#0c1314] to-[#463364] rounded-2xl p-4 transition-all"
    >
        <div
            class="w-full h-54 aspect-square relative bg-cover rounded-2xl border-2 border-brand-950 flex flex-col justify-end z-10"
            :style="`background-image: url(${getAnyPossibleImageFromHtml(post.content_html)[0] ?? post.author_image});`"
        ></div>
        <div class="flex justify-between z-10 text-brand-300">
            <div class="flex gap-2 items-center">
                <img :src="post.author_image" class="size-4 rounded-full ring ring-dark-001" />
                <p class="font-bold truncate">{{ post.author_name }}</p>
            </div>
            <div class="flex gap-2 items-center flex-none">
                <p class="truncate text-sm">{{ dateFormat(post.date) }}</p>
            </div>
        </div>
        <div class="flex flex-col z-10">
            <p class="font-bold truncate">{{ post.title }}</p>
            <p class="line-clamp-4 text-brand-200/75" v-html="removeImageInHTML(post.content_html)"></p>
        </div>

        <div class="absolute h-full w-full rounded-2xl group-hover:bg-brand-950/50 transition-all group-hover:scale-[110%] z-0 -ml-4"></div>
    </RouterLink>
</template>

<script setup lang="ts">
import { Post } from '@/globalInterfaces'
import { getAnyPossibleImageFromHtml, removeImageInHTML, dateFormat } from '@/utils/utils'

defineProps<{
    post: Post
    idx: number
}>()
</script>
