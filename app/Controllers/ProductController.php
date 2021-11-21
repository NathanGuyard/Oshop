<?php

namespace App\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use App\Models\Type;

class ProductController extends CoreController {

    /**
     * Action gérant la page liste des produits
     *
     * @return void
     */
    public function list()
    {
        $productList = Product::findAll();

        $this->show('products/list', [
            'productList' => $productList
        ]);

    }


    /**
     * Action gérant le formulaire d'ajout de produits
     *
     * @return void
     */
    public function add()
    {
        $this->show('products/add');
    }

    public function create()
    {
        // On récupère les infos des champs
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        $picture = filter_input(INPUT_POST, 'picture', FILTER_VALIDATE_URL);
        $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
        $rate = filter_input(INPUT_POST, 'rate', FILTER_VALIDATE_INT);
        $status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);
        $brand_id = filter_input(INPUT_POST, 'brand', FILTER_VALIDATE_INT);
        $category_id = filter_input(INPUT_POST, 'category', FILTER_VALIDATE_INT);
        $type_id = filter_input(INPUT_POST, 'type', FILTER_VALIDATE_INT);

        // On instancie un nouveau produit 
        $newProduct = new Product;

        // On remplit les champs de notre objet
        $newProduct->setName($name);
        $newProduct->setDescription($description);
        $newProduct->setPicture($picture);
        $newProduct->setPrice($price);
        $newProduct->setRate($rate);
        $newProduct->setStatus($status);
        $newProduct->setBrandId($brand_id);
        $newProduct->setCategoryId($category_id);
        $newProduct->setTypeId($type_id);

        // On insère le nouveau produit dans la BDD
        $productInserted = $newProduct->save();


        // Si le produit a bien été inséré, on redirige vers la page liste des produits.
        if($productInserted) {

            $this->redirect('product-list');


        }
    }

    /**
     * Méthode affichant le formulaire d'édition d'un produit.
     *
     * @param int $id
     * @return void
     */
    public function updateForm($id)
    {
        // Récupération du produit à modifier
        $product = Product::find($id);
        
        // On récupère les tags liés au produit
        $tags = Tag::findByProductId($id);
       
        // Pour les champs catégorie/type/marque, on a besoin de récupérer toutes ces infos depuis la BDD : 
        $brands = Brand::findAll();
        $types = Type::findAll();
        $categories = Category::findAll();

        $this->show('products/edit', [
            'product' => $product,
            'brands' => $brands,
            'types' => $types,
            'categories' => $categories,
            'tags' => $tags
        ]);

    }

    public function update($id)
    {
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        $picture = filter_input(INPUT_POST, 'picture', FILTER_VALIDATE_URL);
        $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
        $rate = filter_input(INPUT_POST, 'rate', FILTER_VALIDATE_INT);
        $status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);
        $brand_id = filter_input(INPUT_POST, 'brand', FILTER_VALIDATE_INT);
        $category_id = filter_input(INPUT_POST, 'category', FILTER_VALIDATE_INT);
        $type_id = filter_input(INPUT_POST, 'type', FILTER_VALIDATE_INT);


        // On récupère le produit en cours de modification

        $product = Product::find($id);

        $product->setName($name);
        $product->setDescription($description);
        $product->setPicture($picture);
        $product->setPrice($price);
        $product->setRate($rate);
        $product->setStatus($status);
        $product->setBrandId($brand_id);
        $product->setCategoryId($category_id);
        $product->setTypeId($type_id);

        $result = $product->save();

        if($result) {
            $this->redirect('product-list');
        }
    }


    /**
     * Méthode gérant la suppression d'un produit
     *
     * @param int $id
     * @return 
     */
    public function delete($id)
    {
        // On récupère le produit à supprimer
        $product = Product::find($id);

        // On exécute la méthode permettant de le supprimer.
        $deleted = $product->delete();

        if($deleted) {
            $this->redirect('product-list');
        }
    }
}