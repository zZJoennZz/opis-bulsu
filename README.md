## OPIS Prototype

Online procurement system for BulSU

## For developers - do first

Install the PHP dependencies through composer:

```
composer install
```

and then install JS dependencies through npm or yarn.

```
yarn install
```

Make a copy of the .env.example and rename it to .env. Update the database credentials and run

```
php artisan migrate --seed
```

## For developers - after rebasing/pull

Always run

```
php artisan migrate:fresh --seed
```

That way, our databases are synced.

## Finally

Run

```
php artisan serve
```
