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

            // Check Recent collection if needed an update
            checkRecentCollection()
            checkRecentForums()
            checkWeeklyArts()
        } catch (err) {
            alert('API Error')
        }
    }

    function checkRecentForums() {
        const recent_forums_needs_an_update = recent_forum.value.filter((item: RecentForum) => item.user == undefined)

        // Checks if there's a null users, skip if none
        if (recent_forums_needs_an_update.length > 0) {
            console.log('recent_forum needs an update', recent_forums_needs_an_update)
            updateRecentForums(recent_forums_needs_an_update[0].id)
        }
    }

    async function updateRecentForums(id: string) {
        const { data } = await api.put(`recent-forum/${id}`)

        const index = recent_forum.value.findIndex((item) => item.id === data.id)

        if (index !== -1) {
            // Replace the entire item
            recent_forum.value.splice(index, 1, data)

            checkRecentForums()
        }
    }

    function checkRecentCollection() {
        const recent_collections_needs_an_update = recent_collections.value.filter((item: RecentCollection) => item.user == undefined)

        // Checks if there's a null users, skip if none
        if (recent_collections_needs_an_update.length > 0) {
            console.log('recent_collections needs an update', recent_collections_needs_an_update)
            updateRecentCollection(recent_collections_needs_an_update[0].id)
        }
    }

    async function updateRecentCollection(id: string) {
        const { data } = await api.put(`recent-collection/${id}`)

        const index = recent_collections.value.findIndex((item) => item.id === data.id)

        if (index !== -1) {
            // Replace the entire item
            recent_collections.value.splice(index, 1, data)

            checkRecentCollection()
        }
    }

    function checkWeeklyArts() {
        const weekly_arts_needs_an_update = weekly_arts.value.filter((item: Art) => item.user == undefined)

        // Checks if there's a null users, skip if none
        if (weekly_arts_needs_an_update.length > 0) {
            console.log('weekly_arts needs an update', weekly_arts_needs_an_update)
            updateWeeklyArts(weekly_arts_needs_an_update[0].id)
        }
    }

    async function updateWeeklyArts(id: string) {
        const { data } = await api.put(`weekly-arts/${id}`)

        const index = weekly_arts.value.findIndex((item) => item.id === data.id)

        if (index !== -1) {
            // Replace the entire item
            weekly_arts.value.splice(index, 1, data)

            checkWeeklyArts()
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
