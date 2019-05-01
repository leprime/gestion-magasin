## Application de gestion d'un magasin.
Il s'agit d’un petit magasin où l'on dispose des articles électroniques.
La gestion classique se fait avec des classeurs excel où l'on  peut ajouter des pièces achetées et classer les pièces utilisées. 
Un système calcul automatiquement le stock me montre toutes les pièces et combien il en reste. Cependant, mais les classeurs Excel ne sont plus adaptés et très pratiques pour plusieurs raisons.

C'est ainsi que la création d'une application (web de préférence a été recommandée). 

![application screenshot](public/images/dashboard.png "Petite application web écrit en Symfony 4 pour gérer un magasin (électronique)")


## Pour installer/utiliser l'aplication
Rassurer vous d'avoir `php 7.2.*` and `composer` sur votre machine

1.  Cloner ce dépôt et faîtes un cd vers ce repertoire 
2.  Exécuter `composer install` pour télécharger toutes les dépendences du projet
3.  Copier et renommer le fichier `.env.dist` en `.env` 
4.  Configurer les informatons de votre base de données dans le fichier `.env` et exécuter `bin/console doctrine:database:create`
5.  Créer les migrations avec `bin/console make:migration`
6.  Générer la structure de la base de données en tappant `bin/console doctrine:migrations:migrate`
7.  Faîtes un tour sur la documentation de symfony si tout ceci est encore floue. 

### Pré-requis

* PHP 7.2
* Composer
* Symfony

TODO
----

Dans un future proche, je plannifie finaliser toute les fonctionnalités spécialement : 

- L'export de données en PDF, ce qui est très pratique pour faire des inventaires, historiques, des factures
- Liens vers divers une base de données distante afin stocker les données locales.
- Mettre un système temps réels pour la mise à jour automatique des données  