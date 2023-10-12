<?php

class DatabaseConnexion{
    private $dbname;
    private $username;
    private $password;
    private $host;
    private $pdo;
 
    public function __construct($host, $dbname, $username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        $this->host = $host;
        $this->dbname = $dbname;
    }
   
    

    public function connexion(){
        try{
            $this->pdo = new PDO("mysql:host=$this->host;
            dbname=$this->dbname",
            $this->username,$this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            return $this->pdo;
           
           
        }catch(PDOException $e){
            echo "Erreur de connexion ".$e->getMessage();
        }
    }
    public function deconnexion(){
        $this->pdo =null;
        echo "ferme";
    }
}
?>