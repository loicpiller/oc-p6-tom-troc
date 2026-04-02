# Tom Troc

Tom Troc est une plateforme d'echange de livres developpee en PHP avec une architecture MVC maison.

## Prerequis

Avant de lancer le projet, verifiez que vous avez :

- PHP 8
- Composer
- MySQL ou MariaDB
- les extensions PHP `pdo`, `fileinfo` et `gd`

## Installation

### 1. Cloner le projet

```bash
git clone https://github.com/loicpiller/oc-p6-tom-troc.git
cd oc-p6-tom-troc
```

### 2. Installer les dependances PHP

```bash
composer install
```

### 3. Creer le fichier de configuration local

Le projet attend un fichier [`config/config.php`](config/config.php) qui n'est pas versionne.

Il faut partir du modele [`config/config.php.sample`](config/config.php.sample), le dupliquer puis le renommer :

```bash
cp config/config.php.sample config/config.php
```

Ensuite, modifiez [`config/config.php`](config/config.php) avec vos propres informations :

- `DB_HOST`
- `DB_USER`
- `DB_PASS`
- `DB_NAME`
- `DB_CHARSET`
- `APP_ENV`
- `BASE_URL`

Exemple :

```php
<?php

return [
    'DB_HOST' => 'localhost',
    'DB_USER' => 'root',
    'DB_PASS' => 'root',
    'DB_CHARSET' => 'utf8mb4',
    'DB_NAME' => 'tom_troc',
    'APP_ENV' => 'development',
    'BASE_URL' => 'http://localhost:8000',
];
```

## Base de donnees

### Option recommandee pour une demo

Le projet contient une base de demonstration pre-remplie dans :

- [`database/demo.sql`](database/demo.sql)

Ce fichier permet d'importer rapidement :

- les tables
- les relations
- des utilisateurs de demonstration
- des livres
- des messages

### Creer la base puis importer le jeu de donnees

Exemple avec MySQL ou MariaDB :

```bash
mysql -u root -p -e "CREATE DATABASE tom_troc CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
mysql -u root -p tom_troc < database/demo.sql
```

Important :

- adaptez le nom de la base a votre configuration ;
- pensez a reporter ce meme nom dans [`config/config.php`](config/config.php) via `DB_NAME`.

## Lancer le projet

Depuis la racine du projet :

```bash
php -S localhost:8000 -t public
```

Ensuite, ouvrez :

- [http://localhost:8000](http://localhost:8000)

## SCSS

En developpement, les feuilles de style peuvent etre compilees automatiquement par la couche `View`.

Vous pouvez aussi lancer une compilation manuelle :

```bash
php scripts/compile-scss.php
```

## Donnees de demo

Le fichier [`database/demo.sql`](database/demo.sql) sert a presenter rapidement l'application avec du contenu realiste.

Selon son contenu actuel, il peut inclure :

- plusieurs utilisateurs
- une bibliotheque pre-remplie
- des conversations de messagerie

## Arborescence utile

- [`app/controllers`](app/controllers)
- [`app/entities`](app/entities)
- [`app/repositories`](app/repositories)
- [`app/views`](app/views)
- [`assets/scss`](assets/scss)
- [`config`](config)
- [`database`](database)

## Notes

- si la page ne se charge pas, verifiez en premier le contenu de [`config/config.php`](config/config.php) ;
- si les styles semblent manquer, relancez `php scripts/compile-scss.php` ;
- en local, `APP_ENV` peut rester sur `development`.
