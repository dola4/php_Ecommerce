<?php
require 'env.php';

$conn = mysqli_connect(HOST,DBUSER,DBPASSWORD,DBNAME);

if(!$conn){
     die("Erreur avec la connection a la BD". mysqli_connect_error());
}

?>