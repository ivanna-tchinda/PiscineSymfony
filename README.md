# Formation Piscine PHP Symfony

## Description

# DAY 00 - 

# DAY 01 - Starting

## ex00
## ex01
## ex02
## ex03
## ex04
## ex05
## ex06

# Day 03 - Composer

## ex00
Installer Composer:

```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"

```

```bash
sudo cp composer.phar /usr/local/bin/composer
```

## ex01

Installer les différentes versions de monolog:

- Créer un fichier monolog1/composer.json:
```bash
{
  "require": {
    "monolog/monolog": "^2.3.1"
  }
}
```
et taper dans le terminal
```bash
composer install
```
- Créer un fichier monolog2/composer.json:
```bash
{
  "require": {
    "monolog/monolog": ">2.2.0,<2.3.6"
  }
}
```
et taper dans le terminal
```bash
composer install
```
- Créer un fichier monolog3/composer.json:
```bash
{
  "require": {
    "monolog/monolog": ">=2.1.0,<2.2.1"
  }
}
```
et taper dans le terminal
```bash
composer install
```
- Créer un fichier monolog4/composer.json:
```bash
{
  "require": {
    "monolog/monolog": ">=2.0.0,<2.0.2",
  }
}
```
et taper dans le terminal
```bash
composer install --ignore-platform-reqs
```
- Créer un fichier monolog5/composer.json:
```bash
{
  "require": {
    "monolog/monolog": ">2.0.0,<2.3.5"
  }
}
```
et taper dans le terminal
```bash
composer install
```

## ex02

## ex03
https://stackoverflow.com/questions/33052195/what-are-the-differences-between-composer-update-and-composer-install

# DAY 04

- Creer un projet avec symfony version LTS (6.4):
```bash
symfony new nom_du_projet --version=6.4
```
- Creer un Controller:
```bash
symfony console make:controller NomDuController
```
- Configurer les routes:
```bash
return[
    ...
    App\Controller\DefaultController::class => ['all' => true],
]
```
- Lancer le server:
```bash
symfony server:start
```
- Stopper le server:
```bash
symfony server:stop
```

## ex03

Creer un formulaire symfony

- Installer symfony/form:
```bash
composer require symfony/form
```