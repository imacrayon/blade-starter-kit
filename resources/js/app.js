import 'instant.page'
import './components/local-time'
import './components/modal'
import passkeys from './components/passkeys'
import Alpine from 'alpinejs'
import ajax from '@imacrayon/alpine-ajax'
import './components/anchor-names'

Alpine.plugin(ajax)
Alpine.plugin(passkeys)
Alpine.start()
