<!DOCTYPE html">
<html lang="fr">
    <head>
        <title>Festival</title>
        <meta http-equiv="Content-Language" content="fr">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link href="css/cssGeneral.css" rel="stylesheet" type="text/css">
        <link href="css/cssOnglets.css" rel="stylesheet" type="text/css">
        <!-- ajout du logon du site -->
        <link rel="icon" type="image/png" href="./images/logo.png" />
        <!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="./images/logo.png" /><![endif]-->
    </head>
    <body class='basePage'>
        <!--  Tableau contenant le titre et les menus -->
        <table width="100%" cellpadding="0" cellspacing="0">
            <!-- Titre -->
            <tr> 
                <td class="titre">Festival Folklores du Monde <img class="circle" src="./images/logo.png" align="right" width="75" height="75"><br>
                    <span id="texteNiveau2" class="texteNiveau2">
                        H&eacute;bergement des groupes</span><br>&nbsp;
       
                </td>
            </tr>
            <!-- Menus -->
            <tr> 
                <td>
                    <!-- On inclut le fichier de gestion des onglets ; si on a des 
                    menus traditionnels, il faudra inclure le fichier adéquat -->
                    <?php include("_onglets.inc.php"); ?>

                    <div id='barreMenus'>
                        <ul class='menus'>
                            <?php construireMenu("Accueil", "index.php", 1); ?>
                            <?php construireMenu("Gestion établissements", "cGestionEtablissements.php", 2); ?>
                            <?php construireMenu("Gestion types chambres", "cGestionTypesChambres.php", 3); ?>
                            <?php construireMenu("Offre hébergement", "cOffreHebergement.php", 4); ?>
                            <?php construireMenu("Attribution chambres", "cAttributionChambres.php", 5); ?>
                            <?php construireMenu("Gestion des groupes", "cGestionGroupes.php", 6); ?><!-- Ajout du menu gestion des groupes -->
                        </ul>
                    </div>

                </td>
            </tr>
            <!-- Fin des menus -->
            <tr>
                <td class="basePage">
                    <br><center><br>


