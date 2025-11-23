<template>
    <TransitionRoot as="template" :show="open">
        <Dialog as="div" class="relative z-10">
            <TransitionChild
                as="template"
                enter="ease-out duration-300"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in duration-200"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-brand-950/25 transition-opacity backdrop-blur-md" />
            </TransitionChild>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center py-4 text-center sm:items-center sm:p-0">
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        <DialogPanel
                            class="relative transform overflow-hidden sm:rounded-2xl bg-dark-001 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:p-6 w-full mb-11 sm:max-w-sm"
                        >
                            <div>
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-brand-950">
                                    <Icon icon="memory:alpha-x" class="h-6 w-6 text-brand-300" aria-hidden="true" />
                                </div>
                                <div class="mt-3 text-center sm:mt-5">
                                    <DialogTitle as="h3" class="text-base font-semibold leading-6 text-brand-200"
                                        >This is not an official OpenGameArt</DialogTitle
                                    >
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">Just for a bored developer that is exploring web design.</p>
                                    </div>
                                    <div class="flex gap-4 justify-center text-brand-300">
                                        <a
                                            href="https://github.com/migfus/OpenGameArtTestDesign"
                                            target="_blank"
                                            class="mt-2 flex flex-col items-center gap-1 hover:bg-brand-950 transition-all p-2 rounded-xl"
                                        >
                                            <Icon icon="mdi:github" class="size-8 text-brand-200" />
                                            <p class="text-xs">Open Source</p>
                                        </a>
                                        <a
                                            href="https://trello.com/b/q3T00dXn/oga-ui-test"
                                            target="_blank"
                                            class="mt-2 flex flex-col items-center gap-1 hover:bg-brand-950 transition-all p-2 rounded-xl"
                                        >
                                            <Icon icon="mdi:trello" class="size-8 text-brand-200" />
                                            <p class="text-xs">Dev Status</p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5 sm:mt-6 gap-2 flex flex-col">
                                <AppButton icon="memory:alert-circle" @click="open = false">I understand and explore</AppButton>

                                <AppButton href="https://opengameart.org/" externalLinkOnly icon="memory:arrow-right-up" color="brand"
                                    >Go to Official OpenGameArt.org</AppButton
                                >
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup lang="ts">
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { Icon } from '@iconify/vue'
import AppButton from '../form/AppButton.vue'

import { useLocalStorage } from '@vueuse/core'

const open = useLocalStorage('warning_modal', true)
</script>
