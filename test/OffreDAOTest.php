<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>OffreDAO : test</title>
    </head>

    <body>

        <?php

        use modele\dao\OffreDAO;
        use modele\dao\Bdd;
        use modele\metier\Offre;

        require_once __DIR__ . '/../includes/autoload.php';

        $idEtab = '0350773A';
        $idTypeChambre = 'C1';
        $id = array('idEtab' => $idEtab, 'idTypeChambre' => $idTypeChambre);
        Bdd::connecter();

        echo "<h2>Test OffreDAO</h2>";
        // Test n°1
        echo "<h3>1- getOneById</h3>";
        try {
            $id2 = array('idEtab' => $idEtab, 'idTypeChambre' => 'C2');
            $objet = OffreDAO::getOneById($id2);
            var_dump($objet);
        } catch (Exception $ex) {
            echo "<h4>*** échec de la requête ***</h4>" . $ex->getMessage();
        }

        // Test n°2
        echo "<h3>2- getAll</h3>";
        try {
            $lesObjets = OffreDAO::getAll();
            var_dump($lesObjets);
        } catch (Exception $ex) {
            echo "<h4>*** échec de la requête ***</h4>" . $ex->getMessage();
        }
        
        // Test n°3
        echo "<h3>3- insert</h3>";
        try {
            $objet = new Offre($idEtab, $idTypeChambre, 6);
            //$objet = array('idEtab' => $idEtab, 'idTypeChambre' => $idTypeChambre, 'nombreChambres' => 6);
            $ok = OffreDAO::insert($objet);
            if ($ok) {
                echo "<h4>ooo réussite de l'insertion : ajout de 6 chambres de type C1 pour l'établissement d'id 0350773A ooo</h4>";
                $objetLu = OffreDAO::getOneById($id);
                var_dump($objetLu);
            } else {
                echo "<h4>>ooo réussite du test : la requête d'insertion a échoué ooo</h4>";
            }
        } catch (Exception $ex) {
            echo "<h4>>ooo réussite du test : la requête d'insertion a échoué ooo</h4>" . $ex->getMessage();
        }


        // Test n°4
        echo "<h3>4- update</h3>";
        try {
            //$objet = array('idEtab' => $idEtab, 'idTypeChambre' => $idTypeChambre, 'nombreChambres' => 7);
            $objet->setNbChambres(7);
            $ok = OffreDAO::update($id, $objet);
            if ($ok) {
                echo "<h4>ooo réussite de la mise à jour de l'etablissement d'id 0350773A il a désormais 7 pour les chambres de type C1 ooo</h4>";
                $objetLu = OffreDAO::getOneById($id);
                var_dump($objetLu);
            } else {
                echo "<h4>*** échec de la mise à jour ***</h4>";
            }
        } catch (Exception $ex) {
            echo "<h4>*** échec de la requête ***</h4>" . $ex->getMessage();
        }

        // Test n°5
        echo "<h3>5- delete</h3>";
        try {
            $ok = OffreDAO::delete($id);
            if ($ok) {
                echo "<h4>ooo réussite de la suppression ooo</h4>";
            } else {
                echo "<h4>*** échec de la suppression ***</h4>";
            }
        } catch (Exception $ex) {
            echo "<h4>*** échec de la requête ***</h4>" . $ex->getMessage();
        }

        // Test n°6
        echo "<h3>6- obtenirNbOffre - obtenir le nombre d'offres</h3>";
        try {
            $objet = OffreDAO::obtenirNbOffre($idEtab, 'C2');
            echo "Nombre d'offres pour les chambres de type C2 dans l'établissement d'id ".$idEtab." : ".$objet;
        } catch (Exception $ex) {
            echo "<h4>*** échec de la requête ***</h4>" . $ex->getMessage();
        }
        // Test n°7
        echo "<h3>7- estModifOffreCorrecte - verifier si la modification de l'offre est correcte| CAS CORRECT</h3>";
        try {
            $objet = OffreDAO::estModifOffreCorrecte($idEtab, 'C2', 8);
            if($objet){
                echo "<h4>L'offre est correcte : test conforme</h4>";
            }else{
                echo "<h4>L'offre est incorrecte : test non conforme</h4>";
            }
        } catch (Exception $ex) {
            echo "<h4>*** échec de la requête ***</h4>" . $ex->getMessage();
        } 
        
        // Test n°7-2
        echo "<h3>7-2 estModifOffreCorrecte - verifier si la modification de l'offre est correcte | CAS INCORRECT</h3>";
        try {
            $objet = OffreDAO::estModifOffreCorrecte($idEtab, 'C2', 1);
            if($objet){
                echo "<h4>L'offre est correcte : test non conforme</h4>";
            }else{
                echo "<h4>L'offre est incorrecte : test conforme</h4>";
            }
        } catch (Exception $ex) {
            echo "<h4>*** échec de la requête ***</h4>" . $ex->getMessage();
        } 
        
        Bdd::deconnecter();
        ?>

    </body>
</html>
