## OPIS v1

Online procurement system for BulSU

## For developer - do first

Install the dependencies

```
composer install
```

and

```
yarn install
```

Then copy the .env.example and rename to .env. Update the database credentials and run

```
php artisan migrate --seed
```

## Finally

Run

```
php artisan serve
```
