import 'instant.page'
import './components/modal'
import Alpine from 'alpinejs'
import ajax from '@imacrayon/alpine-ajax'
import './components/popover'
import '@github/relative-time-element'

Alpine.plugin(ajax)
Alpine.start()
