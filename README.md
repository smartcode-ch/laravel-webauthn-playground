## WebAuthn Playground for Laravel

A simple [Laravel](https://laravel.com/) and [Inertia](https://inertiajs.com/) based project to play around with [WebAuthn](https://developer.mozilla.org/en-US/docs/Web/API/Web_Authentication_API).

The implementation is based on [lbuchs/WebAuthn](https://github.com/lbuchs/WebAuthn).

### Run it

1. Clone this repo
2. Copy `env.example` to `.env`
3. Run `composer install && php artisan key:generate && npm install && npm run build`
4. Serve the application using `HTTPS` under the domain name set in the `VITE_DOMAIN` env variable using [Laravel Valet](https://laravel.com/docs/10.x/valet#securing-sites).



