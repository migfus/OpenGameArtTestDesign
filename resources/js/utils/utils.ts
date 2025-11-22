export function clearDelays(e: Event) {
    const el = e.currentTarget as HTMLElement | null
    if (!el) return
    el.style.animationDelay = '0ms'
    el.style.transitionDelay = '0ms'
    // mark it so any future logic can detect delay was removed
    el.dataset.delayRemoved = 'true'
}
