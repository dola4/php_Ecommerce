<?php
    class User {
        protected $id;
        protected $nom;
        protected $prenom;
        protected $age;
        protected $email;
        protected $pw;
        protected $panier;
        protected $commandes = [];
        protected $adresses = [];
        protected $messages = [];


        public function __construct($id, $nom, $prenom, $age, $email, $pw) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->age = $age;
        $this->email = $email;
        $this->pw = $pw;
    }
        public function getUser_id(){
            return $this->id;
        }
        public function setUser_id($id){
            $this->id = $id;
        }
        
        public function getUser_nom(){
            return $this->nom;
        }
        public function setUser_nom($nom){
            $this->nom = $nom;
        }
        public function getUser_prenom(){
            return $this->prenom;
        }
        public function setUser_prenom($prenom){
            $this->prenom = $prenom;
        }
        public function getUser_age(){
            return $this->age;
        }
        public function setUser_age($age){
            $this->age = $age;
        }
        public function getUser_email(){
            return $this->email;
        }
        public function setUser_email($email){
            $this->email = $email;
        }
        public function getUser_pw(){
            return $this->pw;
        }
        public function setUser_pw($pw){
            $this->pw = $pw;
        }
        public function getUser_commandes() {
            return $this->commandes;
        }
        public function setUser_commande($commande) {
            $this->commandes[] = $commande;
        }
        public function removeUser_commande($commande) {
            // Chercher et supprimer la commande...
        }
        public function getUser_adresses() {
            return $this->adresses;
        }
        public function serUser_adresse($adresse) {
            $this->adresses[] = $adresse;
        }
        public function removeUser_adresse($adresse) {
            // Chercher et supprimer l'adresse...
        }
        public function getUser_messages() {
            return $this->messages;
        }
        public function setUser_message($message) {
            $this->messages[] = $message;
        }
        public function removeUser_message($message) {
            // Chercher et supprimer le message...
        }
    }
?>