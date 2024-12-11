> [!TIP]
> Follow the install steps below, use the new branch for collaboration

## Framework/Library Installed

-   [Laravel 11](https://laravel.com/docs/11.x)
-   [Tailwindcss](https://tailwindcss.com/)
-   [Filament Laravel](https://filamentphp.com/)


## Run Locally

Clone the project

```bash
git clone https://github.com/private210/arsipdigi.git
```

Go to the project directory

```bash
cd arsipdigi
```

Copy .ENV

```bash
cp .env.example .env
```

Install dependencies

```bash
composer install
```

```bash
npm install
```

```bash
php artisan key:generate
```

Link Storage
```bash
php artisan storage:link
```
Migrate database & seed
```bash
php artisan migrate --seed
```
Run Jobs Queue
```bash
php artisan queue:work 
```

Run Local

```bash
php artisan serve
```

USER INFORMATION
```bash
email: admin@gmail.com
pass: 123
```
