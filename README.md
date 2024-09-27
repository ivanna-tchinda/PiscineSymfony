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

