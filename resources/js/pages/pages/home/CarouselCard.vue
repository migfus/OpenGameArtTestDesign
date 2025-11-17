<template>
    <Carousel id="gallery" v-bind="galleryConfig" v-model="currentSlide" class="mb-2">
        <Slide v-for="image in images" :key="image.id">
            <img :src="image.url" alt="Gallery Image" class="object-cover w-full" />
        </Slide>
    </Carousel>

    <Carousel id="thumbnails" v-bind="thumbnailsConfig" v-model="currentSlide">
        <Slide v-for="image in images" :key="image.id">
            <template #default="{ currentIndex, isActive }">
                <div :class="['thumbnail', { 'is-active': isActive }]" @click="slideTo(currentIndex)" class="rounded">
                    <img :src="image.url" alt="Thumbnail Image" class="cursor-pointer rounded-2xl opacity-50" />
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
import { ref } from 'vue'

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
