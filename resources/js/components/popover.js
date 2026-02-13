// Only load the polyfill in browsers that don't support CSS anchor positioning natively
if (!('anchorName' in document.documentElement.style)) {
    import('@oddbird/css-anchor-positioning')
}

// Set up CSS anchor positioning for popover triggers
document.querySelectorAll('[data-popover]').forEach(menu => {
    const trigger = document.querySelector(`[commandfor="${menu.id}"]`)
    if (trigger) {
        trigger.style.anchorName = `--${CSS.escape(menu.id)}`
        menu.style.positionAnchor = `--${CSS.escape(menu.id)}`
    }
})

// Native Invoker Commands manage aria-expanded automatically;
// only track it manually when the polyfill is active
if (!('command' in HTMLButtonElement.prototype)) {
    document.addEventListener('toggle', (e) => {
        if (!e.target.hasAttribute('data-popover')) return
        const isOpen = e.newState === 'open'
        document.querySelectorAll(`[commandfor="${e.target.id}"]`).forEach(btn => {
            btn.setAttribute('aria-expanded', isOpen)
        })
    }, true)
}
