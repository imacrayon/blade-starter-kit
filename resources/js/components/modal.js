// Support `open` attribute for modals
document.querySelectorAll('dialog[data-modal][open]').forEach(dialog => {
    dialog.close()
    dialog.showModal()
    document.querySelectorAll(`[commandfor="${dialog.id}"][command="show-modal"]`).forEach(btn => {
        btn.setAttribute('aria-expanded', 'true')
    })
})
