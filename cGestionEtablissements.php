<?php
/**
 * Contrôleur : gestion des établissements
 */
use modele\dao\EtablissementDAO;
use modele\metier\Etablissement;
use modele\dao\Bdd;
require_once __DIR__.'/includes/autoload.php';
Bdd::connecter();

include("includes/_gestionErreurs.inc.php");
//include("includes/gestionDonnees/_connexion.inc.php");
//include("includes/gestionDonnees/_gestionBaseFonctionsCommunes.inc.php");

// 1ère étape (donc pas d'action choisie) : affichage du tableau des 
// établissements 
if (!isset($_REQUEST['action'])) {
    $_REQUEST['action'] = 'initial';
}

$action = $_REQUEST['action'];

// Aiguillage selon l'étape
switch ($action) {
    case 'initial' :
        include("vues/GestionEtablissements/vObtenirEtablissements.php");
        break;

    case 'detailEtab':
        $id = $_REQUEST['id'];
        include("vues/GestionEtablissements/vObtenirDetailEtablissement.php");
        break;

    case 'demanderSupprimerEtab':
        $id = $_REQUEST['id'];
        include("vues/GestionEtablissements/vSupprimerEtablissement.php");
        break;

    case 'demanderCreerEtab':
        include("vues/GestionEtablissements/vCreerModifierEtablissement.php");
        break;

    case 'demanderModifierEtab':
        $id = $_REQUEST['id'];
        include("vues/GestionEtablissements/vCreerModifierEtablissement.php");
        break;

    case 'validerSupprimerEtab':
        $id = $_REQUEST['id'];
        EtablissementDAO::delete($id);
        include("vues/GestionEtablissements/vObtenirEtablissements.php");
        break;

    case 'validerCreerEtab':case 'validerModifierEtab':
        $id = $_REQUEST['id'];
        $nom = $_REQUEST['nom'];
        $adresseRue = $_REQUEST['adresseRue'];
        $codePostal = $_REQUEST['codePostal'];
        $ville = $_REQUEST['ville'];
        $tel = $_REQUEST['tel'];
        $adresseElectronique = $_REQUEST['adresseElectronique'];
        $type = $_REQUEST['type'];
        $civiliteResponsable = $_REQUEST['civiliteResponsable'];
        $nomResponsable = $_REQUEST['nomResponsable'];
        $prenomResponsable = $_REQUEST['prenomResponsable'];

        if ($action == 'validerCreerEtab') {
            verifierDonneesEtabC($id, $nom, $adresseRue, $codePostal, $ville, $tel, $nomResponsable, $adresseElectronique);
            if (nbErreurs() == 0) {
                $unEtab = new Etablissement($id, $nom, $adresseRue, $codePostal, $ville, $tel, $adresseElectronique, $type, $civiliteResponsable, $nomResponsable, $prenomResponsable);
                EtablissementDAO::insert($unEtab);
                include("vues/GestionEtablissements/vObtenirEtablissements.php");
            } else {
                include("vues/GestionEtablissements/vCreerModifierEtablissement.php");
            }
        } else {
            verifierDonneesEtabM($id, $nom, $adresseRue, $codePostal, $ville, $tel, $nomResponsable, $adresseElectronique);
            if (nbErreurs() == 0) {
                $unEtab = new Etablissement($id, $nom, $adresseRue, $codePostal, $ville, $tel, $adresseElectronique, $type, $civiliteResponsable, $nomResponsable, $prenomResponsable);
                EtablissementDAO::update($id, $unEtab);
                include("vues/GestionEtablissements/vObtenirEtablissements.php");
            } else {
                include("vues/GestionEtablissements/vCreerModifierEtablissement.php");
            }
        }
        break;
}

// Fermeture de la connexion au serveur MySql
Bdd::deconnecter();

function verifierDonneesEtabC($id, $nom, $adresseRue, $codePostal, $ville, $tel, $nomResponsable, $adresseElectronique) {
    if ($id == "" || $nom == "" || $adresseRue == "" || $codePostal == "" ||
            $ville == "" || $tel == "" || $nomResponsable == "") {
        ajouterErreur('Chaque champ suivi du caractère * est obligatoire');
    }
    if ($id != "") {
        // Si l'id est constitué d'autres caractères que de lettres non accentuées 
        // et de chiffres, une erreur est générée
        if (!estChiffresOuEtLettres($id)) {
            ajouterErreur
                    ("L'identifiant doit comporter uniquement des lettres non accentuées et des chiffres");
        } else {
            if (EtablissementDAO::isAnExistingId($id)) {
                ajouterErreur("L'établissement $id existe déjà");
            }
        }
    }
    if ($nom != "" && !estLettres($nom)) {
            ajouterErreur
                    ("Le nom d'établissement doit comporter uniquement des lettres");
        } else {
            
        if ($nom != "" && EtablissementDAO::isAnExistingName(true, $id, $nom)) {
        ajouterErreur("L'établissement $nom existe déjà");
        }
    }
    if ($codePostal != "" && !estUnCp($codePostal)) {
        ajouterErreur('Le code postal doit comporter 5 chiffres');
    }
    if ($tel != "" && !estUnNumTel($tel)) {
        ajouterErreur('Le numéro de téléphone doit comporter dix chiffres tout attaché (le premier doit être 0 et le second 1, 2, 3, 4, 5, 6, 7 ou 9) exemple : 0123456789)');
    }
    if ($adresseElectronique != "" && !filter_var($adresseElectronique, FILTER_VALIDATE_EMAIL)){
        ajouterErreur('Le format de l\'adresse élèctronique n\'est pas valide');
    
    }
}

function verifierDonneesEtabM($id, $nom, $adresseRue, $codePostal, $ville, $tel, $nomResponsable, $adresseElectronique) {
    if ($nom == "" || $adresseRue == "" || $codePostal == "" || $ville == "" ||
            $tel == "" || $nomResponsable == "") {
        ajouterErreur('Chaque champ suivi du caractère * est obligatoire');
    }
    if ($nom != "" && !estLettres($nom)) {
            ajouterErreur
                    ("Le nom d'établissement doit comporter uniquement des lettres");
        } else {
            
        if ($nom != "" && EtablissementDAO::isAnExistingName(false, $id, $nom)) {
        ajouterErreur("L'établissement $nom existe déjà");
        }
    }
    if ($codePostal != "" && !estUnCp($codePostal)) {
        ajouterErreur('Le code postal doit comporter 5 chiffres');
    }
    if ($tel != "" && !estUnNumTel($tel)) {
        ajouterErreur('Le numéro de téléphone doit comporter dix chiffres tout attaché (le premier doit être 0 et le second 1, 2, 3, 4, 5, 6, 7 ou 9) exemple : 0123456789)');
    }
    if ($adresseElectronique != "" && !filter_var($adresseElectronique, FILTER_VALIDATE_EMAIL)){
        ajouterErreur('Le format de l\'adresse élèctronique n\'est pas valide');
    
    }
}

function estUnCp($codePostal) {
    // Le code postal doit comporter 5 chiffres
    return strlen($codePostal) == 5 && estEntier($codePostal);
}

function estUnNumTel($telephone) {
    // Le numéro de téléphone doit comporter dix chiffres tout attaché (le premier doit être 0 et le second 1, 2, 3, 4, 5, 6, 7 ou 9) exemple : 0123456789)
    return strlen($telephone) == 10 && estEntier($telephone) && substr ( $telephone , 0, 1) == 0 && (substr ( $telephone , 1, 1) == 1 || substr ( $telephone , 1, 1) == 2 || substr ( $telephone , 1, 1) == 3 || substr ( $telephone , 1, 1) == 4 || substr ( $telephone , 1, 1) == 5 || substr ( $telephone , 1, 1) == 6 || substr ( $telephone , 1, 1) == 7 || substr ( $telephone , 1, 1) == 9);
}