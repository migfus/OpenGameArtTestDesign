<template>
    <BasicTransition>
        <!-- NOTE: REDIRECT MODE -->
        <a
            v-if="externalLinkOnly"
            :href="disabled ? '#' : href"
            target="_blank"
            :type
            :disabled="loading || disabled || forceLoading"
            :class="[
                buttonColor,
                textAlignment,
                buttonSize,
                'border border-brand-900 inline-flex rounded-2xl font-medium hover:shadow-md focus:outline-hidden focus:ring-2 focus:ring-offset-2 transition-all cursor-pointer object-shadow'
            ]"
            @click="clicked = true"
        >
            <Icon
                v-if="(loading && !noLoading) || forceLoading"
                icon="material-symbols:cached-rounded"
                :class="[$props.size == 'sm' && 'h-[15px] w-[15px] mt-[1px]', '-ml-1 mr-2 h-5 w-5 animate-spin', iconColor]"
                aria-hidden="true"
            />
            <Icon :icon v-else-if="icon" :class="[$props.size == 'sm' && 'h-[15px] w-[15px] mt-[1px]', '-ml-1 mr-2 h-5 w-5', iconColor]" aria-hidden="true" />
            <slot></slot>
        </a>
        <!-- NOTE: LINK MODE -->
        <RouterLink
            v-else-if="href"
            :to="disabled ? '#' : href"
            :aria-disabled="loading || disabled || forceLoading"
            :class="[
                buttonColor,
                textAlignment,
                buttonSize,
                'border border-brand-900 inline-flex rounded-2xl font-medium hover:shadow-md focus:outline-hidden focus:ring-2 focus:ring-offset-2 transition-all cursor-pointer object-shadow'
            ]"
            @click="clicked = true"
        >
            <Icon
                v-if="(loading && !noLoading) || forceLoading"
                icon="material-symbols:cached-rounded"
                :class="[$props.size == 'sm' && 'h-[15px] w-[15px] mt-[1px]', '-ml-1 mr-2 h-5 w-5 animate-spin', iconColor]"
                aria-hidden="true"
            />
            <Icon :icon v-else-if="icon" :class="[$props.size == 'sm' && 'h-[15px] w-[15px] mt-[1px]', '-ml-1 mr-2 h-5 w-5', iconColor]" aria-hidden="true" />
            <slot></slot>
        </RouterLink>

        <!-- NOTE: BUTTON MODE [default]-->
        <button
            v-else
            :type
            :disabled="loading || disabled || forceLoading"
            :class="[
                buttonColor,
                textAlignment,
                buttonSize,
                color == 'transparent' ? 'p-0 m-0 ' : '',
                'border border-brand-900 rounded-2xl font-medium hover:shadow-md focus:outline-hidden focus:ring-2 focus:ring-offset-2 transition-all flex items-center gap-2 cursor-pointer object-shadow'
            ]"
            @click="clicked = true"
        >
            <Icon
                v-if="(loading && !noLoading) || forceLoading"
                icon="material-symbols:cached-rounded"
                :class="[color == 'transparent' ? 'size-5' : iconOnly ? 'size-4' : 'size-4', 'animate-spin', iconColor]"
            />
            <Icon v-else-if="icon" :icon :class="[color == 'transparent' ? 'size-5' : iconOnly ? 'size-4' : 'size-4', iconColor]" />
            <slot v-if="!iconOnly"></slot>
        </button>
    </BasicTransition>
</template>

<script setup lang="ts">
import { Icon } from '@iconify/vue'
import BasicTransition from '@/components/transitions/BasicTransition.vue'

import { ref, computed } from 'vue'

const $props = defineProps<{
    icon?: string
    color?: 'brand' | 'danger' | 'transparent'
    type?: 'button' | 'submit' | 'reset'
    alignment?: 'l' | 'c' | 'r'
    size?: 'sm' | 'md' | 'transparent'
    href?: string
    externalLinkOnly?: boolean
    noLoading?: boolean
    disabled?: boolean
    forceLoading?: boolean
    iconOnly?: boolean
}>()
const loading = ref(false)
const clicked = ref(false)

const buttonColor = computed(() => {
    if ($props.disabled) {
        return 'bg-gray-200 text-gray-400 cursor-not-allowed'
    }
    switch ($props.color) {
        case 'brand':
            return 'bg-brand-900 hover:bg-brand-700 text-brand-50 focus:ring-brand-500 '
        case 'danger':
            return 'bg-red-50 text-red-700 hover:bg-red-100 focus:ring-red-500 '
        case 'transparent':
            return 'bg-inherit shadow-none hover:shadow-none focus:ring-none'
        default:
            return 'bg-dark-001 hover:bg-brand-900 text-brand-200 focus:ring-brand-500 '
    }
})

const iconColor = computed(() => {
    if ($props.disabled) {
        return 'text-gray-400'
    }
    switch ($props.color) {
        case 'danger':
            return 'text-red-700'
        case 'brand':
            return 'text-brand-50'
        default:
            return 'text-brand-200'
    }
})

const textAlignment = computed(() => {
    switch ($props.alignment) {
        case 'l':
            return 'justify-left'
        default:
            return 'justify-center'
    }
})
// NOTE: [sm/default]
const buttonSize = computed(() => {
    switch ($props.size) {
        case 'transparent':
            return 'text-sm p-0'
        case 'sm':
            return 'text-xs font px-3 py-2'
        default:
            return 'text-sm px-4 py-2'
    }
})
</script>
