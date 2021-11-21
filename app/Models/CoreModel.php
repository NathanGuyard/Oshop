<?php

namespace App\Models;

// Classe mère de tous les Models
// On centralise ici toutes les propriétés et méthodes utiles pour TOUS les Models
// Notre classe CoreModel est abtraite, c'est à dire qu'on ne peut pas l'instancer. Elle sert uniquement de parent à des classes Enfant
abstract class CoreModel {
    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    protected $created_at;
    /**
     * @var string
     */
    protected $updated_at;


    /**
     * Get the value of id
     *
     * @return  int
     */ 
    // Le ": int" permet d'indique à PHP que la méthode getId doit renvoyer un entier.
    // Or, avant de sauvegarder l'utilisateur la première fois, l'ID n'est pas défini, il n'est donc pas un entier. On ajoute donc un "?" devant int pour autoriser les valeurs nulles.
    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * Get the value of created_at
     *
     * @return  string
     */ 
    public function getCreatedAt() : string
    {
        return $this->created_at;
    }

    /**
     * Get the value of updated_at
     *
     * @return  string
     */ 
    public function getUpdatedAt() : string
    {
        return $this->updated_at;
    }

    public function save()
    {
        // Si l'objet courant possède déjà un ID, c'est qu'on modifie une entrée de la BDD. Sinon, c'est qu'on la crée. On peut donc appeler les méthodes update() et insert() selon le cas de figure.

        if($this->getId() > 0) {
            return $this->update();
        } else {
            return $this->insert();
        }
    }


    // Pour harmoniser notre code, on indique des méthodes abstraites. C'est à dire des méthodes qui doivent obligatoirement etre implémentées par les class enfant de CoreModel
    abstract static public function findAll();
    abstract static public function find($id);
    abstract public function insert();
    abstract public function update();
    abstract public function delete();


}
