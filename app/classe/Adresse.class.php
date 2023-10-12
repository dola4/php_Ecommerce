<?php
    class Adresse {
        protected $id;  // Ajouté
        protected $user_id;
        public $numero;
        public $apartement;
        public $rue;
        public $ville;
        public $codePostal;
        public $province;
        public $pays;
    
        public function __construct($numero, $apartement, $rue, $ville, $codePostal, $province, $pays){
            $this->numero = $numero;
            $this->apartement = $apartement;
            $this->rue = $rue;
            $this->ville = $ville;
            $this->codePostal = $codePostal;
            $this->province = $province;
            $this->pays = $pays;
        }

        public function getId(){  // Ajouté
            return $this->id;
        }
        public function setId($id){  // Ajouté
            $this->id = $id;
        }

        public function getUser_id(){  // Ajouté
            return $this->user_id;
        }
        public function setUser_id($user_id){  // Ajouté
            $this->user_id = $user_id;
        }

        public function getNumero(){
            return $this->numero;
        }
        public function setNumero($numero){
            $this->numero = $numero;
        }

        public function getApartement(){
            return $this->apartement;
        }
        public function setApartement($apartement){
            $this->apartement = $apartement;
        }

        public function getRue(){
            return $this->rue;
        }
        public function setRue($rue){
            $this->rue = $rue;
        }

        public function getVille(){
            return $this->ville;
        }
        public function setVille($ville){
            $this->ville = $ville;
        }

        public function getCodePostal(){
            return $this->codePostal;
        }
        public function setCodePostal($codePostal){
            $this->codePostal = $codePostal;
        }

        public function getProvince(){
            return $this->province;
        }
        public function setProvince($province){
            $this->province = $province;
        }

        public function getPays(){
            return $this->pays;
        }
        public function setPays($pays){
            $this->pays = $pays;
        }
    }

    
?>