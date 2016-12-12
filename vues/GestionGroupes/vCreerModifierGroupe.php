<?php
use modele\dao\GroupeDAO;
use modele\metier\Groupe;
use modele\dao\Bdd;
require_once __DIR__.'/../../includes/autoload.php';
Bdd::connecter();

include("includes/_debut.inc.php");

// CRÉER OU MODIFIER UN GROUPE 
// S'il s'agit d'une création et qu'on ne "vient" pas de ce formulaire (on 
// "vient" de ce formulaire uniquement s'il y avait une erreur), il faut définir 
// les champs à vide sinon on affichera les valeurs précédemment saisies
if ($action == 'demanderCreerGroupe') {
    $id = '';
    $nom = '';
    $identiteResponsable = '';
    $adressePostale = '';
    $nombrePersonnes = '';
    $nomPays = '';
    $hebergement = 'O';
}

// S'il s'agit d'une modification et qu'on ne "vient" pas de ce formulaire, il
// faut récupérer les données sinon on affichera les valeurs précédemment 
// saisies
if ($action == 'demanderModifierGroupe') {
    $unGroupe = GroupeDAO::getOneById($id);
    /* @var $unGroupe Groupe  */
    $nom = $unGroupe->getNom();
    $identiteResponsable = $unGroupe->getIdentite();
    $adressePostale = $unGroupe->getAdresse();
    $nombrePersonnes = $unGroupe->getNbPers();
    $nomPays = $unGroupe->getNomPays();
    $hebergement = $unGroupe->getHebergement();          
}

// Initialisations en fonction du mode (création ou modification) 
if ($action == 'demanderCreerGroupe' || $action == 'validerCreerGroupe') {
    $creation = true;
    $message = "Nouveau groupe";  // Alimentation du message de l'en-tête
    $action = "validerCreerGroupe";
} else {
    $creation = false;
    $message = "$nom ($id)";            // Alimentation du message de l'en-tête
    $action = "validerModifierGroupe";
}

echo "
<form method='POST' action='cGestionGroupes.php?'>
   <input type='hidden' value='$action' name='action'>
   <br>
   <table width='85%' cellspacing='0' cellpadding='0' class='tabNonQuadrille'>
   
      <tr class='enTeteTabNonQuad'>
         <td colspan='3'><strong>$message</strong></td>
      </tr>";

// En cas de création, l'id est accessible sinon l'id est dans un champ
// caché               
if ($creation) {
    // On utilise les guillemets comme délimiteur de champ dans l'echo afin
    // de ne pas perdre les éventuelles quotes saisies (même si les quotes
    // ne sont pas acceptées dans l'id, on a le souci de ré-afficher l'id
    // tel qu'il a été saisi) 
    echo '
         <tr class="ligneTabNonQuad">
            <td> Id*: </td>
            <td><input type="text" value="' . $id . '" name="id" size ="10" 
            maxlength="8"></td>
         </tr>';
} else {
    echo "
         <tr>
            <td><input type='hidden' value='$id' name='id'></td><td></td>
         </tr>";
}
echo '
      <tr class="ligneTabNonQuad">
         <td> Nom*: </td>
         <td><input type="text" value="' . $nom . '" name="nom" size="50" 
         maxlength="100"></td>
      </tr>
      <tr class="ligneTabNonQuad">
         <td> Indentité du responsable: </td>
         <td><input type="text" value="' . $identiteResponsable . '" name="identiteResponsable" 
         size="100" maxlength="45"></td>
      </tr>
      <tr class="ligneTabNonQuad">
         <td> Adresse postale: </td>
         <td><input type="text" value="' . $adressePostale . '" name="adressePostale" 
         size="100" maxlength="5"></td>
      </tr>
      <tr class="ligneTabNonQuad">
         <td> Nombre de personnes*: </td>
         <td><input type="text" value="' . $nombrePersonnes . '" name="nombrePersonnes" size="40" 
         maxlength="2"></td>
      </tr>
      <tr class="ligneTabNonQuad">
         <td> Nom du pays*: </td>
         <td><input type="text" value="' . $nomPays . '" name="nomPays" size ="20"
         maxlength="50"></td>
      </tr>
      <tr class="ligneTabNonQuad">
         <td> Groupe à héberger*: </td>
         <td>';
if ($hebergement == 'O') {
    echo " 
               <input type='radio' name='hebergement' value='O' checked>  
               Oui
               <input type='radio' name='hebergement' value='N'>  Non";
} else {
    echo " 
                <input type='radio' name='hebergement' value='O'> 
                Oui
                <input type='radio' name='hebergement' value='N' checked> Non";
}
echo '
           </td>
         </tr>
   </table>';
echo "
   <table align='center' cellspacing='15' cellpadding='0'>
      <tr>
         <td align='right'><input type='submit' value='Valider' name='valider'>
         </td>
         <td align='left'><input type='reset' value='Annuler' name='annuler'>
         </td>
      </tr>
   </table>
   <a href='cGestionGroupes.php'>Retour</a>
</form>";

include("includes/_fin.inc.php");

?>