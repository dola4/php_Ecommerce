<?php

class Request{
    protected $sql;
    const UNE_SEULE_LIGNE = true;

    public function setSql($sql) {
        $this->sql = $sql;
    }

    /* public function getLines($oCon, $params =[], $uneSeuleLigne = false){
    
        $oStatement = $oCon->prepare($this->sql);
        foreach ($params as $nomParam => $valParam) {
            $oStatement->bindValue(':'.$nomParam, $valParam, is_int($valParam) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $oStatement->execute();
    
        $resultat = $uneSeuleLigne ? $oStatement->fetch(PDO::FETCH_ASSOC) :
        $oStatement->fetchAll(PDO::FETCH_ASSOC);
    
        echo $this->sql;
        print_r($params);
        $oStatement->debugDumpParams();
        return $resultat;
    } */
    public function getLines($oCon, $params = [], $uneSeuleLigne = false) {
        $oStatement = $oCon->prepare($this->sql);
        
        // Check if the array is associative
        if (array_keys($params) !== range(0, count($params) - 1)) {
            // Handle named placeholders
            foreach ($params as $nomParam => $valParam) {
                $oStatement->bindValue(':'.$nomParam, $valParam, is_int($valParam) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }
            $oStatement->execute();
        } else {
            // Handle positional placeholders
            $oStatement->execute($params);
        }
        
        $resultat = $uneSeuleLigne ? $oStatement->fetch(PDO::FETCH_ASSOC) :
        $oStatement->fetchAll(PDO::FETCH_ASSOC);
        
        return $resultat;
    }
    
}

?>

