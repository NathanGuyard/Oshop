<?php

namespace App\Controllers;

abstract class CoreController {

    // On se crée une propriété qui viendra accueillir notre router
    public $router;

    public function __construct($match = [], $router)
    {
        // On reçoit un objet router dans les paramètres du constructeur et on le stocke dans une propriété afin qu'il soit utilisable dans toute la classe (et meme ses enfant)
        $this->router = $router;

        // Si on reçoit des informations sur la page, alors on vérifie qu'elle n'est pas protégée
        if(!empty($match)) {

            // On récupère le nom de la page courante
            $currentRoute = $match['name'];
            
            // On définit notre tableau d'ACL. C'est à dire la liste des routes protégées et les roles qui ont le droit d'y accéder
            $acl = [
                'main-home' => ['catalog-manager', 'admin'],
                'user-add' => ['admin'],
                'user-list' => ['admin'],
                'user-create' => ['admin'],
                'product-list' => ['catalog-manager', 'admin'], 
                'product-add' => ['catalog-manager', 'admin'], 
                'product-create' => ['catalog-manager', 'admin'], 
                'category-list' => ['catalog-manager', 'admin'], 
                'category-add' => ['catalog-manager', 'admin'], 
                'category-updateForm' => ['catalog-manager', 'admin'], 
                'category-homeCategoriesForm' => ['catalog-manager', 'admin'], 
            ];


            // On vérifie que la route courante fait partie des routes protégées
            if(isset($acl[$currentRoute])) {
                // On récupère le tableau des roles autorisés pour cette route
                $authorizedRoles = $acl[$currentRoute];

                // On appelle la méthode qui se charge de vérifier que l'utilisateur connecté possède les bons roles
                $this->checkAuthorization($authorizedRoles);
            }


            // On liste les routes qui sont protégées par token contre les attaques CSRF
            $csrfRoutes = [
                'user-create',
                'user-authenticate',
                'product-update',
                'product-create',
                'category-create',
                'category-saveHomeCategories',
            ];

            // On vérifie que la route actuelle est une route protégée contre les attaques CSRF
            if(in_array($currentRoute, $csrfRoutes)){

                // On récupère le token qui a été généré précédemment et stocké en session.
                $tokenSession = (isset($_SESSION['csrfToken'])) ? $_SESSION['csrfToken'] : '';
                
                // On récupère le token qui doit etre transmis avec le formulaire
                $formToken = filter_input(INPUT_POST, 'token');
                // Si les deux tokens sont différents, ou vides, on arrete tout, c'est un hack !
                if(empty($tokenSession) || empty($formToken) || $formToken != $tokenSession) {
                    // On instancie le controller des erreurs
                    $errorController = new ErrorController;
                    // Et on appelle la méthode qui s'occupe d'afficher l'erreur 403
                    $errorController->err403();
                }
            }

        }

        
    }


    /**
     * Méthode permettant d'afficher du code HTML en se basant sur les views
     *
     * @param string $viewName Nom du fichier de vue
     * @param array $viewData Tableau des données à transmettre aux vues
     * @return void
     */
    protected function show(string $viewName, $viewData = []) {
        // On récupère l'objet AltoRouter stocké dans la propriété du meme nom afin de pouvoir l'utiliser dans les vues
        $router = $this->router;

        // Comme $viewData est déclarée comme paramètre de la méthode show()
        // les vues y ont accès
        // ici une valeur dont on a besoin sur TOUTES les vues
        // donc on la définit dans show()
        $viewData['currentPage'] = $viewName; 

        // définir l'url absolue pour nos assets
        $viewData['assetsBaseUri'] = $_SERVER['BASE_URI'] . 'assets/';
        // définir l'url absolue pour la racine du site
        // /!\ != racine projet, ici on parle du répertoire public/
        $viewData['baseUri'] = $_SERVER['BASE_URI'];

        // On veut désormais accéder aux données de $viewData, mais sans accéder au tableau
        // La fonction extract permet de créer une variable pour chaque élément du tableau passé en argument
        extract($viewData);
        // => la variable $currentPage existe désormais, et sa valeur est $viewName
        // => la variable $assetsBaseUri existe désormais, et sa valeur est $_SERVER['BASE_URI'] . '/assets/'
        // => la variable $baseUri existe désormais, et sa valeur est $_SERVER['BASE_URI']
        // => il en va de même pour chaque élément du tableau

        // $viewData est disponible dans chaque fichier de vue
        require_once __DIR__.'/../views/layout/header.tpl.php';
        require_once __DIR__.'/../views/'.$viewName.'.tpl.php';
        require_once __DIR__.'/../views/layout/footer.tpl.php';
    }

    /**
     * Méthode encapsulant une redirection vers une page donnée
     *
     * @param string $page Etiquette de la page sur laquelle rediriger
     */
    protected function redirect($page)
    {
        header('Location: '. $this->router->generate($page));
        exit;
    }

    /**
     * Méthode permettant de vérifier que l'utilisateur à le droit d'accéder à la page
     * 
     * @param array $roles Tableau contenant les roles autorisés sur la page
     *
     */
    public function checkAuthorization($roles = [])
    {
        // Si la personne n'est pas connectée, on redirige vers la page d'accueil
        if(!isset($_SESSION['connectedUser'])) {
            $this->redirect('user-login');
        } else {
            //Sinon, on vérifie le role de la personne.
            // On commence par récupérer l'objet représentant la personne connectée
            $connectedUser = $_SESSION['connectedUser'];
            // On récupère son role
            $userRole = $connectedUser->getRole();
            
            // Si le role de l'utilisateur n'est pas dans la liste des roles autorisés, on lui affiche une erreur 403
            // A condition que le tableau des roles autorisés ne soit pas vide.
            if(!in_array($userRole, $roles) && !empty($roles)) {
                // On instancie le controller des erreurs
                $errorController = new ErrorController;
                // Et on appelle la méthode qui s'occupe d'afficher l'erreur 403
                $errorController->err403();
            }
        }
    }
}
