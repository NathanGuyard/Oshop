<?php

namespace App\Controllers;

use App\Models\AppUser;

class UserController extends CoreController {

    /**
     * Méthode affichant le formulaire de connexion
     *
     */
    public function login()
    {
        // On génère une clé anti CSRF qu'on envoie au formulaire
        $csrfToken = bin2hex(random_bytes(32));

        // Et on en stocke une copie dans la session de l'utilisateur
        $_SESSION['csrfToken'] = $csrfToken;

        $this->show('user/login', ['csrfToken' => $csrfToken]);
    }


    public function authenticate()
    {
        // On récupère les infos de notre formulaire de connexion
        $email = filter_input(INPUT_POST, 'email');
        $password = filter_input(INPUT_POST, 'password');

        // On récupère le potentiel utilisateur lié à cette adresse.
        $user = AppUser::findByEmail($email);
        
        // Si on récupère bien un utilisateur, alors : 
        if($user) {
            // On compare le mot de passe du formulaire avec celui de la BDD. Si les deux sont identiques, alors on est connecté !
            // Les mots de passe de la BDD sont hashés, c'est à dire transformés en une nouvelle chaine de caracètre, sans qu'on puisse revenir en arrière.
            // On va donc utiliser la fonction password_verify. On lui passe le mot de passe à comparer et un hash de ce mot de passe. Elle va alors hacher notre mot de passe et le comparer au hash stocké en BDD pour savoir s'ils correspondent.
            if(password_verify($password, $user->getPassword())) {

                // Pour modéliser le fait que l'utilisateur est connceté, on décide de partir du principe que tout utilisateur connecté doit avoir une entrée "connectedUser" dans sa session. 
                $_SESSION['connectedUser'] = $user;

                $_SESSION['successList'][] = "Bienvenue, " . $user->getFirstname();

                // On redirige vers la page d'accueil
                $this->redirect('main-home');


            } else {
                // Sinon, on affiche un message d'erreur !
                $_SESSION['errorsList'][] = "Le mot de passe n'est pas correct";

                $this->redirect('user-login');
            }
        } else {
            $_SESSION['errorsList'][] = "Cet email n'existe pas !";
            $this->redirect('user-login');

        }
    }


    // Méthode permettant de se déconnecter du site
    public function logout()
    {
        // Pour se déconnecter, on supprime la clé connectedUser de notre session
        unset($_SESSION['connectedUser']);

        // On redirige vers la page d'accueil
        $this->redirect('main-home');

    }

    /**
     * Page listant les utilisateurs
     *
     */
    public function list()
    {
        // On récupère la liste des utilisateurs
        $userList = AppUser::findAll();
        

        // On affiche la page en lui envoyant la liste
        $this->show('user/list', ['userList' => $userList]);
    }

    /**
     * Méthode affichant le formulaire d'ajout d'user
     *
     */
    public function add()
    {
        // On génère une clé anti CSRF qu'on envoie au formulaire
        $csrfToken = bin2hex(random_bytes(32));

        // Et on en stocke une copie dans la session de l'utilisateur
        $_SESSION['csrfToken'] = $csrfToken;
        
        $this->show('user/add', ['csrfToken' => $csrfToken]);
    }

    public function create()
    {
        // On récupère les différents champs du formulaire
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');
        $firstname = filter_input(INPUT_POST, 'firstname');
        $lastname = filter_input(INPUT_POST, 'lastname');
        $role = filter_input(INPUT_POST, 'role');
        $status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);


        // On crée un tableau qui va servir à stocker les erreurs 

        $errors = [];

        // On valide les informations du formulaire
        if($email === false) {
            $errors[] = "Votre email doit etre valide !";
        }

        // On vérifie que le mot de passe est bien rentré et fait au moins 3 caractères
        if(strlen($password) < 3) {
            $errors[] = "Le mot de passe doit faire 3 caractères au minimum";
        }

        // On vérifie que le nom et le prénom sont bien renseignés
        if(empty($firstname) || empty($lastname)) {
            $errors[] = "Le nom et le prénom sont obligatoires";
        }
        
        // On vérifie que le role existe
        if($role != 'catalog-manager' && $role != 'admin') {
            $errors[] = "Veuillez choisir un role existant";
        }


        // On vérifie le statut
        if($status < 1 || $status > 2) {
            $errors[] = "Veuillez choisir un bon statut";
        }

        // Si on avait stocké les valeurs d'un formulaire précédent, on les vide 
        if(isset($_SESSION['inputValues'])) {
            unset($_SESSION['inputValues']);
        }

        // Si le nombre d'erreurs est différent de 0
        if(count($errors) != 0) {

            // On insère la  liste des erreurs dans une entrée de la session de l'utilisateur courant
            $_SESSION['errorsList'] = $errors;


            // Pour réafficher les données dans le formulaire, on va les stocker en session
            $_SESSION['inputValues'] = $_POST;

            // On redirige vers la page précédente
            $this->redirect('user-add');
        } else {
            
            // Si on n'a pas d'erreur, alors on sauvegarde le nouvel utilisateur

            $newUser  = new AppUser;
            // On hash le mot de passe reçu
            $password = password_hash($password, PASSWORD_DEFAULT);
            // On remplit les propriétés du nouvel utilisateur
            $newUser->setEmail($email);
            $newUser->setPassword($password);
            $newUser->setFirstname($firstname);
            $newUser->setLastname($lastname);
            $newUser->setStatus($status);
            $newUser->setRole($role);

            // On sauvegarde l'utilisateur
            $isSaved = $newUser->save();
            
            if($isSaved) {

                // On stocke dans les sessions un petit message de succès
                $_SESSION['successList'][] = "L'utilisateur a bien été créé !";

                // Puis on redirige vers la liste des utilisateurs
                $this->redirect('user-list');
            }

        }
    }

}