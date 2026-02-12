# Laravel Blade Starter Kit

This is an opinionated starter kit for building multi-tenant Laravel applications with Blade. It comes loaded with features out of the box:

- **Teams & invitations** — create teams, invite users by email, and manage roles
- **Admin panel** — manage users and teams with full-text search
- **User impersonation** — sign in as any user from the admin panel
- **Two-factor authentication** — TOTP-based 2FA powered by [Laravel Fortify](https://laravel.com/docs/fortify)

The UI is composed of beautiful Blade components powered by [Alpine.js](https://alpinejs.dev) and [Tailwind CSS](https://tailwindcss.com), with partial page updates via [Alpine AJAX](https://alpine-ajax.js.org) and route pre-fetching via [instant.page](https://instant.page).

![Preview of the Blade Starter Kit](https://raw.githubusercontent.com/imacrayon/media/main/blade-starter-kit.gif)

## Installation

### Via Laravel Herd

One-click install a new application using this starter kit through [Laravel Herd](https://herd.laravel.com):

<a href="https://herd.laravel.com/new?starter-kit=imacrayon/blade-starter-kit"><img src="https://img.shields.io/badge/Install%20with%20Herd-fff?logo=laravel&logoColor=f53003" alt="Install with Herd"></a>

### Via the Laravel Installer

Create a new Laravel application using this starter kit through the official [Laravel Installer](https://laravel.com/docs/12.x/installation#installing-php):

```bash
laravel new my-app --using=imacrayon/blade-starter-kit
```

## Want More Components?

Get even more high-quality components by purchasing the official [Alpine UI Components](https://alpinejs.dev/components).

## Icons

This starter kit uses the refined and versatile [Phosphor](https://phosphoricons.com/) icon collection. However, you can easily replace this collection with any icon set supported by [Blade UI Kit Icons](https://blade-ui-kit.com/blade-icons). To add a new icon set, `composer require` the icon package and replace any `phosphor-*` references with a reference to your preferred icon. You can even install multiple icon collections in the same project, go nuts.

## Sponsors

<a href="https://moonbaselabs.com">
  <img alt="Moonbase Labs" src="https://alpine-ajax.js.org/img/sponsors/moonbase-labs.svg" height="56" width="192">
</a>

## License

The Laravel Blade Starter Kit is open-sourced software licensed under the MIT license.
