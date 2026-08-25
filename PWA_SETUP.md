# PWA Support Setup for OpenGameArt

This document outlines the Progressive Web App (PWA) implementation for the OpenGameArt Vue application.

## What's Been Added

### 1. **Vite Plugin Configuration** (`vite.config.js`)
- Added `vite-plugin-pwa` for automatic PWA setup
- Configured manifest with app metadata, icons, and theme colors
- Workbox configured for intelligent caching strategies

### 2. **Web App Manifest** (`public/manifest.webmanifest`)
- Defines PWA metadata and branding
- Includes app icons for multiple resolutions
- Shortcuts for quick access to main sections (Collections, Explore, Forums)
- Screenshots for app store displays

### 3. **Service Worker** (`public/sw.js`)
Features:
- **Cache Strategy**: Network-first for APIs, Cache-first for static assets
- **Offline Support**: Caches essential assets for offline availability
- **Background Sync**: Ready for offline action sync when online
- **Asset Caching**: Automatic caching of JS, CSS, images, fonts
- **Update Detection**: Listens for service worker updates

### 4. **PWA Utilities** (`resources/js/utils/pwa.ts`)
Provides helper classes for PWA features:
- `PWAInstaller`: Handle app installation prompts
- `PWAUpdater`: Check for and manage service worker updates

### 5. **HTML Configuration** (`resources/views/app.blade.php`)
- Added manifest link
- Added apple-touch-icon for iOS
- Added theme-color meta tag
- Added viewport-fit for notch support

### 6. **Service Registration** (`resources/js/app.ts`)
- Automatic service worker registration on page load
- Error handling and logging

## Features Enabled

✅ **Installable**: Users can install the app on desktop/mobile  
✅ **Offline Support**: Essential pages and assets available offline  
✅ **Caching**: Smart caching of static assets and API responses  
✅ **Push Notifications**: Ready for push notification support  
✅ **Background Sync**: Can sync data when coming back online  
✅ **Auto Updates**: Service worker updates automatically  

## How to Use

### Add Install Button to UI

```vue
<template>
  <button v-if="canInstall" @click="installApp">
    <Icon icon="memory:download" />
    Install App
  </button>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { PWAInstaller } from '@/utils/pwa'

const canInstall = ref(false)

PWAInstaller.init()

window.addEventListener('pwa-prompt-available', () => {
  canInstall.value = true
})

async function installApp() {
  const outcome = await PWAInstaller.prompt()
  if (outcome === 'accepted') {
    canInstall.value = false
  }
}
</script>
```

### Check for Updates

```typescript
import { PWAUpdater } from '@/utils/pwa'

// Check for updates manually
PWAUpdater.checkForUpdates()

// Listen for updates
PWAUpdater.onUpdateAvailable((registration) => {
  console.log('Service Worker updated')
  // Show update notification to user
})
```

### Detect if Running as PWA

```typescript
import { PWAInstaller } from '@/utils/pwa'

if (PWAInstaller.isRunningAsPWA()) {
  console.log('App is running as installed PWA')
}
```

## Caching Strategy Details

### Static Assets (JS, CSS, Images, Fonts)
- **Strategy**: Cache-first
- **Benefit**: Fast loading, works offline
- **Fallback**: Network if not cached

### API Requests (`/api/*`)
- **Strategy**: Network-first
- **Benefit**: Always gets latest data when online
- **Fallback**: Cached response if offline

### HTML Pages
- **Strategy**: Network-first
- **Benefit**: Always gets latest content
- **Fallback**: Cached page if offline

## Testing PWA Locally

1. **Build for production**:
   ```bash
   npm run build
   ```

2. **Test locally** (use HTTPS locally or localhost):
   - Open DevTools (F12)
   - Go to Application → Service Workers
   - Look for registered service worker
   - Check Cache Storage for cached assets

3. **Install on desktop**:
   - Look for install icon in browser address bar
   - Or right-click → "Install app"

4. **Mobile testing**:
   - Open on Android Chrome
   - Tap menu → "Install app"
   - App will be installable from home screen

## Environment Configuration

The PWA uses your app icon from `/images/icon.png`. Make sure you have:
- **192x192px** icon for mobile
- **512x512px** icon for splash screens
- **PNG format** recommended for best compatibility

For best results, provide icons in multiple formats:
- `icon-192x192.png` (for most devices)
- `icon-512x512.png` (for splash screens)
- `icon.svg` (for modern browsers)

## Build and Deployment

The PWA plugin automatically generates:
- Service worker bundle
- Precache manifest
- Workbox configuration

These are included in your production build automatically.

### Important for Deployment:
1. **HTTPS Required**: PWA only works on HTTPS (except localhost)
2. **Manifest Link**: Already added to `app.blade.php`
3. **Service Worker**: Generated automatically, served from root

## Performance Impact

- ✅ Faster page loads (cached assets)
- ✅ Works offline (network-first strategy)
- ✅ Reduced bandwidth (caching)
- ✅ Better UX on poor connections
- ⚠️ First load may take slightly longer to setup cache

## Troubleshooting

### Service Worker Not Registering
1. Check browser console for errors
2. Ensure app is on HTTPS or localhost
3. Check `/public/sw.js` exists

### Cache Not Working
1. Check Application → Cache Storage in DevTools
2. Try clearing cache and reloading
3. Ensure assets match the glob patterns in `workbox` config

### Installation Button Not Showing
1. Ensure manifest is accessible at `/manifest.webmanifest`
2. Check that manifest is valid JSON
3. App must meet installation criteria (PWA installability requirements)

## Next Steps

1. **Add Update Notification**: Notify users when updates are available
2. **Push Notifications**: Implement push notification support
3. **Background Sync**: Sync unsaved data when back online
4. **Periodic Sync**: Periodically check for new content
5. **Share API**: Allow users to share content from the app

## References

- [vite-plugin-pwa](https://vite-pwa-org.netlify.app/)
- [Web App Manifest](https://developer.mozilla.org/en-US/docs/Web/Manifest)
- [Service Workers](https://developer.mozilla.org/en-US/docs/Web/API/Service_Worker_API)
- [PWA Checklist](https://web.dev/pwa-checklist/)
