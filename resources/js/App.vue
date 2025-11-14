<template>
    <div class="bg-dark-001 flex flex-col text-brand-200">
        <TopBanner />
        <TopNavigation />
        <SideNavigation>Content Here</SideNavigation>
        <RouterView />
    </div>
</template>

<script setup lang="ts">
import TopBanner from './components/header/TopBanner.vue'
import TopNavigation from './components/navigations/TopNavigation.vue'
import SideNavigation from './components/navigations/SideNavigation.vue'
</script>

<style scoped></style>
