<template>
    <div class="flex flex-col gap-2 px-6 pt-2 pb-8">
        <div class="flex justify-between gap-2">
            <p>Announcements</p>
            <RouterLink to="/posts">Check More</RouterLink>
        </div>

        <div class="flex flex-col lg:grid lg:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-4 line-clamp-2 overflow-visible">
            <ArtCardLoader
                v-if="loading"
                v-for="item in [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]"
                :style="{ animationDelay: `${(item * animation_delay) / 2}ms`, transitionDelay: `${(item * animation_delay) / 2}ms` }"
                @animationend.once="clearDelays"
                @transitionend.once="clearDelays"
            />
            <PostCard
                v-else
                v-for="(item, idx) in filtered_posts"
                :post="item"
                :idx
                :style="{ animationDelay: `${(idx * animation_delay) / 2}ms`, transitionDelay: `${(idx * animation_delay) / 2}ms` }"
                @animationend.once="clearDelays"
                @transitionend.once="clearDelays"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import ArtCardLoader from '@/components/cards/ArtCardLoader.vue'
import PostCard from '@/components/cards/PostCard.vue'
import { Post } from '@/globalInterfaces'
import { animation_delay, clearDelays } from '@/utils/utils'
import { computed, reactive } from 'vue'
import { breakpointsTailwind, useBreakpoints } from '@vueuse/core'

const { posts } = defineProps<{
    posts: Post[]
    loading: boolean
}>()

const breakpoints = useBreakpoints(breakpointsTailwind)
const breaks = reactive({
    sm: breakpoints.greaterOrEqual('sm'),
    md: breakpoints.greaterOrEqual('md'),
    lg: breakpoints.greaterOrEqual('lg'),
    xl: breakpoints.greaterOrEqual('xl'),
    xxl: breakpoints.greaterOrEqual('2xl')
})

const filtered_posts = computed(() => {
    if (breaks.xxl) {
        return posts.slice(0, 4)
    } else if (breaks.xl) {
        return posts.slice(0, 3)
    } else if (breaks.md) {
        return posts.slice(0, 4)
    } else {
        return posts
    }
})
</script>
