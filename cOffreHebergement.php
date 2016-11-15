<?php 
/**
 * Contrôleur : gestion des offres d'hébergement
 */
use modele\dao\OffreDAO;
use modele\dao\Bdd;
require_once __DIR__ . '/includes/autoload.php';
Bdd::connecter();

include("includes/_gestionErreurs.inc.php");
//include("includes/gestionDonnees/_connexion.inc.php");
//include("includes/gestionDonnees/_gestionBaseFonctionsCommunes.inc.php");

// 1ère étape (donc pas d'action choisie) : affichage du tableau des offres en 
// lecture seule
if (!isset($_REQUEST['action'])) {
    $_REQUEST['action'] = 'initial';
}

$action = $_REQUEST['action'];

// Aiguillage selon l'étape   
switch ($action) {
    case 'initial' :
        include("vues/OffreHebergement/vConsulterOffreHebergement.php");
        break;

    case 'demanderModifierOffre':
        $idEtab = $_REQUEST['idEtab'];
        include("vues/OffreHebergement/vModifierOffreHebergement.php");
        break;

    case 'validerModifierOffre':
        $idEtab = $_REQUEST['idEtab'];
        $idTypeChambre = $_REQUEST['idTypeChambre'];
        $nbChambres = $_REQUEST['nbChambres'];
        $nbLignes = $_REQUEST['nbLignes'];
        $err = false;
        for ($i = 0; $i < $nbLignes; $i = $i + 1) {
            // Si la valeur saisie n'est pas numérique ou est inférieure aux 
            // attributions déjà effectuées pour cet établissement et ce type de
            // chambre, la modification n'est pas effectuée
            $entier = estEntier($nbChambres[$i]);
            //$modifCorrecte = estModifOffreCorrecte($connexion, $idEtab, $idTypeChambre[$i], $nbChambres[$i]);
            $modifCorrecte = OffreDAO::estModifOffreCorrecte($idEtab, $idTypeChambre[$i], $nbChambres[$i]);
            if (!$entier || !$modifCorrecte) {
                $err = true;
            } else {
                if ($nbChambres[$i] == 0) {
                    $id = array('idEtab' => $idEtab, 'idTypeChambre' => $idTypeChambre[$i], 'nbChambresDemandees' => $nbChambres[$i]);
                    OffreDAO::delete($id);
                } else {
                    $lgOffre = OffreDAO::obtenirNbOffre($idEtab, $idTypeChambre[$i]);
                    if ($lgOffre != 0) {
                        $id = array('idEtab' => $idEtab, 'idTypeChambre' => $idTypeChambre[$i]);
                        OffreDAO::update($id, $nbChambres[$i]);
                    } else {
                        $objet = array('idEtab' => $idEtab, 'idTypeChambre' => $idTypeChambre[$i], 'nbChambresDemandees' => $nbChambres[$i]);
                        OffreDAO::insert($objet);
                    }
                }
                //modifierOffreHebergement($connexion, $idEtab, $idTypeChambre[$i], $nbChambres[$i]);
            }
        }
        if ($err) {
            ajouterErreur(
                    "Valeurs non entières ou inférieures aux attributions effectuées");
            include("vues/OffreHebergement/vModifierOffreHebergement.php");
        } else {
            include("vues/OffreHebergement/vConsulterOffreHebergement.php");
        }
        break;
}
// Fermeture de la connexion au serveur MySql
Bdd::deconnecter();
//// Fermeture de la connexion au serveur MySql
//$connexion = null;

