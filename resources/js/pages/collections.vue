<template>
    <div class="flex flex-col gap-4 my-4 2xl:max-w-[1580px] 2xl:mx-auto">
        <CollectionFilterCard v-model="view_type" />

        <DataTransition class="flex gap-2 items-center">
            <button
                v-for="item in filters"
                :key="item"
                :class="['py-1 px-2 rounded-full first:ml-4 last:mr-4 cursor-pointer', selected_filter === item ? 'bg-brand-900' : 'bg-brand-950']"
                @click="selected_filter = item"
            >
                {{ item }}
            </button>
        </DataTransition>

        <DataTransition class="flex flex-col gap-2">
            <CollectionCard v-for="item in index_data" :key="item.id" :full_view="view_type === 'Full'" :collection="item" />
        </DataTransition>
    </div>
</template>

<script setup lang="ts">
import CollectionFilterCard from '@/components/cards/CollectionFilterCard.vue'
import CollectionCard from '@/components/cards/CollectionCard.vue'
import DataTransition from '@/components/transitions/DataTransition.vue'

import { onMounted, ref } from 'vue'
import { useLocalStorage } from '@vueuse/core'
import { useCollectionStore } from '@/stores/collection.store'
import { storeToRefs } from 'pinia'

const $collectionStore = useCollectionStore()
const { index_data } = storeToRefs($collectionStore)
const { indexAPI } = $collectionStore

const view_type = useLocalStorage('collections_view_type', 'Summary')

const filters = ['Title', 'Contributor', 'Favorites', 'Contents', 'Date']
const selected_filter = ref('Title')

onMounted(() => {
    document.title = 'Collections | OGA (Not Official)'

    indexAPI()
})
</script>
