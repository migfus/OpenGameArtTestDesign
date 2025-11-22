import { Collection, StoreConfig } from '@/globalInterfaces'
import api from '@/utils/axios'
import { defineStore } from 'pinia'
import { ref, reactive } from 'vue'

export const useNavigationStore = defineStore('navigationStore', () => {
    // STATE
    const recent_collections = ref<Collection[]>()
    const config = reactive<StoreConfig>({
        loading: false
    })

    async function getData() {
        try {
            config.loading = true
            const apiData = await api.get<{ recent_collections: Collection[] }>('/')
            recent_collections.value = apiData.data.recent_collections
            config.loading = false

            console.log('recent_collections: ', recent_collections.value)
        } catch (err) {
            alert('API Error')
        }
    }

    return {
        recent_collections,
        config,

        getData
    }
})
