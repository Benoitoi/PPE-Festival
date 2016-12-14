<?php

// Si la valeur transmise ne contient pas d'autres caractères que des chiffres, 
// la fonction retourne vrai
function estEntier($valeur) {
    return preg_match('/[^0-9]/', $valeur) != 1;
}

// Si la valeur transmise ne contient pas d'autres caractères que des chiffres  
// et des lettres non accentuées, la fonction retourne vrai
function estChiffresOuEtLettres($valeur) {
    return preg_match('/[^a-zA-Z0-9]/', $valeur) != 1;
}

function estLettres($valeur) {
    return preg_match('/[^A-zÀ-ú \'-]/', $valeur) != 1;//IL FALLAIT AUSSI ACCEPTER LES ACCENTS appostrophe espace et tiret ACCEPTER LES APPOSTROPHES return preg_match("#^[A-Z \\'.-]{2,100}$#i", $valeur) != 1;//ajout de l'autorisation de l'espace du tiret - et de l'apostrophe ' return preg_match('/[^a-zA-Z]/', $valeur) != 1;
}


function estMail($valeur) {
    return preg_match("/^[a-z0-9_\.-]+@([a-z0-9]+([\-]+[a-z0-9]+)*\.)+[a-z]{2,7}$/i",$valeur) != 1;
}
 

function razErreurs() {
    unset($_REQUEST['erreurs']);
}

function ajouterErreur($msg) {
    if (!isset($_REQUEST['erreurs'])) {
        $_REQUEST['erreurs'] = array();
    }
    $_REQUEST['erreurs'][] = htmlentities($msg, ENT_QUOTES, 'UTF-8');
}

function getErreurs() {
    if (!isset($_REQUEST['erreurs'])) {
        $_REQUEST['erreurs'] = array();
    }
    return $_REQUEST['erreurs'];
}

function nbErreurs() {
    return count(getErreurs());
}

function printErreurs() {
    if (nbErreurs() != 0) {
        echo '<div id="erreur" class="msgErreur">';
        echo '<ul>';
        foreach (getErreurs() as $erreur) {
            echo "<li>$erreur</li>";
        }
        echo '</ul>';
        echo '</div>';
    }
}
