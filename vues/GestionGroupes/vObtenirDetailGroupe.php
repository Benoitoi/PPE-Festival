<?php

use modele\dao\GroupeDAO;
use modele\metier\Groupe;
use modele\dao\Bdd;

require_once __DIR__ . '/../../includes/autoload.php';
Bdd::connecter();

include("includes/_debut.inc.php");

// OBTENIR LE DÉTAIL DU GROUPE SÉLECTIONNÉ

$unGroupe = GroupeDAO::getOneById($id);
/* @var $unGroupe Groupe  */
$nom = $unGroupe->getNom();
$identite = $unGroupe->getIdentite();
$adresse = $unGroupe->getAdresse();
$nbPers = $unGroupe->getNbPers();
$nomPays = $unGroupe->getNomPays();
$hebergement = $unGroupe->getHebergement();

echo "
<br>
<table width='60%' cellspacing='0' cellpadding='0' class='tabNonQuadrille'>
   
   <tr class='enTeteTabNonQuad'>
      <td colspan='3'><strong>$nom</strong></td>
   </tr>
   <tr class='ligneTabNonQuad'>
      <td  width='20%'> Id: </td>
      <td>$id</td>
   </tr>
   <tr class='ligneTabNonQuad'>
      <td> Nom: </td>
      <td>$nom</td>
   </tr>
   <tr class='ligneTabNonQuad'>
      <td> Identité du responsable: </td>
      <td>$identite</td>
   </tr>
   <tr class='ligneTabNonQuad'>
      <td> Adresse postale: </td>
      <td>$adresse</td>
   </tr>
   <tr class='ligneTabNonQuad'>
      <td> Nombre de personnes: </td>
      <td>$nbPers</td>
   </tr>
   <tr class='ligneTabNonQuad'>
      <td> Nom du pays: </td>
      <td>$nomPays</td>
   </tr>
   <tr class='ligneTabNonQuad'>
      <td> Type: </td>";
if ($hebergement == 'O') {
    echo "<td> Ce groupe est à héberger </td>";
} else {
    echo "<td> Ce groupe n'est pas à héberger </td>";
}
echo "
   </tr>
</table>
<br>
<a href='cGestionGroupes.php'>Retour</a>";

include("includes/_fin.inc.php");

