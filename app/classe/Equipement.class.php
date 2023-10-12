<?php
include_once 'Sport.class.php';
include_once 'Categorie.class.php';

class Equipement {
    protected $id;
    protected $nom;
    protected $description;
    protected $prix;
    protected $image;
    protected $sport;
    
    public function __construct($id, $nom, $description, $prix, $image, Sport $sport) {
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
        $this->prix = $prix;
        $this->image = $image;
        $this->sport = $sport;
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

        public function getPrix(){
            return $this->prix;
        }
        public function setPrix($prix){
            $this->prix = $prix;
        }

        public function getImage(){
            return $this->image;
        }
        public function setImage($image){
            $this->image = $image;
        }

        public function getSport(){
            return $this->sport;
        }
        public function setSport($sport){
            $this->sport = $sport;
        }

        public function getCategorie(){
            return $this->sport->getCategorie(); 
        }
    }    
?>