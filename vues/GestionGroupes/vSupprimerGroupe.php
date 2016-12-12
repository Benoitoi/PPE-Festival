<?php
use modele\dao\GroupeDAO;
use modele\metier\Groupe;
use modele\dao\Bdd;
require_once __DIR__ . '/../../includes/autoload.php';
Bdd::connecter();

include("includes/_debut.inc.php");

// SUPPRIMER LE GROUPE SÉLECTIONNÉ

$id = $_REQUEST['id'];  // Non obligatoire mais plus propre
$unGroupe = GroupeDAO::getOneById($id);
/* @var $unGroupe Groupe  */
$nom = $unGroupe->getNom();
echo "
<br><center>Voulez-vous vraiment supprimer le groupe $nom ?
<h3><br>
<a href='cGestionGroupes.php?action=validerSupprimerGroupe&id=$id'>Oui</a>
&nbsp; &nbsp; &nbsp; &nbsp;
<a href='cGestionGroupes.php?'>Non</a></h3>
</center>";

include("includes/_fin.inc.php");

