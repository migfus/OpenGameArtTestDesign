<template>
    <div class="flex flex-col gap-2 mx-6 mt-2">
        <div class="flex justify-between gap-2">
            <p>{{ title }}</p>
            <p>Check More</p>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-2">
            <PostCard v-for="item in posts" :post="item" />
        </div>
    </div>
</template>

<script setup lang="ts">
import PostCard from '@/components/cards/PostCard.vue'
import { Post } from './homeInterfaces'

defineProps<{
    title: string
    posts: Post[]
}>()
</script>
