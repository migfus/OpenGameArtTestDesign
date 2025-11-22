<template>
    <div class="flex flex-col gap-2">
        <div class="w-full h-38 relative bg-cover rounded-2xl border-2 border-brand-950 flex flex-col justify-end">
            <div class="flex flex-col h-full justify-between">
                <div class="p-2 flex gap-1 justify-end relative">
                    <p class="bg-brand-950 px-2 rounded-xl text-sm animate-pulse h-5 w-16"></p>
                </div>

                <DataTransition class="p-2 flex gap-1 rounded-b-xl flex-none">
                    <div class="size-5 rounded-full border border-brand-950 bg-brand-950" />
                    <p class="text-sm font-semibold truncate h-5 w-16 bg-brand-950 rounded-xl" />
                </DataTransition>
            </div>
        </div>
        <DataTransition class="flex flex-col gap-2">
            <p class="font-bold truncate h-6 w-32 bg-brand-950 rounded-xl" />
            <p class="h-6 bg-brand-950/75 rounded-xl" />
            <p class="h-6 bg-brand-950/75 rounded-xl" />
        </DataTransition>
    </div>
</template>

<script setup lang="ts">
import DataTransition from '@/components/transitions/DataTransition.vue'
</script>
