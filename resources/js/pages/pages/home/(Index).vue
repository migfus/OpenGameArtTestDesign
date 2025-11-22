<template>
    <div class="flex flex-col gap-4">
        <NotifyBanner />
        <CarouselCard />
        <ArtCaregorySection :arts="$navigationStore.weekly_arts" :loading="$navigationStore.config.loading" title="Weekly Popular" more="/popular" />
        <ArtCaregorySection :arts="$navigationStore.new_arts" :loading="$navigationStore.config.loading" title="New Arts" more="/latest" />
        <PostCategorySection :posts="$navigationStore.posts" :loading="$navigationStore.config.loading" />
    </div>
</template>

<script setup lang="ts">
import NotifyBanner from '@/components/header/NotifyBanner.vue'
import CarouselCard from './CarouselCard.vue'
import PostCategorySection from './PostCategorySection.vue'

import { useNavigationStore } from '@/stores/navigationStore'
import ArtCaregorySection from './ArtCaregorySection.vue'

const $navigationStore = useNavigationStore()

const post_types: string[] = ['2D Art', '3D Art', 'Concept Art', 'Texture', 'Music', 'Sound Effects', 'Document']
const user = {
    avatar_url:
        'https://images.unsplash.com/photo-1761839257046-84e95464cc52?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDF8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwxfHx8ZW58MHx8fHx8',
    username: 'username'
}
</script>
