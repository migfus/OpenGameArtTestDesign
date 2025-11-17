export interface User {
    avatar_url: string
    username: string
}

export interface Post {
    type: string
    title: string
    description: string
    user: User
    image_url: string
}
