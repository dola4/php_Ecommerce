<?php
if (!function_exists('chargement')) {
function chargement($classe){

    $model = "./app/classe/".$classe.".class.php";
    $interface = "./app/http/interfaces/".$classe.".intf.php";
    $crtl = './app/http/controllers/'.$classe.'.crtl.php';
    if (file_exists($model)) {
        require $model;
    }elseif(file_exists($interface)){
        require $interface;
    }
    elseif(file_exists($crtl)){
        require $crtl;
    }
}

spl_autoload_register('chargement');
}
