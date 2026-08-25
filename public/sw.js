/* OpenGameArt PWA Service Worker */

const CACHE_NAME = 'oga-v1'
const RUNTIME_CACHE = 'oga-runtime'
const ASSETS_TO_CACHE = [
    '/',
    '/index.php',
    '/images/icon.png',
    '/offline.html'
]

// Install event - cache essential assets
self.addEventListener('install', (event) => {
  console.log('Service Worker installing...')
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      console.log('Caching essential assets')
      return cache.addAll(ASSETS_TO_CACHE).catch(() => {
        console.log('Some assets could not be cached')
      })
    })
  )
  self.skipWaiting()
})

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
  console.log('Service Worker activating...')
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheName !== CACHE_NAME && cacheName !== RUNTIME_CACHE) {
            console.log('Deleting old cache:', cacheName)
            return caches.delete(cacheName)
          }
        })
      )
    })
  )
  self.clients.claim()
})

// Fetch event - Network first, fallback to cache
self.addEventListener('fetch', (event) => {
  const { request } = event
  const url = new URL(request.url)

  // Skip non-GET requests
  if (request.method !== 'GET') {
    return
  }

  // Skip cross-origin requests
  if (url.origin !== location.origin) {
    return
  }

  // Handle API calls - Network first
  if (url.pathname.startsWith('/api/')) {
    return event.respondWith(
      fetch(request)
        .then((response) => {
          if (response.ok) {
            // Clone response immediately for caching
            const clonedResponse = response.clone()
            caches.open(RUNTIME_CACHE).then((cache) => {
              cache.put(request, clonedResponse)
            })
          }
          return response
        })
        .catch(() => {
          return caches.match(request).then((cachedResponse) => {
            return cachedResponse || new Response('Offline', { status: 503 })
          })
        })
    )
  }

  // Handle static assets - Cache first
  if (/\.(js|css|png|jpg|jpeg|svg|webp|woff|woff2|ttf|eot)$/.test(url.pathname)) {
    return event.respondWith(
      caches.match(request).then((cachedResponse) => {
        return (
          cachedResponse ||
          fetch(request).then((response) => {
            if (response.ok) {
              // Clone response immediately for caching
              const clonedResponse = response.clone()
              caches.open(CACHE_NAME).then((cache) => {
                cache.put(request, clonedResponse)
              })
            }
            return response
          })
        )
      })
    )
  }

  // Handle HTML pages - Network first
  if (request.headers.get('accept')?.includes('text/html')) {
    return event.respondWith(
      fetch(request)
        .then((response) => {
          if (response.ok) {
            // Clone response immediately for caching
            const clonedResponse = response.clone()
            caches.open(CACHE_NAME).then((cache) => {
              cache.put(request, clonedResponse)
            })
          }
          return response
        })
        .catch(() => {
          return caches.match(request).then((cachedResponse) => {
            return cachedResponse || new Response('Offline', { status: 503 })
          })
        })
    )
  }
})

// Background sync for offline actions
self.addEventListener('sync', (event) => {
  if (event.tag === 'sync-data') {
    event.waitUntil(syncData())
  }
})

async function syncData() {
    console.log('Syncing data...')
    // Implement your sync logic here
}

// Handle messages from clients
self.addEventListener('message', (event) => {
    if (event.data?.type === 'SKIP_WAITING') {
        self.skipWaiting()
    }
})
