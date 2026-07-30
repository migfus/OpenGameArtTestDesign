import { Art, StoreConfig, SearchFilters, ArtType } from '@/global.interfaces'
import api from '@/utils/axios'
import { cleanQuery } from '@/utils/utils'
import { useLocalStorage } from '@vueuse/core'
import axios from 'axios'
import moment from 'moment'
import { defineStore } from 'pinia'
import { reactive, ref, watch } from 'vue'
import { useRouter } from 'vue-router'

const ttl = 24 // 24hrs
const default_art_type = {
    id: 0,
    name: 'All',
    icon: 'pixelarticons:checkbox-on-sharp'
}

export const useArtStore = defineStore('ArtStore', () => {
    const $router = useRouter()
    const cancelSource = ref(axios.CancelToken.source())
    const refreshCancelSource = ref(axios.CancelToken.source())
    const arts = useLocalStorage<Art[]>('arts', [])
    const new_arts = useLocalStorage<Art[]>('new_arts', [])
    const weekly_arts = useLocalStorage<Art[]>('weekly_arts', [])
    const art_types = useLocalStorage('art_types', [default_art_type])
    const search_query = reactive<{
        search: string
        selected_art_type: ArtType
        field_art_tags_tid: string
    }>({
        search: '',
        selected_art_type: art_types.value[0],
        field_art_tags_tid: ''
    })
    const total_result = ref<number>(0)
    const search_url = ref('')

    const show_data = ref<Art | null>(null)

    const config = reactive<StoreConfig>({
        loading: false,
        lazy_page: 1,
        lazy_loading: false
    })

    const audio_volume = useLocalStorage<number>('audio_volume', 0.5)
    const audio_playing = ref(true)

    async function checkExploreArtsForRefresh() {
        // SECTION: EXPLORE
        const arts_that_needs_refresh = arts.value.filter((item: Art) => {
            return item.updated_at == undefined ? true : Math.abs(moment(item.updated_at).diff(moment(), 'hours', true)) >= ttl ? true : false
        })

        await Promise.all(
            arts_that_needs_refresh.map(async (item: Art) => {
                const data = await refreshArt(item.id)
                if (data) {
                    const index = arts.value.findIndex((art: Art) => art.id === data.id)
                    if (index !== -1) {
                        arts.value[index] = data
                    }
                }
            })
        )
    }

    async function checkWeeklyArtsForRefresh() {
        // SECTION: WEEKLY
        const weekly_arts_that_needs_refresh = weekly_arts.value.filter((item: Art) => {
            console.log(item.id)
            console.log(Math.abs(moment(item.updated_at).diff(moment(), 'hours', true)))
            return item.updated_at == undefined ? true : Math.abs(moment(item.updated_at).diff(moment(), 'hours', true)) >= ttl ? true : false
        })

        await Promise.all(
            weekly_arts_that_needs_refresh.map(async (item: Art) => {
                const data = await refreshArt(item.id)
                if (data) {
                    const index = weekly_arts.value.findIndex((art: Art) => art.id === data.id)
                    if (index !== -1) {
                        weekly_arts.value[index] = data
                    }
                }
            })
        )
    }

    async function checkNewArtsForRefresh() {
        // SECTION: NEW
        const new_arts_that_needs_refresh = new_arts.value.filter((item: Art) =>
            item.updated_at == undefined ? true : Math.abs(moment(item.updated_at).diff(moment(), 'hours', true)) >= ttl ? true : false
        )

        await Promise.all(
            new_arts_that_needs_refresh.map(async (item: Art) => {
                const data = await refreshArt(item.id)
                if (data) {
                    const index = new_arts.value.findIndex((art: Art) => art.id === data.id)
                    if (index !== -1) {
                        new_arts.value[index] = data
                    }
                }
            })
        )
    }

    async function refreshArt(id: string): Promise<Art | undefined> {
        try {
            const { data } = await api.put<Art>(`/arts/${id}`, { method: 'refresh' }, { cancelToken: refreshCancelSource.value.token })
            return data
        } catch (err) {
            if (axios.isCancel(err)) {
                return
            }
            console.log('error on ArtStore/refreshArt()')
        }
    }

    async function getArts(page = 1) {
        if (page === 1) {
            refreshCancelSource.value.cancel('Cancelled by getArts(1)')
            refreshCancelSource.value = axios.CancelToken.source()
        }

        config.loading = true
        try {
            $router.replace({
                query: cleanQuery({
                    search: search_query.search,
                    field_art_type_tid: search_query.selected_art_type?.id,
                    field_art_tags_tid: search_query.field_art_tags_tid
                })
            })
            const { data } = await api.get(`/arts`, {
                params: {
                    search: search_query.search,
                    field_art_type_tid: search_query.selected_art_type?.id,
                    field_art_tags_tid: search_query.field_art_tags_tid,
                    page
                }
            })
            // arts.value = []
            arts.value = data.data
            search_url.value = data.url

            console.log('getArts Data', data.data)
            total_result.value = data.total_result
            art_types.value = [default_art_type, ...data.art_types]
            config.lazy_page = 1
        } catch (err) {
            console.log('erro on ArtStore/getArts()', err)
        }
        config.loading = false
    }

    async function lazyGetArts() {
        config.lazy_loading = true
        try {
            const { data } = await api.get(`/arts`, {
                params: {
                    search: search_query.search,
                    page: config.lazy_page,
                    field_art_type_tid: search_query.selected_art_type.id,
                    field_art_tags_tid: search_query.field_art_tags_tid,
                    lazy: true
                }
            })

            arts.value.push(...data.data)

            checkExploreArtsForRefresh()
        } catch (err) {
            console.log('erro on ArtStore/lazyGetArts()', err)
        }
        config.lazy_loading = false
    }

    async function showAPI(id: string) {
        config.loading = true
        try {
            const { data } = await api.get(`/arts/${id}`)
            show_data.value = data
        } catch (err) {
            console.log('error on ArtStore/showAPI()', err)
        }
        config.loading = false
    }

    function cancelAllRequests(msg = 'Requests Cancelled') {
        cancelSource.value.cancel(msg)
        cancelSource.value = axios.CancelToken.source()
    }

    function mountables() {
        watch(
            () => search_query.selected_art_type,
            async () => {
                await getArts(1)
                checkExploreArtsForRefresh()
            }
        )
        watch(
            () => search_query.field_art_tags_tid,
            async () => {
                await getArts(1)
                checkExploreArtsForRefresh()
            }
        )
    }

    return {
        cancelSource,
        arts,
        new_arts,
        weekly_arts,
        config,
        search_query,
        total_result,
        art_types,
        search_url,
        show_data,

        audio_volume,
        audio_playing,

        checkWeeklyArtsForRefresh,
        checkNewArtsForRefresh,
        checkExploreArtsForRefresh,
        getArts,
        cancelAllRequests,
        lazyGetArts,
        mountables,
        showAPI
    }
})
