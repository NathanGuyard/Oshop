<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class Category extends CoreModel {

    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $subtitle;
    /**
     * @var string
     */
    private $picture;
    /**
     * @var int
     */
    private $home_order;

    /**
     * Get the value of name
     *
     * @return  string
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param  string  $name
     */ 
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get the value of subtitle
     */ 
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set the value of subtitle
     */ 
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
    }

    /**
     * Get the value of picture
     */ 
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set the value of picture
     */ 
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    /**
     * Get the value of home_order
     */ 
    public function getHomeOrder()
    {
        return $this->home_order;
    }

    /**
     * Set the value of home_order
     */ 
    public function setHomeOrder($home_order)
    {
        $this->home_order = $home_order;
    }

    /**
     * Méthode permettant de récupérer un enregistrement de la table Category en fonction d'un id donné
     * 
     * @param int $categoryId ID de la catégorie
     * @return Category
     */
    public static function find($categoryId)
    {
        // se connecter à la BDD
        $pdo = Database::getPDO();

        // écrire notre requête
        $sql = 'SELECT * FROM `category` WHERE `id` =' . $categoryId;

        // exécuter notre requête
        $pdoStatement = $pdo->query($sql);

        // un seul résultat => fetchObject
        // Quand on a récupéré un résultat, on veut le récupérer sous la forme d'un objet de la classe courante. On peut utiliser le mot "self::class" pour afficher le FQCN de la classe courante et ainsi éviter d'avoir à le réécrire.
        $category = $pdoStatement->fetchObject(self::class);

        // retourner le résultat
        return $category;
    }

    /**
     * Méthode permettant de récupérer tous les enregistrements de la table category
     * Cette méthode ne fait référence à aucun objet, elle n'utilise pas le mot clé $this. On peut donc ajouter le mot-clé static devant function afin de la rendre statique et donc exécutable sans instancier la classe dans laquelle elle est rangée.
     * 
     * @return Category[]
     */
    public static function findAll()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `category`';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);
        
        return $results;
    }

    /**
     * Récupérer les 5 catégories mises en avant sur la home
     * 
     * @return Category[]
     */
    public static function findAllHomepage()
    {
        $pdo = Database::getPDO();
        $sql = '
            SELECT *
            FROM category
            WHERE home_order > 0
            ORDER BY home_order ASC
        ';
        $pdoStatement = $pdo->query($sql);
        $categories = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);
        
        return $categories;
    }

    /**
     * Récupérer les 3 dernières catégories
     * 
     * @return Category[]
     */
    public static function findLastThree()
    {
        $pdo = Database::getPDO();
        $sql = '
            SELECT *
            FROM category
            ORDER BY id DESC
            LIMIT 3
        ';
        $pdoStatement = $pdo->query($sql);
        $categories = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);
        
        return $categories;
    }

    /**
     * Méthode permettant d'insérer une nouvelle catégorie dans la BDD
     *
     * @return bool
     */
    public function insert()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête INSERT INTO
        // Pour éviter les injections SQL, on va faire une requete préparée. C'est à dire qu'on commence par décrire la requete : sa forme, ses caractéristiques, son nombre d'infos à modifer. En gros, on va indiquer à PDO que la requete doit absolument insérer une catégorie avec 3 infos, ni plus, ni moins.
        // On crée donc 3 emplacements (tokens) qui vont recevoir les bonnes valeurs plus tard.
        $sql = "
            INSERT INTO `category` (name, subtitle, picture)
            VALUES (:name, :subtitle, :picture)
        ";

        // On informe PDO de la requete qu'il va recevoir grace à la méthode prepare(). Et il nous renvoie un objet représentant cette requete, prete à recevoir ses vraies valeurs.
        // https://www.php.net/manual/fr/pdo.prepare.php
        $pdoStatement = $pdo->prepare($sql);

        // Maintenant que la requete est préparée et que PDO est au courant de ses limites, on vient remplacer les emplacements par leurs vraies valeurs.
        // Pour ça on utilise la méthode  bindValue qui permet de remplacer les emplacements (tokens) par leur vraie valeur.
        // En troisième argument, on peut préciser le type de donnée de l'emplacement (par défaut ce sera string)
        // https://www.php.net/manual/fr/pdostatement.bindvalue.php
        
        $pdoStatement->bindValue(':name', $this->name, PDO::PARAM_STR);
        $pdoStatement->bindValue(':subtitle', $this->subtitle, PDO::PARAM_STR);
        $pdoStatement->bindValue(':picture', $this->picture, PDO::PARAM_STR);

        // Maintenant que la requete est préparée et remplie avec les bonnes infos, on utilise la méthode execute qui permet d'exécuter les requetes préparées
        $insertedRows = $pdoStatement->execute();
        
        // Si au moins une ligne ajoutée
        if ($insertedRows > 0) {
            // Alors on récupère l'id auto-incrémenté généré par MySQL
            $this->id = $pdo->lastInsertId();

            // On retourne VRAI car l'ajout a parfaitement fonctionné
            return true;
            // => l'interpréteur PHP sort de cette fonction car on a retourné une donnée
        }
        
        // Si on arrive ici, c'est que quelque chose n'a pas bien fonctionné => FAUX
        return false;

    }

    public function update()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();


        // Ecriture de la requête préparée UPDATE
        $sql = "UPDATE `category`
                SET
                    name = :name,
                    subtitle = :subtitle,
                    picture = :picture,
                    home_order = :home_order,
                    updated_at = NOW()
                WHERE id = :id
        ";

        // On envoie la requete à PDO afin qu'il la prépare
        $pdoStatement = $pdo->prepare($sql);

        // On remplace les emplacements par leur valeur
        $pdoStatement->bindValue(':name', $this->name);
        $pdoStatement->bindValue(':subtitle', $this->subtitle);
        $pdoStatement->bindValue(':picture', $this->picture);
        $pdoStatement->bindValue(':id', $this->id, PDO::PARAM_INT);
        $pdoStatement->bindValue(':home_order', $this->home_order, PDO::PARAM_INT);

        // On exécute la requete et on récupère son résultat dans une variable
        
        $result = $pdoStatement->execute();
        
        // Si la requete fonctionne, $result contient true, sinon false. Et on renvoie cette valeur.
        return $result; 

    }

    /**
     * Méthode supprimant une catégorie
     *
     * @return bool
     */
    public function delete()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();


        // Ecriture de la requête de suppression
        $sql = 'DELETE FROM `category`
                WHERE id = :id';

        // Préparation de la requete
        $pdoStatement = $pdo->prepare($sql);

        // On remplace les emplacements par leurs vraies valeurs
        $pdoStatement->bindValue(':id', $this->id, PDO::PARAM_INT);

        // On exécute et on retourne le résultat de la requete (true ou false)
        return $pdoStatement->execute();
    }

    /**
     * Méthode remettant à 0 tous les champs home_order
     *
     * @return int
     */
    public static function resetHomeOrder()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Requete permettant de remettre à zéro tous les home_order
        $sql = "UPDATE `category`
        SET `home_order` = 0
        WHERE `home_order` > 0
        ";

        // On execute la requete et on renvoie le nombre de lignes modifiées
        // La requete ne contenant aucune variable ou donnée provenant de l'utilisateur, pas besoin de la préparer
        return $pdo->exec($sql);

    }

}
