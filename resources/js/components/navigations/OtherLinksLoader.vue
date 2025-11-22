<template>
    <DataTransition class="flex flex-col gap-2">
        <div
            v-for="(idx, item) in dummy"
            class="bg-brand-950 mx-2 rounded-xl h-7 animate-pulse"
            :style="{ animationDelay: `${idx * 100}ms`, transitionDelay: `${idx * 100}ms` }"
        />
    </DataTransition>
</template>

<script setup lang="ts">
import DataTransition from '../transitions/DataTransition.vue'

const dummy = [0, 1, 2, 3, 4]
</script>

<style scoped></style>
