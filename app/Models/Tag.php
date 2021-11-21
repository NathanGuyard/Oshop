<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class Tag extends CoreModel {

    private $name;


    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }


    public static function find($id)
    {
        // TODO: coder la méthode
    }


    public static function findAll()
    {
        // TODO: coder la méthode
    }

    public  function insert()
    {
        // TODO: coder la méthode

    }

    public function update()
    {
        // TODO: coder la méthode
    }

    public function delete()
    {
        // TODO: coder la méthode
    }

    /**
     * Méthode récupèrant des tags d'après l'ID d'un produit
     *
     * @param int $product_id
     * @return array
     */
    public static function findByProductId($product_id)
    {
        $pdo = Database::getPDO();

        // On crée notre requete récupérant les tags liés à un produit donné. Ce sont des requetes imbriqués. C'est à dire que la requete dans les parenthèses va etre exécutée en premier et son résultat servira a nourir la requete extérieure.

        // Ici, on récupère une liste d'ID de tags liés à un produit. Et cette liste d'ID nous permet de récupérer une liste de tags complets
        $sql = "SELECT * FROM `tag`
                WHERE `id` IN (
                    SELECT `tag_id` FROM `product_has_tag`
                    WHERE `product_id` = :product_id
                )";

        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->bindValue(':product_id', $product_id, PDO::PARAM_INT);
        $pdoStatement->execute();

        $result = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);

        return $result;


    }

}