import 'invokers-polyfill'

document.addEventListener('toggle', (e) => {
    if (!e.target.hasAttribute('data-popover')) return
    const isOpen = e.newState === 'open'
    document.querySelectorAll(`[commandfor="${e.target.id}"]`).forEach(btn => {
        btn.setAttribute('aria-expanded', isOpen)
    })
}, true)

document.addEventListener('command', (e) => {
    if (e.command === 'show-modal' && e.target.hasAttribute('data-modal')) {
        e.source?.setAttribute('aria-expanded', 'true')
    }
})

document.addEventListener('close', (e) => {
    if (e.target instanceof HTMLDialogElement && e.target.hasAttribute('data-modal')) {
        document.querySelectorAll(`[commandfor="${e.target.id}"][command="show-modal"]`).forEach(btn => {
            btn.setAttribute('aria-expanded', 'false')
        })
    }
}, true)
