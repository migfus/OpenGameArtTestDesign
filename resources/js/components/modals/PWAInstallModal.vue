<template>
    <TransitionRoot as="template" :show="showModal">
        <Dialog as="div" class="relative z-50">
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

            <div class="fixed inset-0 z-50 w-screen overflow-y-auto">
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
                            class="relative transform overflow-hidden sm:rounded-2xl bg-dark-001 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:p-6 w-full mb-11 sm:max-w-sm border border-brand-900"
                        >
                            <div>
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-brand-950">
                                    <img src="https://oga.migfus20.site/images/icon.png"/>
                                </div>
                                <div class="mt-3 text-center sm:mt-5">
                                    <DialogTitle as="h3" class="text-base font-semibold leading-6 text-brand-200">
                                        Install OpenGameArt App
                                    </DialogTitle>

                                    <div class="mt-4 flex gap-4 text-brand-300 text-xs justify-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <Icon icon="akar-icons:android-fill" class="size-5 flex-none text-brand-400 mt-0.5" />
                                            <span>Android</span>
                                        </div>
                                        <div class="flex flex-col items-center gap-2">
                                            <Icon icon="ant-design:apple-filled" class="size-5 flex-none text-brand-400 mt-0.5" />
                                            <span>iOS</span>
                                        </div>
                                        <div class="flex flex-col items-center gap-2">
                                            <Icon icon="ant-design:windows-filled" class="size-5 flex-none text-brand-400 mt-0.5" />
                                            <span>Windows</span>
                                        </div>
                                        <div class="flex flex-col items-center gap-2">
                                            <Icon icon="carbon:linux-alt" class="size-5 flex-none text-brand-400 mt-0.5" />
                                            <span>Linux</span>
                                        </div>
                                    </div>

                                    <!-- iOS Safari has no install prompt API, show manual steps -->
                                    <div v-if="isIOS" class="mt-4 text-left bg-brand-950 rounded-xl p-3 text-xs text-brand-300 flex flex-col gap-1">
                                        <p class="font-semibold text-brand-200">To install on iOS:</p>
                                        <p>1. Tap the <Icon icon="ant-design:apple-filled" class="inline size-3.5" /> Share button</p>
                                        <p>2. Select "Add to Home Screen"</p>
                                        <p>3. Tap "Add" to confirm</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5 sm:mt-6 gap-2 flex flex-col">
                                <AppButton v-if="!isIOS" icon="memory:download" @click="handleInstall" :loading="installing">
                                    Install App
                                </AppButton>
                                <AppButton @click="closeModal" color="brand" icon="memory:close">Maybe Later</AppButton>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { Icon } from '@iconify/vue'
import AppButton from '../form/AppButton.vue'
import { PWAInstaller } from '@/utils/pwa'

const showModal = ref(false)
const installing = ref(false)
const isIOS = PWAInstaller.isIOS()

onMounted(() => {
    // Initialize PWA installer
    PWAInstaller.init()

    // iOS Safari never fires beforeinstallprompt, show manual instructions instead
    if (isIOS && PWAInstaller.isSafari() && !PWAInstaller.isRunningAsPWA()) {
        showModal.value = true
    }

    // Listen for installation prompt availability
    window.addEventListener('pwa-prompt-available', () => {
        showModal.value = true
    })

    // Hide modal when app is installed
    window.addEventListener('pwa-installed', () => {
        showModal.value = false
    })
})

const handleInstall = async () => {
    installing.value = true
    const outcome = await PWAInstaller.prompt()
    installing.value = false

    if (outcome === 'accepted') {
        showModal.value = false
    }
}

const closeModal = () => {
    showModal.value = false
}
</script>
