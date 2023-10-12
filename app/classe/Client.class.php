<?php
    include './class/Utilisateur.class.php';

    class Client extends User {
        protected $telephone;
        protected $user_id;

        public function __construct($nom, $prenom, $age, $email, $pw, $telephone) {
            parent::__construct(null, $nom, $prenom, $age, $email, $pw);
            $this->telephone = $telephone;
        }
    
        public function voirCommandes() {
            // Implémentation de la visualisation des commandes...
        }
        public function gererMessages() {
            // Implémentation de la gestion des messages...
        }
        public function authentification() {
            // Implémentation de l'authentification...
        }
        public function gestionPanier() {
            // Implémentation de la gestion du panier...
        }
        public function getClient_telephone(){
            return $this->telephone;
        }
        public function setClient_telephone($telephone){
            $this->telephone = $telephone;
        }
    }
?>