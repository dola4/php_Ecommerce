<?php 
    class Categorie{
        protected $categorie;
        protected $image;
        protected $sport = [];

        
        public function __construct($categorie, $image){
            $this->categorie = $categorie;
            $this->image = $image;
        }

        public function getCategorie(){
            return $this->categorie;
        }
        public function setCategorie($categorie){
            $this->categorie = $categorie;
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
        public function setSprt($sport){
            $this->sport = $sport;
        }
    }
?>