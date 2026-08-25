// PWA Installation and Update Utilities

// Type definition for BeforeInstallPromptEvent
interface BeforeInstallPromptEvent extends Event {
    prompt(): Promise<void>
    userChoice: Promise<{ outcome: 'accepted' | 'dismissed' }>
}

export const PWAInstaller = {
  deferredPrompt: null as BeforeInstallPromptEvent | null,

  // Initialize PWA installation prompt
  init() {
    window.addEventListener('beforeinstallprompt', (e: Event) => {
      const event = e as BeforeInstallPromptEvent
      event.preventDefault()
      this.deferredPrompt = event
      console.log('PWA installation prompt available')
      // You can emit an event here to show an install button
      window.dispatchEvent(
        new CustomEvent('pwa-prompt-available', {
          detail: { prompt: e }
        })
      )
    })

    window.addEventListener('appinstalled', () => {
      console.log('PWA installed')
      this.deferredPrompt = null
      window.dispatchEvent(
        new CustomEvent('pwa-installed', {
          detail: { installed: true }
        })
      )
    })
  },

  // Show installation prompt
  async prompt() {
    if (this.deferredPrompt) {
      this.deferredPrompt.prompt()
      const { outcome } = await this.deferredPrompt.userChoice
      console.log(`User response to PWA prompt: ${outcome}`)
      this.deferredPrompt = null
      return outcome
    }
    return null
  },

  // Check if PWA is available
  isAvailable() {
    return this.deferredPrompt !== null
  },

  // Check if app is running as PWA
  isRunningAsPWA() {
    return window.matchMedia('(display-mode: standalone)').matches ||
           (window.navigator as any).standalone === true ||
           document.referrer.includes('android-app://')
  },

  // iOS Safari never fires beforeinstallprompt - detect it to show manual instructions
  isIOS() {
    return /iphone|ipad|ipod/i.test(window.navigator.userAgent) && !(window as any).MSStream
  },

  isSafari() {
    const ua = window.navigator.userAgent
    return /^((?!chrome|android|crios|fxios).)*safari/i.test(ua)
  }
}

// Service Worker Update Checker
export const PWAUpdater = {
  async checkForUpdates() {
    if ('serviceWorker' in navigator) {
      try {
        const registration = await navigator.serviceWorker.getRegistration()
        if (registration) {
          await registration.update()
          console.log('Service Worker update checked')
        }
      } catch (error) {
        console.error('Error checking for updates:', error)
      }
    }
  },

  // Listen for service worker updates
  onUpdateAvailable(callback: (registration: ServiceWorkerRegistration) => void) {
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.addEventListener('controllerchange', () => {
        callback(navigator.serviceWorker.controller as any)
      })
    }
  },

  // Force update to latest service worker
  async updateServiceWorker() {
    if ('serviceWorker' in navigator) {
      const registrations = await navigator.serviceWorker.getRegistrations()
      for (const registration of registrations) {
        registration.unregister()
      }
      window.location.reload()
    }
  }
}
