import { Affiliate, Art, RecentCollection, RecentForum, StoreConfig, Post } from '@/globalInterfaces'
import api from '@/utils/axios'
import { defineStore } from 'pinia'
import { ref, reactive } from 'vue'

export const useNavigationStore = defineStore('navigationStore', () => {
    // STATE
    const recent_collections = ref<RecentCollection[]>([])
    const recent_forum = ref<RecentForum[]>([])
    const affiliates = ref<Affiliate[]>([])
    const latest_banner_title = ref<string>('')
    const weekly_arts = ref<Art[]>([])
    const new_arts = ref<Art[]>([])
    const posts = ref<Post[]>([])

    const config = reactive<StoreConfig>({
        loading: false
    })

    async function getData() {
        try {
            config.loading = true
            const { data } = await api.get<{
                recent_collections: RecentCollection[]
                recent_forum: RecentForum[]
                affiliates: Affiliate[]
                latest_banner_title: string
                weekly_arts: Art[]
                new_arts: Art[]
                posts: Post[]
            }>('/')

            recent_collections.value = data.recent_collections
            recent_forum.value = data.recent_forum
            affiliates.value = data.affiliates
            latest_banner_title.value = data.latest_banner_title
            weekly_arts.value = data.weekly_arts
            new_arts.value = data.new_arts
            posts.value = data.posts

            config.loading = false

            console.log('recent_forum: ', recent_forum.value)
        } catch (err) {
            alert('API Error')
        }
    }

    return {
        recent_collections,
        recent_forum,
        affiliates,
        latest_banner_title,
        weekly_arts,
        new_arts,
        posts,

        config,

        getData
    }
})
