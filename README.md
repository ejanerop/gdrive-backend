# Google Drive Permission Manager API

App that uses the Google Drive API to bulk remove of users permissions on files.

## Installation

Clone repository:

```
git clone https://github.com/ejanerop/gdrive-backend.git
cd gdrive-backend
```

Install dependencies:

```
composer install
```

Create environment file:

```
cp .env.example .env
```

Configure your database connection in the .env file:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gdrive
DB_USERNAME=root
DB_PASSWORD=
```

Set the frontend URL in the .env file:

```
FRONT_END_URL=http://route-to-front-app.com
```

Set up the Google application credentials in the .env file:

```
GOOGLE_APP_ID=
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URL=
```

Run migrations:

```
php artisan migrate
```

Run server:

```
php artisan serve
```
