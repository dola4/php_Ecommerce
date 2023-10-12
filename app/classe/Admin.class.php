<?php
    include './class/Utilisateur.class.php';
    
    class Admin extends User {
        protected $num_employe;
        protected $is_admin;
        protected $user_id;
    
        public function __construct($nom, $prenom, $age, $email, $pw, $num_employe, $is_admin) {
            parent::__construct(null, $nom, $prenom, $age, $email, $pw);
            $this->num_employe = $num_employe;
            $this->is_admin = $is_admin;
        }

        public function gererCommandes() {
            // Implémentation de la gestion des commandes...
        }
        public function gererEquipements() {
            // Implémentation de la gestion des équipements...
        }
        public function gererSports() {
            // Implémentation de la gestion des sports...
        }
        public function gererCategories() {
            // Implémentation de la gestion des catégories...
        }
        public function gererAdmins() {
            // Implémentation de la gestion des admins...
        }
        public function getAdmin_nun_employe(){
            return $this->num_employe;
        }
        public function setAdmin_num_employee($num_employe){
            $this->num_employe = $num_employe;
        }
        public function getAdmin_is_admin(){
            return $this->is_admin;
        }
        public function setAdmin_is_admin($is_admin){
            $this->is_admin = $is_admin;
        }


    }
?>