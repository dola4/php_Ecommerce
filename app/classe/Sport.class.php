<?php
include_once 'Categorie.class.php';

class Sport {
    protected $id;
    protected $nom;
    protected $description;
    protected $image;
    protected $categorieId; 

    public function __construct($id, $nom, $description, $image, $categorieId){
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
        $this->image = $image;
        $this->categorieId = $categorieId; 
    }

    public function getId(){
        return $this->id;
    }
    public function setId($id){
        $this->id = $id;
    }

    public function getNom(){
        return $this->nom;
    }
    public function setNom($nom){
        $this->nom = $nom;
    }

    public function getDescription(){
        return $this->description;
    }
    public function setDescription($description){
        $this->description = $description;
    }

    public function getImage(){
        return $this->image;
    }
    public function setImage($image){
        $this->image = $image;
    }

    public function getCategorieId(){
        return $this->categorieId;
    }
    public function setCategorie($categorieId){
        $this->categorieId = $categorieId;
    }
}
?>
