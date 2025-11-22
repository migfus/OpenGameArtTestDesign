<template>
    <div class="flex flex-col gap-4">
        <SearchCard />
        <ArtCategorySection :arts="$navigationStore.new_arts" :loading="$navigationStore.config.loading" title="Arts" more="/" />
    </div>
</template>

<script setup lang="ts">
import ArtCategorySection from '../home/ArtCategorySection.vue'
import SearchCard from './SearchCard.vue'

import { useNavigationStore } from '@/stores/navigationStore'

const $navigationStore = useNavigationStore()
</script>
