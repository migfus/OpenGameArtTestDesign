<template>
    <div class="flex flex-col gap-4 mb-8">
        <NotifyBanner />
        <CarouselCard />
        <ArtCategorySection :arts="$navigationStore.weekly_arts" :loading="$navigationStore.config.loading" title="Weekly Popular" more="/popular" />
        <ArtCategorySection :arts="$navigationStore.new_arts" :loading="$navigationStore.config.loading" title="New Arts" more="/latest" />

        <PostCategorySection :posts="$navigationStore.posts" :loading="$navigationStore.config.loading" />
    </div>
</template>

<script setup lang="ts">
import NotifyBanner from '@/components/header/NotifyBanner.vue'
import CarouselCard from './CarouselCard.vue'
import PostCategorySection from './PostCategorySection.vue'

import { useNavigationStore } from '@/stores/navigationStore'
import ArtCategorySection from './ArtCategorySection.vue'

const $navigationStore = useNavigationStore()
</script>
