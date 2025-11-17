<template>
    <div class="bg-dark-001 flex flex-col text-brand-200">
        <TopBanner v-model="top_hidden" />
        <TopNavigation v-model="top_hidden" />
        <SideNavigation v-model="top_hidden"> <RouterView /></SideNavigation>
    </div>
</template>

<script setup lang="ts">
import TopBanner from './components/header/TopBanner.vue'
import TopNavigation from './components/navigations/TopNavigation.vue'
import SideNavigation from './components/navigations/SideNavigation.vue'

import { ref } from 'vue'

const top_hidden = ref(false)
</script>

<style scoped></style>
