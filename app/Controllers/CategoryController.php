<?php

namespace App\Controllers;

use App\Models\Category;

class CategoryController extends CoreController {

    /**
     * Action gérant la page listant les catégories
     *
     */
    public function list()
    {

        // On a besoin de récupérer toutes les catégories
        // La méthode findAll de la class Category est une méthode ne faisant pas référence à un objet Category. C'est à dire qu'elle n'est pas pas propre à une catégorie en particulier mais utilisée plutot pour générer des objets Category.
        // Cette méthode peut donc etre passée en "static", ce qui implique qu'on n'a plus besoin d'instancier la classe pour l'utiliser.
        // Pour exécuter une méthode statique, on utilise la notation Class::méthode. 
        $categoriesList = Category::findAll();


        $this->show('categories/list', [
            'categoriesList' => $categoriesList,
            'title' => 'Page listant les catégories'
        ]);
    }

    /**
     * Action gérant le formulaire d'ajout de catégories
     *
     * @return void
     */
    public function add()
    {

        // On génère une clé anti CSRF qu'on envoie au formulaire
        $csrfToken = bin2hex(random_bytes(32));

        // Et on en stocke une copie dans la session de l'utilisateur
        $_SESSION['csrfToken'] = $csrfToken;

        $this->show('categories/add', ['csrfToken' => $csrfToken]);
    }


    /**
     * Page gérant l'insertion d'une nouvelle catégorie en BDD
     *
     */
    public function create()
    {
        // On commence par récupérer les valeurs inscrites dans les champs, et qui sont maintenant disponibles dans $_POST.

        $name = filter_input(INPUT_POST, 'name');
        $subtitle = filter_input(INPUT_POST, 'subtitle');
        // Pour l'image, on applique un filtre de validation qui vérifie que c'est une url valide
        // https://www.php.net/manual/fr/filter.filters.validate.php
        $picture = filter_input(INPUT_POST, 'picture', FILTER_VALIDATE_URL);

        // Maintenant qu'on a récupéré nos informations, on va créer une nouvelle catégorie à l'aide du model Category.

        $newCategory = new Category;
        
        // Une fois le model Category instancié, et donc un objet vide crééé, on remplit les propriétés cet objet avec les infos récupérées et à l'aide des setters.

        $newCategory->setName($name);
        $newCategory->setSubtitle($subtitle);
        $newCategory->setPicture($picture);
 
        // On sauvegarde la nouvelle catégorie en BDD
        $categoryInserted = $newCategory->save();

        // Si la catégorie a bien été insérée, on redirige vers la page liste des catégories.
        if($categoryInserted) {
            $this->redirect('category-list');
        }

    }

    /**
     * Méthode affichant le formulaire d'édition d'une catégorie
     *
     * @param int $id
     */
    public function updateForm($id)
    {
        // On a besoin des infos de la catégorie à modifier afin de préremplir les champs. On va donc chercher la catégorie concernée avec la méthode find du model Category
        $category = Category::find($id);

        $this->show('categories/update', ['category' => $category]);
    }


    /**
     * Méthode gérant la mise à jour d'une catégorie après soumission du formulaire d'édition
     *
     * @param int $id
     */
    public function update($id)
    {
        // On commence par récupérer les valeurs inscrites dans les champs, et qui sont maintenant disponibles dans $_POST.

        $name = filter_input(INPUT_POST, 'name');
        $subtitle = filter_input(INPUT_POST, 'subtitle');
        // Pour l'image, on applique un filtre de validation qui vérifie que c'est une url valide
        // https://www.php.net/manual/fr/filter.filters.validate.php
        $picture = filter_input(INPUT_POST, 'picture', FILTER_VALIDATE_URL);

        // Maintenant qu'on a récupéré les infos du formulaire, on va les insérer dans la catégorie à modifier. Il faut donc la récupérer.
        $category = Category::find($id);
      

        // On met à jour l'objet avec les infos récupérées en POST
        $category->setName($name);
        $category->setSubtitle($subtitle);
        $category->setPicture($picture);

        $result = $category->save();

        if($result) {
            $this->redirect('category-list');
        }
    }

    /**
     * Méthode gérant la suppression d'une catégorie
     *
     * @param int $id
     * @return 
     */
    public function delete($id)
    {
        // On récupère la catégorie à supprimer
        $category = Category::find($id);

        // On exécute la méthode permettant de la supprimer.
        $deleted = $category->delete();

        if($deleted) {
            $this->redirect('category-list');
        }
    }

    /**
     * Page affichant le formulaire de gestion des catégories en hompa
     *
     */
    public function homeCategoriesForm()
    {
        // On génère une clé anti CSRF qu'on envoie au formulaire
        $csrfToken = bin2hex(random_bytes(32));

        // Et on en stocke une copie dans la session de l'utilisateur
        $_SESSION['csrfToken'] = $csrfToken;

        // On  a besoin de récupérer toutes les catégories afin de peupler les différents select de la page.
        $categoriesList = Category::findAll();

        // On envoie la liste des catégories à notre vue
        $this->show('categories/home', [
            'categoriesList' => $categoriesList,
            'csrfToken' => $csrfToken
        ]);
    }

    public function saveHomeCategories()
    {
        // On récupère la liste des ID des catégories à afficher sur la page d'accueil
        $categoriesToSave  = filter_input(INPUT_POST, 'emplacement', FILTER_DEFAULT , FILTER_REQUIRE_ARRAY);

        // On remet à zéro toutes les catégories
        Category::resetHomeOrder();

        // On parcourt les 5 ID de catégories à modifier
        foreach($categoriesToSave as $order => $categoryId) {
            
            // L'index du tableau commence à zéro, or on veut que cet ordre commence à 1. Donc avec un petit calcul, on convertit cet ordre en lui ajoutant 1.
            $order++;

            // On récupère chaque catégorie depuis la BDD (utiliser la méthode find)
            $category = Category::find($categoryId);
            
            // Mettre à jour le champ home_order avec sa nouvelle valeur (utiliser le setter)
            $category->setHomeOrder($order);

            // On sauvegarde la catégorie dans la BDD (utiliser la méthode save)
            $category->save();
            
        }

        // On indique que la sauvegarde s'est bien passée
        $_SESSION['successList'][] = "L'ordre des catégories a bien été modifié !";
        $this->redirect('category-homeCategoriesForm');


    }
}