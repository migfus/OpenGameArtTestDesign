<template>
    <div class="flex flex-col gap-4 2xl:max-w-[1580px] 2xl:mx-auto">
        <SearchCard />
        <ArtCategorySection title="Arts" more="/" />
    </div>
</template>

<script setup lang="ts">
import ArtCategorySection from '@/components/others/ArtCategorySectionExplore.vue'
import SearchCard from '@/components/others/SearchCard.vue'

import { onMounted } from 'vue'

onMounted(() => {
    document.title = 'Explore | OGA (Not Official)'
})
</script>
