# Movies API

Laravel 12 REST API that fetches movies, series and genres from TMDB and serves them with multi-language support (EN, PL, DE).

## Running with Docker

```bash
cp .env.example .env
# Set TMDB_API_TOKEN in .env

docker-compose up -d --build

docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
docker-compose exec app php artisan tmdb:import
```

App available at: http://localhost:8000

---

## Requirements (without Docker)

- PHP 8.4+
- MySQL 8.0+ / MariaDB
- Composer
- Node.js + npm
- TMDB API token ([get one here](https://www.themoviedb.org/settings/api))

## Setup

```bash
composer install
npm install

cp .env.example .env

# Configure .env: DB_DATABASE, DB_USERNAME, DB_PASSWORD, TMDB_API_TOKEN

php artisan key:generate
php artisan migrate
npm run build
```

## Importing data from TMDB

Dispatches jobs to import 50 movies, 10 series and all genres (with Docker: `docker-compose exec app <command>`):

```bash
php artisan tmdb:import
```

Then process the queue:

```bash
php artisan queue:work
```

Or set `QUEUE_CONNECTION=sync` in `.env` to run jobs synchronously.

## Running the application

```bash
php artisan serve
```

## Running tests

Tests use a separate MySQL database. Create it before running:

```bash
# Local
mysql -u root -p -e "CREATE DATABASE movies_api_test;"

# Docker
docker-compose exec mysql mysql -u root -proot -e "CREATE DATABASE movies_api_test;"
```

Then run tests (with Docker: `docker-compose exec app <command>`):

```bash
php artisan test
```

---

## API Endpoints

All endpoints support multi-language responses via the `Accept-Language` header.
Supported languages: `en` (default), `pl`, `de`.

### Movies

| Method | Endpoint           | Description             |
|--------|--------------------|-------------------------|
| GET    | `/api/movies`      | List movies (paginated) |
| GET    | `/api/movies/{id}` | Get single movie        |

### Series

| Method | Endpoint           | Description             |
|--------|--------------------|-------------------------|
| GET    | `/api/series`      | List series (paginated) |
| GET    | `/api/series/{id}` | Get single serie        |

### Genres

| Method | Endpoint           | Description             |
|--------|--------------------|-------------------------|
| GET    | `/api/genres`      | List genres (paginated) |
| GET    | `/api/genres/{id}` | Get single genre        |

### Query parameters

| Parameter | Type    | Description              |
|-----------|---------|--------------------------|
| `page`    | integer | Page number (default: 1) |

### Example response (`GET /api/movies`)

```json
{
    "data": [
        {
            "id": 1,
            "title": "Inception",
            "overview": "A thief who steals corporate secrets...",
            "original_title": "Inception",
            "poster_path": "/path.jpg",
            "backdrop_path": "/path.jpg",
            "release_date": "2010-07-16",
            "vote_average": "8.40",
            "vote_count": 35000,
            "popularity": "95.4320",
            "genres": [
                { "id": 1, "name": "Action" }
            ]
        }
    ],
    "links": { "...": "..." },
    "meta": {
        "current_page": 1,
        "per_page": 15,
        "total": 50
    }
}
```

### Multi-language support

```bash
curl http://localhost:8000/api/movies -H "Accept-Language: pl"
curl http://localhost:8000/api/movies -H "Accept-Language: de"
```

---

## Livewire component

Paginated movie list with language switcher available at:

```
http://localhost:8000/movies
```

---

## Tech stack

|                                 |                                  |
|---------------------------------|----------------------------------|
| **Laravel 12**                  | Framework                        |
| **Livewire 4**                  | Reactive frontend component      |
| **spatie/laravel-translatable** | Multi-language JSON translations |
| **MySQL / MariaDB**             | Database                         |
| **TMDB API**                    | Data source                      |
