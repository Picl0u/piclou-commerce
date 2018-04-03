Piclou Commerce
===============

### Laravel 5.5 – PHP &gt;= 7.0 – MySQL 5.7 
### Version ALPHA 0.1  
### Cette version est une version alpha, des bugs sont sans doutes encore présents.

La version de démo en ligne sera disponible bientôt !
La documentation n'est pas encore rédigée, je m'en occupe très prochainement !

Développement à venir
---------------------

-   Mettre toutes les traductions en place sur le front office et back
    office

-   Développement de l'API REST

-   Importation en CSV des déclinaisons de produits

-   Liste de souhaits en publique

 

Installation
------------

1.  Télécharger le projet

2.  Lancer la commande :  `composer update`

3.  Lancer la commande :  `npm install`

4.  Modifier le fichier .env pour mettre à jours les informations de
    laravel (base de données, site internet, ect...)

5.  Modifier le fichier `config/IkCommerce.php` et modifier les
    informations pour votre application

6.  Lancer la commande :  `php artisan migrate`

7.  Lancer la commande : ` php artisan db:seed`

8.  Lancer la commande `npm run watch` pour builder les assets. N'oubliez
    pas de modifier le fichiers `/resources/assets/css/_variables.scss` pour mettre vos couleurs et
    vos polices !

 

Merci à vous de prendre de le temps d'installer le projet et de le
tester. N'hésitez pas à me communiquer le moindre retour ! 

 

  [db:seed]: db:seed
