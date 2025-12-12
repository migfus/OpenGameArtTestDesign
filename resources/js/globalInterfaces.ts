export interface User {
    id: string
    username: string
    image_url: string
    created_at: string
}

export interface Post {
    title: string
    link: string
    author_name: string
    author_linkg: string
    date: string
    content_html: string
    comment_link: string
    author_image: string
}

// SECTION: FROM API
export interface RecentCollection {
    id: string
    title: string
    user?: User
    created_at: string
}

export interface RecentForum {
    id: string
    title: string
    content?: string
    created_at?: string
    user?: User
}

export interface StoreConfig {
    loading: boolean
}

export interface Affiliate {
    id: string // url
    title: string
    image_url?: string
    created_at?: string
}

export interface Art {
    id: string
    title: string
    content?: string
    preview_image: string
    audio_ogg?: string
    audio_mp3?: string
    user?: User
}

export interface Collection {
    id: string
    title: string
    string: string

    user?: User
    arts?: Art[]
}
