<template>
    <div
        :class="[
            'bg-linear-to-r from-[#31575c] to-[#60458c] px-6 py-2 font-semibold flex justify-between gap-2 text-brand-50 sticky transition-all duration-500',
            top_hidden_model ? '-translate-y-40' : 'translate-y-0'
        ]"
    >
        <!-- SECTION LEFT  -->
        <div class="flex gap-2 items-center">
            <a href="https://discord.com/invite/yDaQ4NcCux" target="_blank" class="flex gap-1 items-center hover:bg-brand-950 px-2 rounded transition-all">
                <div class="size-6">
                    <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path
                                d="M23.6361 9.33998C22.212 8.71399 20.6892 8.25903 19.0973 8C18.9018 8.33209 18.6734 8.77875 18.5159 9.13408C16.8236 8.89498 15.1469 8.89498 13.4857 9.13408C13.3283 8.77875 13.0946 8.33209 12.8974 8C11.3037 8.25903 9.77927 8.71565 8.35518 9.3433C5.48276 13.4213 4.70409 17.3981 5.09342 21.3184C6.99856 22.6551 8.84487 23.467 10.66 23.9983C11.1082 23.4189 11.5079 22.8029 11.8523 22.1536C11.1964 21.9195 10.5683 21.6306 9.9748 21.2951C10.1323 21.1856 10.2863 21.071 10.4351 20.9531C14.0551 22.5438 17.9881 22.5438 21.5649 20.9531C21.7154 21.071 21.8694 21.1856 22.0251 21.2951C21.4299 21.6322 20.8 21.9211 20.1442 22.1553C20.4885 22.8029 20.8865 23.4205 21.3364 24C23.1533 23.4687 25.0013 22.6567 26.9065 21.3184C27.3633 16.7738 26.1261 12.8335 23.6361 9.33998ZM12.3454 18.9075C11.2587 18.9075 10.3676 17.9543 10.3676 16.7937C10.3676 15.6331 11.2397 14.6783 12.3454 14.6783C13.4511 14.6783 14.3422 15.6314 14.3232 16.7937C14.325 17.9543 13.4511 18.9075 12.3454 18.9075ZM19.6545 18.9075C18.5678 18.9075 17.6767 17.9543 17.6767 16.7937C17.6767 15.6331 18.5488 14.6783 19.6545 14.6783C20.7602 14.6783 21.6514 15.6314 21.6323 16.7937C21.6323 17.9543 20.7602 18.9075 19.6545 18.9075Z"
                                fill="#f3f9fb"
                            />
                        </g>
                    </svg>
                </div>
                <p class="text-sm">Discord</p>
            </a>

            <a href="https://www.patreon.com/opengameart" target="_blank" class="flex gap-1 items-center hover:bg-brand-950 transition-all px-2 rounded">
                <div class="size-4">
                    <svg
                        viewBox="0 0 600 600"
                        xmlns="http://www.w3.org/2000/svg"
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        stroke-linejoin="round"
                        stroke-miterlimit="2"
                    >
                        <g transform="matrix(.47407 0 0 .47407 .383 .422)">
                            <clipPath id="prefix__a"><path d="M0 0h1080v1080H0z" /></clipPath>
                            <g clip-path="url(#prefix__a)">
                                <path
                                    d="M1033.05 324.45c-.19-137.9-107.59-250.92-233.6-291.7-156.48-50.64-362.86-43.3-512.28 27.2-181.1 85.46-237.99 272.66-240.11 459.36-1.74 153.5 13.58 557.79 241.62 560.67 169.44 2.15 194.67-216.18 273.07-321.33 55.78-74.81 127.6-95.94 216.01-117.82 151.95-37.61 255.51-157.53 255.29-316.38z"
                                    fill="#f3f9fb"
                                />
                            </g>
                        </g>
                    </svg>
                </div>
                <p class="text-sm">Donate</p>
            </a>
        </div>

        <!-- SECTION: RIGHT -->
        <div class="flex items-center">
            <a
                href="https://www.patreon.com/opengameart"
                target="_blank"
                class="gap-1 items-center hidden md:flex hover:bg-brand-950 transition-all px-2 rounded"
            >
                <img
                    src="https://plus.unsplash.com/premium_photo-1763034565065-9bd329f7e007?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHx0b3BpYy1mZWVkfDl8dG93SlpGc2twR2d8fGVufDB8fHx8fA%3D%3D"
                    class="size-4 rounded-full border border-light-001"
                />
                <p class="text-sm">@Dummy1</p>
            </a>
            <a
                href="https://www.patreon.com/opengameart"
                target="_blank"
                class="gap-1 items-center hidden md:flex hover:bg-brand-950 transition-all px-2 rounded"
            >
                <img
                    src="https://plus.unsplash.com/premium_photo-1763034565065-9bd329f7e007?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHx0b3BpYy1mZWVkfDl8dG93SlpGc2twR2d8fGVufDB8fHx8fA%3D%3D"
                    class="size-4 rounded-full border border-light-001"
                />
                <p class="text-sm">@DummyDemo2</p>
            </a>
            <a
                href="https://www.patreon.com/opengameart"
                target="_blank"
                class="gap-1 items-center hidden md:flex hover:bg-brand-950 transition-all px-2 rounded"
            >
                <img
                    src="https://plus.unsplash.com/premium_photo-1763034565065-9bd329f7e007?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHx0b3BpYy1mZWVkfDl8dG93SlpGc2twR2d8fGVufDB8fHx8fA%3D%3D"
                    class="size-4 rounded-full border border-light-001"
                />
                <p class="text-sm">@DummyTest3</p>
            </a>

            <a href="https://www.patreon.com/opengameart" target="_blank" class="flex gap-1 items-center hover:bg-brand-950 transition-all px-2 rounded">
                <div class="flex -space-x-2">
                    <img
                        src="https://plus.unsplash.com/premium_photo-1763034565065-9bd329f7e007?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHx0b3BpYy1mZWVkfDl8dG93SlpGc2twR2d8fGVufDB8fHx8fA%3D%3D"
                        class="size-4 rounded-full border border-light-001"
                    />
                    <img
                        src="https://plus.unsplash.com/premium_photo-1763034565065-9bd329f7e007?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHx0b3BpYy1mZWVkfDl8dG93SlpGc2twR2d8fGVufDB8fHx8fA%3D%3D"
                        class="size-4 rounded-full border border-light-001"
                    />
                    <img
                        src="https://plus.unsplash.com/premium_photo-1763034565065-9bd329f7e007?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHx0b3BpYy1mZWVkfDl8dG93SlpGc2twR2d8fGVufDB8fHx8fA%3D%3D"
                        class="size-4 rounded-full border border-light-001"
                    />
                </div>
                <p class="text-sm md:hidden">Recent Donators</p>
                <p class="text-sm hidden md:flex">more...</p>
            </a>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Icon } from '@iconify/vue'

const top_hidden_model = defineModel<boolean>()
</script>
