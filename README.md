# Titre du projet
GESTION DE STOCK

## A Propos
C'est une App de demande consulaire des ressortisants guinéens en Côte d'Ivoire.

## Dépendences / Prérequis
LARAVEL = 10
PHP > = 8.1
Composer 2.0
MYSQL > = 5.0
Apache 2.4

## Installation des packages
1. Dépôt vide
```
git init
git add README.md
git commit -m "first commit"
git branch -M main
git remote add origin https://github.com/FabioDesign/GESTSHOP.git
git push -u origin main
```

2. Installer Log-viewer
```
composer require arcanedev/log-viewer
```

3. Installer PHP Mailer
```
composer require phpmailer/phpmailer
```

4. Installer DomPDF
```
composer require barryvdh/laravel-dompdf
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

5. Créer le dossier de stockage des images
```
php artisan storage:link
```

6. Copier le contenu de .env.example vers .env et modifier les paramètres
```
cp .env.example .env
```

7. Faire la migration de la base de données
```
php artisan migrate
php artisan db:seed
```

8. Exécuter la commande à la fin
```
php artisan key:generate
```

9. Vérifier si tout est bien installé
```
composer dump-autoload
```

## Réalisé avec
Liste des programmes/logiciels utilisés pour développer le projet

* [Laravel] (https://laravel.com/) - Framework PHP
* [Visual Studio Code] (https://code.visualstudio.com/) - Editeur de textes


## Ressource
OGOU Fabrice - R&D Team Lead (https://www.linkedin.com/in/fabiodesign2010)