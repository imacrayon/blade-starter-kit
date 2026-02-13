if (!('command' in HTMLButtonElement.prototype)) {
    import('invokers-polyfill')

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
}

// Support `open` attribute for modals
document.querySelectorAll('dialog[data-modal][open]').forEach(dialog => {
    dialog.close()
    dialog.showModal()
    document.querySelectorAll(`[commandfor="${dialog.id}"][command="show-modal"]`).forEach(btn => {
        btn.setAttribute('aria-expanded', 'true')
    })
})
