export interface User extends LaravelTimestamp {
    id: number
    url_username: string
    username: string
    image_url: string
}

export interface Post extends LaravelTimestamp {
    id: string
    title: string
    link: string
    author_name: string
    author_linkg: string
    date: string
    content_html: string
    comment_link: string
    author_image: string
}

export interface Forum extends LaravelTimestamp {
    id: string
    title: string
    content?: string
    user?: User
}

export interface StoreConfig {
    loading: boolean
    lazy_page: number
    lazy_loading: boolean
}

export interface Affiliate extends LaravelTimestamp {
    id: string // url
    title: string
    image_url?: string
}

export interface Art extends LaravelTimestamp {
    id: string
    title: string
    favorites_count: number
    content?: string
    art_previews: ArtPreview[]
    image_preview?: string
    audio_ogg?: string
    audio_mp3?: string
    comments_count: number

    user?: User // null on anonymous
    art_category: ArtCategory // database & temporary [Art, Music]
    files: File[]
    art_comments: ArtComment[]
    tags: Tag[]
    licenses: License[]
}

export interface License extends LaravelTimestamp {
    id: number
    name: string
    url: string
}

export interface Collection extends LaravelTimestamp {
    id: string
    title: string
    string: string
    favorites_count: number
    art_collected: number

    user?: User
    arts?: Art[]
}

export interface ArtCategory {
    id: number
    name: string
}

export interface ArtPreview {
    id: number
    url: string

    art_preview_category: ArtPreviewCategory
}

export interface ArtPreviewCategory {
    name: 'image' | 'audio'
}

export interface File {
    id: number
    file_url: string
    name: string
    download_count: number
}

export interface ArtComment extends LaravelTimestamp {
    id: number
    content: string

    user?: User // null on anonymous
    art: Art
}

export interface Auth extends User {}

export interface Tag {
    id: number
    name: string
}

export interface LaravelTimestamp {
    created_at: string
    updated_at: string
}

export interface SearchFilters {
    search: string
}

export interface ArtType {
    id: number
    name: string
    icon: string
}
