<template>
    <CarouselCardLoader v-if="$navigationStore.config.loading" is_main />
    <Carousel v-else id="gallery" v-bind="galleryConfig" v-model="currentSlide" class="mb-2">
        <Slide v-for="item in $navigationStore.posts" :key="item.link">
            <p class="absolute font-bold text-2xl bottom-0 bg-linear-to-t from-brand-950 to-transparent px-2 py-4 w-full text-center anek-latin">
                {{ item.title }}
            </p>
            <img :src="getAnyPossibleImageFromHtml(item.content_html)[0] ?? item.author_image" alt="Gallery Image" class="object-cover w-full" />
        </Slide>
    </Carousel>

    <CarouselCardLoader v-if="$navigationStore.config.loading" />
    <Carousel v-else id="thumbnails" v-bind="thumbnailsConfig" v-model="currentSlide">
        <Slide v-for="item in $navigationStore.posts" :key="item.link">
            <template #default="{ currentIndex, isActive }">
                <div :class="[]" @click="slideTo(currentIndex)" class="rounded">
                    <img
                        :src="getAnyPossibleImageFromHtml(item.content_html)[0] ?? item.author_image"
                        alt="Thumbnail Image"
                        :class="[isActive ? 'opacity-75' : 'opacity-50', 'cursor-pointer rounded-2xl']"
                    />
                </div>
            </template>
        </Slide>

        <template #addons>
            <Navigation />
        </template>
    </Carousel>
</template>

<script setup lang="ts">
import 'vue3-carousel/carousel.css'
import { Carousel, Slide, Navigation } from 'vue3-carousel'
import CarouselCardLoader from './CarouselCardLoader.vue'

import { ref } from 'vue'
import { useNavigationStore } from '@/stores/navigationStore'
import { getAnyPossibleImageFromHtml } from '@/utils/utils'

const $navigationStore = useNavigationStore()

const currentSlide = ref(0)

const slideTo = (nextSlide: number) => (currentSlide.value = nextSlide)

const galleryConfig = {
    itemsToShow: 1,
    wrapAround: true,
    slideEffect: 'fade' as const,
    mouseDrag: false,
    touchDrag: false,
    height: 320,
    autoplay: 4000
}

const thumbnailsConfig = {
    height: 80,
    itemsToShow: 6,
    wrapAround: true,
    touchDrag: false,
    gap: 10
}

const images = [
    {
        id: 1,
        url: 'https://asset-store-test.nbg1.your-objectstorage.com/assets/285/godot-asset-store-thumbnail.png'
    },
    {
        id: 2,
        url: 'https://asset-store-test.nbg1.your-objectstorage.com/publishers/204/Tsothan_Grove_375px.png'
    },
    {
        id: 3,
        url: 'https://screamingbrainstudios.com/wp-content/uploads/2025/11/Watermark-384x384-2.png'
    },
    {
        id: 4,
        url: 'https://opengameart.org/sites/default/files/archive/ogabacklinkinggodotassetstorev2.png'
    },
    {
        id: 5,
        url: 'https://img.itch.zone/aW1hZ2UyL2phbS80MDcxODUvMjE1NTYxMjAucG5n/original/eXrSca.png'
    },
    {
        id: 6,
        url: 'https://img.itch.zone/aW1hZ2UyL2phbS8zOTE3OTQvMTgxMzU3MzIucG5n/original/f5JPT1.png'
    },
    {
        id: 7,
        url: 'https://opengameart.org/sites/default/files/styles/medium/public/ogamarch24jamthumbnaillarge_0.png'
    }
]
</script>
