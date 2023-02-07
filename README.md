# Helicksr

Helicksr is a website with a simple purpose: to ease sharing short musical ideas between guitar players with a searchable library planned and additional features to aid in its purpose on the way.

## Feaures
-   Simple interface with inline viewer and player for audio and MusicXML scores/tabs.
-   Easy sharing directly to other users or groups through the Teams feature.
-   Planned library of licks to never lose track of those moments of inspiration.

The overall aim is not to replace tab sites but to ease communication between musicians.

## Development

Helicksr is made on top of Laravel with a frontend based on [Inertia](https://inertiajs.com/how-it-works) (through Laravel Jetsream) following a monolith-based architecture. Simple MVC pattern for HTTP related stuff and some specialized classes for dedicated business logic.

### Local deployment

[Laravel Sail](https://laravel.com/docs/9.x/sail) is the recommended way to locally deploy for development. As such, Docker is required so make sure its installed and available. First install dependencies with `composer install`. Then use the following command from the project root to bring the local server up:

```
./vendor/bin/sail up
```

For frontend development with hot reloading, first install dependencies with `npm install`. then run:

```
npm run dev
```

Which will set hot reloading based on Vite.

### Testing

TBD