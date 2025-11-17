<template>
    <div class="flex flex-col gap-2 cursor-pointer">
        <div
            class="w-full h-38 relative bg-cover rounded-2xl border-2 border-brand-950 flex flex-col justify-end"
            :style="`background-image: url(${post.image_url});`"
        >
            <div class="flex flex-col h-full justify-between">
                <div class="p-2 flex gap-1 justify-end">
                    <p class="bg-brand-950 px-2 rounded-xl text-sm">{{ post.type }}</p>
                </div>
                <div class="p-2 flex gap-1 bg-linear-to-t from-brand-950 to-transparent rounded-b-xl">
                    <img :src="post.user.avatar_url" class="size-5 rounded-full border border-brand-950" />
                    <p class="text-sm font-semibold truncate">{{ post.user.username }}</p>
                </div>
            </div>
        </div>
        <div class="flex flex-col">
            <p class="font-bold truncate">{{ post.title }}</p>
            <p class="line-clamp-3">{{ post.description }}</p>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Post } from '@/pages/pages/home/homeInterfaces'

defineProps<{
    post: Post
}>()
</script>

<style scoped></style>
