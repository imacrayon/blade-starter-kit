function applyAnchorNames(root = document) {
    root.querySelectorAll('[data-popover][id]').forEach((popover) => {
        const anchorName = `--anchor-${popover.id}`
        popover.style.positionAnchor = anchorName
        document.querySelectorAll(`[commandfor="${popover.id}"]`).forEach((btn) => {
            btn.style.anchorName = anchorName
        })
    })

    root.querySelectorAll('[commandfor]').forEach((btn) => {
        const popover = document.getElementById(btn.getAttribute('commandfor'))
        if (popover?.hasAttribute('data-popover')) {
            btn.style.anchorName = `--anchor-${popover.id}`
        }
    })
}

applyAnchorNames()

new MutationObserver((mutations) => {
    for (const { addedNodes } of mutations) {
        for (const node of addedNodes) {
            if (node.nodeType !== Node.ELEMENT_NODE) continue
            applyAnchorNames(node)
            if (node.matches('[data-popover][id]') || node.matches('[commandfor]')) {
                applyAnchorNames(node.parentElement ?? document)
            }
        }
    }
}).observe(document.body, { childList: true, subtree: true })
