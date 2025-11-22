<template>
    <div>
        <div v-if="is_main" class="h-72 bg-brand-950 transition-all animate-pulse" />
        <DataTransition v-else class="flex justify-center">
            <div v-for="(item, idx) in [1]" :class="['bg-brand-950 transition-all animate-pulse rounded-2xl h-32']" />
        </DataTransition>
    </div>
</template>

<script setup lang="ts">
import DataTransition from '@/components/transitions/DataTransition.vue'

defineProps<{
    is_main?: boolean
}>()
</script>
