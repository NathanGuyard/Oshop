<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class AppUser extends CoreModel
{
    private $firstname;
    private $lastname;
    private $password;
    private $email;
    private $role;
    private $status;


    public static function find($id)
    {
        // TODO: coder la méthode
    }

    /**
     * Méthode permettant de retrouver une utilisateur d'après son email
     *
     * @param string $email Email de l'utilisateur à retrouver
     * @return AppUser
     */
    public static function findByEmail($email)
    {
        // se connecter à la BDD
        $pdo = Database::getPDO();

        // On écrit  notre requete
        $sql = "SELECT * FROM `app_user`
        WHERE `email` = :email";

        // On demande à PDO de la préparer, c'est à dire analyser sa structure
        $pdoStatement = $pdo->prepare($sql);

        // On remplace les emplacements par les vraies valeurs
        $pdoStatement->bindValue(':email', $email);

        // On exécute la requete
        $pdoStatement->execute();

        // On traduit le résultat sous la forme d'un objet. Objet issue de la classe courante (AppUser). Self::class représente le FQCN de la classe courante (ici App\Models\AppUser)
        return $pdoStatement->fetchObject(self::class);

    }

    /**
     * Méthode récupérant tous les utilisateurs de la BDD
     *
     * @return array
     */
    public static function findAll()
    {
        $pdo = Database::getPDO();

        $sql = 'SELECT * FROM `app_user`';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);
        
        return $results;
    }

    public  function insert()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête INSERT INTO
        // Pour éviter les injections SQL, on va faire une requete préparée. C'est à dire qu'on commence par décrire la requete : sa forme, ses caractéristiques, son nombre d'infos à modifer. 
        $sql = "
            INSERT INTO `app_user` (email, password, firstname, lastname, status, role)
            VALUES (:email, :password, :firstname, :lastname, :status, :role)
        ";

        // On informe PDO de la requete qu'il va recevoir grace à la méthode prepare(). Et il nous renvoie un objet représentant cette requete, prete à recevoir ses vraies valeurs.
        // https://www.php.net/manual/fr/pdo.prepare.php
        $pdoStatement = $pdo->prepare($sql);

        // Maintenant que la requete est préparée et que PDO est au courant de ses limites, on vient remplacer les emplacements par leurs vraies valeurs.
        // Pour ça on utilise la méthode  bindValue qui permet de remplacer les emplacements (tokens) par leur vraie valeur.
        // En troisième argument, on peut préciser le type de donnée de l'emplacement (par défaut ce sera string)
        // https://www.php.net/manual/fr/pdostatement.bindvalue.php
        
        $pdoStatement->bindValue(':email', $this->email);
        $pdoStatement->bindValue(':password', $this->password);
        $pdoStatement->bindValue(':firstname', $this->firstname);
        $pdoStatement->bindValue(':lastname', $this->lastname);
        $pdoStatement->bindValue(':role', $this->role);
        $pdoStatement->bindValue(':status', $this->status, PDO::PARAM_INT);
   
        // Maintenant que la requete est préparée et remplie avec les bonnes infos, on utilise la méthode execute qui permet d'exécuter les requetes préparées
        $insertedRows = $pdoStatement->execute();
        
        // Si au moins une ligne ajoutée
        if ($insertedRows == true) {
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
        // TODO: coder la méthode
    }

    public function delete()
    {
        // TODO: coder la méthode
    }

    /**
     * Get the value of role
     */ 
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set the value of role
     *
     * @return  self
     */ 
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of lastname
     */ 
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set the value of lastname
     *
     * @return  self
     */ 
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get the value of firstname
     */ 
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set the value of firstname
     *
     * @return  self
     */ 
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get the value of status
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */ 
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }
}