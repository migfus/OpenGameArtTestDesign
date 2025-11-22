export interface User {
    avatar_url: string
    username: string
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
    title: string
    link: string
}

export interface RecentForum {
    title: string
    link: string
    time_ago: string
    username: string
}

export interface StoreConfig {
    loading: boolean
}

export interface Affiliate {
    title: string
    link: string
}

export interface Art {
    title: string
    link: string
    preview_image: string
    audio_ogg?: string
    audio_mp3?: string
}
