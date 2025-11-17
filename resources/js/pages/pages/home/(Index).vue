<template>
    <div class="flex flex-col gap-4">
        <CarouselCard />
        <PostCaregorySection :posts="post_popular.posts" :title="post_popular.name" />
        <PostCaregorySection :posts="post_new_art.posts" :title="post_new_art.name" />
    </div>
</template>

<script setup lang="ts">
import CarouselCard from './CarouselCard.vue'
import { Post } from './homeInterfaces'
import PostCaregorySection from './PostCaregorySection.vue'

const post_types: string[] = ['2D Art', '3D Art', 'Concept Art', 'Texture', 'Music', 'Sound Effects', 'Document']
const user = {
    avatar_url:
        'https://images.unsplash.com/photo-1761839257046-84e95464cc52?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDF8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwxfHx8ZW58MHx8fHx8',
    username: 'username'
}

const post_popular: {
    name: string
    posts: Post[]
} = {
    name: 'Popular',
    posts: [
        {
            type: post_types[0],
            title: 'Boss Type',
            description: 'Hello Description',
            user: user,
            image_url:
                'https://plus.unsplash.com/premium_photo-1750235095427-03dfa05bf952?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyfHx8ZW58MHx8fHx8'
        },
        {
            type: post_types[0],
            title: 'Boss Type',
            description: 'Hello Description',
            user: user,
            image_url:
                'https://plus.unsplash.com/premium_photo-1750235095427-03dfa05bf952?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyfHx8ZW58MHx8fHx8'
        },
        {
            type: post_types[0],
            title: 'Boss Type',
            description: 'Hello Description',
            user: user,
            image_url:
                'https://plus.unsplash.com/premium_photo-1750235095427-03dfa05bf952?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyfHx8ZW58MHx8fHx8'
        },
        {
            type: post_types[0],
            title: 'Boss Type',
            description: 'Hello Description',
            user: user,
            image_url:
                'https://plus.unsplash.com/premium_photo-1750235095427-03dfa05bf952?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyfHx8ZW58MHx8fHx8'
        },
        {
            type: post_types[0],
            title: 'Boss Type',
            description: 'Hello Description',
            user: user,
            image_url:
                'https://plus.unsplash.com/premium_photo-1750235095427-03dfa05bf952?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyfHx8ZW58MHx8fHx8'
        },
        {
            type: post_types[0],
            title: 'Boss Type',
            description: 'Hello Description',
            user: user,
            image_url:
                'https://plus.unsplash.com/premium_photo-1750235095427-03dfa05bf952?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyfHx8ZW58MHx8fHx8'
        },
        {
            type: post_types[0],
            title: 'Boss Type',
            description: 'Hello Description',
            user: user,
            image_url:
                'https://plus.unsplash.com/premium_photo-1750235095427-03dfa05bf952?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyfHx8ZW58MHx8fHx8'
        },
        {
            type: post_types[0],
            title: 'Boss Type',
            description: 'Hello Description',
            user: user,
            image_url:
                'https://plus.unsplash.com/premium_photo-1750235095427-03dfa05bf952?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyfHx8ZW58MHx8fHx8'
        },
        {
            type: post_types[0],
            title: 'Boss Type',
            description: 'Hello Description',
            user: user,
            image_url:
                'https://plus.unsplash.com/premium_photo-1750235095427-03dfa05bf952?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyfHx8ZW58MHx8fHx8'
        },
        {
            type: post_types[0],
            title: 'Boss Type',
            description: 'Hello Description',
            user: user,
            image_url:
                'https://plus.unsplash.com/premium_photo-1750235095427-03dfa05bf952?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyfHx8ZW58MHx8fHx8'
        },
        {
            type: post_types[0],
            title: 'Boss Type',
            description: 'Hello Description',
            user: user,
            image_url:
                'https://plus.unsplash.com/premium_photo-1750235095427-03dfa05bf952?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyfHx8ZW58MHx8fHx8'
        },
        {
            type: post_types[0],
            title: 'Boss Type',
            description: 'Hello Description',
            user: user,
            image_url:
                'https://plus.unsplash.com/premium_photo-1750235095427-03dfa05bf952?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyfHx8ZW58MHx8fHx8'
        }
    ]
}

const post_new_art: {
    name: string
    posts: Post[]
} = {
    name: 'New Arts',
    posts: [
        {
            type: post_types[0],
            title: 'Boss Type',
            description: 'Hello Description',
            user: user,
            image_url:
                'https://plus.unsplash.com/premium_photo-1750235095427-03dfa05bf952?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyfHx8ZW58MHx8fHx8'
        },
        {
            type: post_types[0],
            title: 'Boss Type',
            description: 'Hello Description',
            user: user,
            image_url:
                'https://plus.unsplash.com/premium_photo-1750235095427-03dfa05bf952?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyfHx8ZW58MHx8fHx8'
        },
        {
            type: post_types[0],
            title: 'Boss Type',
            description: 'Hello Description',
            user: user,
            image_url:
                'https://plus.unsplash.com/premium_photo-1750235095427-03dfa05bf952?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyfHx8ZW58MHx8fHx8'
        },
        {
            type: post_types[0],
            title: 'Boss Type',
            description: 'Hello Description',
            user: user,
            image_url:
                'https://plus.unsplash.com/premium_photo-1750235095427-03dfa05bf952?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyfHx8ZW58MHx8fHx8'
        },
        {
            type: post_types[0],
            title: 'Boss Type',
            description: 'Hello Description',
            user: user,
            image_url:
                'https://plus.unsplash.com/premium_photo-1750235095427-03dfa05bf952?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyfHx8ZW58MHx8fHx8'
        },
        {
            type: post_types[0],
            title: 'Boss Type',
            description: 'Hello Description',
            user: user,
            image_url:
                'https://plus.unsplash.com/premium_photo-1750235095427-03dfa05bf952?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyfHx8ZW58MHx8fHx8'
        },
        {
            type: post_types[0],
            title: 'Boss Type',
            description: 'Hello Description',
            user: user,
            image_url:
                'https://plus.unsplash.com/premium_photo-1750235095427-03dfa05bf952?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyfHx8ZW58MHx8fHx8'
        },
        {
            type: post_types[0],
            title: 'Boss Type',
            description: 'Hello Description',
            user: user,
            image_url:
                'https://plus.unsplash.com/premium_photo-1750235095427-03dfa05bf952?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyfHx8ZW58MHx8fHx8'
        },
        {
            type: post_types[0],
            title: 'Boss Type',
            description: 'Hello Description',
            user: user,
            image_url:
                'https://plus.unsplash.com/premium_photo-1750235095427-03dfa05bf952?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyfHx8ZW58MHx8fHx8'
        },
        {
            type: post_types[0],
            title: 'Boss Type',
            description: 'Hello Description',
            user: user,
            image_url:
                'https://plus.unsplash.com/premium_photo-1750235095427-03dfa05bf952?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyfHx8ZW58MHx8fHx8'
        },
        {
            type: post_types[0],
            title: 'Boss Type',
            description: 'Hello Description',
            user: user,
            image_url:
                'https://plus.unsplash.com/premium_photo-1750235095427-03dfa05bf952?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyfHx8ZW58MHx8fHx8'
        },
        {
            type: post_types[0],
            title: 'Boss Type',
            description: 'Hello Description',
            user: user,
            image_url:
                'https://plus.unsplash.com/premium_photo-1750235095427-03dfa05bf952?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyfHx8ZW58MHx8fHx8'
        }
    ]
}
</script>
