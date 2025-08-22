# auth-cookies
A fully integrated authentication system between the Laravel 12 backend and the Vue.js frontend. Authentication is handled via Sanctum tokens stored in cookies, using two distinct tokens:  access_token – valid for 10 minutes  refresh_token – valid for 24 hours  A custom middleware automatically refreshes token expiration times.
