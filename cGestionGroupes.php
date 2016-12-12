<?php
/**
 * Contrôleur : gestion des groupes
 */
use modele\dao\GroupeDAO;
use modele\metier\Groupe;
use modele\dao\Bdd;
require_once __DIR__.'/includes/autoload.php';
Bdd::connecter();

include("includes/_gestionErreurs.inc.php");
//include("includes/gestionDonnees/_connexion.inc.php");
//include("includes/gestionDonnees/_gestionBaseFonctionsCommunes.inc.php");

// 1ère étape (donc pas d'action choisie) : affichage du tableau des 
// groupes 
if (!isset($_REQUEST['action'])) {
    $_REQUEST['action'] = 'initial';
}

$action = $_REQUEST['action'];

// Aiguillage selon l'étape
switch ($action) {
    case 'initial' :
        include("vues/GestionGroupes/vObtenirGroupes.php");
        break;

    case 'detailGroupe':
        $id = $_REQUEST['id'];
        include("vues/GestionGroupes/vObtenirDetailGroupe.php");
        break;

    case 'demanderSupprimerGroupe':
        $id = $_REQUEST['id'];
        include("vues/GestionGroupes/vSupprimerGroupe.php");
        break;

    case 'demanderCreerGroupe':
        include("vues/GestionGroupes/vCreerModifierGroupe.php");
        break;

    case 'demanderModifierGroupe':
        $id = $_REQUEST['id'];
        include("vues/GestionGroupes/vCreerModifierGroupe.php");
        break;

    case 'validerSupprimerGroupe':
        $id = $_REQUEST['id'];
        GroupeDAO::delete($id);
        include("vues/GestionGroupes/vObtenirGroupes.php");
        break;
	
    case 'validerCreerGroupe':case 'validerModifierGroupe':
        $id = $_REQUEST['id'];
        $nom = $_REQUEST['nom'];
        $identiteResponsable = $_REQUEST['identiteResponsable'];
        $adressePostale = $_REQUEST['adressePostale'];
        $nombrePersonnes = $_REQUEST['nombrePersonnes'];
        $nomPays = $_REQUEST['nomPays'];
        $hebergement = $_REQUEST['hebergement'];

        if ($action == 'validerCreerGroupe') {
            verifierDonneesGroupeC($id, $nom, $identiteResponsable, $adressePostale, $nombrePersonnes, $nomPays, $hebergement);
            if (nbErreurs() == 0) {
                $unGroupe = new Groupe($id, $nom, $identiteResponsable, $adressePostale, $nombrePersonnes, $nomPays, $hebergement);
                GroupeDAO::insert($unGroupe);
                include("vues/GestionGroupes/vObtenirGroupes.php");
            } else {
                include("vues/GestionGroupes/vCreerModifierGroupe.php");
            }
        } else {
            verifierDonneesGroupeM($id, $nom, $identiteResponsable, $adressePostale, $nombrePersonnes, $nomPays, $hebergement);
            if (nbErreurs() == 0) {
                $unGroupe = new Groupe($id, $nom, $identiteResponsable, $adressePostale, $nombrePersonnes, $nomPays, $hebergement);
                GroupeDAO::update($id, $unGroupe);
                include("vues/GestionGroupes/vObtenirGroupes.php");
            } else {
                include("vues/GestionGroupes/vCreerModifierGroupe.php");
            }
        }
        break;
}

// Fermeture de la connexion au serveur MySql
Bdd::deconnecter();

function verifierDonneesGroupeC($id, $nom, $identiteResponsable, $adressePostale, $nombrePersonnes, $nomPays, $hebergement) {
    if ($id == "" || $nom == "" || $nombrePersonnes == "" || $nomPays == "" ||
            $hebergement == "") {
        ajouterErreur('Chaque champ suivi du caractère * est obligatoire');
    }
    if ($id != "") {
        // Si l'id est constitué d'autres caractères que de lettres non accentuées 
        // et de chiffres, une erreur est générée
        if (!estChiffresOuEtLettres($id)) {
            ajouterErreur
                    ("L'identifiant doit comporter uniquement des lettres non accentuées et des chiffres");
        } else {
            if (GroupeDAO::isAnExistingId($id)) {
                ajouterErreur("Le groupe $id existe déjà");
            }
        }
    }
    if ($nom != "" && !estLettres($nom)) {
            ajouterErreur("Le nom du groupe doit comporter uniquement des lettres");
        } else {
            
        if ($nom != "" && GroupeDAO::isAnExistingName(true, $id, $nom)) {
            ajouterErreur("Le groupe $nom existe déjà");
        }
    }
    if ($identiteResponsable != "" && !estLettres($identiteResponsable)) {
        ajouterErreur("L'identité du responsable du groupe doit comporter uniquement des lettres");
    }
    if ($nomPays != "" && !estLettres($nomPays)) {
        ajouterErreur("Le pays du groupe doit comporter uniquement des lettres");
    }
    if($nombrePersonnes != "" && !estEntier($nombrePersonnes) || $nombrePersonnes < 1){//le champ empeche déjà de saisir plus de 100 car sa longueur max est fixée à 2 caractères
        ajouterErreur("Le nombre de personne doit être un entier entre 1 et 99");
    }
}

function verifierDonneesGroupeM($id, $nom, $identiteResponsable, $adressePostale, $nombrePersonnes, $nomPays, $hebergement) {
    if ($nom == "" || $nombrePersonnes == "" || $nomPays == "" ||
            $hebergement == "") {
        ajouterErreur('Chaque champ suivi du caractère * est obligatoire');
    }
    if ($nom != "" && !estLettres($nom)) {
            ajouterErreur("Le nom du groupe doit comporter uniquement des lettres");
        } else {
            
        if ($nom != "" && GroupeDAO::isAnExistingName(false, $id, $nom)) {
            ajouterErreur("Le groupe $nom existe déjà");
        }
    }
    if ($identiteResponsable != "" && !estLettres($identiteResponsable)) {
        ajouterErreur("L'identité du responsable du groupe doit comporter uniquement des lettres");
    }
    if ($nomPays != "" && !estLettres($nomPays)) {
        ajouterErreur("Le pays du groupe doit comporter uniquement des lettres");
    }
    if($nombrePersonnes != "" && !estEntier($nombrePersonnes) || $nombrePersonnes < 1){//le champ empeche déjà de saisir plus de 100 car sa longueur max est fixée à 2 caractères
        ajouterErreur("Le nombre de personne doit être un entier entre 1 et 99");
    }
}
