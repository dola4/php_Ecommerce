<?php
    interface GestionArticle {
        public function ajouterArticle(Equipement $equipement, $quantite);
        public function supprimerArticle(Equipement $equipement);
        public function calculerTotal();
    }   

?>