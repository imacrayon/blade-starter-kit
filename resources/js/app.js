import 'instant.page'
import './components/local-time'
import './components/modal'
import Alpine from 'alpinejs'
import ajax from '@imacrayon/alpine-ajax'
if (!('command' in HTMLButtonElement.prototype)) {
    import('./polyfills/invokers')
}
if (!('anchorName' in document.documentElement.style)) {
    import('./polyfills/anchor-positioning')
}

Alpine.plugin(ajax)
Alpine.start()
