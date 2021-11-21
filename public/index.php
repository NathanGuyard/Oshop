<?php

// POINT D'ENTRÉE UNIQUE : 
// FrontController

// inclusion des dépendances via Composer
// autoload.php permet de charger d'un coup toutes les dépendances installées avec composer
// mais aussi d'activer le chargement automatique des classes (convention PSR-4)
require_once '../vendor/autoload.php';


// On démarre le système de sessions de PHP.
// Au chargement de n'importe quelle page, PHP va vérifier que le visiteur possède un cookie PHPSESSID (c'est la clé de notre coffre fort). 
// S'il la possède, alors on aura accès aux informations stockées dans $_SESSION.
// Sinon, s'il n'en a pas ou si cette clé ne correspond à aucune session (coffre-fort), on lui crée une nouvelle session vide.
session_start();


// dump($_SESSION);



/* ------------
--- ROUTAGE ---
-------------*/


// création de l'objet router
// Cet objet va gérer les routes pour nous, et surtout il va 
$router = new AltoRouter();

// le répertoire (après le nom de domaine) dans lequel on travaille est celui-ci
// Mais on pourrait travailler sans sous-répertoire
// Si il y a un sous-répertoire
if (array_key_exists('BASE_URI', $_SERVER)) {
    // Alors on définit le basePath d'AltoRouter
    $router->setBasePath($_SERVER['BASE_URI']);
    // ainsi, nos routes correspondront à l'URL, après la suite de sous-répertoire
}
// sinon
else {
    // On donne une valeur par défaut à $_SERVER['BASE_URI'] car c'est utilisé dans le CoreController
    $_SERVER['BASE_URI'] = '/';
}

// On doit déclarer toutes les "routes" à AltoRouter, afin qu'il puisse nous donner LA "route" correspondante à l'URL courante
// On appelle cela "mapper" les routes
// 1. méthode HTTP : GET ou POST (pour résumer)
// 2. La route : la portion d'URL après le basePath
// 3. Target/Cible : informations contenant
//      - le nom de la méthode à utiliser pour répondre à cette route
//      - le nom du controller contenant la méthode
// 4. Le nom de la route : pour identifier la route, on va suivre une convention
//      - "NomDuController-NomDeLaMéthode"
//      - ainsi pour la route /, méthode "home" du MainController => "main-home"
$router->map(
    'GET',
    '/',
    [
        'method' => 'home',
        'controller' => 'MainController'
    ],
    'main-home'
);


$router->map(
    'GET',
    '/categories',
    [
        'method' => 'list',
        'controller' => 'CategoryController'
    ],
    'category-list'
);

$router->map(
    'GET',
    '/category/add',
    [
        'method' => 'add',
        'controller' => 'CategoryController'
    ],
    'category-add'
);

$router->map(
    'GET',
    '/category/update/[i:id]',
    [
        'method' => 'updateForm',
        'controller' => 'CategoryController'
    ],
    'category-updateForm'
);


$router->map(
    'POST',
    '/category/add',
    [
        'method' => 'create',
        'controller' => 'CategoryController'
    ],
    'category-create'
);



$router->map(
    'POST',
    '/category/update/[i:id]',
    [
        'method' => 'update',
        'controller' => 'CategoryController'
    ],
    'category-update'
);


$router->map(
    'GET',
    '/category/delete/[i:id]',
    [
        'method' => 'delete',
        'controller' => 'CategoryController'
    ],
    'category-delete'
);



$router->map(
    'GET',
    '/products',
    [
        'method' => 'list',
        'controller' => 'ProductController'
    ],
    'product-list'
);

$router->map(
    'GET',
    '/product/add',
    [
        'method' => 'add',
        'controller' => 'ProductController'
    ],
    'product-add'
);

$router->map(
    'POST',
    '/product/add',
    [
        'method' => 'create',
        'controller' => 'ProductController'
    ],
    'product-create'
);


$router->map(
    'GET',
    '/product/update/[i:id]',
    [
        'method' => 'updateForm',
        'controller' => 'ProductController'
    ],
    'product-updateForm'
);


$router->map(
    'POST',
    '/product/update/[i:id]',
    [
        'method' => 'update',
        'controller' => 'ProductController'
    ],
    'product-update'
);

$router->map(
    'GET',
    '/product/delete/[i:id]',
    [
        'method' => 'delete',
        'controller' => 'ProductController'
    ],
    'product-delete'
);

$router->map(
    'GET',
    '/login',
    [
        'method' => 'login',
        'controller' => 'UserController'
    ],
    'user-login'
);

$router->map(
    'POST',
    '/login',
    [
        'method' => 'authenticate',
        'controller' => 'UserController'
    ],
    'user-authenticate'
);

$router->map(
    'GET',
    '/logout',
    [
        'method' => 'logout',
        'controller' => 'UserController'
    ],
    'user-logout'
);

$router->map(
    'GET',
    '/user/list',
    [
        'method' => 'list',
        'controller' => 'UserController'
    ],
    'user-list'
);

$router->map(
    'GET',
    '/user/add',
    [
        'method' => 'add',
        'controller' => 'UserController'
    ],
    'user-add'
);

$router->map(
    'POST',
    '/user/add',
    [
        'method' => 'create',
        'controller' => 'UserController'
    ],
    'user-create'
);

$router->map(
    'GET',
    '/home-categories',
    [
        'method' => 'homeCategoriesForm',
        'controller' => 'CategoryController'
    ],
    'category-homeCategoriesForm'
);

$router->map(
    'POST',
    '/home-categories',
    [
        'method' => 'saveHomeCategories',
        'controller' => 'CategoryController'
    ],
    'category-saveHomeCategories'
);







/* -------------
--- DISPATCH ---
--------------*/

// On demande à AltoRouter de trouver une route qui correspond à l'URL courante
$match = $router->match();


// Code permettant d'ajouter le namespace, mais depuis la mise à jour 1.3 d'altoDispatcher, on n'en a plus besoin.
// $match['target']['controller'] = "\\App\\Controllers\\".$match['target']['controller'];

// Ensuite, pour dispatcher le code dans la bonne méthode, du bon Controller
// On délègue à une librairie externe : https://packagist.org/packages/benoclock/alto-dispatcher
// 1er argument : la variable $match retournée par AltoRouter
// 2e argument : le "target" (controller & méthode) pour afficher la page 404
$dispatcher = new Dispatcher($match, '\App\Controllers\ErrorController::err404');
// Tous nos controllers sont situés dans le meme namespace, on utilise la méthode setControllersNamespace pour le préciser au dispatcher.

$dispatcher->setControllersNamespace('App\Controllers');

// Pour faire nos ACL, on a besoin de connaitre la page courante. On passe donc le tableau $match en argument du controller. Il sera récupéré en paramètre du constructeur de CoreController
$dispatcher->setControllersArguments($match, $router);

// Une fois le "dispatcher" configuré, on lance le dispatch qui va exécuter la méthode du controller
$dispatcher->dispatch();

