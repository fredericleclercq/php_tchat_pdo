<?php

// ouverture de session
session_start();

// ouverture de la connexion BDD
$pdo = new PDO(
    'mysql:host=localhost;dbname=chat',
    'root',
    '',
    array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    )
);

// Initialisation variable
$msg = array();


// Fonction de calcul d'age à partir d'une date de naissance sous la forme AAAA-MM-JJ
function age($naiss)
{
    $today = new DateTime();
    $date_naiss = new DateTime($naiss);
    $interval = $today->diff($date_naiss);
    return $interval->format('%y');
}

define('URL', '/chat/');
