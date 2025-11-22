<template>
    <div class="bg-dark-001 flex flex-col text-brand-200">
        <TopBanner v-model="top_navigation_hidden" />
        <TopNavigation v-model:top_navigation_hidden="top_navigation_hidden" v-model:show_side_navigation="show_side_navigation" />
        <SideNavigation v-model:top_navigation_hidden="top_navigation_hidden" v-model:show_side_navigation="show_side_navigation">
            <RouterView />
        </SideNavigation>

        <WarningModal />
    </div>
</template>

<script setup lang="ts">
import TopBanner from './components/header/TopBanner.vue'
import SideNavigation from './components/navigations/SideNavigation.vue'
import TopNavigation from './components/navigations/TopNavigation.vue'

import { ref } from 'vue'
import WarningModal from './components/modals/WarningModal.vue'

const top_navigation_hidden = ref(false)
const show_side_navigation = ref(false)
</script>

<style scoped></style>
