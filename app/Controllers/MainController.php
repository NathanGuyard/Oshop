<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\CoreModel;
use App\Models\Product;

// Si j'ai besoin du Model Category
// use App\Models\Category;

class MainController extends CoreController {

    /**
     * Méthode s'occupant de la page d'accueil
     *
     * @return void
     */
    public function home()
    {

        // On récupère les trois dernières catégories
        $categories = Category::findLastThree();

        // On récupère aussi les trois derniers produits:
        $products = Product::findLastThree();
        
        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        $this->show('main/home' , [
            'categories' => $categories,
            'products' => $products
        ]);
    }
}
