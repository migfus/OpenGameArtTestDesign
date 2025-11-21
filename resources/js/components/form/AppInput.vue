<template>
    <BasicTransition>
        <div>
            <label v-if="!$props.noLabel" class="block text-sm font-medium leading-6 text-brand-50">{{ $props.name }}</label>
            <input
                v-model="$model"
                :id="name"
                :name
                :type="type ?? 'text'"
                :placeholder="placeholder ?? ''"
                :class="[
                    inputSize,
                    injectCSS,
                    color == 'white' ? 'bg-white' : 'bg-dark-001',
                    error && 'border-red-500',
                    'w-full rounded-xl border border-brand-900 placeholder-brand-900 focus:border-brand-500 focus:outline-none focus:ring-brand-500 h-8 py-4 px-3 text-brand-200 font-semibold'
                ]"
                autocomplete="off"
            />
            <label v-if="$props.error" for="password" class="block text-sm font-medium text-red-600">
                {{ $props.error }}
            </label>
        </div>
    </BasicTransition>
</template>

<script setup lang="ts">
import BasicTransition from '@/components/transitions/BasicTransition.vue'

import { computed } from 'vue'

type TProps = {
    error?: string | undefined
    name: string
    type?: 'text' | 'email' | 'password' | 'date'
    placeholder?: string
    size?: 'sm' | 'xs'
    noLabel?: true | false
    injectCSS?: string
    color?: 'white'
}

const $props = defineProps<TProps>()
const $model = defineModel()

const inputSize = computed(() => {
    switch ($props.size) {
        case 'sm':
            return 'text-sm h-[34px]'
        case 'xs':
            return 'text-xs h-[30px]'
        default:
            return ''
    }
})
</script>
