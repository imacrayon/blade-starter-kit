class LocalTime extends HTMLElement {
    connectedCallback() {
        const date = new Date(this.getAttribute('datetime'))
        if (isNaN(date)) return

        const options = {}
        for (const name of ['weekday', 'day', 'month', 'year', 'hour', 'minute']) {
            const value = this.getAttribute(name)
            if (value) options[name] = value
        }
        const tz = this.getAttribute('timezone')
        if (tz) options.timeZoneName = tz

        const lang = this.closest('[lang]')?.lang || document.documentElement.lang
        this.textContent = new Intl.DateTimeFormat(lang || undefined, options).format(date)
    }
}

customElements.define('local-time', LocalTime)
