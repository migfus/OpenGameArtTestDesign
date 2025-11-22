<template>
    <div class="flex flex-col gap-2 px-6 pt-2 pb-8">
        <div class="flex justify-between gap-2">
            <p>Announcements</p>
            <RouterLink to="/posts">Check More</RouterLink>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-3 2xl:grid-cols-3 gap-2 line-clamp-2 overflow-visible">
            <ArtCardLoader v-if="loading" v-for="item in [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]" />
            <PostCard v-else v-for="(item, idx) in posts" :post="item" :idx />
        </div>
    </div>
</template>

<script setup lang="ts">
import ArtCardLoader from '@/components/cards/ArtCardLoader.vue'
import PostCard from '@/components/cards/PostCard.vue'
import { Post } from '@/globalInterfaces'

defineProps<{
    posts: Post[]
    loading: boolean
}>()
</script>
