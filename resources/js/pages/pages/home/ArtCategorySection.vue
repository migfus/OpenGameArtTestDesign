<template>
    <div class="flex flex-col gap-2 px-6 pt-2">
        <div class="flex justify-between gap-2">
            <p>{{ title }}</p>
            <RouterLink :to="more">Check More</RouterLink>
        </div>

        <DataTransition class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-2 line-clamp-2 overflow-visible">
            <ArtCardLoader
                v-if="loading"
                v-for="item in [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]"
                :style="{ animationDelay: `${(item * animation_delay) / 4}ms`, transitionDelay: `${(item * animation_delay) / 4}ms` }"
                @animationend.once="clearDelays"
                @transitionend.once="clearDelays"
            />
            <ArtCard
                v-else
                v-for="(item, idx) in arts"
                :art="item"
                :idx
                :style="{ animationDelay: `${(idx * animation_delay) / 4}ms`, transitionDelay: `${(idx * animation_delay) / 4}ms` }"
                @animationend.once="clearDelays"
                @transitionend.once="clearDelays"
            />
        </DataTransition>
    </div>
</template>

<script setup lang="ts">
import ArtCard from '@/components/cards/ArtCard.vue'
import ArtCardLoader from '@/components/cards/ArtCardLoader.vue'
import DataTransition from '@/components/transitions/DataTransition.vue'
import { Art } from '@/globalInterfaces'
import { animation_delay, clearDelays } from '@/utils/utils'

defineProps<{
    title: string
    arts: Art[]
    loading: boolean
    more: string
    large?: boolean
}>()
</script>
