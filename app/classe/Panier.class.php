<?php
include './includes/chargement.php';
include './app/config/database.php';
include './asset/bd/env.php';

    class Panier implements GestionArticle {
        protected $id;
        protected $client;
        protected $equipement = [];
        protected $total;
    
        public function __construct(User $client) {
            $this->id = $client->getUser_id();
            $this->client = $client;
        }

        public function getId(){
            return $this->id;
        }
        public function setId($id){
            $this->id = $id;
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

        public function ajouterArticle(Equipement $equipement, $quantite) {
            // Ajoute un équipement au panier avec la quantité spécifiée.
        
            // Vérifiez d'abord si l'équipement est déjà dans le panier.
            $found = false;
            foreach($this->equipement as &$item) {
                echo "Item ID: " . $item['article']->getId() . " | Equipement ID: " . $equipement->getId() . "<br>";  // Accéder à l'objet Equipement
                if($item['article']->getId() == $equipement->getId()) {  // Accéder à l'objet Equipement
                    $found = true;
                    $item['quantite'] += $quantite;
                    break;
                }
            }
            
            // Si l'équipement n'était pas déjà dans le panier, ajoutez-le.
            if(!$found) {
                $this->equipement[$equipement->getId()] = ['article' => $equipement, 'quantite' => $quantite];
            } else {
                $this->equipement[$equipement->getId()]['quantite'] += $quantite;
            }
            // Ajoutez maintenant l'équipement au panier dans la base de données.
            $dbConn = new DatabaseConnexion($GLOBALS['DBHOST'], $GLOBALS['DBNAME'], $GLOBALS['DBUSER'], $GLOBALS['DBPASS']);
            $conn = $dbConn->connexion();

            $request = new Request();

            // Vérifiez si l'article est déjà dans la base de données.
            $request->setSql("SELECT * FROM PanierEquipement WHERE panier_id = :panier_id AND equipement_id = :equipement_id");
            $result = $request->getLines($conn, [
                "panier_id" => $this->client->getUser_id(),
                "equipement_id" => $equipement->getId()
            ]);

            if(!empty($result)) {  // Si l'enregistrement existe déjà
                $request->setSql("UPDATE PanierEquipement SET quantite = quantite + :quantite WHERE panier_id = :panier_id AND equipement_id = :equipement_id");
            } else {  // Si l'enregistrement n'existe pas encore
                $request->setSql("INSERT INTO PanierEquipement (panier_id, equipement_id, quantite) VALUES (:panier_id, :equipement_id, :quantite)");
            }

            // Utilisation de getLines pour exécuter la requête. Comme nous n'attendons pas de résultat en retour pour les requêtes INSERT ou UPDATE, nous n'assignons pas le retour.
            $request->getLines($conn, [
                "panier_id" => $this->client->getUser_id(),
                "equipement_id" => $equipement->getId(),  // Assurez-vous que la classe Equipement ait une méthode getId()
                "quantite" => $quantite
            ]);

            $dbConn->deconnexion();

        }

        public function chargerArticle(Equipement $equipement, $quantite) {
            $this->equipement[$equipement->getId()] = ['article' => $equipement, 'quantite' => $quantite];
        }
        
        
    
        public function supprimerArticle(Equipement $equipement) {
            // Supprime un équipement du panier.
            unset($this->equipement[$equipement->getId()]);
            // Vous devriez également mettre à jour la base de données pour refléter cette suppression.
        }
    
        public function calculerTotal() {
            $this->total = 0;
            foreach ($this->equipement as $item) {
                $this->total += $item['article']->getPrix() * $item['quantite'];
            }
            return $this->total;
        }
        
    }
