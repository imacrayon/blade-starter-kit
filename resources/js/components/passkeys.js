// Loaded on demand so pages without passkey UI don't download the WebAuthn client.
let load = () => import('@laravel/passkeys').then(module => module.Passkeys)

export default (Alpine) => {
    Alpine.data('passkeys', (routes = {}) => ({
        busy: false,
        error: '',
        // Same check as `Passkeys.isSupported()`, without loading the client.
        supported: typeof window.PublicKeyCredential === 'function',

        // Offer passkeys in the browser's autofill dropdown when an input opts in with `autocomplete="... webauthn"`.
        async init() {
            if (! this.supported || ! this.$root.querySelector('[autocomplete~="webauthn"]')) return

            try {
                let response = await (await load()).autofill({ routes, remember: () => this.remember() })
                if (response) window.location.assign(response.redirect)
            } catch (error) {
                this.error = error.message
            }
        },

        verify() {
            return this.attempt(async (Passkeys) => {
                let { redirect } = await Passkeys.verify({ routes, remember: () => this.remember() })
                window.location.assign(redirect)
            })
        },

        register(name) {
            return this.attempt(async (Passkeys) => {
                await Passkeys.register({ name })
                window.location.reload()
            })
        },

        // Suggest a recognizable name such as "Chrome on Mac".
        deviceName() {
            let agent = navigator.userAgent
            let match = (patterns) => patterns.find(([pattern]) => pattern.test(agent))?.[1]

            return [
                match([[/Edg|Edge/, 'Edge'], [/OPR|Opera|OPiOS/, 'Opera'], [/Firefox|FxiOS/, 'Firefox'], [/Chrome|CriOS/, 'Chrome'], [/Safari/, 'Safari']]),
                match([[/iPhone/, 'iPhone'], [/iPad|Macintosh(?=.*Mobile)/, 'iPad'], [/Android/, 'Android'], [/Mac/, 'Mac'], [/Windows/, 'Windows']]),
            ].filter(Boolean).join(' on ')
        },

        remember() {
            return this.$root.querySelector('[name="remember"]')?.checked ?? false
        },

        // Busy stays set on success because the page navigates away.
        async attempt(ceremony) {
            this.busy = true
            this.error = ''

            try {
                await ceremony(await load())
            } catch (error) {
                if (error.name !== 'UserCancelledError') this.error = error.message
                this.busy = false
            }
        },
    }))
}
