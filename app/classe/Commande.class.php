<?php
include './class/Utilisateur.class.php';
include './class/Adresse.class.php';
include './class/Equipement.class.php';
include '../http/interfaces/GestionArticle.intf.php';


    class Commande implements GestionArticle{
        protected $client;
        protected $equipement = [];
        protected $total;
        protected $dateCommande;
        protected $adresseLivraison;
        protected $etat;
    
        public function __construct(User $client, $total, Adresse $adresseLivraison) {
            $this->client = $client;
            $this->total = $total;
            $this->adresseLivraison = $adresseLivraison;
            $this->dateCommande = new DateTime();
            $this->etat = 'en attente';
        }
    
        public function getClient(){
            return $this->client;
        }
        public function setClient($client){
           $this->client = $client;
        }

        public function getEquipement(){
            return $this->equipement;
        }
        public function setEquipement($equipement){
           $this->equipement = $equipement;
        }

        public function getTotal(){
            return $this->total;
        }
        public function setTotal($total){
           $this->total = $total;
        }

        public function getDateCommande(){
            return $this->dateCommande;
        }
        public function setDateCommande($dateCommande){
           $this->dateCommande = $dateCommande;
        }

        public function getAdresseLivraison(){
            return $this->adresseLivraison;
        }
        public function setAdresseLivraison($adresseLivraison){
           $this->adresseLivraison = $adresseLivraison;
        }

        public function getEtat(){
            return $this->etat;
        }
        public function setEtat($etat){
           $this->etat = $etat;
        }
    
        public function ajouterArticle(Equipement $equipement, $quantite) {
            $found = false;
            foreach($this->equipement as &$item) {
                if($item['equipement']->getId() == $equipement->getId()) {
                    $found = true;
                    $item['quantite'] += $quantite;
                    break;
                }
            }
            if(!$found) {
                $this->equipement[] = ['equipement' => $equipement, 'quantite' => $quantite];
            }
    
            // Database logic
            $dbConn = new DatabaseConnexion($GLOBALS['DBHOST'], $GLOBALS['DBNAME'], $GLOBALS['DBUSER'], $GLOBALS['DBPASS']);
            $conn = $dbConn->connexion();
    
            $request = new Request();
    
            if($found) {
                $request->setSql("UPDATE CommandeEquipement SET quantite = quantite + :quantite WHERE commande_id = :commande_id AND equipement_nom = :equipement_nom");
            } else {
                $request->setSql("INSERT INTO CommandeEquipement (commande_id, equipement_nom, equipement_prix, quantite) VALUES (:commande_id, :equipement_nom, :equipement_prix, :quantite)");
            }
    
            $request->getLines($conn, [
                "commande_id" => $this->client->getUser_id(),
                "equipement_nom" => $equipement->getNom(),
                "equipement_prix" => $equipement->getPrix(),
                "quantite" => $quantite
            ]);
    
            $dbConn->deconnexion();
        }
    
        public function supprimerArticle(Equipement $equipement) {
            foreach($this->equipement as $key => $item) {
                if($item['equipement']->getId() == $equipement->getId()) {
                    unset($this->equipement[$key]);
                    break;
                }
            }
    
            // Database logic
            $dbConn = new DatabaseConnexion($GLOBALS['DBHOST'], $GLOBALS['DBNAME'], $GLOBALS['DBUSER'], $GLOBALS['DBPASS']);
            $conn = $dbConn->connexion();
    
            $request = new Request();
            $request->setSql("DELETE FROM CommandeEquipement WHERE commande_id = :commande_id AND equipement_nom = :equipement_nom");
            $request->getLines($conn, [
                "commande_id" => $this->client->getUser_id(),
                "equipement_nom" => $equipement->getNom()
            ]);
    
            $dbConn->deconnexion();
        }
    
        public function calculerTotal() {
            $this->total = 0;
            foreach ($this->equipement as $item) {
                $this->total += $item['equipement']->getPrix() * $item['quantite'];
            }
            return $this->total;
        }
    }
    
?>