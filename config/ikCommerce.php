<?php
 return[

     'demo' => false,
     'checkUpdate' => false,
     'version' => 'alpha0.1',
     'author' => 'Picl0u',
     'website' => '#',
     'theme' => 'default',

     // Traductions
     'languages' => [
        'fr',
     ],
     'translateKey' => 'translate',

     // Administration
     'adminUrl' => 'admin',
     'adminRole' => 'admin',

     // Administration - Menu
     'adminMenuOrders' => [
       'sale', 'personalize', 'configuring'
     ],

     // Administrateurs
     'superAdminUsers' => [
         [
             'firstname' => 'John',
             'lastname' => 'Doe',
             'email' => 'admin@admin.com',
             'password' => 'password',
             'username' => 'admin-demo'
         ],
     ],
     'superAdminRole' => 'SuperAdmin',

     // Prefixs
     'productPrefix' => 'boutique',

     // Images
     'fileUploadFolder' => 'uploads',
     'imageQuality' => 80,
     'imageCacheFolder' => 'caches',
     'imageMaxWidth' => 1400,
     'imageNotFound' => 'images/no-found.jpg',

     // Cache
     'cacheFolder' => 'cache',

     // Partage réseaux sociaux
     'shareWebsites' => ['facebook', 'twitter', 'pinterest', 'gplus'],
     'shareView' => 'share.share',

     // Importation produits
     'directoryImport' =>  'shop/imports',

     // Commandes
     'orderRef' => date('Ym'),
     'refCount' => 4, //Nombre zéro pour la référence de la commande
     'invoicePath' => 'invoices',
     'invoiceExportPath' => 'exports',
     'invoiceLayout' => 'invoices.layout',
     'invoiceName' => 'Facture',
     'invoiceLogoHeight' => 60
 ];
